"use strict";

let formTouched = false; 
let currentTab = 0;
showTab(currentTab);

function showTab(n) {
    const tabs = document.querySelectorAll(".tab");
    const steps = document.querySelectorAll(".setup-panel .stepwizard-step a");

    // Sembunyikan semua tab dan setel ulang status tombol wizard
    tabs.forEach(tab => (tab.style.display = "none"));
    steps.forEach(step => {
        step.classList.remove("btn-primary");
        step.classList.add("btn-light");
    });

    // Tampilkan tab yang sesuai dan aktifkan langkah wizard
    tabs[n].style.display = "block";
    steps[n].classList.remove("btn-light");
    steps[n].classList.add("btn-primary");

    // Tampilkan atau sembunyikan tombol Previous
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    prevBtn.style.display = n === 0 ? "none" : "inline";
    nextBtn.innerHTML = n === tabs.length - 1 ? "Submit" : "Next";
}

function nextPrev(n) {
    const tabs = document.querySelectorAll(".tab");
    const form = document.querySelector("form");

    if (n === 1) {
        formTouched = true;
    }

    // Validasi input sebelum melanjutkan ke tab berikutnya
    if (n === 1 && !validateForm()) {
        return false;
    }

    // Jika berada di tab terakhir dan menekan "Next", maka submit form
    if (currentTab === tabs.length - 1 && n === 1) {
        console.warn("Submitting form..."); 
        Swal.fire({
            title: 'Confirmation',
            text: 'Are you sure you want to submit the form?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                submitForm(form); // Gunakan form yang sedang aktif
            }
        });
        return false;
    }

    // Jika di tab pertama dan menekan "Previous", cegah kembali ke index negatif
    if (currentTab === 0 && n === -1) {
        console.warn("Already on first page. Preventing back navigation.");
        return false;
    }

    // Sembunyikan tab saat ini
    tabs[currentTab].style.display = "none";

    // Perbarui indeks tab jika belum mencapai batas maksimal
    currentTab += n;
    showTab(currentTab);
}

document.querySelectorAll(".setup-panel .stepwizard-step a").forEach((stepLink, index) => {
    stepLink.addEventListener("click", function (e) {
        e.preventDefault();

        if (index > currentTab) {
            // Validasi semua langkah di antara langkah saat ini dan tujuan
            let allValid = true;
            for (let i = currentTab; i < index; i++) {
                currentTab = i;
                if (!validateForm()) {
                    console.warn(`Validation failed on step ${i}.`);
                    allValid = false;
                    showTab(i);
                    break;
                }
            }

            if (allValid) {
                currentTab = index;
                showTab(currentTab);
            } else {
                console.warn(`Cannot navigate to step ${index}. Validation failed.`);
            }
        } else {
            currentTab = index;
            showTab(currentTab);
        }
    });
});

function validateForm() {
    let valid = true;
    const inputs = document.querySelectorAll(".tab")[currentTab].querySelectorAll("input, select, textarea");

    inputs.forEach(input => {

        // Mengabaikan halaman yang disabled
        if (input.disabled) {
            return; 
        }

        const isRequired = input.hasAttribute("required");
        const isEmpty = input.value.trim() === "";
        const isEmail = input.type === "email";

        if (isRequired && isEmpty) {
            console.warn(`Validation failed: input name=${input.name} is empty.`);
            input.classList.add("is-invalid");

            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "This field is required.";
            }
            valid = false;
        } else if (!isEmpty && isEmail && !validateEmail(input.value)) {
            console.warn(`Validation failed: input name=${input.name} is not a valid email.`);
            input.classList.add("is-invalid");

            // Validassi Email
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "Please enter a valid email address.";
            }
            valid = false;
        } 

        // Input valid
        else {
            input.classList.remove("is-invalid");

            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "";
            }
        }
    });
    return valid; // Kembalikan true jika semua input valid, false jika ada error
}


function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Pola sederhana untuk validasi email
    return emailRegex.test(email);
}

// Ajax sweet alert
function submitForm(form) {
    if (!form) {
        console.error(`Form not found.`);
        return;
    }

    const formData = new FormData(form);
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMetaTag ? csrfMetaTag.content : '';

    Swal.fire({
        title: 'Submitting...',
        text: 'Please wait while we process your request.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading(); 
        }
    });

    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                title: 'Success!',
                text: 'Your form has been submitted successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            console.error('Server Response:', data);

            let errorMessage = 'Terjadi kesalahan.';
            if (data.errors) {
                errorMessage = '<ul>';
                Object.values(data.errors).forEach(errorArray => {
                    errorArray.forEach(error => {
                        errorMessage += `<li>${error}</li>`;
                    });
                });
                errorMessage += '</ul>';
            }

            Swal.fire({
                title: 'Validation Failed!',
                html: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Unexpected response from the server. Please try again later.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}
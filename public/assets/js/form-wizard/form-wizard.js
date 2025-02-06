"use strict";

let formTouched = false; // Menandakan apakah pengguna mencoba lanjut
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

    console.log(`Before navigation: currentTab = ${currentTab}, next action = ${n > 0 ? "Next" : "Prev"}`);

    if (n === 1) {
        formTouched = true;
    }

    // Validasi input sebelum melanjutkan
    if (n === 1 && !validateForm()) {
        console.log("Validation failed. Staying on currentTab.");
        return false;
    }

    // Sembunyikan tab saat ini
    tabs[currentTab].style.display = "none";

    // Perbarui indeks tab
    currentTab += n;

    // Debugging batas tab
    if (currentTab < 0) {
        console.warn("currentTab is less than 0. Resetting to 0.");
        currentTab = 0;
    }
    if (currentTab >= tabs.length) {
        console.warn("currentTab exceeds tabs length. Triggering form submission.");
   
        document.getElementById("regForm").addEventListener("submit", confirmSubmission);
        // document.getElementById("regForm").submit();
        return false;
    }

    console.log(`After navigation: currentTab = ${currentTab}`);
    showTab(currentTab);
}

document.querySelectorAll(".setup-panel .stepwizard-step a").forEach((stepLink, index) => {
    stepLink.addEventListener("click", function (e) {
        e.preventDefault();

        console.log(`Wizard step clicked: currentTab = ${currentTab}, index = ${index}`);

        if (index > currentTab) {
            // Validasi semua langkah di antara langkah saat ini dan tujuan
            let allValid = true;
            for (let i = currentTab; i < index; i++) {
                currentTab = i;
                console.log(`Validating step ${i}`);
                if (!validateForm()) {
                    console.warn(`Validation failed on step ${i}.`);
                    allValid = false;
                    showTab(i);
                    break;
                }
            }

            if (allValid) {
                console.log(`Navigating to step ${index}.`);
                currentTab = index;
                showTab(currentTab);
            } else {
                console.warn(`Cannot navigate to step ${index}. Validation failed.`);
            }
        } else {
            // Jika pengguna kembali ke langkah sebelumnya, izinkan navigasi
            console.log(`Navigating to step ${index}.`);
            currentTab = index;
            showTab(currentTab);
        }
    });
});


function confirmSubmission(event) {
    event.preventDefault(); // Mencegah submit default
    console.log('Confirm submission function called'); // Debugging

    const form = event.target; // Form elemen
    console.log('Form action:', form.action); // Debugging

    const action = form.action.includes('update') ? 'update' : 'store';
    const actionMessages = {
        update: 'Are you sure you want to update the data?',
        store: 'Are you sure you want to save the data?'
    };

    Swal.fire({
        title: 'Confirmation',
        text: actionMessages[action],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData(form);
            console.log('Form data ready to be submitted'); // Debugging

            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                console.log('Response received:', response); // Debugging
                return response.json(); // Pastikan response adalah JSON
            })
            .then(data => {
                console.log('Data from response:', data); // Debugging
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: `Data has been ${action === 'update' ? 'updated' : 'saved'}.`,
                        icon: 'success',
                        timer: 4000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        title: 'Failed!',
                        text: data.message || 'Unable to process data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while processing the data.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function validateForm() {
    let valid = true;
    const inputs = document.querySelectorAll(".tab")[currentTab].querySelectorAll("input, select, textarea");

    console.log(`Validating inputs for tab ${currentTab}`);

    document.querySelectorAll("select").forEach(select => {
        console.log(`Checking select: name=${select.name}, required=${select.hasAttribute("required")}`);
    });
    
    

    inputs.forEach(input => {
        // Abaikan input yang dinonaktifkan
        if (input.disabled) {
            console.log(`Skipping validation: input name=${input.name} is disabled.`);
            return; // Langsung lewati input ini
        }

        const isRequired = input.hasAttribute("required");
        const isEmpty = input.value.trim() === "";
        const isEmail = input.type === "email";

        // Validasi input required yang kosong
        if (isRequired && isEmpty) {
            console.warn(`Validation failed: input name=${input.name} is empty.`);
            input.classList.add("is-invalid");

            // Tampilkan pesan error
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "This field is required.";
            }
            valid = false;

         
        } 
        // Validasi format email jika input bertipe email
        else if (!isEmpty && isEmail && !validateEmail(input.value)) {
            console.warn(`Validation failed: input name=${input.name} is not a valid email.`);
            input.classList.add("is-invalid");

            // Tampilkan pesan error khusus untuk email
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "Please enter a valid email address.";
            }
            valid = false;
        } 
        // Input valid
        else {
            console.log(`Validation passed: input name=${input.name}`);
            input.classList.remove("is-invalid");

            // Bersihkan pesan error
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "";
            }
        }
    });

    console.log(`Validation result for tab ${currentTab}: ${valid ? "Valid" : "Invalid"}`);
    return valid; // Kembalikan true jika semua input valid, false jika ada error
}


// Fungsi untuk memvalidasi email menggunakan RegEx
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Pola sederhana untuk validasi email
    return emailRegex.test(email);
}


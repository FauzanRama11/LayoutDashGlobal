"use strict";

var currentTab = 0; // Initialize the first tab
showTab(currentTab); // Display the first tab

function showTab(n) {
  var tabs = document.getElementsByClassName("tab");
  var steps = document.querySelectorAll(".setup-panel .stepwizard-step a");


  for (let i = 0; i < tabs.length; i++) {
    tabs[i].style.display = "none";
    steps[i].classList.remove("btn-primary");
    steps[i].classList.add("btn-light");
  }

  tabs[n].style.display = "block";
  steps[n].classList.remove("btn-light");
  steps[n].classList.add("btn-primary");

  document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
  document.getElementById("nextBtn").innerHTML = n === tabs.length - 1 ? "Submit" : "Next";
}

function nextPrev(n) {
  var tabs = document.getElementsByClassName("tab");

  if (n === 1 && !validateForm()) return false;

  tabs[currentTab].style.display = "none";

  currentTab += n;

  if (currentTab >= tabs.length) {
    document.getElementById("regForm").submit();
    return false;
  }

  showTab(currentTab);
}

// Function to validate the current tab's inputs
function validateForm() {
  var valid = true;
  var inputs = document.querySelectorAll(".tab")[currentTab].querySelectorAll("input");

  inputs.forEach(input => {
    if (input.value === "") {
      input.classList.add("invalid");
      valid = false;
    } else {
      input.classList.remove("invalid");
    }
  });

  return valid;
}

// Add click functionality to step wizard navigation
document.querySelectorAll(".setup-panel .stepwizard-step a").forEach((stepLink, index) => {
  stepLink.addEventListener("click", function (e) {
    e.preventDefault();

    if (index <= currentTab || validateForm()) {
      currentTab = index; 
      showTab(currentTab); 
    }
  });
});

document.addEventListener("DOMContentLoaded", function() {
  const form = document.forms["counter"];
  form.addEventListener("submit", function(event) {
    event.preventDefault();
    const height = parseFloat(form.elements["height"].value) / 100;
    const weight = parseFloat(form.elements["weight"].value);
    const bmi = calculateBMI(weight, height);

    displayBMI(bmi);
  });

  function calculateBMI(weight, height) {
    return (weight / (height * height)).toFixed(1);
  }
  
  function displayBMI(bmi) {
    const bmiResult = document.getElementById("bmi-result");
    bmiResult.textContent = bmi;
  }
});

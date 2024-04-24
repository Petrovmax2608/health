document.addEventListener("DOMContentLoaded", function() {
  const form = document.forms["counter"];

  form.addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting

    // Retrieve input values
    const height = parseFloat(form.elements["height"].value) / 100; // Convert height to meters
    const weight = parseFloat(form.elements["weight"].value);

    // Calculate BMI
    const bmi = calculateBMI(weight, height);

    // Display BMI
    displayBMI(bmi);
  });

  // Function to calculate BMI
  function calculateBMI(weight, height) {
    return (weight / (height * height)).toFixed(1);
  }

  // Function to display BMI
  function displayBMI(bmi) {
    const bmiResult = document.getElementById("bmi-result");
    bmiResult.textContent = bmi;
  }
});

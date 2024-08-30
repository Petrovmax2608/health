document.addEventListener("DOMContentLoaded", function() {
  const form = document.forms['counter'];
  const calculateButton = document.querySelector('button[name="submit"]');
  const clearButton = document.querySelector('button[name="reset"]');
  const counterResult = document.querySelector('.counter__result');
  const activityRadios = document.querySelectorAll('input[name="activity"]');
  const genderRadios = document.querySelectorAll('input[name="gender"]');
  const bmiResult = document.getElementById('bmi-result');
  const toast = document.querySelector(".toast");
  const progress = document.querySelector(".progress");
  const goalMaintainButton = document.getElementById('goal-maintain');
  const goalGainButton = document.getElementById('goal-gain');
  const goalLoseButton = document.getElementById('goal-lose');
  const progressBarFill = document.getElementById('calories-progress-bar-fill'); 
  const caloriesMarker = document.getElementById('calories-marker');
  const bmiProgressBarFill = document.getElementById('bmi-progress-bar-fill');
  const bmiMarker = document.getElementById('bmi-marker');
  const proteinResult = document.getElementById('protein-result'); 
  const fatResult = document.getElementById('fat-result'); 
  const carbsResult = document.getElementById('carbs-result'); 
  const waterResult = document.getElementById('water-result');
  let timer1, timer2;
  let activityCoefficient = 1.2;
  let genderFactor = 5;
  let caloriesNorm = 0;
  let caloriesMinimal = 0;
  let caloriesMaximal = 0;
  var infoButton = document.getElementById("info-button");
  var modal = document.getElementById("info-modal");

  toast.classList.remove("active");
  progress.classList.remove("active");

  function updateActivityCoefficient() {
    activityRadios.forEach(function(radio) {
      if (radio.checked) {
        switch (radio.value) {
          case 'min':
            activityCoefficient = 1.2;
            break;
          case 'low':
            activityCoefficient = 1.3;
            break;
          case 'medium':
            activityCoefficient = 1.5;
            break;
          case 'high':
            activityCoefficient = 1.7;
            break;
          case 'max':
            activityCoefficient = 1.9;
            break;
          default:
            break;
        }
      }
    });
  }

  function updateGenderFactor() {
    genderRadios.forEach(function(radio) {
      if (radio.checked) {
        genderFactor = radio.value === 'male' ? 5 : -161;
      }
    });
  }

  function calculateCalories() {
    const ageInput = parseInt(document.getElementById('age').value);
    const heightInput = parseInt(document.getElementById('height').value);
    const weightInput = parseInt(document.getElementById('weight').value);

    if (isNaN(ageInput) || isNaN(heightInput) || isNaN(weightInput) ||
        ageInput <= 0 || heightInput <= 0 || weightInput <= 0) {
      alert('Пожалуйста, введите корректные значения для возраста, роста и веса.');
      return;
    }

    const basalMetabolicRate = 10 * weightInput + 6.25 * heightInput - 5 * ageInput + genderFactor;
    const maintenanceCalories = basalMetabolicRate * activityCoefficient;

    caloriesNorm = Math.round(maintenanceCalories);
    caloriesMinimal = Math.round(maintenanceCalories * 0.85);
    caloriesMaximal = Math.round(maintenanceCalories * 1.15);

    document.getElementById('calories-norm').textContent = caloriesNorm;
    document.getElementById('calories-minimal').textContent = caloriesMinimal;
    document.getElementById('calories-maximal').textContent = caloriesMaximal;

    const heightInMeters = heightInput / 100;
    const bmi = calculateBMI(weightInput, heightInMeters);
    displayBMI(bmi);

    counterResult.classList.remove('counter__result--hidden');
    document.querySelector('.goal-selection').classList.remove('counter__result--hidden');
    document.querySelector('.progress-section').classList.remove('counter__result--hidden');

    updateBMIProgressBar(bmi);

    updateMacronutrients(caloriesNorm);
    updateWaterRequirement(weightInput);
  }

  function calculateBMI(weight, height) {
    return (weight / (height * height)).toFixed(1);
  }

  function displayBMI(bmi) {
    bmiResult.textContent = bmi;
  }

  function updateProgressBar(calories) {
    const maxCalories = 5000;
    const percentage = (calories / maxCalories) * 100;

    progressBarFill.style.width = percentage + '%';
    caloriesMarker.style.left = percentage + '%';
    caloriesMarker.innerHTML = `<span>${calories}</span>`;
  }

  function updateBMIProgressBar(bmi) {
    const minBMI = 16;
    const maxBMI = 40;
    const percentage = ((bmi - minBMI) / (maxBMI - minBMI)) * 100;

    bmiProgressBarFill.style.width = percentage + '%';
    bmiMarker.style.left = percentage + '%';
    bmiMarker.innerHTML = `<span>${bmi}</span>`;
  }

  let macrosChart;
  function createMacrosChart(protein, fat, carbs) {
    const ctx = document.getElementById('macrosChart').getContext('2d');
    const data = {
      labels: ['Белки', 'Жиры', 'Углеводы'],
      datasets: [{
        data: [protein, fat, carbs],
        backgroundColor: ['#2E8B57', '#DC143C', '#008B8B'],
        hoverBackgroundColor: ['#2E8B57', '#DC143C', '#008B8B']
      }]
    };
    const options = {
      responsive: true,
      animation: {
        animateScale: true,
        animateRotate: true
      }
    };
    return new Chart(ctx, {
      type: 'doughnut',
      data: data,
      options: options
    });
  }

  function updateMacrosChart(protein, fat, carbs) {
    if (macrosChart) {
      macrosChart.destroy();
    }
    macrosChart = createMacrosChart(protein, fat, carbs);
  }

  function updateMacronutrients(calories) {
    const protein = Math.round(calories * 0.3 / 4);
    const fat = Math.round(calories * 0.3 / 9);
    const carbs = Math.round(calories * 0.4 / 4);

    proteinResult.textContent = protein;
    fatResult.textContent = fat;
    carbsResult.textContent = carbs;

    updateMacrosChart(protein, fat, carbs);
  }

  function updateWaterRequirement(weight) {
    const waterNorm = calculateWater(weight);
    waterResult.textContent = waterNorm;
  }

  function calculateWater(weight) {
    return Math.round(weight * 30);
  }

  goalMaintainButton.addEventListener('click', function() {
    updateProgressBar(caloriesNorm);
    updateMacronutrients(caloriesNorm);
  });

  goalGainButton.addEventListener('click', function() {
    updateProgressBar(caloriesMaximal);
    updateMacronutrients(caloriesMaximal);
  });

  goalLoseButton.addEventListener('click', function() {
    updateProgressBar(caloriesMinimal);
    updateMacronutrients(caloriesMinimal);
  });

  clearButton.addEventListener('click', function(event) {
    event.preventDefault();
    const inputs = form.querySelectorAll('input[type="text"]');
    inputs.forEach(input => input.value = ''); // Clear input values

    bmiResult.textContent = ''; // Clear BMI result
    counterResult.classList.add('counter__result--hidden');
    document.querySelector('.goal-selection').classList.add('counter__result--hidden');
    document.querySelector('.progress-section').classList.add('counter__result--hidden');

    showToast("Поля очищены");
  });

  calculateButton.addEventListener('click', function(event) {
    event.preventDefault();
    updateActivityCoefficient();
    updateGenderFactor();
    if (!isEmptyInput()) {
      calculateCalories();
      showToast("Готово");
    }
  });

  function showToast(message) {
    const toastText = document.querySelector(".toast .text");
    toastText.textContent = message;
    toast.classList.add("active");
    progress.classList.add("active");

    timer1 = setTimeout(() => {
      toast.classList.remove("active");
    }, 5000);

    timer2 = setTimeout(() => {
      progress.classList.remove("active");
    }, 5300);
  }

  function isEmptyInput() {
    const ageInput = document.getElementById('age').value.trim();
    const heightInput = document.getElementById('height').value.trim();
    const weightInput = document.getElementById('weight').value.trim();
    return ageInput === '' || heightInput === '' || weightInput === '';
  }

  infoButton.onclick = function() {
    modal.style.display = "block";
  }

  var closeButton = document.getElementsByClassName("close")[0];
  closeButton.onclick = function() {
    modal.style.display = "none";
  }

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const form = document.forms['counter'];
  const calculateButton = document.querySelector('button[name="submit"]');
  const clearButton = document.querySelector('button[name="reset"]');
  const counterResult = document.querySelector('.counter__result');
  const activityRadios = document.querySelectorAll('input[name="activity"]');
  const genderRadios = document.querySelectorAll('input[name="gender"]');
  const bmiResult = document.getElementById('bmi-result');
  const toast = document.querySelector(".toast");
  const progress = document.querySelector(".progress");
  let timer1, timer2;

  toast.classList.remove("active");
  progress.classList.remove("active");

  let activityCoefficient = 1.2;
  let genderFactor = 5;

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

    const weightLossCalories = maintenanceCalories * 0.85;
    const weightGainCalories = maintenanceCalories * 1.15;

    const caloriesNorm = document.getElementById('calories-norm');
    const caloriesMinimal = document.getElementById('calories-minimal');
    const caloriesMaximal = document.getElementById('calories-maximal');

    caloriesNorm.textContent = Math.round(maintenanceCalories);
    caloriesMinimal.textContent = Math.round(weightLossCalories);
    caloriesMaximal.textContent = Math.round(weightGainCalories);

    // Calculate BMI
    const heightInMeters = heightInput / 100;
    const bmi = calculateBMI(weightInput, heightInMeters);

    // Display BMI
    displayBMI(bmi);
    
    counterResult.classList.remove('counter__result--hidden');
  }

  function calculateBMI(weight, height) {
    return (weight / (height * height)).toFixed(1);
  }

  function displayBMI(bmi) {
    bmiResult.textContent = bmi;
  }

  clearButton.addEventListener('click', function(event) {
    event.preventDefault();
    const form = document.forms['counter'];
    const inputs = form.querySelectorAll('input[type="text"]');
    inputs.forEach(input => input.value = ''); // Clear input values

    const bmiResult = document.getElementById('bmi-result');
    bmiResult.textContent = ''; // Clear BMI result

    counterResult.classList.add('counter__result--hidden');

    // Показать сообщение о успешном очищении полей
    showToast("Поля очищены");
  });

  calculateButton.addEventListener('click', function(event) {
    event.preventDefault();
    if (!isEmptyInput()) {
      calculateCalories();
      // Показать сообщение о завершении расчета
      showToast("Готово");
    }
  });

  // Функция для показа toast-сообщения
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

  // Функция для проверки заполненности полей ввода
  function isEmptyInput() {
    const ageInput = document.getElementById('age').value.trim();
    const heightInput = document.getElementById('height').value.trim();
    const weightInput = document.getElementById('weight').value.trim();
    return ageInput === '' || heightInput === '' || weightInput === '';
  }
});

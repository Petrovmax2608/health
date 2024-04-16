document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form[name="counter"]');
  const calculateButton = document.querySelector('button[name="submit"]');
  const clearButton = document.querySelector('button[name="reset"]');
  const counterResult = document.querySelector('.counter__result');
  const activityRadios = document.querySelectorAll('input[name="activity"]');
  const genderRadios = document.querySelectorAll('input[name="gender"]');

  let activityCoefficient = 1.2; // По умолчанию устанавливаем коэффициент для минимальной активности
  let genderFactor = 5; // Фактор пола для формулы поддержания веса

  // Функция для обновления коэффициента активности
  function updateActivityCoefficient() {
    activityRadios.forEach(function(radio) {
      if (radio.checked) {
        switch (radio.value) {
          case 'min':
            activityCoefficient = 1.2;
            break;
          case 'low':
            activityCoefficient = 1.375;
            break;
          case 'medium':
            activityCoefficient = 1.55;
            break;
          case 'high':
            activityCoefficient = 1.725;
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

  // Функция для обновления фактора пола и пересчета результатов
  function updateGenderFactor() {
    genderRadios.forEach(function(radio) {
      if (radio.checked) {
        genderFactor = radio.value === 'male' ? 5 : -161;
        calculateCalories();
      }
    });
  }

  // Функция для расчета калорий
  function calculateCalories() {
    const ageInput = parseInt(document.getElementById('age').value);
    const heightInput = parseInt(document.getElementById('height').value);
    const weightInput = parseInt(document.getElementById('weight').value);

    if (isNaN(ageInput) || isNaN(heightInput) || isNaN(weightInput)) {
      // Если хотя бы одно из полей не заполнено, выходим из функции
      return;
    }

    const basalMetabolicRate = 10 * weightInput + 6.25 * heightInput - 5 * ageInput + genderFactor; // Изменение формулы в зависимости от пола
    const maintenanceCalories = basalMetabolicRate * activityCoefficient;

    const weightLossCalories = maintenanceCalories * 0.85;
    const weightGainCalories = maintenanceCalories * 1.15;

    const caloriesNorm = document.getElementById('calories-norm');
    const caloriesMinimal = document.getElementById('calories-minimal');
    const caloriesMaximal = document.getElementById('calories-maximal');

    caloriesNorm.textContent = Math.round(maintenanceCalories);
    caloriesMinimal.textContent = Math.round(weightLossCalories);
    caloriesMaximal.textContent = Math.round(weightGainCalories);

    counterResult.classList.remove('counter__result--hidden');
    calculateButton.removeAttribute('disabled');
  }

  // Обработчик изменения уровня активности
  activityRadios.forEach(function(radio) {
    radio.addEventListener('change', function() {
      updateActivityCoefficient();
      calculateCalories();
    });
  });

  // Обработчик изменения пола
  genderRadios.forEach(function(radio) {
    radio.addEventListener('change', function() {
      updateGenderFactor();
    });
  });

  // Обработчик для кнопки "Очистить поля и расчет"
  clearButton.addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('age').value = '';
    document.getElementById('height').value = '';
    document.getElementById('weight').value = '';
    document.getElementById('calories-norm').textContent = '0';
    document.getElementById('calories-minimal').textContent = '0';
    document.getElementById('calories-maximal').textContent = '0';
    counterResult.classList.add('counter__result--hidden');
    calculateButton.removeAttribute('disabled');
  });

  // Обработка отправки формы
  form.addEventListener('submit', function(event) {
    event.preventDefault();
    calculateCalories();
  });
});

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Калькулятор калорий</title>
  <link rel="stylesheet" href="styles/stylesmain.css">
  <link rel="stylesheet" href="styles/styleheader.css">
  <link rel="icon" href="include/logo.png" type="image/x-icon">
</head>
<body class="page">
<header class="header-container">
  <div class="logo">
    <img src="include/logo.png" alt="Логотип">
  </div>
  <nav class="nav">
    <ul>
    <li><a href="login.html">Вход</a></li>
      <li><a href="main.php">Калькулятор калорий</a></li>
      <li><a href="quit.php">Привычки</a></li>
      <li><a href="#">Услуги</a></li>
    </ul>
  </nav>
  <div class="user-info">
    <?php
    session_start();
    if (isset($_SESSION['username'])) {
        echo '<span>Вы вошли как: ' . $_SESSION['username']. '</span>';
        echo '<a href="logout.php">Выход</a>';
    } else {
        echo '<span>Вы вошли как: Гость  </span>';
    }
    ?>
  </div>
</header>
<main class="main">
  <div class="container">
    <article class="counter">
      <h1 class="counter__heading heading-main">Калькулятор калорий</h1>
      <form class="counter__form form" name="counter" action="#" method="post">
        <div class="form__parameters">
          <fieldset class="form__item form__parameters" name="parameters">
            <legend class="heading">ПОЛ</legend>
            <ul class="switcher">
              <li class="switcher__item">
                <input id="gender-male" name="gender" value="male" type="radio" checked>
                <label for="gender-male">Мужчина</label>
              </li>
              <li class="switcher__item">
                <input id="gender-female" name="gender" value="female" type="radio">
                <label for="gender-female">Женщина</label>
              </li>
            </ul>
          </fieldset>
          <fieldset class="form__item form__parameters" name="parameters">
            <legend class="visually-hidden">ФИЗИЧЕСКИЕ ПАРАМЕТРЫ</legend>
            <div class="inputs-group">
              <div class="input">
                <div class="input__heading">
                  <label class="heading" for="age">Возраст,</label>
                  <span class="input__heading-unit">лет</span>
                </div>
                <div class="input__wrapper">
                  <input type="text" id="age" name="age" placeholder="0" inputmode="decimal" maxlength="3" required>
                </div>
              </div>
              <div class="input">
                <div class="input__heading">
                  <label class="heading" for="height">Рост,</label>
                  <span class="input__heading-unit">см</span>
                </div>
                <div class="input__wrapper">
                  <input type="text" id="height" name="height" placeholder="0" inputmode="decimal" maxlength="3" required>
                </div>
              </div>
              <div class="input">
                <div class="input__heading">
                  <label class="heading" for="weight">Вес,</label>
                  <span class="input__heading-unit">кг</span>
                </div>
                <div class="input__wrapper">
                  <input type="text" id="weight" name="weight" placeholder="0" inputmode="decimal" maxlength="3" required>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset class="form__item">
            <legend class="heading">ФИЗИЧЕСКАЯ АКТИВНОСТЬ</legend>
            <ul class="radios-group">
              <li class="radio">
                <input id="activity-minimal" name="activity" value="min" type="radio" checked required>
                <label for="activity-minimal" class="radio__wrapper">Минимальная
                  <p class="radio__description">Сидячая работа и нет физических нагрузок</p>
                </label>
              </li>
              <li class="radio">
                <input id="activity-low" name="activity" value="low" type="radio" required>
                <label for="activity-low" class="radio__wrapper">Низкая
                  <p class="radio__description">Редкие, нерегулярные тренировки, активность в быту</p>
                </label>
              </li>
              <li class="radio">
                <input id="activity-medium" name="activity" value="medium" type="radio" required>
                <label for="activity-medium" class="radio__wrapper">Средняя
                  <p class="radio__description">Тренировки 3-5 раз в неделю</p>
                </label>
              </li>
              <li class="radio">
                <input id="activity-high" name="activity" value="high" type="radio" required>
                <label for="activity-high" class="radio__wrapper">Высокая
                  <p class="radio__description">Тренировки 6-7 раз в неделю</p>
                </label>
              </li>
              <li class="radio">
                <input id="activity-maximal" name="activity" value="max" type="radio" required>
                <label for="activity-maximal" class="radio__wrapper">Очень высокая
                  <p class="radio__description">Больше 6 тренировок в неделю и физическая работа</p>
                </label>
              </li>
            </ul>
          </fieldset>
        </div>
        <div class="form__submit">
          <button class="form__submit-button button" name="submit" type="submit">Рассчитать</button>
          <button class="form__reset-button" name="reset" type="button">
            <span>Очистить поля и расчёт</span>
          </button>
        </div>
      </form>
      <section class="counter__result counter__result--hidden">
        <fieldset class="form__item">
          <legend class="heading">Ваша норма калорий и ИМТ</legend>
          <ul class="counter__result-list">
            <li class="counter__result-item">
              <h3><span id="calories-norm">3 800</span> ккал <p>поддержание веса</p></h3>
            </li>
            <li class="counter__result-item">
              <h3><span id="calories-minimal">3 300</span> ккал <p>снижение веса</p></h3>
            </li>
            <li class="counter__result-item">
              <h3><span id="calories-maximal">4 000</span> ккал <p>набор веса</p></h3>
            </li>
            <li class="counter__result-item">
              <h3>Ваш ИМТ: <span id="bmi-result"></span></h3>
              <canvas id="bmi-chart" width="400" height="200"></canvas>
            </li>
          </ul>
        </fieldset>
      </section>
    </article>
  </div>
</main>
<div class="toast">
  <div class="toast-content">
    <i class="fas fa-solid fa-check check"></i>
    <div class="message">
      <span class="text text-1">Готово!</span>
    </div>
  </div>
  <i class="fa-solid fa-xmark close"></i>
  <div class="progress"></div>
</div>
<script src="include/script.js" defer></script>
</body>
</html>

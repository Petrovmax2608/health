// Объявляем переменную timerInterval глобально
let timerInterval;

document.addEventListener("DOMContentLoaded", function() {
    var startBtn = document.getElementById("start-btn");
    var giveUpBtn = document.getElementById("giveup-btn");
    var userId = startBtn.dataset.userId;
    console.log("userId:", userId); // Добавим вывод значения userId

    // Проверяем, есть ли сохраненное время в локальном хранилище
    const storedStartTime = parseInt(localStorage.getItem("startTime_" + userId));
    if (!isNaN(storedStartTime)) {
        // Если время есть, запускаем таймер с этого времени
        startTimer(userId, storedStartTime);
    }

    startBtn.addEventListener("click", function() {
        startTimer(userId);
    });

    giveUpBtn.addEventListener("click", function() {
        giveUp(userId);
    });
    // Функция для запуска таймера
    function startTimer(userId, storedStartTime) {
        console.log("Start timer called with userId:", userId);
        if (userId === 'null') {
            console.log("User ID is null. Timer cannot start.");
            return;
        }
        
        console.log("Start timer called for user:", userId);
        clearInterval(timerInterval);
        let startTime = storedStartTime || new Date().getTime(); // Используем сохраненное время, если оно есть
        console.log("startTime:", startTime);
        if (!storedStartTime) {
            // Если это новый запуск таймера, сохраняем время в локальном хранилище
            localStorage.setItem("startTime_" + userId, startTime.toString());
        }
        timerInterval = setInterval(() => updateTimer(userId), 1000);
    }

    // Функция для остановки таймера и вывода времени
    function giveUp(userId) {
        console.log("Give up called for user:", userId);
        clearInterval(timerInterval);
        localStorage.removeItem("startTime_" + userId);
        const formattedTime = document.getElementById("timer").textContent;
        alert("Вы продержались " + formattedTime);
        document.getElementById("timer").textContent = "00:00:00:00";
    }

    // Функция для обновления таймера
    function updateTimer(userId) {
        console.log("Update timer called for userId:", userId);
        const currentTime = new Date().getTime();
        const startTime = parseInt(localStorage.getItem("startTime_" + userId));
        console.log("startTime:", startTime);
        if (isNaN(startTime)) return;
        const elapsedTime = Math.floor((currentTime - startTime) / 1000);
        const days = Math.floor(elapsedTime / (60 * 60 * 24));
        const hours = Math.floor((elapsedTime % (60 * 60 * 24)) / (60 * 60));
        const minutes = Math.floor((elapsedTime % (60 * 60)) / 60);
        const seconds = elapsedTime % 60;
        const formattedTime = padNumber(days) + ":" + padNumber(hours) + ":" + padNumber(minutes) + ":" + padNumber(seconds);
        console.log("formattedTime:", formattedTime);
        document.getElementById("timer").textContent = formattedTime;
    }

    // Функция для добавления ведущих нулей к числам меньше 10
    function padNumber(number) {
        if (number < 10) {
            return "0" + number;
        }
        return number;
    }
});

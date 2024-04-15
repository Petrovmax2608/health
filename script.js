document.getElementById("login-form").addEventListener("submit", function(event) {
  event.preventDefault();
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  // Here you can perform authentication logic, for example, sending the username and password to a server for validation
  console.log("Username:", username);
  console.log("Password:", password);
});

// Получаем все цитаты
const quotes = document.querySelectorAll('.quote');

// Функция для показа цитаты
function showQuote(index) {
  // Скрываем все цитаты
  quotes.forEach((quote, i) => {
    if (i !== index) {
      quote.style.transform = 'translateX(100%)'; // Сдвигаем вправо за пределы экрана
    }
  });

  // Показываем цитату с указанным индексом
  quotes[index].style.transform = 'translateX(0)'; // Показываем на экране
}

// Инициализируем переменную для индекса текущей цитаты
let currentQuoteIndex = 0;

// Показываем первую цитату сразу после загрузки страницы
showQuote(currentQuoteIndex);

// Устанавливаем интервал для показа следующей цитаты
setInterval(() => {
  // Увеличиваем индекс цитаты
  currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
  
  // Показываем следующую цитату
  showQuote(currentQuoteIndex);
}, 3000); // Изменяйте время между цитатами по необходимости (в миллисекундах)

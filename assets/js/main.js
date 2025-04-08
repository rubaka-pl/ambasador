// Функция для обновления логотипа в зависимости от ширины экрана
function updateLogo() {
    var logo = document.querySelector('.logo-img');
    if (logo) {
        if (window.innerWidth <= 768) {
            logo.src = logo.dataset.logoSmall; // Используем маленький логотип
            logo.classList.add('small-logo'); // Добавляем класс для маленького логотипа
        } else {
            logo.src = logo.dataset.logoDefault; // Используем стандартный логотип
            logo.classList.remove('small-logo'); // Убираем класс для маленького логотипа
        }
    }
}

// Обновляем логотип при загрузке страницы
document.addEventListener('DOMContentLoaded', function () {
    updateLogo();
});

// Обновляем логотип при изменении размера окна
window.addEventListener('resize', function () {
    updateLogo();
});


//modal for contaсts
function openContactModal() {
    document.getElementById('contactModal').style.display = 'block';
}

function closeContactModal() {
    document.getElementById('contactModal').style.display = 'none';
}


// symbols counter in modal window
document.getElementById('message').addEventListener('input', function (e) {
    document.getElementById('charCount').textContent = e.target.value.length;
});

// VALIDATION BEFORE SENDING
function validateForm() {
    let email = document.getElementById('email').value.trim();
    let phone = document.getElementById('phone').value.trim();
    let message = document.getElementById('message').value.trim();
    let errorMessage = document.getElementById('error-message');

    if (!email && !phone) {
        errorMessage.textContent = "Podaj e-mail lub numer telefonu!";
        return false; // отменяет отправку формы
    }

    if (!message) {
        errorMessage.textContent = "Treść wiadomości nie może być pusta!";
        return false;
    }

    errorMessage.textContent = ""; // очищаем сообщение об ошибке
    return true; // форма отправляется
}


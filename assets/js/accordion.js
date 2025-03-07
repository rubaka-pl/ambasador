jQuery(document).ready(function ($) {
    $('.faq-question').on('click', function (event) {
        event.preventDefault(); // Предотвращаем действие по умолчанию
        $(this).parent('.faq-item').toggleClass('active');
    });
});
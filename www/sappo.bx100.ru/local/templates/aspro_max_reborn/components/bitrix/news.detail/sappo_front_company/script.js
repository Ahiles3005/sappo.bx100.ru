function toggleParagraphsBasedOnScreenWidth() {
    var introtext = $(".company-introtext");
    var paragraphs = $(".company-introtext > *:not(:first-of-type):not(.toggle-arrow-container)");
    var arrow = $(".toggle-arrow");

    // Проверяем, есть ли скрытые параграфы
    if (paragraphs.length > 0) {
        paragraphs.hide(); // Скрываем все параграфы, кроме первого
    }

     // Если в .introtext только один параграф, убираем градиент
    if ($(".company-introtext p").length <= 1) {
        introtext.addClass("no-gradient");
        arrow.hide();
    } else {
        introtext.removeClass("no-gradient");
        arrow.show();
    }

    // Функция для раскрытия текста при клике
    arrow.off('click').on('click', function() {
        if (introtext.hasClass("expanded")) {
            // Скрываем текст при повторном клике
            paragraphs.slideUp(500);
            introtext.removeClass("expanded");
            arrow.removeClass("up").addClass("down");
        } else {
            // Показываем текст и убираем градиент
            paragraphs.slideDown(500);
            introtext.addClass("expanded");
            arrow.removeClass("down").addClass("up");
        }
    });
}

// Изначальная проверка при загрузке страницы
toggleParagraphsBasedOnScreenWidth();
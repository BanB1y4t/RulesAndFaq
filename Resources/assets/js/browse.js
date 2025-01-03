$('.menu-rulesandfaq > .menu-rulesandfaq-header').on('click', function () {
    const $currentButton = $(this).parent();
    const isAlreadyOpen = $currentButton.hasClass('show');

    // Переключаем класс 'show' на текущей вкладке
    $currentButton.toggleClass('show');

    // Открываем текущее меню, если оно закрыто
    if (!isAlreadyOpen) {
        $currentButton.addClass('show');
    }
});

// Заменяем символы \n на <br> в заголовках
document.querySelectorAll('.rulesandfaq-header').forEach(header => {
    header.innerHTML = header.innerHTML.replace(/\\n/g, '<br>');
});

$(document).on('click', '.menu-rulesandfaq-content-item', function (e) {
    e.preventDefault();

    const rulesId = $(this).data('filter-rule');
    const faqId = $(this).data('filter-question');

    $.ajax({
        url: '/rulesandfaq/load',
        type: 'POST',
        data: {
            rules_id: rulesId,
            faq_id: faqId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                $('.rulesandfaq-title').text(response.title); // Обновление заголовка
                $('.rulesandfaq-block').html(response.block); // Обновление содержимого
            } else {
                alert('Ошибка загрузки данных.');
            }
        },
        error: function () {
            alert('Ошибка запроса.');
        }
    });
});


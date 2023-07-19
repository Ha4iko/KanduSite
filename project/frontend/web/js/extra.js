function ajaxPopup(url) {
    $.get(url)
        .then(response => {
            let container = $('#ajaxPopups');
            container.empty().append(response);

            $('.js-popup-redirect').val(location.href);

            setTimeout(() => {
                container.find('.popup')
                    .addClass('active anime')
                    .find('.popup-wrap')
                    .addClass('active');
                selectReInit();
                datepickerReInit();
                if (typeof selectCustomReInit === "function") {
                    selectCustomReInit();
                }

                $('html, body').addClass('popuped');
            }, 250)

        });

    $('.popups').addClass('active anime');
}

function popupFastClose(popup) {
    if (popup.length) {
        popup.removeClass('anime');
        $('.popups').removeClass('anime').removeClass('active');
        popup.removeClass('active');
        popup.find('.popup-wrap').removeClass('active');
        $('html, body').removeClass('popuped');
    }
}

$(document).on('pjax:send', function(e) {
    $(e.target).addClass('waiting');
});

$(document).on('pjax:complete', function(e, xhr) {
    if ((xhr.responseText || '').length) {
        selectReInit();
        datepickerReInit();
        cellEdit();
        tabsInit();
        if (typeof autocompleteParticipants === "function") {
            autocompleteParticipants();
        }
        if (typeof datepickerWithClearReInit === "function") {
            datepickerWithClearReInit();
        }
        if (typeof selectCustomReInit === "function") {
            selectCustomReInit();
        }
        $(e.target).removeClass('waiting');
    }
});

$(document).on('pjax:error', function(e) {
    alert('Error. Try again, please');
    $(e.target).removeClass('waiting');
});

$(document).on('click', '.js-ajax-popup', function(e) {
    e.preventDefault();
    let url = $(e.target).attr('data-url');

    ajaxPopup(url);
});

$(window).on('hashchange', function(e) {
    $('[name="__hash"]').val(window.location.hash);
});

/**
 * Make participants unique in selector
 *
 * @param selector
 * @param allNicks
 * @param reInit
 */
function updateAvailableParticipants(selector, allNicks, reInit = true) {
    let selectedParticipants = $(selector)
        .map((index, el) => parseInt(el.value))
        .get()
        .filter(i => !!i);

    let available = [...allNicks].filter(item => selectedParticipants.indexOf(item.id) === -1);

    $(selector).each((index, el) => {
        let options = [...available];
        let select = $(el);
        let value = parseInt(select.val());
        let valueText = select.find('option:selected').text();

        if (value && value !== 0) {
            options.push({id: value, name: valueText});
        }

        options.sort((a, b) => a.name.localeCompare(b.name));
        options.unshift({id: 0, name: 'choose'});

        let html = options.map(item => `<option value="${item.id}">${item.name}</option>`).join('');
        select.empty().html(html).val(value);
    });

    $(document).one('change', selector, function(e) {
        updateAvailableParticipants(selector, allNicks, reInit);
    });

    reInit && selectCustomReInit();
}
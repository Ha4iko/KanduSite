function datepickerWithClearInitDesktop() {
    $('.js-datepicker-clear').each(function (i, item) {

        let date = $(item).find('input').val();
        let initialized = false;

        $(item)
            .find('input')
            .datepicker({
                language: 'en',
                autoClose: true,
                clearButton: true,
                onShow: function () {
                    $(item).addClass('active');
                },
                onHide: function () {
                    $(item).removeClass('active');
                },
                onSelect: function (formattedDate, date, inst) {
                    if (!date) {
                        $(item).find('input').datepicker().data('datepicker').hide();
                    }

                    if (formattedDate.length > 0) {
                        $(item).addClass('selected');
                        $(item).find('.datepicker-el__btn-text').text(formattedDate);
                    } else {
                        $(item).removeClass('selected');
                        $(item).find('.datepicker-el__btn-text').text('choose');
                    }

                    if (initialized) {
                        setTimeout(() => $(item).trigger('datepickerclearupdated'), 200);
                    }
                }
            });
        if (date) {
            $(item).find('input').datepicker().data('datepicker').selectDate(new Date(date));
        }
        initialized = true;
    });
}

function datepickerWithClearInitMobile() {
    $('.js-datepicker-clear').each(function (i, item) {
        let date = $(item).find('input').val();
        let initialized = false;

        $(item)
            .find('input')
            .datepicker({
                language: 'en',
                clearButton: true,
                inline: true,
                onSelect: function (formattedDate, date, inst) {
                    if (formattedDate.length > 0) {
                        $(item).addClass('selected');
                        $(item).find('.datepicker-el__btn-text').text(formattedDate);
                        $(item).removeClass('active');
                        setTimeout(function () {
                            $('html').removeClass('overflow');
                            $('body').removeClass('selected');
                            for (var i = 0; i < window.selectedElements.length; i++) {
                                $(item)
                                    .closest(window.selectedElements[i])
                                    .removeClass('active');
                            }
                        }, 300);
                    } else {
                        $(item).removeClass('selected');
                        $(item).find('.datepicker-el__btn-text').text('choose');
                    }

                    if (initialized) {
                        setTimeout(() => $(item).trigger('datepickerclearupdated'), 200);
                    }
                },
            });


        if (date) {
            $(item).find('input').datepicker().data('datepicker').selectDate(new Date(date));
        }
        initialized = true;

        $(item)
            .find('.datepicker-el__btn')
            .on('click', function () {
                $(item).find('.datepicker-el__drop').css({
                    width: window.innerWidth,
                    height: window.innerHeight,
                });
                $(item).addClass('active');
                $('html').addClass('overflow');
                $('body').addClass('selected');
                for (var i = 0; i < window.selectedElements.length; i++) {
                    $(item).closest(window.selectedElements[i]).addClass('active');
                }
                if ($(item).closest('.popup').length) {
                    disableScroll($(item).find('.datepicker-el__inner')[0]);
                }
            });

        $(item)
            .find('.js-close')
            .on('click', function () {
                $(item).removeClass('active');
                setTimeout(function () {
                    $('html').removeClass('overflow');
                    $('body').removeClass('selected');
                    for (var i = 0; i < window.selectedElements.length; i++) {
                        $(item).closest(window.selectedElements[i]).removeClass('active');
                    }
                }, 300);
            });
    });

}

function datepickerWithClear() {
    if (window.innerWidth < 768) {
        datepickerWithClearInitMobile();
    } else {
        datepickerWithClearInitDesktop();
    }
}

function datepickerWithClearReInit() {
    $('.js-datepicker-clear').each(function (i, item) {
        $(item).find('input').datepicker().data('datepicker').destroy();
    });
    datepickerWithClear();
}
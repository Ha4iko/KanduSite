function selectCustomInitDesktop(item) {
    function scroll() {
        var options = $('.select2-results__options');
        var results = $('.select2-results');
        if (results.innerHeight() < options.innerHeight()) {
            if (
                typeof results.attr('style') !== typeof undefined &&
                results.attr('style') !== false
            ) {
                results.css('width', options.innerWidth());
            } else {
                results.css('width', options.innerWidth() + 5);
            }
            setTimeout(function () {
                new SimpleBar(results.get(0));
            }, 0);
        }
    }
    $(item)
        .select2({
            minimumResultsForSearch: -1,
            width: '100%',
            /*allowClear: true,*/
            placeholder: {
                id: 0,
                text: 'choose',
            },
            selectionCssClass: 'style-' + $(item).attr('data-style'),
            dropdownCssClass:
                'style-' + $(item).attr('data-style') + ' ' + $(item).attr('data-drop'),
            dropdownAutoWidth: true,
        })
        .on('select2:open', function (e) {
            if (window.innerWidth > 768) {
                setTimeout(function () {
                    $(item).closest('.select').addClass('active');
                    $('.select2-dropdown').addClass('active');
                    setTimeout(function () {
                        scroll();
                    }, 0);
                }, 100);
            }
        })
        .on('select2:closing', function (e) {
            if (window.innerWidth > 767) {
                $(item).closest('.select').removeClass('active');
                $('.select2-dropdown').removeClass('active');
            }
        });
}
function selectCustomInitMobile(item) {
    var select = $(item).closest('.select');
    $(item)
        .select2({
            minimumResultsForSearch: -1,
            width: '100%',
            closeOnSelect: false,
            allowClear: true,
            selectionCssClass: 'style-' + $(item).attr('data-style'),
            dropdownCssClass:
                'style-' + $(item).attr('data-style') + ' ' + $(item).attr('data-drop'),
            dropdownAutoWidth: true,
            dropdownParent: select.find('.select-drop'),
        })
        .on('select2:closing', function (e) {
            if (window.innerWidth < 768) {
                e.preventDefault();
            }
        })
        .on('select2:closed', function (e) {
            if (window.innerWidth < 768) {
                $(item).select2('open');
            }
        })
        .on('select2:select', function (e) {
            if (window.innerWidth < 768) {
                select.toggleClass('active');
                if (!select.hasClass('active')) {
                    setTimeout(function () {
                        $('html').removeClass('overflow');
                        $('body').removeClass('selected');
                        for (var i = 0; i < window.selectedElements.length; i++) {
                            $(item).closest(window.selectedElements[i]).removeClass('active');
                        }
                    }, 300);
                }
            }
        })
        .on('select2:open', function (e) {
            const evt = 'scroll.select2';
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
    $(item).select2('open');
    select.find('.select-btn').on('click', function (e) {
        if (window.innerWidth < 768) {
            if (!select.hasClass('active')) {
                $('.select').removeClass('active');
            }
            select.find('.select-drop').css({
                width: window.innerWidth,
                height: window.innerHeight,
            });
            select.addClass('active');
            $('html').addClass('overflow');
            $('body').addClass('selected');
            for (var i = 0; i < window.selectedElements.length; i++) {
                $(item).closest(window.selectedElements[i]).addClass('active');
            }
            if (select.closest('.popup').length) {
                disableScroll(select.find('.select2-results')[0]);
            }
        }
    });
    select.find('.js-close').on('click', function () {
        if (window.innerWidth < 768) {
            select.removeClass('active');
            setTimeout(function () {
                $('html').removeClass('overflow');
                $('body').removeClass('selected');
                for (var i = 0; i < window.selectedElements.length; i++) {
                    $(item).closest(window.selectedElements[i]).removeClass('active');
                }
            }, 300);
        }
    });
}

function selectCustomDestroy() {
    $('.js-custom-select').select2('destroy');
    window.select2CustomDesktop = false;
    window.select2CustomMobile = false;
}
function selectCustomInit() {
    if (window.innerWidth < 768) {
        if (!window.select2CustomMobile) {
            if (window.select2CustomDesktop) {
                $('.js-custom-select').each(function (i, item) {
                    $(item).select2('destroy');
                });
                window.select2CustomDesktop = false;
            }
            window.select2CustomMobile = true;
            $('.js-custom-select').each(function (i, item) {
                selectCustomInitMobile(item);
            });
        }
    } else {
        if (!window.select2CustomDesktop) {
            if (window.select2CustomMobile) {
                $('.js-custom-select').each(function (i, item) {
                    $(item).select2('destroy');
                });
                window.select2CustomMobile = false;
            }
            window.select2CustomDesktop = true;
            $('.js-custom-select').each(function (i, item) {
                selectCustomInitDesktop(item);
            });
        }
    }
}
function selectCustomReInit() {
    $('.js-custom-select').each(function() {
        try {
            $(this).select2('destroy');
        } catch(e) {
            console.debug('Select already initialized');
        }
    });
    window.select2CustomDesktop = false;
    window.select2CustomMobile = false;
    selectCustomInit();
}

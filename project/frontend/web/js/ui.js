function selectReInit() {
    $('.js-select').each(function() {
        try {
            $(this).select2('destroy');
        } catch(e) {
            console.debug('Select already initialized');
        }
    });
    window.select2Desktop = false;
    window.select2Mobile = false;
    selectInit();
}

function datepickerReInit() {
    $('.js-datepicker').each(function (i, item) {
        $(item).find('input').datepicker().data('datepicker').destroy();
    });
    window.datepickerDesktop = false;
    window.datepickerMobile = false;
    datepicker();
}
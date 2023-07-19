$(document).on('pjax:send', function(e) {
    $(e.target).addClass('waiting');
});

$(document).on('pjax:complete', function(e) {
    $(e.target).removeClass('waiting');
});

$(document).on('pjax:error', function(e) {
    alert('Error. Try again, please');
});
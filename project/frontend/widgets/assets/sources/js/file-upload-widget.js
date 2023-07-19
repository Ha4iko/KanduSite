$(document).on('change', __file_upload_widget_data.fileInputSelector, function(e) {
    let files = e.target.files;
    let formData = new FormData();
    for (let file of files) {
        formData.append('files[]', file);
    }

    $(__file_upload_widget_data.loadingSelector).addClass('waiting');

    $.ajax({
        url: __file_upload_widget_data.uploadUrl,
        data: formData,
        type: 'POST',
        contentType: false,
        processData: false,
    }).then(response => {
        if (response.files[0]) {
            $(__file_upload_widget_data.inputSelector).val(response.files[0]);
            $(__file_upload_widget_data.previewSelector)
                .attr('src', response.files[0])
                .parent().addClass('active');
        } else {
            alert('Error when upload file.');
        }
    }).catch(error => {
        alert('Error when upload file.');
    }).done(() => {
        $(this)
            .closest(__file_upload_widget_data.loadingSelector)
            .removeClass('waiting');
    })
});
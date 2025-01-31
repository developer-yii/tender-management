handleFormSubmission('#addForm', createUrl, 'addModal', listUrl);

$('body').on('click', '.edit-admin', function () {
    var id = $(this).data('id');
    $(".user_id").val(id);
    $.ajax({
        type: "POST",
        url: getUrl,
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#description').val(data.description);
            $('#status').val(data.is_active ? 1 : 0);

            $('#oldProfile').html(`<img src="${data.profile_photo_url}" alt="Profilbild" class="img-thumbnail" style="width:70px;">`);
            $('#addModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addModal').find('#exampleModalLabel').html("Admin bearbeiten");
            $('#addModal').find('#password-label').html("Passwort");
            $('#addModal').find('#profile-label').html("Profilbild");
        }
    });
});

$('body').on('click', '.delete-admin', function () {
    var id = $(this).attr('data-id');
    var confirmed = confirm('Sind Sie sicher, dass Sie diesen Administrator löschen möchten?');

    if (confirmed) {
        $.ajax({
            url: deleteUrl,
            data: { id: id },
            type: 'POST',
            dataType: 'json',
            success: function (result) {
                toastr.success(result.message);
                setTimeout(function() {
                    window.location.reload();
                }, 5000);
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                var responseMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : errorMessage;
                toastr.error("Error - " + responseMessage);
            }
        });
    } else {
        // User canceled the action, do nothing
        toastr.info('Deletion canceled.');
    }

});

$('#addModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addForm')[0].reset();
    $('#user_id').val('');
    $('#oldProfile').html('');
    $('#addModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addModal').find('#exampleModalLabel').html("Admin hinzuhügen");
    $('#addModal').find('#password-label').html("Passwort*");
    $('#addModal').find('#profile-label').html("Profilbild*");
});



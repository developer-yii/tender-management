handleFormSubmission('#addForm', createUrl, 'addModal', listUrl);

$('body').on('click', '.edit-employee', function () {
    var id = $(this).data('id');
    $(".employee_id").val(id);
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
            $('#user_status').val(data.is_active ? 1 : 0);
            let selectedTags = data.tags.map(tag => tag.id); // Extract tag IDs from the data
            $('#tags').val(selectedTags).trigger('change');

            $('#oldProfile').html(`<img src="${data.profile_photo_url}" alt="Profile Photo" class="img-thumbnail" style="width:70px;">`);
            $('#oldCv').html(`<a href="${data.cv_url}" target="_blank" class="btn btn-primary">View CV</a>`);
            $('#oldDocument').html(`<a href="${data.document_url}" target="_blank" class="btn btn-primary">View Document</a>`);

            $('#addModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addModal').find('#exampleModalLabel').html("Mitarbeiter bearbeiten");
            $('#addModal').find('#password-label').html("Password");
            $('#addModal').find('#profile-label').html("Profile Photo");
            $('#addModal').find('#cv-label').html("Upload CV");
            $('#addModal').find('#document-label').html("Upload Document");
        }
    });
});

$('#addModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addForm')[0].reset();
    $('#employee_id').val('');
    $('#tags').val('').trigger('change');
    $('#oldProfile').html('');
    $('#oldCv').html('');
    $('#oldDocument').html('');
    $('#addModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addModal').find('#exampleModalLabel').html("Mitarbeiter hinzuhügen");
    $('#addModal').find('#password-label').html("Password*");
    $('#addModal').find('#profile-label').html("Profile Photo*");
    $('#addModal').find('#cv-label').html("Upload CV*");
    $('#addModal').find('#document-label').html("Upload Document*");
});



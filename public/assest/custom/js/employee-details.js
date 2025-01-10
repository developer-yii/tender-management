$('body').on('click', '.edit-employee', function () {
    var id = $(this).data('id');
    $(".employee_id").val(id);
    $.ajax({
        type: "POST",
        url: getEmployeeUrl,
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            $('#first_name').val(data.first_name);
            $('#last_name').val(data.last_name);
            $('#email').val(data.email);
            $('#description').val(data.description);
            $('#user_role').val(data.role);
            $('#user_status').val(data.is_active ? 1 : 0);
            let selectedTags = data.tags.map(tag => tag.id); // Extract tag IDs from the data
            $('#tags').val(selectedTags).trigger('change');

            $('#oldProfile').html(`<img src="${data.profile_photo_url}" alt="Profile Photo" class="img-thumbnail" style="width:70px;">`);
            $('#oldCv').html(`<a href="${data.cv_url}" target="_blank" class="btn btn-primary">View CV</a>`);
            $('#oldDocument').html(`<a href="${data.document_url}" target="_blank" class="btn btn-primary">View Document</a>`);

            $('#addEmployeeModal').find('button[type="submit"]').html("Aktualisieren");
            $('#addEmployeeModal').find('#exampleModalLabel').html("Mitarbeiter bearbeiten");
            $('#addEmployeeModal').find('#password-label').html("Password");
            $('#addEmployeeModal').find('#profile-label').html("Profile Photo");
            $('#addEmployeeModal').find('#cv-label').html("Upload CV");
            $('#addEmployeeModal').find('#document-label').html("Upload Document");
        }
    });
});
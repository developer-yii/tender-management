handleFormSubmission('#addForm', createUrl, 'addModal', listUrl);
// $('#addForm').submit(function (event) {
//     event.preventDefault();
//     $('.error').html("");

//     const $form = $(this);
//     // const $submitButton = $form.find('button[type="submit"]');
//     const dataString = new FormData($form[0]);

//     $.ajax({
//         type: "POST",
//         url: createUrl,
//         data: dataString,
//         contentType: false,
//         processData: false,
//         cache: false,
//         async: false,
//         beforeSend: function () {
//             $("#loaderOverlay").fadeIn();
//         },
//         success: function (result) {
//             $("#loaderOverlay").fadeOut();
//             if (result.status == true) {
//                 $form[0].reset();
//                 toastr.success(result.message);
//                 $('#addModal').modal('hide');
//                 if(result.isNew){
//                     window.location.href = listUrl;
//                 }else{
//                     window.location.reload();
//                 }
//             } else {
//                 first_input = "";
//                 $('.error').html("");
//                 $.each(result.error, function (key) {
//                     if (first_input == "") first_input = key;
//                     $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
//                 });
//                 $('#addForm').find("#" + first_input).focus();
//             }
//         },
//         error: function (error) {
//             $("#loaderOverlay").fadeOut();
//             alert('Something went wrong!', 'error');
//         }
//     });
// });

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
            $('#user_role').val(data.role);
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



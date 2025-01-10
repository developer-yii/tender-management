// handleFormSubmission('#addemployee', createEmployeeUrl, 'addReferenceModal', addEmployeeModal);
$('#addemployee').submit(function (event) {
    event.preventDefault();
    $('.error').html("");

    const $form = $(this);
    const $submitButton = $form.find('button[type="submit"]');
    const dataString = new FormData($form[0]);

    $.ajax({
        type: "POST",
        url: createEmployeeUrl,
        data: dataString,
        contentType: false,
        processData: false,
        cache: false,
        async: false,
        beforeSend: function () {
            $submitButton.prop('disabled', true);
        },
        success: function (result) {
            $submitButton.prop('disabled', false);
            if (result.status == true) {
                $form[0].reset();
                toastr.success(result.message);
                $('#addEmployeeModal').modal('hide');
                if(result.isNew){
                    window.location.href = employeeListUrl;
                }else{
                    window.location.reload();
                }
            } else {
                first_input = "";
                $('.error').html("");
                $.each(result.error, function (key) {
                    if (first_input == "") first_input = key;
                    $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                });
                $('#addemployee').find("#" + first_input).focus();
            }
        },
        error: function (error) {
            $submitButton.prop('disabled', false);
            alert('Something went wrong!', 'error');
        }
    });
});

$('#addEmployeeModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addemployee')[0].reset();
    $('#employee_id').val('');
    $('#tags').val('').trigger('change');
    $('#oldProfile').html('');
    $('#oldCv').html('');
    $('#oldDocument').html('');
    $('#addEmployeeModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addEmployeeModal').find('#exampleModalLabel').html("Mitarbeiter hinzuhügen");
    $('#addEmployeeModal').find('#password-label').html("Password*");
    $('#addEmployeeModal').find('#profile-label').html("Profile Photo*");
    $('#addEmployeeModal').find('#cv-label').html("Upload CV*");
    $('#addEmployeeModal').find('#document-label').html("Upload Document*");
});

// $('#addreference').submit(function (event) {
//     event.preventDefault();
//     $('.error').html("");

//     const $form = $(this);
//     const $submitButton = $form.find('button[type="submit"]');
//     const dataString = new FormData($form[0]);

//     $.ajax({
//         type: "POST",
//         url: createReferenceUrl,
//         data: dataString,
//         contentType: false,
//         processData: false,
//         cache: false,
//         async: false,
//         beforeSend: function () {
//             $submitButton.prop('disabled', true);
//         },
//         success: function (result) {
//             $submitButton.prop('disabled', false);
//             if (result.status == true) {
//                 $form[0].reset();
//                 toastr.success(result.message);
//                 $('#addReferenceModal').modal('hide');
//                 if(result.isNew){
//                     window.location.href = referenceListUrl;
//                 }else{
//                     window.location.reload();
//                 }
//             } else {
//                 first_input = "";
//                 $('.error').html("");
//                 $.each(result.error, function (key) {
//                     if (first_input == "") first_input = key;
//                     if (key === 'start_date' || key === 'end_date') {
//                         if (result.error.start_date && result.error.end_date) {
//                             $('#start_date').closest('.dateSelect').find('.error').html(result.error.start_date.join(', '));
//                         }
//                         // If only end_date has an error (when start_date is not blank)
//                         else if (result.error.end_date) {
//                             $('#end_date').closest('.dateSelect').find('.error').html(result.error.end_date.join(', '));
//                         }
//                         // If only start_date has an error (when end_date is not blank)
//                         else if (result.error.start_date) {
//                             $('#start_date').closest('.dateSelect').find('.error').html(result.error.start_date.join(', '));
//                         }
//                     } else {
//                         $('#' + key).closest('.form-group').find('.error').html(result.error[key].join(', '));
//                     }
//                 });
//                 $('#addreference').find("#" + first_input).focus();
//             }
//         },
//         error: function (error) {
//             $submitButton.prop('disabled', false);
//             alert('Something went wrong!', 'error');
//         }
//     });
// });

handleFormSubmission('#addreference', createReferenceUrl, 'addReferenceModal', referenceListUrl);

$('#addReferenceModal').on('hidden.bs.modal', function () {
    $('.error').html("");
    $('#addreference')[0].reset();
    $('#reference_id').val('');
    $('#tags').val('').trigger('change');
    $('#oldFileWord').html('');
    $('#oldFilePdf').html('');
    $('#addReferenceModal').find('button[type="submit"]').html("Hinzufügen");
    $('#addReferenceModal').find('#exampleModalLabel').html("Referenz hinzufügen");
    $('#addReferenceModal').find('#file-word-label').html("File(Word)*");
    $('#addReferenceModal').find('#file-pdf-label').html("File(PDF)*");
});
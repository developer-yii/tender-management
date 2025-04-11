$('body').on('click', '.edit-company-profile', function () {

    $.ajax({
        type: "POST",
        url: getCompanyDetailsUrl,
        dataType: 'json',
        success: function (data) {
            $('#name').val(data.name);
            $('#art').val(data.type);
            $('#address').val(data.address);
            $('#managing_director').val(data.managing_director);
            $('#bank_name').val(data.bank_name);
            $('#iban_number').val(data.iban_number);
            $('#bic_number').val(data.bic_number);
            $('#ust_id').val(data.vat_id);
            $('#trade_register').val(data.trade_register);
            $('#email').val(data.email);
            $('#phone').val(data.phone);
            $('#website_url').val(data.website_url);
        }
    });
});

$('#addcompanydetail').submit(function (event) {
    event.preventDefault();
    $('.error').html("");

    const $form = $(this);
    const $submitButton = $form.find('button[type="submit"]');
    const dataString = new FormData($form[0]);

    $.ajax({
        type: "POST",
        url: createCompanyDetailsUrl,
        data: dataString,
        contentType: false,
        processData: false,
        cache: false,
        async: true,
        beforeSend: function () {
            $("#loaderOverlay").fadeIn();
        },
        success: function (result) {
            $("#loaderOverlay").fadeOut();
            if (result.status == true) {
                $form[0].reset();
                toastr.success(result.message);
                $('#companyDetailModal').modal('hide');
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
                $('#addcompanydetail').find("#" + first_input).focus();
            }
        },
        error: function (error) {
            $("#loaderOverlay").fadeOut();
            alert('Etwas ist schiefgelaufen!', 'error');
        }
    });
});
$(document).ready(function () {
    var mainTable = $('#mainTable').DataTable({
        // language: language_check(),
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            type: "GET",
            url: listUrl,
        },
        order: [0, 'desc'],
        columns: [
            { data: 'id', name: 'id', visible:false},
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'action', orderable: false, searchable: false, className: 'text-right' },
        ],
        
    });

    $('#addabgabeform').submit(function (event) {
        event.preventDefault();
        $('.error').html("");

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const dataString = new FormData($form[0]);

        $.ajax({

            type: "POST",
            url: createUrl,
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
                    $('#addAbgabeformModal').modal('hide');
                    $('#mainTable').DataTable().ajax.reload();
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function (key) {
                        if (first_input == "") first_input = key;
                        $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                    });
                    $('#addabgabeform').find("#" + first_input).focus();
                }
            },
            error: function (error) {
                $submitButton.prop('disabled', false);
                alert('Etwas ist schiefgelaufen!', 'error');
            }
        });
    });

    $('body').on('click', '.edit-abgabeform', function () {
        var id = $(this).data('id');
        $(".abgabeform_id").val(id);
        $.ajax({
            type: "POST",
            url: getUrl,
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                $('#name').val(data.name);
                $('#addAbgabeformModal').find('button[type="submit"]').html("Aktualisieren");
                $('#addAbgabeformModal').find('#exampleModalLabel').html("Abgabeform bearbeiten");
            }
        });
    });

    $('#addAbgabeformModal').on('hidden.bs.modal', function () {
        $('.error').html("");
        $('#addabgabeform')[0].reset();
        $('#abgabeform_id').val("");
        $('#addAbgabeformModal').find('button[type="submit"]').html("Speichern");
        $('#addAbgabeformModal').find('#exampleModalLabel').html("Abgabeform hinzufügen");
    });   
});
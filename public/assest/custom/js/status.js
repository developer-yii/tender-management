$(document).ready(function () {
    var statusTable = $('#statusTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            type: "GET",
            url: statusListUrl,
        },
        order: [0, 'desc'],
        columns: [
            { data: 'id', name: 'id', visible:false},
            { data: 'title', name: 'title' },
            {
                data: 'icon',
                name: 'icon',
                render: function (data, type, row) {
                    return '<img src="' + data +'" height="20">';
                },
                orderable: false,
                searchable: false
            },
            { data: 'created_at', name: 'created_at'},
            { data: 'updated_at', name: 'updated_at'},
            { data: 'action', orderable: false, searchable: false, className: 'text-right' },
        ],
    });

    $('#addstatus').submit(function (event) {
        event.preventDefault();
        $('.error').html("");

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const dataString = new FormData($form[0]);

        $.ajax({

            type: "POST",
            url: createStatusUrl,
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
                    $('#addStatusModal').modal('hide');
                    setTimeout(function(){
                        window.location.reload()
                    }, 1000);
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function (key) {
                        if (first_input == "") first_input = key;
                        $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                    });
                    $('#addstatus').find("#" + first_input).focus();
                }
            },
            error: function (error) {
                $submitButton.prop('disabled', false);
                alert('Etwas ist schiefgelaufen!', 'error');
            }
        });
    });

    $('body').on('click', '.edit-status', function () {
        var id = $(this).data('id');
        $(".status_id").val(id);
        $.ajax({
            type: "POST",
            url: getStatusUrl,
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                $('#title').val(data.title);
                $('#addStatusModal').find('button[type="submit"]').html("Aktualisieren");
                $('#addStatusModal').find('#exampleModalLabel').html("Status bearbeiten");
                $('#addStatusModal').find('.icon-label').html("Icon");
            }
        });
    });

    $('#addStatusModal').on('hidden.bs.modal', function () {
        $('.error').html("");
        $('#addstatus')[0].reset();
        $('#status_id').val("");
        $('#addStatusModal').find('button[type="submit"]').html("Speichern");
        $('#addStatusModal').find('#exampleModalLabel').html("Status hinzufügen");
        $('#addStatusModal').find('.icon-label').html("Icon*");
    });
});
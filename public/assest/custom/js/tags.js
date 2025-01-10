$(document).ready(function () {
    var tagsTable = $('#tagsTable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 25,
        ajax: {
            type: "GET",
            url: tagListUrl,
        },
        order: [0, 'desc'],
        columns: [
            { data: 'id', name: 'id'},
            { data: 'name', name: 'name' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],
    });

    $('#addtag').submit(function (event) {
        event.preventDefault();
        $('.error').html("");

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const dataString = new FormData($form[0]);

        $.ajax({

            type: "POST",
            url: createTagUrl,
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
                    $('#addTagModal').modal('hide');
                    $('#tagsTable').DataTable().ajax.reload();
                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function (key) {
                        if (first_input == "") first_input = key;
                        $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                    });
                    $('#addtag').find("#" + first_input).focus();
                }
            },
            error: function (error) {
                $submitButton.prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });

    $('body').on('click', '.edit-tag', function () {
        var id = $(this).data('id');
        $(".tag_id").val(id);
        $.ajax({
            type: "POST",
            url: getTagUrl,
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                $('#name').val(data.name);
                $('#addTagModal').find('button[type="submit"]').html("Update");
                $('#addTagModal').find('#exampleModalLabel').html("Edit Tag");
            }
        });
    });

    $('#addTagModal').on('hidden.bs.modal', function () {
        $('.error').html("");
        $('#addtag')[0].reset();
        $('#tag_id').val("");
        $('#addTagModal').find('button[type="submit"]').html("Save");
        $('#addTagModal').find('#exampleModalLabel').html("Add Tag");
    });

    $('body').on('click', '.delete-tag', function () {
        var id = $(this).attr('data-id');
        var confirmed = confirm('Are you sure you want to delete this tag?');

        if (confirmed) {
            $.ajax({
                url: deleteTagUrl,
                data: { id: id },
                type: 'POST',
                dataType: 'json',
                success: function (result) {
                    toastr.success(result.message);
                    $('#tagsTable').DataTable().ajax.reload();
                },
                error: function (error) {
                    console.error('Error response:', error.responseJSON || error);
                    toastr.error('An error occurred while deleting the tag.');
                }
            });
        } else {
            // User canceled the action, do nothing
            toastr.info('Deletion canceled.');
        }

    });

    // clearErrorOnInput('#addingredienttype');
});
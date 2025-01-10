let offset = 0; // Initial offset
const limit = 6; // Number of records to load per request
let isLoading = false; // Prevent multiple simultaneous AJAX requests
let hasMoreData = true;

function fetchServers() {
    if (isLoading || !hasMoreData) {
        return;
    }

    isLoading = true;
    $('#loading').show();

    $.ajax({
        url: serverListUrl,
        type: "GET",
        data: { offset: offset },
        success: function (response) {
            if (response.html) {
                $('#serverList').append(response.html);
                offset += limit; // Update offset for the next batch

                // If no more data, unbind the scroll event
                if (!response.hasMore) {
                    hasMoreData = false;
                    // $(window).off('scroll');
                    $('.loginSection').off('scroll');
                }
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
        complete: function () {
            isLoading = false; // Allow new requests
            $('#loading').hide();
        }
    });
}

// Check if user has scrolled to the bottom
// function isScrolledToBottom() {
//     return $(window).scrollTop() + $(window).height() >= $(document).height() - 50;
// }

function isScrolledToBottom() {
    return $('.loginSection').scrollTop() + $('.loginSection').height() >= $('.loginSection')[0].scrollHeight - 50;
}

// Add a click event listener to all btnCopy buttons
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('btnCopy')) {
        document.querySelectorAll('.btnCopy').forEach(button => {
            button.innerText = "KOPIEREN";
        });

        let inputElement;
        if (e.target.closest('.form-group').querySelector('input')) {
            inputElement = e.target.closest('.form-group').querySelector('input');
        }

        if (inputElement && (inputElement.tagName === 'INPUT' || inputElement.classList.contains('password'))) {
            if (inputElement.type === 'password') {
                inputElement.type = 'text';
                inputElement.select();
                document.execCommand('copy');
                inputElement.type = 'password';
            } else {
                inputElement.select();
                document.execCommand('copy');
            }
            e.target.innerText = "Kopiert!";
        }
    }
});

$(document).ready(function () {
    fetchServers(); // Load initial data

    $('body').on('click', '.togglePassword', function () {
        const passwordField = $(this).closest('.passwordShow').find('input');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);

        if (type === 'text') {
            $(this).removeClass('bi-eye-slash').addClass('bi-eye-fill');
        } else {
            $(this).removeClass('bi-eye-fill').addClass('bi-eye-slash');
        }
    });

    $('.loginSection').on('scroll', function () {
        if (isScrolledToBottom() && !isLoading && hasMoreData) {
            fetchServers();
        }else {
            $('.loginSection').off('scroll');
        }
    });

    $('#addportal').submit(function (event) {
        event.preventDefault();
        $('.error').html("");

        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const dataString = new FormData($form[0]);

        $.ajax({
            type: "POST",
            url: createServerUrl,
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
                    $('#addPortalModal').modal('hide');
                    if (result.isNew) {
                        appendNewServer(result.server);
                    } else {
                        updateExistingServer(result.server);
                    }

                } else {
                    first_input = "";
                    $('.error').html("");
                    $.each(result.error, function (key) {
                        if (first_input == "") first_input = key;
                        $('#' + key).closest('.form-group').find('.error').html(result.error[key]);
                    });
                    $('#addportal').find("#" + first_input).focus();
                }
            },
            error: function (error) {
                $submitButton.prop('disabled', false);
                alert('Something went wrong!', 'error');
            }
        });
    });

    $('body').on('click', '.edit-server', function () {
        var id = $(this).data('id');
        $(".server_id").val(id);
        $.ajax({
            type: "POST",
            url: getServerUrl,
            data: { id: id },
            dataType: 'json',
            success: function (data) {
                $('#portal_name').val(data.name);
                $('#login_url').val(data.login_url);
                $('#username').val(data.username);
                $('#password').val(data.password);
                $('#addPortalModal').find('button[type="submit"]').html("Bearbeiten");
                $('#addPortalModal').find('#exampleModalLabel').html("Edit Portalen");
            }
        });
    });

    $('#addPortalModal').on('hidden.bs.modal', function () {
        $('.error').html("");
        $('#addportal')[0].reset();
        $('#server_id').val("");
        $('#addPortalModal').find('button[type="submit"]').html("Save");
        $('#addPortalModal').find('#exampleModalLabel').html("Add Portalen");
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

function appendNewServer(server) {
    const newServerHtml = `
        <div class="fillDetailsBox" id="server_${server.id}">
            <div class="editForm">
                <h5>${server.name}</h5>
                <button class="btn editBtn edit-server" data-bs-toggle="modal" data-bs-target="#addPortalModal" data-id="${server.id}">INFOS BEARBEITEN</button>
            </div>
            <h6>Login Daten:</h6>
            <div class="innerFilltier">
                <div class="form-group commonFill">
                    <label for="">Server:</label>
                    <input type="text" placeholder="${server.login_url}" value="${server.login_url}" readonly>
                    <button class="btn btnCopy">KOPIEREN</button>
                </div>
                <div class="form-group commonFill">
                    <label for="">Username:</label>
                    <input type="text" placeholder="${server.username}" value="${server.username}" readonly>
                    <button class="btn btnCopy">KOPIEREN</button>
                </div>
                <div class="form-group commonFill">
                    <label for="">Passwort:</label>
                    <div class="passwordShow">
                        <input class="password" id="password" type="password" placeholder="**********" value="${server.password}" readonly>
                        <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                    </div>
                    <button class="btn btnCopy">KOPIEREN</button>
                </div>
            </div>
        </div>
    `;
    $('#serverList').prepend(newServerHtml); // Append the new server HTML to the list
}

// Function to update an existing server section
function updateExistingServer(server) {
    const serverHtml = `
        <div class="editForm">
            <h5>${server.name}</h5>
            <button class="btn editBtn edit-server" data-bs-toggle="modal" data-bs-target="#addPortalModal" data-id="${server.id}">INFOS BEARBEITEN</button>
        </div>
        <h6>Login Daten:</h6>
        <div class="innerFilltier">
            <div class="form-group commonFill">
                <label for="">Server:</label>
                <input type="text" placeholder="${server.login_url}" value="${server.login_url}" readonly>
                <button class="btn btnCopy">KOPIEREN</button>
            </div>
            <div class="form-group commonFill">
                <label for="">Username:</label>
                <input type="text" placeholder="${server.username}" value="${server.username}" readonly>
                <button class="btn btnCopy">KOPIEREN</button>
            </div>
            <div class="form-group commonFill">
                <label for="">Passwort:</label>
                <div class="passwordShow">
                    <input class="password" id="password" type="password" placeholder="**********" value="${server.password}" readonly>
                    <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
                </div>
                <button class="btn btnCopy">KOPIEREN</button>
            </div>
        </div>
    `;
    $(`#server_${server.id}`).html(serverHtml); // Update the server section with the new data
}
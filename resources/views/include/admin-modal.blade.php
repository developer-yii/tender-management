<div class="modal fade addEmployModal" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Admin hinzufügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" class="user_id" id="user_id">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="first_name">Vorname*</label>
                                <input type="text" id="first_name" name="first_name">
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="last_name" class="control-label">Nachname*</label>
                                <input type="text" id="last_name" name="last_name" value="">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="email" class="control-label">E-Mail*</label>
                                <input type="email" id="email" name="email">
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="status" class="control-label">Status*</label>
                                <select name="status" id="status">
                                    <option value="" class="d-none">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="password" class="control-label" id="password-label">Passwort*</label>
                                <input type="password" id="password" name="password" value="">
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="password" class="control-label" id="password-label">Passwort bestätigen*</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" value="">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="profile_photo" class="control-label" id="profile-label">Profilbild*</label>
                                <input type="file" name="profile_photo" id="profile_photo" value="">
                                <span class="error"></span>
                                <span id="oldProfile" class="m-1"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
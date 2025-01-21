<div class="modal fade addEmployModal" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Mitarbeiter hinzuhügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="employee_id" class="employee_id" id="employee_id">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="first_name">First Name*</label>
                                <input type="text" id="first_name" name="first_name">
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="last_name" class="control-label">Last Name*</label>
                                <input type="text" id="last_name" name="last_name" value="">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="email" class="control-label">Email*</label>
                                <input type="email" id="email" name="email">
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="password" class="control-label" id="password-label">Password*</label>
                                <input type="password" id="password" name="password" value="">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="user_role" class="control-label">User Role*</label>
                                <select name="user_role" id="user_role">
                                    <option value="" class="d-none">Select User Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Employee</option>
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="user_status" class="control-label">User Status*</label>
                                <select name="user_status" id="user_status">
                                    <option value="" class="d-none">Select User Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group boxForm">
                                <label for="tags" class="control-label">Tags</label>
                                <select name="tags[]" id="tags" multiple="multiple" data-placeholder="Choose Tags">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="profile_photo" class="control-label" id="profile-label">Profile Photo*</label>
                                <input type="file" name="profile_photo" id="profile_photo" value="">
                                <span class="error"></span>
                                <span id="oldProfile" class="m-1"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="cv" class="control-label" id="cv-label">Upload CV*</label>
                                <input type="file" name="cv" id="cv" value="">
                                <span class="error"></span>
                                <span id="oldCv" class="m-1"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="document" class="control-label" id="document-label">Upload Document*</label>
                                <input type="file" name="document" id="document" value="">
                                <span class="error"></span>
                                <span id="oldDocument" class="m-1"></span>
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
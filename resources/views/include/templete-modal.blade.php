<div class="modal fade addEmployModal" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Vorlage hinzufügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="edit_id" class="edit_id" id="edit_id">
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="title">{{ trans('message.title') }}*</label>
                                <input type="text" id="title" name="title">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="templete_file" class="file-label" id="file-label">{{ trans('message.file') }}*</label>
                                <input type="file" name="templete_file" id="templete_file" value="">
                                <span class="error"></span>
                                <span id="oldFile" class="m-1"></span>
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
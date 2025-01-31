<div class="modal fade addEmployModal" id="addFolderModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Ordner hinzufügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addFolder" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        {{-- <input type="hidden" name="folder_id" class="folder_id" id="folder_id"> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="folder_name">Folder Name*</label>
                                <input type="text" id="folder_name" name="folder_name">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button type="submit" id="saveFolderBtn" class="btn btn-info waves-effect waves-light">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
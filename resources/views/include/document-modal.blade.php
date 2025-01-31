<div class="modal fade addEmployModal" id="addDocumentModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Unterlagen / Bestätigungen hinzufügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adddocument">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="document_id" class="document_id" id="document_id">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="category">{{ trans('message.category') }}*</label>
                                <select id="category" name="category">
                                    <option class="d-none" value="">{{ trans('message.select-category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>
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
                                <label for="document_pdf" class="document-pdf-label" id="document-pdf-label">Unterlagen / Bestätigungen(PDF)*</label>
                                <input type="file" name="document_pdf" id="document_pdf" value="">
                                <span class="error"></span>
                                <span id="oldDocumentPdf" class="m-1"></span>
                            </div>
                        </div>
                    </div>

                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">{{ trans('message.add-extra-field') }}</legend>
                        <div id="extraFieldContainer"> <!-- Container for extra fields -->
                            <!-- extra fields will be added here -->
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 form-group">
                                <a id="addfield" class="btn btn-info"><i class="fa fa-plus"></i> {{ trans('message.add-field') }}</a>
                                <span class="error"></span>
                            </div>
                        </div>
                    </fieldset>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">Speichern</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade addEmployModal" id="addCertificateModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Zertifizierung hinzufügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addcertificate">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="certificate_id" class="certificate_id" id="certificate_id">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="title">{{ trans('message.title') }}*</label>
                                <input type="text" id="title" name="title">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group boxForm">
                                <label for="description">{{ trans('message.description') }}*</label>
                                <textarea name="description" id="description" rows="5"></textarea>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="dateSelect">
                                <label for="">Ausschreibungszeitraum (Startdatum / Enddatum)*</label>
                                <div class="form-group boxForm range">
                                    <input type="text" class="flat" placeholder="{{ trans('message.start-date') }}" name="start_date" id="start_date">
                                    <span>To</span>
                                    <input type="text" class="flat" placeholder="{{ trans('message.end-date') }}" name="end_date" id="end_date">
                                </div>
                                <span class="error" id="error_message"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="certificate_word" class="certificate-word-label" id="certificate-word-label">{{ trans('message.certificate-word') }}*</label>
                                <input type="file" name="certificate_word" id="certificate_word" value="">
                                <span class="error"></span>
                                <span id="oldCertificateWord" class="m-1"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="certificate_pdf" class="certificate-pdf-label" id="certificate-pdf-label">{{ trans('message.certificate-pdf') }}*</label>
                                <input type="file" name="certificate_pdf" id="certificate_pdf" value="">
                                <span class="error"></span>
                                <span id="oldCertificatePdf" class="m-1"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="logo" class="control-label" id="logo-label">Logo*</label>
                                <input type="file" name="logo" id="logo" value="">
                                <span id="oldLogo" class="m-1"></span>
                                <span class="error"></span>
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
<div class="modal fade addEmployModal" id="addReferenceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Referenz hinzufügen</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addreference">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="reference_id" class="reference_id" id="reference_id">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group boxForm">
                                <label for="project_title">{{ trans('message.project-title') }}*</label>
                                <input type="text" id="project_title" name="project_title">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group boxForm">
                                <label for="tags" class="control-label">Tags*</label>
                                <select name="tags[]" id="tags" multiple="multiple" data-placeholder="Choose">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group boxForm">
                                <label for="scope" class="control-label">{{ trans('message.scope') }}*</label>
                                <textarea name="scope" id="scope" rows="5"></textarea>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="dateSelect">
                                <label for="">{{ trans('message.performance-period') }}*</label>
                                <div class="form-group boxForm range">
                                    <input type="text" class="flat" placeholder="{{trans('message.start-date')}}" name="start_date" id="start_date">
                                    <span>{{ trans('message.to') }}</span>
                                    <input type="text" class="flat" placeholder="{{trans('message.end-date')}}" name="end_date" id="end_date">
                                </div>
                                <span class="error" id="error_message"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="file_pdf" class="file-pdf-label" id="file-pdf-label">{{trans('message.file-pdf')}}*</label>
                                <input type="file" name="file_pdf" id="file_pdf" value="">
                                <span class="error"></span>
                                <span id="oldFilePdf" class="m-1"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group boxForm">
                                <label for="file_word" class="file-word-label" id="file-word-label">{{trans('message.file-word')}}*</label>
                                <input type="file" name="file_word" id="file_word" value="">
                                <span class="error"></span>
                                <span id="oldFileWord" class="m-1"></span>
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
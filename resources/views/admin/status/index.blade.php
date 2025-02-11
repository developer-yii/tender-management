@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', "Admin | Status-Liste")
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="certificationsMain">
        <div class="adminTable">
            <button type="button" class="btn btn-sm btn-info" style="float: right;" data-bs-toggle="modal" data-bs-target="#addStatusModal">
                Status hinzufügen
            </button>
            <div class="titleBox"><h5>Status-Liste</h5></div>
            <table id="statusTable" class="dataTable table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Status</th>
                        <th>Icon</th>
                        <th>Erstellungsdatum</th>
                        <th>Update-Datum</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    </div>
</section>
@endsection

@section('modal')
    <div class="modal fade" id="addStatusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Status hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addstatus">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="status_id" class="status_id" id="status_id">
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label">Title *</label>
                            <input class="form-control" type="text" id="title" name="title">
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label icon-label">Icon *</label>
                            <input class="form-control" type="file" id="icon" name="icon">
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    var statusListUrl = "{{ route('status.list') }}";
    var createStatusUrl = "{{ route('status.addupdate') }}";
    var getStatusUrl = "{{ route('status.detail') }}";
</script>
<script src="{{ $baseUrl }}custom/js/status.js"></script>
@endsection


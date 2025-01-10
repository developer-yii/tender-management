@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')

@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="tenderDetails">
        <div class="adminTable">
            <button type="button" class="btn btn-sm btn-primary" style="float: right;" data-bs-toggle="modal" data-bs-target="#addTagModal">
                Add Tag
            </button>
            <div class="titleBox"><h5>Tag List</h5></div>
            <table id="tagsTable" class="dataTable table table-striped"  style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tag</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    </div>
</section>
@endsection

@section('modal')
    <div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addtag">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="tag_id" class="tag_id" id="tag_id">
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Name *</label>
                            <input class="form-control" type="text" id="name" name="name">
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    var tagListUrl = "{{ route('tag.list') }}";
    var createTagUrl = "{{ route('tag.addupdate') }}";
    var getTagUrl = "{{ route('tag.detail') }}";
    var deleteTagUrl = "{{ route('tag.delete') }}";
</script>
<script src="{{ $baseUrl }}custom/js/tags.js"></script>
@endsection


@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Admin List ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus"></i> Admin hinzuhügen</button>
        </div>
        <div class="allEmploysSec adminList">
            @if($admins->isNotEmpty())
            <div class="employsBoxList">
                <div class="listAllemploys">
                    @foreach($admins as $admin)
                        <div class="employcardBox">
                            <div class="imgBox">
                                <img src="{{ $admin->getAdminProfilePicUrl() }}" alt="{{ $admin->first_name }}">
                            </div>
                            <div class="textbox">
                                <div>
                                    <h6>
                                        {{ $admin->first_name }} {{ $admin->last_name }}
                                        {{-- @if($admin->is_active)
                                            <img src="{{ $baseUrl }}images/green-dot.png" alt="Active">
                                        @else
                                            <img src="{{ $baseUrl }}images/orange-dot.png" alt="Inactive">
                                        @endif --}}
                                    </h6>
                                    <span>{{ $admin->email }}</span>
                                </div>
                                <div class="adminBtn">
                                    <button class="btn btnDetails edit-admin" data-bs-toggle="modal" data-bs-target="#addModal" data-id="{{$admin->id}}">BEARBEITEN</button>
                                    <button class="btn btnDetails delete-admin" data-id="{{$admin->id}}">LÖSCHEN</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                </div>
            @else
                <div class="employsBoxList">
                    <div class="titleBox">
                        <h5>{{ trans('message.no data available') }}</h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.admin-modal')
@endsection
@section('js')
    <script>
        var createUrl = "{{ route('admin.addupdate') }}";
        var getUrl = "{{ route('admin.detail') }}";
        var listUrl = "{{ route('admin.index') }}";
        var deleteUrl = "{{ route('admin.delete') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/common.js"></script>
    <script src="{{ $baseUrl }}custom/js/admin.js"></script>
@endsection


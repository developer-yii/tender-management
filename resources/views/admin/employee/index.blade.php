@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fa-solid fa-plus"></i> Mitarbeiter hinzuhügen</button>
        </div>
        {{-- @foreach($tags as $tag)
            <a href="#{{$tag->name}}">{{$tag->name}}</a>
        @endforeach --}}
        <div class="allEmploysSec">
            @foreach($tags as $tag)
                @if($tag->users->isNotEmpty())
                    <div class="employsBoxList" id="{{ $tag->name }}">
                        <div class="titleBox">
                            <h5>{{ $tag->name }}</h5>
                        </div>
                        <div class="listAllemploys">
                            @foreach ($tag->users as $user)
                                <div class="employcardBox">
                                    <div class="imgBox">
                                        <img src="{{ $user->getProfilePicUrl() }}" alt="{{ $user->first_name}}">
                                    </div>
                                    <div class="textbox">
                                        <h6>{{ $user->first_name }} {{ $user->last_name }}</h6>
                                        <span>{{ $user->email }}</span>
                                        <p>{{ $user->description }}</p>
                                        <a href="{{ route('employee.details', [$user->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="employsBoxList" id="no_tag">
                <div class="titleBox">
                    <h5>Employee Without Tags</h5>
                </div>
                <div class="listAllemploys">
                    @foreach($employeesWithoutTags as $employee)
                        <div class="employcardBox">
                            <div class="imgBox">
                                <img src="{{ $user->getProfilePicUrl() }}" alt="{{ $employee->first_name}}">
                            </div>
                            <div class="textbox">
                                <h6>{{ $employee->first_name }} {{ $employee->last_name }}</h6>
                                <span>{{ $employee->email }}</span>
                                <p>{{ $employee->description }}</p>
                                <a href="{{ route('employee.details', [$employee->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('modal')
    @include('include.employee-modal')
@endsection
@section('js')
    <script>
        var createEmployeeUrl = "{{ route('employee.addupdate') }}";
        var getEmployeeUrl = "{{ route('employee.detail') }}";
        var employeeListUrl = "{{ route('employee.index') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/common.js"></script>
    <script src="{{ $baseUrl }}custom/js/employees.js"></script>
@endsection


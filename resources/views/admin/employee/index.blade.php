@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="addCommonBtn">
            <a href="{{route('employee.add-edit')}}" class="btn btnAdd"><i class="fa-solid fa-plus"></i> Mitarbeiter hinzuhügen</a>
        </div>
        <div class="allEmploysSec">
            @if($users->isNotEmpty())
                @foreach($tags as $tag)
                    @if($tag->users->isNotEmpty())
                        <div class="employsBoxList" id="{{ $tag->name }}">
                            <div class="titleBox">
                                <h5>{{ $tag->name }}</h5>
                            </div>
                            <div class="listAllemploys">
                                @foreach ($tag->users as $user)
                                    @include('admin.employee.employee_card_partial', ['user' => $user])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
                @if($employeesWithoutTags->isNotEmpty())
                    <div class="employsBoxList" id="no_tag">
                        <div class="titleBox">
                            <h5>Employee Without Tags</h5>
                        </div>
                        <div class="listAllemploys">
                            @foreach($employeesWithoutTags as $employee)
                                @include('admin.employee.employee_card_partial', ['user' => $employee])
                            @endforeach
                        </div>
                    </div>
                @endif
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
@section('js')
    <script>
        var createUrl = "{{ route('employee.addupdate') }}";
        var listUrl = "{{ route('employee.index') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/common.js"></script>
    <script src="{{ $baseUrl }}custom/js/employees.js"></script>
@endsection


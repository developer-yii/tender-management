@php
    $baseUrl = asset('assest')."/";
    $title = $employee ? 'Bearbeiten' : 'anlegen';
@endphp
@extends('layouts.app-main')
@section('title', "Admin | {$title} Mitarbeiter ")
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="create_profile">
            <form id="addForm">
                @csrf
                <div class="form-group">
                    <input type="hidden" name="user_id" class="user_id" id="user_id" value="{{ $employee ? $employee->id : '' }}">
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="create_leftBox">
                            <div class="form-group">
                                <div class="topPlusBox">
                                    @if($employee)
                                        <img id="blah" src="{{ $employee->getProfilePicUrl() }}" alt="{{ $employee->first_name }}">
                                    @else
                                        <img id="blah" src="{{$baseUrl}}images/plus-new.png" alt="plus-new" class="plus-new">
                                    @endif

                                    <input type="file" class="file-upload" onchange="readURL(this);" name="profile_photo" id="profile_photo">
                                    <div class="edit-icon">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </div>
                                </div>
                                <span class="error"></span>
                            </div>
                            <div class="generateDetail">
                                {{-- <div class="inputBox">
                                    <div class="gropubox">
                                        <input type="text" id="first_name" name="first_name" placeholder="Vorname *">
                                        <input type="text" id="last_name" name="last_name" placeholder="Nachname *">
                                    </div>
                                </div> --}}
                                <div class="inputBox">
                                    <div class="gropubox">
                                        <div class="form-group gropuboxinner">
                                            <input type="text" id="first_name" name="first_name" placeholder="Vorname *" value="{{ $employee ? $employee->first_name : '' }}">
                                            <span class="error"></span>
                                        </div>
                                        <div class="form-group gropuboxinner">
                                            <input type="text" id="last_name" name="last_name" placeholder="Nachname *" value="{{ $employee ? $employee->last_name : '' }}">
                                            <span class="error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputBox">
                                    <div class="form-group">
                                        <input type="email" id="email" name="email" placeholder="E-Mail *" value="{{ $employee ? $employee->email : '' }}">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="inputBox">
                                    <div class="form-group">
                                        <input type="password" id="password" name="password" value="" placeholder="Passwort *">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="inputBox">
                                    <div class="form-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation" value="" placeholder="Passwort bestätigen *">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="inputBox">
                                    <div class="form-group">
                                        <button type="submit" class="btn createBtn">PROFIL ERSTELLEN</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="create_rightBox">
                            <div class="form-group">
                                <div class="uploadCvBox">
                                    <p>Lebenslauf hochladen</p>
                                    <input type="file" name="cv" id="cv" value="" class="custom-file-input-german">
                                    @if($employee)
                                        <span id="oldCv" class="m-1">
                                            <a href="{{ $employee->cv_url }}" target="_blank" class="btn btn-info">Lebenslauf ansehen</a>
                                        </span>
                                    @endif
                                    {{-- <button class="btn btnCv"><img src="{{$baseUrl}}images/plus-new.png" alt="plus-new"></button> --}}
                                </div>
                                <span class="error"></span>
                            </div>
                            <div class="form-group">
                                <div class="uploadCvBox">
                                    <p>Laden Sie Dokument hoch</p>
                                    <input type="file" name="document" id="document" value="" class="custom-file-input-german">
                                    @if($employee)
                                        <span id="oldDocument" class="m-1">
                                            <a href="{{ $employee->document_url }}" target="_blank" class="btn btn-info">Dokument anzeigen</a>
                                        </span>
                                    @endif
                                </div>
                                <span class="error"></span>
                            </div>
                            <div class="discriptBox form-group">
                                <p>Beschreibung</p>
                                <textarea name="description" id="description" rows="5">{{ $employee ? $employee->description : '' }}</textarea>
                                <span class="error"></span>
                            </div>
                            {{-- <div class="mySkillBox"> --}}
                            <div class="mySkillBox">
                                <p>Meine Fähigkeiten</p>
                                <div class="skillSelect">
                                    <select name="tags[]" id="tags" multiple="multiple" data-placeholder="Choose Tags">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                @if (isset($employee) && $employee->tags->contains('id', $tag->id)) selected @endif>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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


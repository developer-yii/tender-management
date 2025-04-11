@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Mitarbeiter Details ')
@section('content')
<section class="mainSection">
    <div class="homeSectionPart">
        <div class="create_profile">
            <form id="addForm">
                @csrf
                <div class="form-group">
                    <input type="hidden" name="user_id" class="user_id" id="user_id" value="{{ $user ? $user->id : '' }}">
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="create_leftBox">
                            <div class="form-group">
                                <div class="topPlusBox">
                                    @if($user)
                                        <img id="blah" src="{{ $user->getProfilePicUrl() }}" alt="{{ $user->first_name }}">
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
                                <div class="inputBox">
                                    <div class="gropubox">
                                        <div class="form-group gropuboxinner">
                                            <input type="text" id="first_name" name="first_name" placeholder="Vorname *" value="{{ $user ? $user->first_name : '' }}">
                                            <span class="error"></span>
                                        </div>
                                        <div class="form-group gropuboxinner">
                                            <input type="text" id="last_name" name="last_name" placeholder="Nachname *" value="{{ $user ? $user->last_name : '' }}">
                                            <span class="error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="inputBox">
                                    <div class="form-group">
                                        <input type="email" id="email" name="email" placeholder="E-Mail *" value="{{ $user ? $user->email : '' }}">
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
                                        <input type="password" id="password-confirm" name="password_confirmation" value="" placeholder="Passwort bestätigen *">
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
                    @if(isEmployee())
                        <div class="col-lg-6">
                            <div class="create_rightBox">
                                <div class="form-group">
                                    <div class="uploadCvBox">
                                        <p>Lebenslauf hochladen</p>
                                        <input type="file" name="cv" id="cv" value="">
                                        @if($user)
                                            <span id="oldCv" class="m-1">
                                                <a href="{{ $user->cv_url }}" target="_blank" class="btn btn-info">Meinen Lebenslauf ansehen</a>
                                            </span>
                                        @endif
                                        {{-- <button class="btn btnCv"><img src="{{$baseUrl}}images/plus-new.png" alt="plus-new"></button> --}}
                                    </div>
                                    <span class="error"></span>
                                </div>
                                <div class="form-group">
                                    <div class="uploadCvBox">
                                        <p>Laden Sie Dokument hoch</p>
                                        <input type="file" name="document" id="document" value="">
                                        @if($user)
                                            <span id="oldDocument" class="m-1">
                                                <a href="{{ $user->document_url }}" target="_blank" class="btn btn-info">Meinen Dokument anzeigen</a>
                                            </span>
                                        @endif
                                    </div>
                                    <span class="error"></span>
                                </div>
                                <div class="discriptBox form-group">
                                    <p>Beschreibung</p>
                                    <textarea name="description" id="description" rows="5">{{ $user ? $user->description : '' }}</textarea>
                                    <span class="error"></span>
                                </div>
                                <div class="mySkillBox">
                                    <p>Meine Fähigkeiten</p>
                                    <div class="skillSelect">
                                        <select name="tags[]" id="tags" multiple="multiple" data-placeholder="Tags auswählen">
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    @if (isset($user) && $user->tags->contains('id', $tag->id)) selected @endif>
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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


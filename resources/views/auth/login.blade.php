@extends('layouts.app')

@section('content')
<section id="sign_in">
    <div class="sign_inBox">
        <div class="leftBox">
            <div class="signMainbox">
                <div class="titlebox">
                    <h4>Willkommen zurück</h4>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="commonFillBox">
                        <label class="comLable" for="">E-Mail</label>
                        <input id="email" type="text" class="form-control comInput @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Deine E-Mail" autocomplete="email" autofocus>

                        @error('email')
                            <span class="errorCommon">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="commonFillBox">
                        <label class="comLable" for="">Passwort</label>
                        <input id="password" type="password" class="form-control comInput @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Dein Passwort">

                        @error('password')
                            <span class="errorCommon">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="checkBoxSec">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" name="remember">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Angemeldet bleiben</label>
                        </div>
                    </div>
                    <div class="regBtn">
                        <button class="btn btnReg">ANMELDEN</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="rightBox">
            <div class="logoImg">
                <a href="javascript:void(0)"><img src="assest/images/logo-img.png" alt="logo-img"></a>
            </div>
            <div class="layer-img">
                <img src="assest/images/login-img.png" alt="login-img">
            </div>
        </div>
    </div>
</section>
@endsection

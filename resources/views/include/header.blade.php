@php
    $baseUrl = asset('assest')."/";
    $loginUser = Auth::user();
    $imagePath = $loginUser->profile_pic ? Storage::url('employee/profile-photo/' . $loginUser->profile_pic) : $baseUrl."images/default-user.jpg" ;
@endphp

<header class="header">
    <div class="header-part">
        <div class="header-box">
            <div class="headContent">
                <div class="menuClick">
                    <div class="mobileIcon">
                        <a href="javascript:void(0)"><i class="fa-solid fa-bars"></i></a>
                    </div>
                    <div class="searchBox">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Suchen...">
                    </div>
                </div>
                <div class="sittingBox">
                    <a href="javascript:void(0)"><i class="bi bi-gear-wide-connected"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="userHeader">
        <div class="userHeaderBox">
            <div class="userProfile">
                <div class="userimg">
                    <img src="{{ $imagePath }}" alt="{{$loginUser->first_name}}">
                </div>
                <div class="username">
                    <h6>{{$loginUser->first_name}} {{$loginUser->last_name}}</h6>
                    <p>{{$loginUser->email}}</p>
                </div>
            </div>
            <div class="tenderStatus">
                <div class="comstatusBox">
                    <div class="statusIcon">
                        <img src="{{$baseUrl}}images/Wait.png" alt="Wait">
                    </div>
                    <div class="text">
                        <p>in Bearbeitung</p>
                    </div>
                    <div class="countPro">
                        <span>1</span>
                    </div>
                </div>
                <div class="comstatusBox">
                    <div class="statusIcon">
                        <img src="{{$baseUrl}}images/orange-dot.png" alt="orange-dot">
                    </div>
                    <div class="text">
                        <p>in Betracht</p>
                    </div>
                    <div class="countPro">
                        <span>4</span>
                    </div>
                </div>
                <div class="comstatusBox">
                    <div class="statusIcon">
                        <img src="{{$baseUrl}}images/green-dot.png" alt="green-dot">
                    </div>
                    <div class="text">
                        <p>Erhalten</p>
                    </div>
                    <div class="countPro">
                        <span>5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
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
                @php
                    $statuses = [
                        1 => ['label' => 'in Bearbeitung', 'icon' => 'Wait.png'],
                        2 => ['label' => 'in Betracht', 'icon' => 'orange-dot.png'],
                        3 => ['label' => 'Erhalten', 'icon' => 'green-dot.png']
                    ];
                @endphp

                @foreach ($statuses as $status => $details)
                    <div class="comstatusBox">
                        <div class="statusIcon">
                            <img src="{{ $baseUrl }}images/{{ $details['icon'] }}" alt="{{ $details['label'] }}">
                        </div>
                        <div class="text">
                            <p>{{ $details['label'] }}</p>
                        </div>
                        <div class="countPro">
                            <span>{{ $statusCounts[$status] ?? 0 }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</header>
@php
    $baseUrl = asset('assest')."/";
    $loginUser = Auth::user();
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
                    <a href="{{route('my-profile')}}"><i class="bi bi-gear-wide-connected"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="userHeader">
        <div class="userHeaderBox">
            <div class="userProfile">
                <div class="userimg">
                    <img src="{{ isAdmin() ? $loginUser->getAdminProfilePicUrl() :$loginUser->getProfilePicUrl() }}" alt="{{$loginUser->first_name}}">
                </div>
                <div class="username">
                    <h6>{{$loginUser->first_name}} {{$loginUser->last_name}}</h6>
                    <p>{{$loginUser->email}}</p>
                </div>
            </div>
            <div class="tenderStatus">
                @foreach ($statuses as $status)
                    <div class="comstatusBox">
                        <div class="statusIcon">
                            <img src="{{ $status->getIconUrl() }}" alt="{{ $status->title }}">
                        </div>
                        <div class="text">
                            <p>{{ $status->title }}</p>
                        </div>
                        <div class="countPro">
                            <span>{{ $statusCounts[$status->id] ?? 0 }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="tenderStatus">
                @php
                    $statuses = [
                        1 => ['label' => 'in Bearbeitung', 'icon' => 'Wait.png'],
                        2 => ['label' => 'in Betracht', 'icon' => 'orange-dot.png'],
                        3 => ['label' => 'Erhalten', 'icon' => 'green-dot.png'],
                        4 => ['label' => 'Abgeschlossen', 'icon' => 'gray-dot.png']
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
            </div> --}}
        </div>
    </div>
</header>
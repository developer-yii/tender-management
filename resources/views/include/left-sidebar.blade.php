@php
    $baseUrl = asset('assest')."/";
@endphp
<div class="sidebar">
    <div class="sidebar-fix">
        <div class="side-menubar">
            <div class="logo-box">
                <div class="logo-img">
                    <img class="open" src="{{$baseUrl}}images/logo-img.png" alt="logo-img">
                    <!-- <img class="close" src="{{$baseUrl}}images/close-logo.png" alt="close-logo"> -->
                </div>
                <!-- <div class="click-btn">
                            <a href="javascript:void(0)"><img src="{{$baseUrl}}images/menu-btn.svg" alt="menu-btn"></a>
                        </div> -->
            </div>
            <div class="menu-box">
                <div class="navbar-box">
                    @php
                        if (!function_exists('isActiveRoute')) {
                            function isActiveRoute($routes) {
                                return Request::routeIs($routes);
                            }
                        }

                        $currentPath = request()->path(); // Get the current path

                        $activeRoutes = [
                            'tenders' => ['tender.index', 'employee.tenders'],
                            'companyDetails' => ['company-details.index'],
                            'employees' => ['employee.index', 'employee.details'],
                            'certificates' => ['certificate.index', 'certificate.details'],
                            'references' => ['reference.index', 'reference.details'],
                            'documents' => ['document.index', 'document.details'],
                            'templetes' => ['templete.index', 'document.details'],
                            'tags' => ['tag.index'],
                            'servers' => ['server.index'],
                        ];
                    @endphp

                    <ul>
                        @if(isAdmin())
                            {{-- tenders --}}
                            <li>
                                <a href="{{ route('tender.index') }}" class="{{ isActiveRoute($activeRoutes['tenders']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Invite.png" alt="Invite"></span>
                                    Ausschreibungen
                                </a>
                            </li>

                            {{-- company details --}}
                            <li>
                                <a href="{{ route('company-details.index') }}" class="{{ isActiveRoute($activeRoutes['companyDetails']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Postcard.png" alt="Postcard"></span>
                                    Firmendaten
                                </a>
                            </li>

                            {{-- employees --}}
                            <li>
                                <a href="{{ route('employee.index') }}" class="{{ isActiveRoute($activeRoutes['employees']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Account.png" alt="Account"></span>
                                    Mitarbeiter
                                </a>
                            </li>

                            {{-- Certificates --}}
                            <li>
                                <a href="{{ route('certificate.index') }}" class="{{ isActiveRoute($activeRoutes['certificates']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Diploma.png" alt="Diploma"></span>
                                    Zertifizierungen
                                </a>
                            </li>

                            {{-- reference --}}
                            <li>
                                <a href="{{ route('reference.index') }}" class="{{ isActiveRoute($activeRoutes['references']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Report-Card.png" alt="Report-Card"></span>
                                    Referenzen
                                </a>
                            </li>

                            {{-- Documents --}}
                            <li>
                                <a href="{{ route('document.index') }}" class="{{ isActiveRoute($activeRoutes['documents']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Verified-Account.png" alt="Verified-Account"></span>
                                    Unterlagen / Bestätigungen
                                </a>
                            </li>

                            {{-- templete --}}
                            <li>
                                <a href="{{ route('templete.index') }}" class="{{ isActiveRoute($activeRoutes['templetes']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Terms-and-Conditions.png" alt="Terms-and-Conditions"></span>
                                    Vorlage
                                </a>
                            </li>

                            {{-- Ai --}}
                            <li>
                                <a href="{{route('ai.index')}}">
                                    <span><img src="{{$baseUrl}}images/Artificial-Intelligence.png" alt="Artificial-Intelligence"></span> KI-Tools
                                </a>
                            </li>

                            {{-- portals --}}
                            <li>
                                <a href="{{route('server.index')}}" class="{{ isActiveRoute($activeRoutes['servers']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Access.png" alt="Access"></span>
                                    Logins zu Portalen
                                </a>
                            </li>

                            {{-- tags --}}
                            <li>
                                <a class="kiTools {{ isActiveRoute($activeRoutes['tags']) ? 'main-active' : '' }}" href="javascript:void">
                                    <span><i class="bi bi-gear-wide-connected setting-icon"></i></span> Admin
                                </a>
                                <ul class="dropDownInner {{ isActiveRoute($activeRoutes['tags']) ? 'show' : '' }}">
                                    <li>
                                        <a href="{{ route('tag.index') }}">
                                            <span><img src="{{$baseUrl}}images/Artificial-Intelligence.png" alt="Artificial-Intelligence"></span>
                                            Tags
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('employee.tenders') }}" class="{{ isActiveRoute($activeRoutes['tenders']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Invite.png" alt="Invite"></span>
                                    Ausschreibungen Employee
                                </a>
                            </li>
                        @endif
                            <li>
                                <a {{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span><img src="{{$baseUrl}}images/Access.png" alt="Access"></span>
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                    </ul>
                </div>
            </div>
            @if(isEmployee())
                <div class="currentOffer">
                    <div class="offertitle">
                        <h6>ANGEBOTFRIST DER AKTUELLEN AUSSCHREIBUNGEN</h6>
                    </div>
                    <div class="offerComBox">
                        <div class="offerLeft">
                            <div class="imgBox">
                                <img src="{{$baseUrl}}images/offerimg1.png" alt="offerimg1">
                            </div>
                            <div class="textBox">
                                <p>Museum für Naturkunde Berlin</p>
                            </div>
                        </div>
                        <div class="offerRight">
                            <div class="tagBox">
                                <h5>28 <br><span>Tage</span></h5>
                            </div>
                        </div>
                    </div>
                    <div class="offerComBox">
                        <div class="offerLeft">
                            <div class="imgBox">
                                <img src="{{$baseUrl}}images/offerimg2.png" alt="offerimg2">
                            </div>
                            <div class="textBox">
                                <p>Kaleidico</p>
                            </div>
                        </div>
                        <div class="offerRight">
                            <div class="tagBox">
                                <h5>2 <br><span>Tage</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="startTenerbtn">
            <a href="{{route('tender.start')}}" class="btn startBtn"><i class="fa-solid fa-plus"></i> Ausschreibung starten</a>
        </div>
    </div>
</div>

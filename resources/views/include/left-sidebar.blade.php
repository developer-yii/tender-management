@php
    $baseUrl = asset('assest')."/";
@endphp
<div class="sidebar">
    <div class="sidebar-fix">
        <div class="side-menubar">
            <div class="logo-box">
                <div class="logo-img">
                    <img class="open" src="{{$baseUrl}}images/logo-img.png" alt="logo-img">
                </div>
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
                            'tenders' => ['tender.index', 'employee.tenders', 'tender.start', 'tender.details', 'tender.add', 'tender.preview-docx', 'tender.preview-pdf'],
                            'companyDetails' => ['company-details.index'],
                            'employees' => ['employee.index', 'employee.details'],
                            'certificates' => ['certificate.index', 'certificate.details'],
                            'references' => ['reference.index', 'reference.details'],
                            'documents' => ['document.index', 'document.details'],
                            'templetes' => ['templete.index'],
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
                                    <span><img src="{{$baseUrl}}images/admin-setting.png" height="25px; width:25px;" alt="Tags"></span> Admin
                                </a>
                                <ul class="dropDownInner {{ isActiveRoute($activeRoutes['tags']) ? 'show' : '' }}">
                                    <li>
                                        <a href="{{ route('tag.index') }}">
                                            <span><img src="{{$baseUrl}}images/tags.png" alt="Tags"></span>
                                            Tags
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('employee.tenders') }}" class="{{ isActiveRoute($activeRoutes['tenders']) ? 'main-active' : '' }}">
                                    <span><img src="{{$baseUrl}}images/Invite.png" alt="Invite"></span>
                                    Ausschreibungen
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
                @include('include.tender_deadline_section')
            @endif
        </div>
        <div class="startTenerbtn">
            <a href="{{route('tender.start')}}" class="btn startBtn"><i class="fa-solid fa-plus"></i> Ausschreibung starten</a>
        </div>
    </div>
</div>

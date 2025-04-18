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
                            'ai' => ['ai.index'],
                            'tags' => ['tag.index'],
                            'status' => ['status.index'],
                            'servers' => ['server.index'],
                            'admins' => ['admin.index'],
                            'abgabeform' => ['abgabeform.index'],
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
                                <a href="{{route('ai.index')}}" class="{{ isActiveRoute($activeRoutes['ai']) ? 'main-active' : '' }}">
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

                            {{-- Setting --}}
                            <li>
                                <a class="kiTools {{ (isActiveRoute($activeRoutes['tags']) || isActiveRoute($activeRoutes['admins']) || isActiveRoute($activeRoutes['status']) || isActiveRoute($activeRoutes['abgabeform'])) ? 'main-active' : '' }}" href="javascript:void(0)">
                                    <span><img src="{{$baseUrl}}images/admin-setting.png" height="25px; width:25px;" alt="Einstellungen"></span> Einstellungen
                                </a>
                                <ul class="dropDownInner {{ (isActiveRoute($activeRoutes['tags']) || isActiveRoute($activeRoutes['admins']) || isActiveRoute($activeRoutes['status']) || isActiveRoute($activeRoutes['abgabeform']))  ? 'show' : '' }}">
                                    <li>
                                        <a href="{{ route('admin.index') }}" class="{{ isActiveRoute($activeRoutes['admins']) ? 'main-active' : '' }}">
                                            <span><img src="{{$baseUrl}}images/Account.png" alt="Account"></span>
                                            Admins
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('tag.index') }}" class="{{ isActiveRoute($activeRoutes['tags']) ? 'main-active' : '' }}">
                                            <span><img src="{{$baseUrl}}images/tags.png" alt="Tags"></span>
                                            Tags
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('status.index') }}" class="{{ isActiveRoute($activeRoutes['status']) ? 'main-active' : '' }}">
                                            <span><img src="{{$baseUrl}}images/checkdot.png" alt="status"></span>
                                            Status
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('abgabeform.index') }}" class="{{ isActiveRoute($activeRoutes['abgabeform']) ? 'main-active' : '' }}">
                                            <span><img src="{{$baseUrl}}images/checkdot.png" alt="abgabeform"></span>
                                            Abgabeform
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
                                <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

@php
    $baseUrl = asset('assest') . '/';
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Portal Login Details ')
@section('content')
    <section class="mainSection">
        <div class="homeSectionPart">
            <div class="addCommonBtn">
                <button class="btn btnAdd" data-bs-toggle="modal" data-bs-target="#addPortalModal">
                    <i class="fa-solid fa-plus"></i> Login hinzufügen
                </button>
            </div>
            <div class="loginSection">
                <div id="serverList" class="logBoxFill">
                    <!-- Server data will be dynamically loaded here -->
                </div>
                <div id="loading" class="text-center mt-4" style="display: none;">
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade addEmployModal" id="addPortalModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLabel">Portal hinzufügen</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addportal">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="server_id" class="server_id" id="server_id">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group boxForm">
                                    <label for="portal_name">Portalen Name*</label>
                                    <input type="text" id="portal_name" name="portal_name">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group boxForm">
                                    <label for="login_url" class="control-label">Login Url*</label>
                                    <input type="text" id="login_url" name="login_url">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="username" class="control-label">Nutzername*</label>
                                    <input type="text" id="username" name="username">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="server_password" class="control-label">Passwort*</label>
                                    <input type="password" id="server_password" name="server_password">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                        <button type="submit" class="btn btn-info waves-effect waves-light">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var serverListUrl = "{{ route('server.list') }}";
        var createServerUrl = "{{ route('server.addupdate') }}";
        var getServerUrl = "{{ route('server.detail') }}";
    </script>
    <script src="{{ $baseUrl }}custom/js/servers.js"></script>
@endsection

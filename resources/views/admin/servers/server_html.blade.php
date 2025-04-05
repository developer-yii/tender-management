<div class="fillDetailsBox"  id="server_{{ $server->id }}">
    <div class="editForm">
        <h5>{{ $server->name }}</h5>
        <button class="btn editBtn edit-server" data-bs-toggle="modal" data-bs-target="#addPortalModal" data-id="{{ $server->id }}">INFOS BEARBEITEN</button>
    </div>
    <h6>Login Daten:</h6>
    <div class="innerFilltier">
        <div class="form-group commonFill">
            <label for="">Server:</label>
            <input type="text" placeholder="{{ $server->login_url }}" value="{{ $server->login_url }}" readonly>
            <button class="btn btnCopy">KOPIEREN</button>
        </div>
        <div class="form-group commonFill">
            <label for="">Nutzername:</label>
            <input type="text" placeholder="{{ $server->username }}" value="{{ $server->username }}" readonly>
            <button class="btn btnCopy">KOPIEREN</button>
        </div>
        <div class="form-group commonFill">
            <label for="">Passwort:</label>
            <div class="passwordShow">
                <input class="password" id="password" type="password" placeholder="**********" value="{{ $server->password }}" readonly>
                <a href="javascript:void(0)"><i class="bi bi-eye-slash togglePassword"></i></a>
            </div>
            <button class="btn btnCopy">KOPIEREN</button>
        </div>
    </div>
</div>

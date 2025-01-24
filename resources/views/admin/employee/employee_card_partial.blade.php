<div class="employcardBox">
    <div class="imgBox">
        <img src="{{ $user->getProfilePicUrl() }}" alt="{{ $user->first_name }}">
    </div>
    <div class="textbox">
        <h6>{{ $user->first_name }} {{ $user->last_name }}</h6>
        <span>{{ $user->email }}</span>
        <p>{{ $user->description }}</p>
        <a href="{{ route('employee.details', [$user->id]) }}" class="btn btnDetails">DETAILS ANSEHEN</a>
    </div>
</div>

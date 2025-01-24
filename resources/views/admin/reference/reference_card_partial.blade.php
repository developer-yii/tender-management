<div class="projectDetailsBox">
    <h6>Projekt: {{ $reference->project_title }}</h6>
    <div class="projectBox">
        <p>Umfang:</p>
        <pre class="pre">{{ $reference->scope }}</pre>
        <p class="date">Leistungszeitraum:<span>{{formatDateRange($reference->start_date, $reference->end_date)}}</span></p>
        <a href="{{ route('reference.details', [$reference->id])}}" class="btn btnDetails">DETAILS ANSEHEN</a>
    </div>
</div>
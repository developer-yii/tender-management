<div class="currentOffer">
    <div class="offertitle">
        <h6>ANGEBOTFRIST DER AKTUELLEN AUSSCHREIBUNGEN</h6>
    </div>
    @foreach($tenders as $tender)
        <div class="offerComBox">
            <div class="offerLeft">
                <div class="imgBox">
                    <img src="{{ getTenderMainImage($tender) }}" alt="{{$tender->tender_name}}">
                </div>
                <div class="textBox">
                    <p>{{$tender->tender_name}}</p>
                </div>
            </div>
            <div class="offerRight">
                <div class="tagBox">
                    <h5>{{ getRemainingDays($tender->offer_period_expiration) }} <br><span>Tage</span></h5>
                </div>
            </div>
        </div>
    @endforeach

</div>
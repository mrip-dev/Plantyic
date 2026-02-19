{{-- Car Damage View Section - Static Content for PDF --}}
<div class="report-card">
    <div class="card-header"><i class="fa-solid fa-triangle-exclamation"></i>Damage Assessment</div>
    <div class="card-body" style="text-align: center;">


        @if(isset($damageImagePath) && file_exists($damageImagePath))
        <img src="{{ $damageImagePath }}" alt="Damage Assessment" style="max-width: 100%; height: auto;">
        @else
        <div class="damage-assessment">
            <div class="status-pill status-good">
                <i class="fas fa-check-circle"></i>
                No Damage Reported or Image Not Found
            </div>
        </div>
        @endif
    </div>
</div>
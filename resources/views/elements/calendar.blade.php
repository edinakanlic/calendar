{{-- Register absence modal --}}
@include('modals.register_absence', [
    'modalIdPrefix' => 'registerAbsence',
    'confirmAction' => 'registerAbsenceConfirm()',
    'confirmButton' => 'Sačuvaj'
])

<div class ="container">

    <div class="notification">
        <div id="danger-notification" class="alert alert-danger" role="danger" style="display: none;"></div>
        <div id="success-notification" class="alert alert-success" role="success" style="display: none;"></div>
    </div>

    <div class="header-calendar mt-5">
        <div>
            <span class="bold">Kalendar</span>
        </div>

        <div>
            <button onclick="registerAbsence()" class="btn btn-primary tiles-button">
                <span class="bold">Registruj odsustvo</span>
            </button>
        </div>

    </div>

    <div>
        @include('elements.calendar_table')
    </div>

    <div class="header-calendar mt-5">
        <div></div>

        <div>
            <button onclick="storeInArchive()" class="btn btn-primary tiles-button">
                <span class="bold">Sačuvaj u arhivi</span>
            </button>
        </div>

    </div>



</div>


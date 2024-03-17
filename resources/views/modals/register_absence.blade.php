<link href="/css/modal.css" rel="stylesheet" type="text/css">

<!-- Modal -->
<div class="modal fade" id="{{ $modalIdPrefix }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $modalIdPrefix }}ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <!-- Modal value holders -->

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title bold" id="{{ $modalIdPrefix }}ModalLabel">Registrovanje odsustva</h5>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label class="" for="absence_from">Od:</label>
                    <input type="text" name="absence_from" class="form-control" value="">
                </div>

                <div class="form-group">
                    <label class="" for="absence_to">Do:</label>
                    <input type="text" name="absence_to" class="form-control" value="">
                </div>

                <div class="form-group">
                    <label class="" for="absence_type">Vrsta odsustva:</label>

                    <select name="absence_type" class="form-control">
                        @foreach ($absenceTypes as $type => $name)
                            <option value="{{ $type }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="footer">
                <button type="submit" class="btn positive btn-primary tiles-button" onclick="{{ $confirmAction }}">{{ $confirmButton }}</button>
            </div>
        </div>
    </div>
</div>

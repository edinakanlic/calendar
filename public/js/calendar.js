$(document).ready(function(){

    var today = new Date();
    var firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    var lastDayOfMonth  = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    $("input[name='absence_from']").datetimepicker({
        locale: moment.locale('en', {
            week: { dow: 1 }
        }),
        format: 'DD.MM.YYYY',
        minDate: firstDayOfMonth,
        maxDate: lastDayOfMonth,
        daysOfWeekDisabled: [0, 6]

    }).on('dp.hide', function (e) {

        let absenceFrom = $(this).val();
        let absenceTo   = $("input[name='absence_to']").val();

        let start = new Date(absenceFrom).getTime() / 1e3;
        let end   = new Date(absenceTo).getTime() / 1e3;

        if (start > end) {
            $('*[type=submit]').attr('disabled', true);
            $('*[type=submit]').attr('title', 'Ne može kranji datum biti veći od početnog datuma.');
        } else {
            $('*[type=submit]').attr('disabled', false);
            $('*[type=submit]').attr('title', '');
        }
    });

    $("input[name='absence_to']").datetimepicker({
        locale: moment.locale('en', {
            week: { dow: 1 }
        }),
        format: 'DD.MM.YYYY',
        minDate: firstDayOfMonth,
        maxDate: lastDayOfMonth,
        daysOfWeekDisabled: [0, 6]

    }).on('dp.hide', function (e) {

        let absenceFrom = $("input[name='absence_from']").val();
        let absenceTo   = $(this).val();

        let start = new Date(absenceFrom).getTime() / 1e3;
        let end   = new Date(absenceTo).getTime() / 1e3;

        if (start > end) {
            $('*[type=submit]').attr('disabled', true);
            $('*[type=submit]').attr('title', 'Ne može krajnji datum biti veći od početnog datuma.');
        } else {
            $('*[type=submit]').attr('disabled', false);
            $('*[type=submit]').attr('title', '');
        }
    });
    
});


function registerAbsence() {
    $('#registerAbsenceModal').modal('show');
}

function registerAbsenceConfirm() {

    let type     = $("select[name=absence_type]").val();
    let dateFrom = $("input[name=absence_from]").val();
    let dateTo   = $("input[name=absence_to]").val();

    $.ajax({
        type: 'POST',
        url: '/absence/store',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'type'     : type,
            'dateFrom' : dateFrom,
            'dateTo'   : dateTo
        },
        success: function(response) {
            notification(response.message_type, response.message);

            renderAbsenceToCalendar(response.data);

            $('#registerAbsenceModal').modal('hide');

            $("select[name=absence_type]").val(0)
            $("input[name=absence_from]").val('');
            $("input[name=absence_to]").val('');
        },
        error: function (response) {
        },
    });

}

function renderAbsenceToCalendar(data){

    if(data.length !== 0) {
        let days  = data['days'];
        let type = data['typeName'];

        $.each(days, function( key, day ) {
            $('#absence_' + day).html(type);
        });

    }

}

function storeInArchive () {

    $.ajax({
        type: 'POST',
        url: '/absences/archive',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
        },
        success: function(response) {
            location.reload();
        },
        error: function (response) {
        },
    });

}

function notification($type, $message) {

    if($type == 'success') {
        $('#success-notification').css('display', 'block');
        $('#success-notification').html($message);
    } else {
        $('#danger-notification').css('display', 'block');
        $('#danger-notification').html($message);
    }

    setInterval(() => {
        $('.alert').fadeOut()
    }, 3000);
}


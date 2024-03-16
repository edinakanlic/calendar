$(document).ready(function(){

    var today = new Date();
    var firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    $("input[name='absence_date']").datetimepicker({
        locale: 'ba',
        format: 'DD.MM.YYYY',
        minDate: firstDayOfMonth,
        maxDate: lastDayOfMonth
    });
});


function registerAbsence() {
    $('#registerAbsenceModal').modal('show');
}

function registerAbsenceConfirm() {

    let type = $("select[name=absence_type]").val();
    let date = $("input[name=absence_date]").val();

    $.ajax({
        type: 'POST',
        url: '/absence/store',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'type': type,
            'date': date
        },
        success: function(response) {
            notification(response.message_type, response.message);

            renderAbsenceToCalendar(response.data);
            
            $('#registerAbsenceModal').modal('hide');
        },
        error: function (response) {
        },
    });

}


function renderAbsenceToCalendar(data){

    if(data.length !== 0) {
        let day  = data['dateDay'];
        let type = data['typeName'];

        $('#absence_' + day).html(type);
    }

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


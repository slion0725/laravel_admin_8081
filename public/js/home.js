$(function () {
    $('#Loading').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    $('#submenu').submenupicker();

    $('#menu .active').each(function (i, e) {
        $(e).parent().addClass('in');
    });

    $('#logout').on('click', function () {
        $.ajax({
            url: '/logout',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function (jqXHR, settings) {
                $('#Loading').modal('show');
            },
            success: function (data, textStatus, jqXHR) {
                if (data.status === true) {
                    $.notify({
                        message: data.message
                    }, {
                        type: 'success',
                        z_index: 100000,
                    });
                    window.location.href = '/login';
                } else {
                    $.notify({
                        message: data.message
                    }, {
                        type: 'danger',
                        z_index: 100000,
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $.notify({
                    message: 'Error'
                }, {
                    type: 'danger',
                    z_index: 100000,
                });
            },
            complete: function (jqXHR, textStatus) {
                $('#Loading').modal('hide');
            }
        }, 'json');
    });
});
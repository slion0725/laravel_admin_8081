$(function () {
    $('#resetpasswordbysavenewpassword-form').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled'
    }).on('success.form.fv', function (event) {

        event.stopPropagation();
        event.preventDefault();

        let el = $(this);
        let fd = new FormData(this);

        $.ajax({
            url: '/password/reset',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: fd,
            processData: false,
            contentType: false,
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
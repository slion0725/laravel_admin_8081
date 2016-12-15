$(function () {
    $('#Profile-Form').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    }).on('success.form.fv', function (event) {

        event.stopPropagation();
        event.preventDefault();

        $.ajax({
            url: '/profile',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: new FormData(this),
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

    $('#Password-Form').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    }).on('success.form.fv', function (event) {

        event.stopPropagation();
        event.preventDefault();

        let el = $(this);

        $.ajax({
            url: '/profile/password',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: new FormData(this),
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
                    el.trigger('reset').data('formValidation').resetForm();
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
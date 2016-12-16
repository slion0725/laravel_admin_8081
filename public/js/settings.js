const vars = {
    datatableTitle: 'Settings',
    ajaxUrl: '/settings',
    view: [
        'id',
        'name',
        'data',
        'description',
        'created_at',
        'updated_at',
    ],
    edit: [
        'id',
        'name',
        'data',
        'description',
    ],
};

$.extend($.fn.dataTable.defaults, {
    dom: `<
              <'row'
                  <'col-sm-6 dtL'l>
                  <'col-sm-6 dtB text-right'B>
              >
              <'row'
                  <'col-sm-12'tr>
              >
              <'row'
                  <'col-sm-5'i>
                  <'col-sm-7'p>
              >
        >`,
    renderer: 'bootstrap',
    displayLength: 10,
    lengthMenu: [
        [
            10, 25, 50
        ],
        [10, 25, 50]
    ],
    pagingType: 'simple_numbers',
    language: {
        lengthMenu: '_MENU_',
        search: '',
        paginate: {
            first: '&laquo;',
            previous: '&lsaquo;',
            next: '&rsaquo;',
            last: '&raquo;'
        }
    },
    serverSide: true,
    ajax: {
        url: '',
        type: 'GET'
    },
    colReorder: true,
    select: true,
    responsive: true,
    processing: true,
    autoWidth: false,
    stateSave: false,
    buttons: [{
        extend: 'excel',
        text: '<i class="fa fa-file-excel-o"></i>',
        titleAttr: 'Excel',
        title: vars.datatableTitle,
        exportOptions: {
            columns: ':visible'
        }
    }, {
        extend: 'csv',
        text: '<i class="fa fa-file-code-o"></i>',
        titleAttr: 'CSV',
        title: vars.datatableTitle,
        exportOptions: {
            columns: ':visible'
        }
    }, {
        extend: 'pdf',
        text: '<i class="fa fa-file-pdf-o"></i>',
        titleAttr: 'PDF',
        title: vars.datatableTitle,
        exportOptions: {
            columns: ':visible'
        },
        orientation: 'landscape'
    }, {
        extend: 'print',
        text: '<i class="fa fa-print"></i>',
        titleAttr: 'Print',
        title: vars.datatableTitle,
        exportOptions: {
            columns: ':visible'
        }
    }, {
        extend: 'colvis',
        text: '<i class="fa fa-th"></i>',
        titleAttr: 'Colvis'
    }]
});

$(function () {
    let datatable = $('#datatable').DataTable({
        ajax: {
            url: vars.ajaxUrl + '/datatable'
        },
        order: [
            [0, 'asc']
        ],
        columns: [{
            data: 'id',
            name: 'id',
            searchable: true
        }, {
            data: 'name',
            name: 'name',
            searchable: true
        }, {
            data: 'description',
            name: 'description',
            searchable: true
        }, {
            data: 'created_at',
            name: 'created_at',
            searchable: true,
            render:function(data,type,full,meta){
                moment.tz.setDefault(moment.tz.guess());
                if(data != null){
                    return moment(moment.utc(data).toDate()).format("YYYY-MM-DD HH:mm:ss");
                }
                return data;
            }
        }, {
            data: 'updated_at',
            name: 'updated_at',
            searchable: true,
            render:function(data,type,full,meta){
                moment.tz.setDefault(moment.tz.guess());
                if(data != null){
                    return moment(moment.utc(data).toDate()).format("YYYY-MM-DD HH:mm:ss");
                }
                return data;
            }
        }]
    });

    $('#View-Modal,#Add-Modal,#Edit-Modal').modal({
        backdrop: false,
        keyboard: false,
        show: false
    });

    $('#View').on('click', function () {
        let datas = datatable.rows({
            selected: true
        }).data();

        if (datas.length !== 1) {
            $.notify({
                message: 'Please select 1 data'
            }, {
                type: 'warning',
                z_index: 100000,
            });
            return;
        }

        let data = datas[0];

        $.ajax({
            url: vars.ajaxUrl + '/' + data.id,
            method: 'GET',
            beforeSend: function (jqXHR, settings) {
                $('#Loading').modal('show');
            },
            success: function (data, textStatus, jqXHR) {
                if (data.status === true) {
                    vars.view.map(function (v, i) {
                        $('#View-Modal .view-' + v).html(data.data[v]);
                    });
                    $('#View-Modal').modal('show');
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

    $('#Add').on('click', function () {
        $('#Add-Modal').modal('show');
    });

    $('#Edit').on('click', function () {
        let datas = datatable.rows({
            selected: true
        }).data();

        if (datas.length !== 1) {
            $.notify({
                message: 'Please select 1 data'
            }, {
                type: 'warning',
                z_index: 100000,
            });
            return;
        }

        let data = datas[0];

        $.ajax({
            url: vars.ajaxUrl + '/' + data.id + '/edit',
            method: 'GET',
            beforeSend: function (jqXHR, settings) {
                $('#Loading').modal('show');
            },
            success: function (data, textStatus, jqXHR) {
                if (data.status === true) {
                    vars.edit.map(function (v, i) {
                        $('#Edit-Form [name="' + v).val(data.data[v]);
                    });
                    $('#Edit-Modal').modal('show');
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

    // size: 'small', null, 'large'
    $('#Remove').on('click', function () {
        let datas = datatable.rows({
            selected: true
        }).data();

        if (datas.length !== 1) {
            $.notify({
                message: 'Please select 1 data'
            }, {
                type: 'warning',
                z_index: 100000,
            });
            return;
        }

        bootbox.confirm({
            message: 'Remove?',
            size: 'small',
            backdrop: false,
            callback: function (result) {
                if (result === false) {
                    return;
                }

                let data = datas[0];

                $.ajax({
                    url: vars.ajaxUrl + '/' + data.id,
                    method: 'DELETE',
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
                                z_index: 100000
                            });
                        } else {
                            $.notify({
                                message: data.message
                            }, {
                                type: 'danger',
                                z_index: 100000
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.notify({
                            message: 'Error'
                        }, {
                            type: 'danger',
                            z_index: 100000
                        });
                    },
                    complete: function (jqXHR, textStatus) {
                        $('#Loading').modal('hide');
                        datatable.page(datatable.page()).draw(false);
                    }
                }, 'json');
            }
        });
    });

    $('#Add-Form').formValidation({
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
            url: vars.ajaxUrl,
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
                    el.parents('.modal').modal('hide');
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
                datatable.page(datatable.page()).draw(false);
            }
        }, 'json');
    });

    $('#Edit-Form').formValidation({
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

        let id = $(this).find('input[name="id"]').val();

        $.ajax({
            url: vars.ajaxUrl + '/' + id,
            method: 'POST',
            data: fd,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                    el.parents('.modal').modal('hide');
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
                datatable.page(datatable.page()).draw(false);
            }
        }, 'json');
    });
});
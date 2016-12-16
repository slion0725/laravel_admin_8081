var vars = {
    datatableTitle: 'Weblogs',
    ajaxUrl: '/weblogs',
    view: [
        'id',
        'user_id',
        'name',
        'level',
        'method',
        'require_data',
        'description',
        'created_at',
        'updated_at'
    ]
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
        title: 'Weblogs',
        exportOptions: {
            columns: ':visible'
        }
    }, {
        extend: 'csv',
        text: '<i class="fa fa-file-code-o"></i>',
        titleAttr: 'CSV',
        title: 'Weblogs',
        exportOptions: {
            columns: ':visible'
        }
    }, {
        extend: 'pdf',
        text: '<i class="fa fa-file-pdf-o"></i>',
        titleAttr: 'PDF',
        title: 'Account',
        exportOptions: {
            columns: ':visible'
        },
        orientation: 'landscape'
    }, {
        extend: 'print',
        text: '<i class="fa fa-print"></i>',
        titleAttr: 'Print',
        title: 'Weblogs',
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
            url: '/weblogs/datatable'
        },
        order: [
            [0, 'dest']
        ],
        columns: [{
            data: 'id',
            name: 'id',
            searchable: true
        }, {
            data: 'user_id',
            name: 'user_id',
            searchable: true
        }, {
            data: 'name',
            name: 'name',
            searchable: true
        }, {
            data: 'level',
            name: 'level',
            searchable: true
        }, {
            data: 'method',
            name: 'method',
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
            url: '/weblogs/' + data.id,
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
});
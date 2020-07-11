$(function() {
    console.log(app_url);
    var becaTabla = $('#beca-table').DataTable({
        processing: true,
        order: [[1, 'asc']],
        serverSide: true,
        ajax: {
            url: urlIndexBeca
        },
        deferRender: true,
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false , visible: false},
            { data: 'Beca', name: 'Beca', title: 'Descripción' },
            { data: 'action', name: 'action', orderable: false, searchable: false },


        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()).empty())
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                    column.search(val ? val : '', true, false).draw();
                });                             
            });
        },
        language: { "url": '/lang/datatables.es.json' },
        dom: 'lftip',
    });

    $('#beca-table tbody').on('click', 'tr', function () {
        var data = becaTabla.row( this ).data();
        vm.$options.methods.showBeca (data.id);
    });
});


var vm = new Vue({
    el: '#beca-app',
    data: {
        //accounting: accounting,
        //auth: auth,
        errorBag: {},
        isLoading: false,
        beca: {},
    },
    methods: {
       
        newBeca () {
            vm.beca = {};
            vm.errorBag = {};
            $('#frm-beca').modal('show');
        },
        saveBeca () {
            axios.post( urlSaveBeca, vm.beca)
                .then ( result => {
                    response = result.data;
                    toastr.success(response.msg, 'Correcto!');
                    //$('#view-beca').modal('show');
                    $('#frm-beca').modal('hide');
                    var becaTabla = $('#beca-table').DataTable();
                    becaTabla.draw();
                })
                .catch( error => {
                    vm.errorBag = error.data.errors;
                });
        },
        showBeca (id) {
            axios.post( urlShowBeca, { id: id, beca: 6 })
                .then ( result => {
                        response = result.data;
                    vm.beca = response.data;
                    $('#view-beca').modal('show');
                })
                .catch ( error => {
                    console.log( error );
                });
        },
        editBeca () {
            $('#frm-beca').modal('show');  
            $('#view-beca').modal('hide');
        },
        deleteBeca () {
            /*axios.post( urlDestroyParametro, {id : vm.parametro.id} )
                 .then( result => {
                     response = result.data;
                     toastr.success(response.msg, 'Correcto!');
                     var becaTabla = $('#beca-table').DataTable();
                     becaTabla.draw();
                     $('#view-beca').modal('hide');
                 })
                 .catch( error => {
                     console.log ( error );
                 })
            $('#view-beca').modal('hide');
            */
            swal({
                title: "Estas seguro que deseas eliminar?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                    axios.post( urlDestroyBeca, {id : vm.beca.id} )
                        .then( result => {
                            response = result.data;
                            toastr.success(response.msg, 'Correcto!');
                            var becaTabla = $('#beca-table').DataTable();
                            becaTabla.draw();
                            $('#view-beca').modal('hide');
                        })
                        .catch( error => {
                            console.log ( error );
                        })
                } else {
                  //swal("Your imaginary file is safe!");
                }
              });
        }


    },
});

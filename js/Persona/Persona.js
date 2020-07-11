$(function () {
    $("#FechaNacimiento")
    .datepicker({
      format: "yyyy-mm-dd",
      autoclose: true
    })
    .on("change", function() {
      vm.persona.FechaNacimiento = $("#FechaNacimiento").val();
    });
    var personaTabla = $('#persona-table').DataTable({
        processing: true,
        order: [[0, 'asc']],
        serverSide: true,
        ajax: {
            url: urlIndexPersona
        },
        deferRender: true,
        columns: [
            { data: 'id', name: 'id', orderable: false, searchable: false, visible: false },
            // { data: 'null', name: 'null', orderable: false, searchable: false, render: function(data, type, row){
            //     if(row.Fotografia)
            //         return '<img style="width:50px;" src="/storage/documents/' + row.Fotografia + '">';
            //     else
            //         return '<img style="width:50px;" src="/images/default_image_profile.png">';
            // }},
            { data: 'UnidadAcademica', name: 'ua.UnidadAcademica', title: 'UnidadAcademica' },
            { data: 'Rol', name: 'r.Rol', title: 'Rol' },
            { data: 'CodigoAlumno', name: 'p.CodigoAlumno', title: 'CodAlumno' },
            { data: 'CI', name: 'p.CI', title: 'CI' },
            { data: 'Persona', name: 'p.Persona', title: 'Nombre Completo' },
            { data: 'EsRegular', name: 'p.EsRegular', title: 'Est. Nuevo', orderable: true, searchable: true, render: function(data,type,row) { if(row.EsRegular == 1) return '<i class="fa fa-ban text-danger"></i>'; else return '<i class="fa fa-check text-success"></i>';} },
            { data: 'email', name: 'p.email', title: 'Email' },
            { data: 'FechaNacimiento', name: 'p.FechaNacimiento', title: 'Fecha de Nacimiento' },
            { data: 'Sexo', name: 'p.Sexo', title: 'Sexo' },
            { data: 'Direccion', name: 'p.Direccion', title: 'Direccion' },
            { data: 'Telefono', name: 'p.Telefono', title: 'Telefono' },
            { data: 'Observaciones', name: 'p.Observaciones', title: 'Observaciones' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        language: { "url": "/lang/datatables.es.json" },
        dom: 'lftip',
    });

    $('#persona-table tbody').on('click', 'tr', function () {
        var data = personaTabla.row(this).data();
        vm.$options.methods.showPersona(data.id);
    });
});

var vm = new Vue({
    el: '#persona-app',
    data: {
        //accounting: accounting,
        moment: moment,
        auth: auth,
        errorBag: {},
        isLoading: false,
        isLoadingFile: false,
        persona: {},
        unidadAcademicas: {},
        roles: {},
        estados: {},
        isEditing: false,
        personaEspecialidad: {},
        password: {}
    },
    methods: {
        loadFile(input) {
            vm.isLoadingFile = true;
            var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.readAsDataURL(input.files[0]);
                    var data = new FormData();
                    data.append('File', input.files[0]);
                    axios.post(urlUploadFile, data)
                        .then( result => {
                            if (result.data.success) {
                                toastr.info(result.data.msg, 'Correcto!');
                                vm.persona.Fotografia = result.data.data;
                            } else {
                                toastr.error(result.data.msg, 'Oops!');
                            }
                            vm.isLoadingFile = false;
                        })
                        .catch( error => {
                            toastr.error('Error subiendo archivo', 'Oops!');
                            vm.isLoadingFile = false;

                        });
                }
        },            
        getUnidadAcademicas () {
            axios.get( urlListUnidadAcademica )
                .then( result => {
                    response = result.data;
                    vm.unidadAcademicas = response.data; 
                })
                .catch( error => {
                    console.log( error );
                })
        },
        getRoles () {
            axios.get( urlListRol )
                .then( result => {
                    response = result.data;
                    vm.roles = response.data; 
                })
                .catch( error => {
                    console.log( error );
                })
        },
        getEstados() {
            axios.get( urlListEstado)
                .then( result => {
                    response = result.data;
                    vm.estados = response.data;
                })
                .catch( error => {
                    console.log( error );
                });
        },
        newPersona() {
            vm.persona = {};
            $('#frm-persona').modal('show');
        },
        showPersona(id) {
            axios.get(urlShowPersona, { params: { id: id }})
                .then(result => {
                    response = result.data;
                    vm.persona = response.data;
                    $('#view-persona').modal('show');
                })
                .catch(error => {
                    console.log(error);
                });
        },
        cambiopassword(id) {
            $('#view-persona').modal('hide');
            $('#view-password').modal('show');
        },
        changePassword() {
            vm.password.Persona = vm.persona.id;
            axios.post( urlChangePasswordPersona, vm.password)
                .then( result => {
                    response = result.data;
                    if ( response.success ) {
                        toastr.success(response.msg, 'Correcto!');
                        $('#view-password').modal('hide');
                        $('#view-persona').modal('show');
                    } else {
                        toastr.error(response.msg, 'Oops!');
                    }
                })
                .catch( error => {
                    console.log( error );
                    toastr.error('Error al guardar el registro', 'Oops!');
                    vm.errorBag = error.data.errors;
                })
        },
        editPersona() {
            $('#view-persona').modal('hide');
            $('#frm-persona').modal('show');
        },
        savePersona() {
            axios.post(urlSavePersona, vm.persona)
                .then(result => {
                    response = result.data;
                    vm.persona = response.data;
                    toastr.success(response.msg, 'Correcto!');
                    $('#frm-persona').modal('hide');
                    $('#view-persona').modal('show');
                    var personaTabla = $('#persona-table').DataTable();
                    personaTabla.draw();
                })
                .catch(error => {
                    console.log(error);
                    toastr.error('Error al guardar el registro', 'Oops!');
                    vm.errorBag = error.data.errors;
                });
        },
        
        deletePersona() {
            swal({
                title: "Estas seguro que deseas eliminar el registro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlDestroyPersona, { id: vm.persona.id })
                        .then(result => {
                            response = result.data;
                            if ( response.success ) {
                                toastr.success(response.msg, 'Correcto!');
                                var personaTabla = $('#persona-table').DataTable();
                                personaTabla.draw();
                                $('#view-persona').modal('hide');
                            } else {
                                toastr.error(response.msg, 'Oops!');
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
                } else {
                }
            });
        },
        editPersonaEspecialidadEstado(personaEspecialidad) {
            vm.isEditing = true;
            vm.personaEspecialidad = personaEspecialidad;
            

        }
    },

    mounted () {
        this.getUnidadAcademicas();
        this.getRoles();
    } 
});
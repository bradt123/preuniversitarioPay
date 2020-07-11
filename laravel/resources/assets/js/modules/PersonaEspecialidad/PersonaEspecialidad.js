$(function () {
    var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable({
        processing: true,
        order: [[10, 'asc']],
        serverSide: true,
        ajax: {
            url: urlIndexPersonaEspecialidad,
            data: function(d) { d.PlanPagosEnviado = $('#planPagosEnviado').val()}
        },
        deferRender: true,
        columns: [
            { data: 'id', name: 'pe.id', orderable: false, searchable: false, visible: false },
            { data: 'UnidadAcademica', name: 'ua.UnidadAcademica', title: 'Unidad Académica', orderable: true, searchable: true },
            { data: 'NivelAcademico', name: 'nv.NivelAcademico', title: 'Nivel Académico', orderable: true, searchable: true },
            { data: 'Especialidad', name: 'e.Especialidad', title: 'Especialidad', orderable: true, searchable: true },
            { data: 'EsRegular', name: 'p.EsRegular', title: 'Est. Nuevo', orderable: true, searchable: true, render: function(data,type,row) { if(row.EsRegular == 1) return '<i class="fa fa-ban text-danger"></i>'; else return '<i class="fa fa-check text-success"></i>';} },
            { data: 'Beca', name: 'b.Beca', title: 'Descuento', orderable: true, searchable: true },
            { data: 'Persona', name: 'p.Persona', title: 'Nombre Completo', orderable: true, searchable: true },
            { data: 'CodigoAlumno', name: 'p.CodigoAlumno', title: 'CodAlumno' },
            { data: 'email', name: 'p.email', title: 'Correo Electrónico' },
            { data: 'CI', name: 'p.CI', title: 'Cédula de Identidad', orderable: true, searchable: true },
//            { data: 'ProntoPago', name: 'pe.ProntoPago', searchable: false, orderable:false, title: 'Pronto Pago',  render: function(data,type,row) { if(row.ProntoPago) return '<i class="fa fa-check text-success"></i>'; else return '<i class="fa fa-ban text-danger"></i>';} },
            //{ data: 'PlanPagosEnviado', name: 'pe.PlanPagosEnviado', searchable: false, orderable:false, title: 'Plan de Pagos enviados',  render: function(data,type,row) { if(row.PlanPagosEnviado) return '<i class="fa fa-check text-success"></i>'; else return '<i class="fa fa-ban text-danger"></i>';} },
            //{ data: 'Total', name: 'Total', visible: auth.Rol<6 ? true:false,searchable: false, orderable:false, title: 'Total Bs.',  render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
            { data: 'created_at', name: 'pe.created_at', title: 'Fecha', orderable: true, searchable: true,
                render: function (data, type, row) {
                    return moment(row.created_at).format('DD-MM-YYYY') ;
                }
            },
            { data: 'Estado', name: 'es.Estado', title: 'Estado', orderable: true, searchable: true,
                render: function (data, type, row) {
                    if(row.idEstado == 1)
                        return `<span class="badge badge-secondary">${row.Estado}</span>` ;
                    if(row.idEstado == 2)
                        return `<span class="badge badge-warning">${row.Estado}</span>` ;
                    if(row.idEstado == 3)
                        return `<span class="badge badge-primary">${row.Estado}</span>` ;
                    if(row.idEstado == 4)
                        return `<span class="badge badge-success">${row.Estado}</span>` ;
                    if(row.idEstado == 5)
                        return `<span class="badge badge-dark">${row.Estado}</span>` ;
                    if(row.idEstado == 6)
                        return `<span class="badge badge-success">${row.Estado}</span>` ;
                    if(row.idEstado == 7)
                        return `<span class="badge badge-danger">${row.Estado}</span>` ;

                        return '';
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        language: { "url": "/lang/datatables.es.json" },
        dom: 'lftip',
    });
    /* filtro por plan de pagos enviado */
    $('#planPagosEnviado').on('change', function(){
        personaEspecialidadTabla.draw();
    });

    $('#personaEspecialidad-table tbody').on('click', 'tr', function () {
        var data = personaEspecialidadTabla.row(this).data();
        vm.$options.methods.showPersonaEspecialidad(data.id);
    });
});

var vm = new Vue({
    el: '#personaEspecialidad-app',
    data: {
        moment: moment,
        accounting: accounting,
        auth: auth,
        errorBag: {},
        isLoading: false,
        isEditing: false,
        persona: {},
        personaEspecialidad: {},
        estados: {},
        especialidad: {},
        observaciones: null,
        planPagos: {},
        siscoinMontos: {}
    },
    computed: {
        totalPlanPagos () {
             var suma = 0;
            if(this.planPagos.length > 0){
                this.planPagos.forEach( e => {
                    suma += parseFloat(e.monto);
                })

            }
            return suma;
        }
    },
    methods: {
        getEstados() {
            axios.get( urlListEstado )
                .then( result => { vm.estados = result.data.data})
                .catch( error => { console.log( error )});
        },
        drawEstado(estado) {
            if(estado.Estado == 1)
                return `<span class="badge badge-secondary">${estado.estado.Estado}</span>` ;
            if(estado.Estado == 2)
                return `<span class="badge badge-warning">${estado.estado.Estado}</span>` ;
            if(estado.Estado == 3)
                return `<span class="badge badge-primary">${estado.estado.Estado}</span>` ;
            if(estado.Estado == 4)
                return `<span class="badge badge-success">${estado.estado.Estado}</span>` ;
            if(estado.Estado == 5)
                return `<span class="badge badge-dark">${estado.estado.Estado}</span>` ;
            if(estado.Estado == 6)
                return `<span class="badge badge-success">${estado.estado.Estado}</span>` ;
            if(estado.Estado == 7)
                return `<span class="badge badge-danger">${estado.estado.Estado}</span>` ;

            return '';
        },
        newPersonaEspecialidad() {
            vm.personaEspecialidad = {};
            $('#frm-personaEspecialidad').modal('show');
        },
        showPersonaEspecialidad(id) {
            axios.get(urlShowPersonaEspecialidad, { params: { id: id }})
                .then(result => {
                    response = result.data;
                    vm.personaEspecialidad = response.data;
                    $('#view-personaEspecialidad').modal('show');
                })
                .catch(error => {
                    console.log(error);
                });
        },
        generaBoleta(materia) {
            swal({
                title: "Estas Seguro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlDestroyMateria,  vm.personaEspecialidad)
                        .then(result => {
                            response = result.data;
                            toastr.success(response.msg, 'Correcto!');
                            $('#view-especialidad').modal('hide');
                            this.downloadBoleta();
                            var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable();
                            personaEspecialidadTabla.draw();
                        })
                        .catch(error => {
                            console.log(error);
                            toastr.error(response.msg, 'Oops!');
                            vm.errorBag = error.data.errors;
                        });
                } else {
                }
            });
        },
        generaReincorporacion(materia) {
            swal({
                title: "Estas Seguro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlReincorporacionMateria,  vm.personaEspecialidad)
                        .then(result => {
                            response = result.data;
                            toastr.success(response.msg, 'Correcto!');
                            $('#view-especialidad').modal('hide');
                            //this.downloadBoleta();
                            var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable();
                            personaEspecialidadTabla.draw();
                        })
                        .catch(error => {
                            console.log(error);
                            toastr.error(response.msg, 'Oops!');
                            vm.errorBag = error.data.errors;
                        });
                } else {
                }
            });
        },
        generaAdelanto(materia) {
            swal({
                title: "Estas Seguro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlAdelantoMateria,  vm.personaEspecialidad)
                        .then(result => {
                            response = result.data;
                            toastr.success(response.msg, 'Correcto!');
                            $('#view-especialidad').modal('hide');
                            //this.downloadBoleta();
                            var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable();
                            personaEspecialidadTabla.draw();
                        })
                        .catch(error => {
                            console.log(error);
                            toastr.error(response.msg, 'Oops!');
                            vm.errorBag = error.data.errors;
                        });
                } else {
                }
            });
        },
        generaRepite(materia) {
            swal({
                title: "Estas Seguro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlRepiteMateria,  vm.personaEspecialidad)
                        .then(result => {
                            response = result.data;
                            toastr.success(response.msg, 'Correcto!');
                            $('#view-especialidad').modal('hide');
                            //this.downloadBoleta();
                            var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable();
                            personaEspecialidadTabla.draw();
                        })
                        .catch(error => {
                            console.log(error);
                            toastr.error(response.msg, 'Oops!');
                            vm.errorBag = error.data.errors;
                        });
                } else {
                }
            });
        },
        generaBoletaEspecial(materia) {
            swal({
                title: "Estas Seguro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlDestroyMateria,  vm.personaEspecialidad)
                        .then(result => {
                            response = result.data;
                            toastr.success(response.msg, 'Correcto!');
                            $('#view-especialidad').modal('hide');
                            this.downloadBoletaEspecial();
                            var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable();
                            personaEspecialidadTabla.draw();
                        })
                        .catch(error => {
                            console.log(error);
                            toastr.error(response.msg, 'Oops!');
                            vm.errorBag = error.data.errors;
                        });
                } else {
                }
            });
        },
        downloadPersona() {
            axios.post( urlPrintPersona,vm.personaEspecialidad.persona)
            .then( result => {
                response = result.data;
                var urlFile = response.data.url;
                //window.open(urlFile);
                var x = new XMLHttpRequest();
                x.open("GET", urlFile, true);
                x.responseType = 'blob';
                    x.onload = e => {
                        //console.log(pd);
                        //vm.isLoading = false;
                        download(x.response, vm.persona.Persona + '.pdf', "application/pdf" );
                    }
                    x.send();
                })
                .catch( error => {
                    console.log( error );
                });
            },
        downloadBoleta() {
            axios.get( urlPrintBoleta, { params: {id: vm.personaEspecialidad.id }} )
            .then( result => {
                response = result.data;
                var urlFile = response.data.url;
                //window.open(urlFile);
                var x = new XMLHttpRequest();
                x.open("GET", urlFile, true);
                x.responseType = 'blob';
                    x.onload = e => {
                        //console.log(pd);
                        //vm.isLoading = false;
                        download(x.response, 'Boleta de Inscripcion '+vm.personaEspecialidad.persona.Persona + '.pdf', "application/pdf" );
                    }
                    x.send();
                })
                .catch( error => {
                    console.log( error );
                });
        },
        downloadBoletaEspecial() {
            axios.get( urlPrintBoletaEspecial, { params: {id: vm.personaEspecialidad.id }} )
            .then( result => {
                response = result.data;
                var urlFile = response.data.url;
                //window.open(urlFile);
                var x = new XMLHttpRequest();
                x.open("GET", urlFile, true);
                x.responseType = 'blob';
                    x.onload = e => {
                        //console.log(pd);
                        //vm.isLoading = false;
                        download(x.response, 'Boleta de Inscripcion '+vm.personaEspecialidad.persona.Persona + '.pdf', "application/pdf" );
                    }
                    x.send();
                })
                .catch( error => {
                    console.log( error );
                });
        },
        savePersonaEspecialidadMateria(materia) {
            console.log(materia);
            axios.post(urlSaveMateria,  materia)
                .then(result => {
                    response = result.data;
                    toastr.success(response.msg, 'Correcto!');
                })
                .catch(error => {
                    console.log(error);
                    toastr.error(response.msg, 'Oops!');
                    vm.errorBag = error.data.errors;
                });
        },
        savePersonaEspecialidadRequisito(requisito) {
            axios.post(urlSaveRequisito,  requisito)
                .then(result => {
                    response = result.data;
                    toastr.success(response.msg, 'Correcto!');
                })
                .catch(error => {
                    console.log(error);
                    toastr.error(response.msg, 'Oops!');
                    vm.errorBag = error.data.errors;
                });
        },
        getEspecialidad (id) {
            axios.get( urlListEspecialidad, {params: {UnidadAcademica: id, Activo: true}})
                .then( result => {
                    vm.especialidad = result.data.data;
                    $('#view-especialidad').modal('show');
                })
                .catch( error => {
                    console.log( error );
                })
        },
        deletePersonaEspecialidad() {
            swal({
                title: "Estas seguro que deseas eliminar el registro?",
                text: "Esta accion es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.post(urlDestroyPersonaEspecialidad, { id: vm.personaEspecialidad.id })
                        .then(result => {
                            response = result.data;
                            if ( response.success ) {
                                toastr.success(response.msg, 'Correcto!');
                                var personaEspecialidadTabla = $('#personaEspecialidad-table').DataTable();
                                personaEspecialidadTabla.draw();
                                $('#frm-personaEspecialidad').modal('hide');
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
        insertInformixPersonaEspecialidad() {
            swal({
                title: "Estas seguro que deseas realizar la inserción en la base de datos SISCOIN?",
                text: "Ésta acción es irreversible!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    vm.isLoading = true;
                    axios.get(urlInsertInformixPersonaEspecialidad, { params: {id: vm.personaEspecialidad.id }} )
                        .then(result => {
                            response = result.data;
                            if ( response.success ) {
                                toastr.success(response.msg, 'Correcto!');
                                //$('#view-personaEspecialidad').modal('hide');
                            } else {
                                toastr.error(response.msg, 'Oops!');
                            }
                            vm.isLoading = false;
                        })
                        .catch(error => {
                            vm.isLoading = false;
                            console.log(error);
                        })
                } else {
                }
            });
        },
        sendRequisitosPersonaEspecialidad() {
            swal({
                title: "Estas seguro que deseas reenviar los formularios y requisitos al estudiante?",
                text: "Se enviarán los documentos por correo electrónico!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    vm.isLoading = true;
                    axios.get(urlSendRequisitosPersonaEspecialidad, { params: {id: vm.personaEspecialidad.id }} )
                        .then(result => {
                            response = result.data;
                            if ( response.success ) {
                                toastr.success(response.msg, 'Correcto!');
                                $('#view-personaEspecialidad').modal('hide');
                            } else {
                                toastr.error(response.msg, 'Oops!');
                            }
                            vm.isLoading = false;
                        })
                        .catch(error => {
                            vm.isLoading = false;
                            console.log(error);
                        })
                } else {
                }
            });
        },
        viewPlanPagosPersonaEspecialidad() {
            axios.get( urlViewPlanPagosPersonaEspecialidad , { params: {id: vm.personaEspecialidad.id }} )
            .then(result => {
                    response = result.data;
                    vm.planPagos = response.data;
                    $('#view-planPagos').appendTo("body").modal('show');
                })
                .catch(error => {
                    console.log(error);
                });
            },
        viewMontoPersonaEspecialidad(){
            axios.get( urlViewInformixPersonaEspecialidad , { params: {id: vm.personaEspecialidad.id }} )
            .then(result => {
                    response = result.data;
                    vm.siscoinMontos = response.data;
                    $('#view-siscoin').appendTo("body").modal('show');
                })
                .catch(error => {
                    console.log(error);
                });

        },
        sendPlanPagosPersonaEspecialidad() {
            swal({
                title: "Estas seguro que deseas enviar el plan de pagos al estudiante?",
                text: "Se notificará por correo electrónico al estudiante!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    vm.isLoading = true;
                    $('#view-personaEspecialidad').modal('hide');
                    axios.get(urlSendPlanPagosPersonaEspecialidad, { params: {id: vm.personaEspecialidad.id }} )
                        .then(result => {
                            response = result.data;
                            if ( response.success ) {
                                toastr.success(response.msg, 'Correcto!');
                            } else {
                                toastr.error(response.msg, 'Oops!');
                            }
                            vm.isLoading = false;
                            $('#view-personaEspecialidad').modal('show');

                        })
                        .catch(error => {
                            vm.isLoading = false;
                            console.log(error);
                        })
                } else {
                }
            });
        },
        descargarFoto(){

                    var x = new XMLHttpRequest();
                    x.open("GET", (vm.personaEspecialidad.persona ? vm.personaEspecialidad.persona.URLFoto:null), true);
                    var extension = vm.personaEspecialidad.persona ? (vm.personaEspecialidad.persona.Fotografia.substr(vm.personaEspecialidad.persona.Fotografia.indexOf('.'),6)): '';
                    //console.log(extension);
                    x.responseType = 'blob';
                    x.onload = e => {
                        //vm.isLoading = false;
                        download(x.response, (vm.personaEspecialidad.persona ? vm.personaEspecialidad.persona.Persona: '') + extension , "image/jpg" );
                    }
                    x.send();
        },
        personaEspecialidadPrint(){
            axios.get( urlPrintPersonaEspecialidad, { params: { id: vm.personaEspecialidad.id}})
                .then( result => {
                    response = result.data;
                    var urlFile = response.data.url;
                    // window.open(urlFile);
                    var x = new XMLHttpRequest();
                    x.open("GET", urlFile, true);
                    x.responseType = 'blob';
                    x.onload = e => {
                        //vm.isLoading = false;
                        download(x.response, vm.personaEspecialidad.persona.Persona + '.pdf');
                    }
                    x.send();
                })
                .catch( error => {
                    console.log( error );
                });
        },
        downloadPersonaEspecialidadRequisito(per){
            axios.get( urlDownloadPersonaEspecialidadRequisito, { params: { id: per.id}})
                .then( result => {
                    response = result.data;
                    var urlFile = response.data.url;
                    // window.open(urlFile);
                    var x = new XMLHttpRequest();
                    x.open("GET", urlFile, true);
                    x.responseType = 'blob';
                    x.onload = e => {
                        //vm.isLoading = false;
                        download(x.response, per.requisito.Requisito + '.docx' , "application/vnd.openxmlformats-officedocument.wordprocessingml.document" );
                    }
                    x.send();
                })
                .catch( error => {
                    console.log( error );
                });
        },
        savePersonaEspecialidadEstado() {
            axios.post( urlSavePersonaEspecialidadEstado, {id: vm.personaEspecialidad.id, Estado: vm.personaEspecialidad.Estado, Observaciones: vm.observaciones})
                .then( result => {
                    response = result.data;
                    vm.showPersonaEspecialidad(response.data.id);
                    toastr.success(response.msg, 'Correcto!');
                    vm.isEditing = false;
                })
                .catch( error => {
                    console.log( error );
                });
        },
        cancelPersonaEspecialidadEstado() {
            vm.showPersonaEspecialidad(vm.personaEspecialidad.id);
            vm.isEditing = false;
        }
    },
    mounted() {
        this.getEstados();
        // if(auth.Rol == 4)
        //     $('#planPagosEnviado').val(0);
    }
});

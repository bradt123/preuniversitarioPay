@extends('layouts.app')
@section('content')

<div id="personaEspecialidad-app">
    <loading v-if="isLoading"></loading>
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">{{ trans('labels.modules.PersonaEspecialidad') }}</h4>
            </div>
        </div>
        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-dark m-0">Detalle de Registros</h3>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                    <strong style="font-size:17px; float:left; color:#169F85;padding-right:15%;padding-top: 15px;"> SELECCIONE SUS SERVICIOS O CUOTAS Y HAGA CLICK EN EL BOTON PAGAR
                                    </strong>
                                    <a href="#" @click.prevent="showModalPay" class="btn btn-success waves-effect waves-light m-l-8" style="font-size:17px; font-weight:bold;">
                                    <img src="{{URL::asset('/assets/images/tarjeta.png')}}" class="img-fluid" alt="Tarjeta Pago" width="80" />&nbsp;PAGAR</a>
                                  </div>
                                  </div>
                                <!-- <div class="btn-group float-right">
                                  <strong style="font-size:17px; float:left; color:#169F85"> SELECCIONE SUS SERVICIOS O CUOTAS Y HAGA CLICK EN EL BOTON PAGAR </strong>
                                  <a href="#" @click.prevent="showModalPay" class="btn btn-success waves-effect waves-light m-l-10"
                                  style="font-size:20px; float:right; padding: 0px 2px 0px 0px;">
                                  <img src="{{URL::asset('/assets/images/tarjeta.png')}}" class="img-fluid" alt="Tarjeta Pago" width="80" />&nbsp;PAGAR</a> -->
                                    {{-- <a href="#" @click.prevent="newPersonaEspecialidad" class="btn btn-success waves-effect waves-light m-l-10"><i class="fa fa-plus"></i> {{ trans('labels.actions.new')}}</a> --}}
                                <!-- </div> -->
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Filtrar por Envío de Plan de Pagos</label>
                                            <select name="planPagosEnviado" id="planPagosEnviado" class="form-control">
                                                <option value="2">Todos</option>
                                                <option value="0">Sólo pendientes</option>
                                                <option value="1">Enviados</option>
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>

                                <table class="refresh_emi" id="personaEspecialidad-table" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- vista de Plan de Pagos-->
    <div class="modal fade animated zoomIn" id="view-planPagos" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Plan de Pagos de @{{ personaEspecialidad.persona ? personaEspecialidad.persona.Persona : '' }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Detalle</th>
                                <th>Monto</th>
                                <th>Fecha Límite de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="pp in planPagos">
                                <td>@{{ pp.despag }}</td>
                                <td>@{{ accounting.formatMoney(pp.monto, "Bs.", 2, ".", ",") }}</td>
                                <td>@{{ pp.feclim }}</td>
                            </tr>
                            <tr>
                                <td class="text-right"><b><h3>Total</h3></b></td>
                                <td><b><h3>@{{ accounting.formatMoney(totalPlanPagos, "Bs.", 2, ".", ",") }}</h3></b></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade animated zoomIn" id="view-especialidad" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.Persona : '' }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8" >
                            <div class="form-group">
                                <label for="Especialidad"><i class="fa fa-asterisk"></i>Seleccione la carrera:</label>
                                <select type="text" class="form-control" name="Especialidad" v-model="personaEspecialidad.Especialidad">
                                    <option :value="e.id" v-for="e in especialidad">@{{ e.Especialidad }}</option>
                                </select>
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Especialidad"><li class="parsley-required">@{{ errorBag.Especialidad }}</li></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group float-left" v-if="auth.Rol == 1">
                        <a href="#" @click.prevent="generaBoletaEspecial" class="btn btn-danger waves-effect waves-light m-l-10">
                        <i class="fa fa-print"></i> GENERA BOLETA ESPECIAL</a>
                    </div>
                    <div class="btn-group float-left">
                        <a href="#" @click.prevent="generaBoleta" class="btn btn-info waves-effect waves-light m-l-10">
                        <i class="fa fa-print"></i> CONFIRMAR CARRERA</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade animated zoomIn" id="view-siscoin" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.Persona : '' }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <h4>Información registrada en SISCOIN </h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>CI</th>
                                <th>Monto</th>
                                <th>Gestión</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sm in siscoinMontos">
                                <td>@{{ sm.codal}}</td>
                                <td>@{{ sm.monto}}</td>
                                <td>@{{ sm.gestion}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- formulario de nivelAcademico-->
    <div class="modal fade animated zoomIn" id="view-personaEspecialidad" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.Persona : '' }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10" >
                            <div class="btn-group" v-if="personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == true">
                                <button v-if="auth.Rol < 6" type="button" class="btn btn-xs btn-danger" data-dismiss="modal"  @click.prevent="deletePersonaEspecialidad"> <i class="far fa-check-circle"></i> {{ trans('labels.actions.destroy')}} </button>
                                <a v-if="auth.Rol < 6 && personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == true" href="#" class="btn btn-info btn-xs" @click.prevent="downloadBoleta" ><i class="fa fa-print"></i> IMPRIMIR BOLETA</a>
                                <a v-if="auth.Rol < 6 && personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == true" href="#" class="btn btn-warning btn-xs" @click.prevent="downloadBoletaEspecial" ><i class="fa fa-print"></i> IMPRIMIR BOLETA ESPECIAL</a>
                                <a v-if="auth.Rol < 6 && personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == true" href="#" class="btn btn-info btn-xs" @click.prevent="downloadPersona" ><i class="fa fa-print"></i> IMPRIMIR FORMULARIO</a>
                                <button v-if="auth.Rol == 3 || auth.Rol == 1" type="button" class="btn btn-xs btn-danger" data-dismiss="modal"  @click.prevent="getEspecialidad(personaEspecialidad.persona.UnidadAcademica)"> <i class="far fa-check-circle"></i> GENERAR NUEVA BOLETA </button>
                                <button v-if="auth.Rol == 3 || auth.Rol == 1" type="button" class="btn btn-xs btn-info" data-dismiss="modal"  @click.prevent="generaReincorporacion(personaEspecialidad.persona.UnidadAcademica)"> <i class="far fa-check-circle"></i> REINCORPORACION </button>
                                <button v-if="auth.Rol == 3 || auth.Rol == 1" type="button" class="btn btn-xs btn-success" data-dismiss="modal"  @click.prevent="generaAdelanto(personaEspecialidad.persona.UnidadAcademica)"> <i class="far fa-check-circle"></i> ADELANTO </button>
                                <button v-if="auth.Rol == 3 || auth.Rol == 1" type="button" class="btn btn-xs btn-warning" data-dismiss="modal"  @click.prevent="generaRepite(personaEspecialidad.persona.UnidadAcademica)"> <i class="far fa-check-circle"></i> REPITE </button>
                                <a v-if="auth.Rol == 1 || auth.Rol == 3" href="#" @click="insertInformixPersonaEspecialidad" class="btn btn-xs btn-info"><i class="fas fa-coins"></i> Copiar datos a SISCOIN</a>
                                <a v-if="auth.Rol == 1 || auth.Rol == 3" href="#" @click="viewMontoPersonaEspecialidad" class="btn btn-xs btn-success"><i class="fas fa-coins"></i> Ver Monto en SISCOIN</a>
                                <!-- <a v-if="auth.Rol == 1" href="#" @click="insertInformixPersonaEspecialidad" class="btn btn-xs btn-info"><i class="fas fa-coins"></i> Copiar datos a SISCOIN</a> -->
                            </div>
                            <div class="btn-group" v-if="personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == false">
                                <button v-if="auth.Rol < 6" type="button" class="btn btn-xs btn-danger" data-dismiss="modal"  @click.prevent="deletePersonaEspecialidad"> <i class="far fa-check-circle"></i> {{ trans('labels.actions.destroy')}} </button>
                                <a v-if="auth.Rol < 6" href="#" @click="sendRequisitosPersonaEspecialidad" class="btn btn-xs btn-info"><i class="fas fa-clipboard-list"></i> Reenviar Requisitos</a>
                                <a v-if="auth.Rol == 1 || auth.Rol == 3" href="#" @click="insertInformixPersonaEspecialidad" class="btn btn-xs btn-info"><i class="fas fa-coins"></i> Copiar datos a SISCOIN</a>
                                <a href="#" @click.prevent="personaEspecialidadPrint()" class="btn btn-xs btn-info"><i class="fas fa-print"></i> Boleta Inscripción</a>
                                <a v-if="auth.Rol == 1 || auth.Rol == 3" href="#" @click="viewMontoPersonaEspecialidad" class="btn btn-xs btn-success"><i class="fas fa-coins"></i> Ver Monto en SISCOIN</a>
                                {{-- <a v-if="auth.Rol < 6" href="#" @click="viewPlanPagosPersonaEspecialidad" class="btn btn-xs btn-success"><i class="fas fa-eye"></i> Ver Plan de Pagos</a>
                                <a v-if="auth.Rol == 4 || auth.Rol == 1" href="#" @click="sendPlanPagosPersonaEspecialidad" class="btn btn-xs btn-success"><i class="fas fa-envelope"></i> Envíar Plan de Pagos</a> --}}
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <img v-if="personaEspecialidad.persona" :src="personaEspecialidad.persona.URLFoto" style="width:150px;">
                            <a href="#" class="btn btn-info btn-xs" @click.prevent="descargarFoto" ><i class="ion ion-md-photos"></i>DESCARGAR FOTO </a>

                                <!-- <div><img :src="/images/default_image_profile.png" style="width:150px;"></div> -->
                        </div>
                        <div class="col-md-5">
                            <div><b>CI: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.CI : ''}}</div>
                            <div><b>Teléfono: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.Telefono : ''}}</div>
                            <div><b>Celular: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.Celular : ''}}</div>
                            <div><b>E-mail: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.email : ''}}</div>
                            <div><b>Tutor o Apoderado: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.Tutor  : ''}}</div>
                            <div><b>Profesión del Tutor o Apoderado: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.ProfesionTutor  : ''}}</div>
                            <div><b>Teléfono del Tutor o Apoderado: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.TelefonoTutor  : ''}}</div>
                            <div><b>Nivel Académico: </b>@{{ personaEspecialidad.especialidad ? personaEspecialidad.especialidad.nivel_academico.NivelAcademico : ''}}</div>
                            <!-- <div><b>Unidad Académica: </b>@{{ personaEspecialidad.especialidad ? personaEspecialidad.especialidad.unidad_academica.UnidadAcademica : '' }}</div> -->
                            <div><b>Unidad Académica: </b>@{{ personaEspecialidad.persona ? personaEspecialidad.persona.unidad_academica.UnidadAcademica : '' }}</div>
                            <div><b>Especialidad: </b>@{{ personaEspecialidad.especialidad ? personaEspecialidad.especialidad.Especialidad : ''}}</div>
                            <div v-if="personaEspecialidad.Beca"><b>Beca: </b>@{{ personaEspecialidad.beca ? personaEspecialidad.beca.Beca : ''}}</div>
                            <div><b>Fecha: </b>@{{ moment(personaEspecialidad.created_at).format('DD-MM-YYYY')}}</div>
                            <div v-if="auth.Rol<6 && personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == false"><b>Total Bs.: </b>@{{ accounting.formatMoney(personaEspecialidad.TotalMonto, "Bs.", 2, ".", ",") }}</div>
                            <div><b>Estado: </b>
                                <span v-if="!isEditing">@{{ personaEspecialidad.estado ? personaEspecialidad.estado.Estado : '' }}
                                    <a v-if="auth.Rol < 6" href="#" @click="isEditing=true"class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                                </span>
                                <span v-else>
                                    <div class="form-group">
                                        <select class="form-control" name="Estado" id="Estado" v-model="personaEspecialidad.Estado">
                                            <option v-for="e in estados" :value="e.id">@{{ e.Estado }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Observaciones">Observaciones: </label>
                                        <textarea class="form-control" name="Observaciones" id="Observaciones" cols="30" rows="3" v-model="observaciones"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <a href="#" @click="savePersonaEspecialidadEstado" class="btn btn-xs btn-success"><i class="fa fa-save"></i></a>
                                        <a href="#" @click="cancelPersonaEspecialidadEstado" class="btn btn-xs btn-danger"><i class="fas fa-ban"></i></a>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4" v-if="personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == false">
                            <h4><b>Historial</b></h4>
                            <div>
                                <div v-for="estado in personaEspecialidad.estado_inscrito">
                                        <table width="100%">
                                            <tr>
                                                <td width="40%"><span v-html="drawEstado(estado)"></span></td>
                                                <td> <i class="fa fa-clock"></i> @{{ moment(estado.created_at).format('DD-MM-YYYY HH:mm:ss') }}</td>
                                            </tr>
                                            <tr v-if="estado.Observaciones">
                                                <td colspan="2"><i class="fas fa-comment"></i> @{{ estado.Observaciones }}</td>
                                            </tr>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-7 col-md-offset-1" v-if="personaEspecialidad.persona">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Semestre</th>
                                            <th>Descripción</th>
                                            <th>Habilitado</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="mm in personaEspecialidad.persona_especialidad_materia" v-if="mm.CursoNuevo==true">
                                            <td>@{{ mm.NumeroSemestre }}°</td>
                                            <td>@{{ mm.HSemestre }}</td>
                                            <td>@{{ mm.HMateria ? mm.HMateria : mm.materia_monto.materia.Materia }}</td>
                                            <td>
                                                <input type="checkbox" :checked="mm.Verificado" :name="'Verificado'+mm.id" :id="'Verificado'+mm.id" @change="savePersonaEspecialidadMateria(mm)">
                                            </td>
                                            <td>@{{mm.Monto}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <section id="cd-timeline" class="cd-container">
                                <div class="cd-timeline-block" v-for="estadoInscrito in personaEspecialidad.estado_inscrito">
                                    <div class="cd-timeline-img cd-success">
                                        <i class="fa fa-tag"></i>
                                    </div> <!-- cd-timeline-img -->
                                    <div class="cd-timeline-content">
                                        <h5>@{{ estadoInscrito.estado ? estadoInscrito.estado.Estado : '' }}</h5>
                                        <p>@{{ estadoInscrito.Observaciones }}</p>
                                        <span class="cd-date">@{{ moment(estadoInscrito.created_at).format('DD-MM-YYYY')}}</span>
                                    </div> <!-- cd-timeline-content -->
                                </div> <!-- cd-timeline-block -->
                            </section>
                        </div> --}}
                        <div class="col-md-5" v-if="personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == false">
                            <h4>Requisitos</h4>
                                <template v-for="requisito in personaEspecialidad.persona_especialidad_requisito" v-if="personaEspecialidad.persona && personaEspecialidad.persona.EsRegular == false">
                                    <div class="checkbox checkbox-success" v-if="auth.Rol < 6">
                                        <!--Si el DARE-->
                                        <template v-if="(auth.Rol == 2 || auth.Rol == 1) && requisito.requisito.TipoRequisito == 'D'">
                                            <input :id="'requisito'+requisito.id" :name="requisito.id" type="checkbox" @click="savePersonaEspecialidadRequisito(requisito)" :checked="requisito.Presentado" >
                                            <label style="display: inline !important;" :for="'requisito'+requisito.id">@{{requisito.requisito.Requisito}}</label>
                                            <span style="position: absolute; right: 10px !important;"><a v-if="requisito.requisito.Archivo" @click.prevent="downloadPersonaEspecialidadRequisito(requisito)" href="#" class="btn btn-info btn-xs"><i class="fa fa-download"></i></a></span>
                                        </template>
                                        <!--Si el Tesoreria-->
                                        <template v-if="(auth.Rol == 4 || auth.Rol == 1) && requisito.requisito.TipoRequisito == 'T'">
                                            <input :id="'requisito'+requisito.id" :name="requisito.id" type="checkbox" @click="savePersonaEspecialidadRequisito(requisito)" :checked="requisito.Presentado" >
                                            <label style="display: inline !important;" :for="'requisito'+requisito.id">@{{requisito.requisito.Requisito}}</label>
                                            <span style="position: absolute; right: 10px !important;"><a v-if="requisito.requisito.Archivo" @click.prevent="downloadPersonaEspecialidadRequisito(requisito)" href="#" class="btn btn-info btn-xs"><i class="fa fa-download"></i></a></span>
                                        </template>
                                        <!--Si es informatica-->
                                        <template v-if="(auth.Rol == 3 || auth.Rol == 1) && requisito.requisito.TipoRequisito == 'I'">
                                            <input :id="'requisito'+requisito.id" :name="requisito.id" type="checkbox" @click="savePersonaEspecialidadRequisito(requisito)" :checked="requisito.Presentado" >
                                            <label style="display: inline !important;" :for="'requisito'+requisito.id">@{{requisito.requisito.Requisito}}</label>
                                            <span style="position: absolute; right: 10px !important;"><a v-if="requisito.requisito.Archivo" @click.prevent="downloadPersonaEspecialidadRequisito(requisito)" href="#" class="btn btn-info btn-xs"><i class="fa fa-download"></i></a></span>
                                        </template>
                                        <!--Si es Credenciales-->
                                        <template v-if="(auth.Rol == 5 || auth.Rol == 1) && requisito.requisito.TipoRequisito == 'C'">
                                            <input :id="'requisito'+requisito.id" :name="requisito.id" type="checkbox" @click="savePersonaEspecialidadRequisito(requisito)" :checked="requisito.Presentado" >
                                            <label style="display: inline !important;" :for="'requisito'+requisito.id">@{{requisito.requisito.Requisito}}</label>
                                            <span style="position: absolute; right: 10px !important;"><a v-if="requisito.requisito.Archivo" @click.prevent="downloadPersonaEspecialidadRequisito(requisito)" href="#" class="btn btn-info btn-xs"><i class="fa fa-download"></i></a></span>
                                        </template>
                                    </div>
                                    <div v-else>
                                        <label> <i :class="requisito.Presentado ? 'fa fa-check text-success' : 'fa fa-ban text-danger'"></i> @{{requisito.requisito.Requisito}}</label>
                                        <span style="position: absolute; right: 10px !important;"><a v-if="requisito.requisito.Archivo" @click.prevent="downloadPersonaEspecialidadRequisito(requisito)" href="#" class="btn btn-info btn-xs"><i class="fa fa-download"></i></a></span>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <a href="#" @click.prevent="savePersonaEspecialidad()" class="btn btn-success">{{ trans('labels.actions.save') }}</a> --}}
                    {{-- <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-window-close"></i> {{ trans('labels.actions.cancel')}} </button> --}}
                </div>
            </div>
        </div>
        <!-- /.modal-dialog -->

        <!-- modal Recepcion de datos para pago -->
          <div class="modal fade" id="PagoCursoModal" tabindex="-1" role="dialog" aria-hidden="true" >
            <div class="modal-dialog modal-lg" role="document" >
              <div class="modal-content" style="padding: 5px;border-radius:1%;">
                <div class="modal-header" style="text-align:center;background:#5A738E;color:white;font-weight:bold;padding-left:35%;">
                  <center><h3 class="modal-title" id="exampleModalLabel" style="color:white;">DATOS DE FACTURA</h3></center>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 50px;color: white;"  @click.prevent="closeModal()">&times;</span>
                  </button>
                </div>
              <!-- <div class="modal-header" style="text-align:center;background:#5A738E;color:white;font-weight:bold; height:60px;">
                <button id="cerrarModalEmiPago" type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" style="color: white;font-size: 2em;">&times;</span>
                </button>
                <h3 class="modal-title">DATOS DE FACTURA</h3>
              </div> -->
                <div class="modal-body">
                  <form>
                    <div class="form-row">
                      <input type="hidden" id="idsemi" name="idsemi">
                      <!-- <input type="hidden" id="mail" name="mail"> -->
                      <input type="hidden" id="orderCode" name="orderCode">
                      <div class="col-md-6 mb-3">
                        <label for="razonsocial">Razon social</label>
                        <input type="text" class="form-control" id="razonsocial" name="razon_social" placeholder="Razon social" required>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="nit">NIT</label>
                        <input type="text" class="form-control" id="nit" name="nit" placeholder="Nit" required>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label for="montoPago">Monto a Pagar</label>
                        <input type="text" class="form-control"  id="montoPago" name="montoPago" placeholder="Pago" required disabled="true">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="sucursal">Correo</label>
                        <input type="email" class="form-control" id="mail" name="mail" placeholder="Correo">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="sucursal">Sucursal</label>
                        <input type="text" class="form-control"  id="sucursal" name="sucursal" placeholder="Sucursal" required disabled="true">
                      </div>
                    </div>
                  </form>
                  <br>
                  <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true" style="font-size: 50px;color: blue;">&times;</span></button>
                      <center><h4>Favor verifique los <strong>datos</strong> de su factura antes de seleccionar la forma de pago.</h4></center>
                  </div>
                  <br>
                  <a id="aemiurl"></a>
                  <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="framemi" class="embed-responsive-item"></iframe>
                  </div>
                </div>
                <!-- <div class="modal-footer"> -->
                <!-- <a href="#" @click.prevent="showModalPay" class="btn btn-success waves-effect waves-light m-l-10"><img src="{{URL::asset('/assets/images/tarjeta.png')}}" class="img-fluid" alt="Tarjeta Pago" width="50" height="50" />&nbsp;PAGAR</a> -->
                  <!-- <button type="button" class="btn btn-success" @click.prevent="procesarPago()">Procesar Pago</button>
                  <button type="button" class="btn btn-danger" @click.prevent="cancelarPago()" data-dismiss="modal">Cancelar</button>
                </div> -->
              </div>
            </div>
          </div>

          <div id="loader_emi" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="background:transparent; margin-left: 50%; margin-top:30%; margin-right: 30%; width:70px;">
                    <center><span class="fa fa-spinner fa-spin" style="color:white; font-size:70px;"></span><center>
                </div>
            </div>
        </div>
        <!-- fin modal -->
    </div>
</div>

<script>
    var auth = {!! Auth::user() !!};
    var urlIndexPersonaEspecialidad = '{!! route('PersonaEspecialidad.index') !!}';
    var urlShowPersonaEspecialidad = '{!! route('PersonaEspecialidad.show') !!}';
    var urlSavePersonaEspecialidad = '{!! route('PersonaEspecialidad.store') !!}';
    var urlSaveRequisito = '{!! route('PersonaEspecialidadRequisito.store') !!}';
    var urlSaveMateria = '{!! route('PersonaEspecialdadMateria.store') !!}';
    var urlDestroyMateria = '{!! route('PersonaEspecialdadMateria.destroy') !!}';
    var urlReincorporacionMateria = '{!! route('PersonaEspecialdadMateria.reincorporacion') !!}';
    var urlAdelantoMateria = '{!! route('PersonaEspecialdadMateria.adelanto') !!}';
    var urlRepiteMateria = '{!! route('PersonaEspecialdadMateria.repite') !!}';
    var urlSavePersonaEspecialidadEstado = '{!! route('PersonaEspecialidad.state') !!}';
    var urlSendPlanPagosPersonaEspecialidad = '{!! route('PersonaEspecialidad.sendPlanPagos') !!}';
    var urlViewPlanPagosPersonaEspecialidad = '{!! route('PersonaEspecialidad.viewPlanPagos') !!}';
    var urlInsertInformixPersonaEspecialidad = '{!! route('PersonaEspecialidad.insertInformix') !!}';
    var urlViewInformixPersonaEspecialidad = '{!! route('PersonaEspecialidad.viewInformix') !!}';
    var urlSendRequisitosPersonaEspecialidad = '{!! route('PersonaEspecialidad.sendRequisitos') !!}';
    var urlDownloadPersonaEspecialidadRequisito = '{!! route('PersonaEspecialidadRequisito.download') !!}';
    var urlDestroyPersonaEspecialidad = '{!! route('PersonaEspecialidad.destroy') !!}';
    var urlListEstado = '{!! route('Estado.list') !!}';
    var urlListEspecialidad     = '{!! route('Especialidad.list')!!}';

    var urlPrintPersonaEspecialidad             = '{!! route('PersonaEspecialidad.imprimir')!!}';
    var urlPrintPersona             = '{!! route('Persona.print')!!}';
    var urlPrintBoleta              = '{!! route('PersonaEspecialidad.printboleta')!!}';
    var urlPrintBoletaEspecial      = '{!! route('PersonaEspecialidad.printboletaespecial')!!}';
    var urlRequestUrl      = '{!! route('PersonaEspecialidad.getUrl')!!}';
    var urlVerfyPay      = '{!! route('PersonaEspecialidad.verifyCode')!!}';
    var urlInsertBill      = '{!! route('PersonaEspecialidad.insertBill')!!}';

</script>
{!! Html::script('/js/PersonaEspecialidad/PersonaEspecialidad.js') !!}
@endsection

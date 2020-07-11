@extends('layouts.app')
@section('content') 
<div id="persona-app">
    <loading v-if="isLoading"></loading>
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">{{ trans('labels.modules.Persona') }}</h4>
            </div>
        </div>
        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-dark m-0">Detalle de Registros</h3>
                                <div class="btn-group float-right">
                                    <a href="#" @click.prevent="newPersona" class="btn btn-success waves-effect waves-light m-l-10"><i class="fa fa-plus"></i> {{ trans('labels.actions.new')}}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="persona-table" class="table table-striped table-bordered dt-responsive no-wrap" cellspacing="0" width="100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- formulario de persona-->
    <div class="modal fade animated zoomIn" id="frm-persona" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>{{ trans('labels.modules.Persona') }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label for="Nombre">Nombre(s)</label>
                                    <input type="text" class="form-control" name="Nombre" v-model="persona.Nombre">                                                   
                                    <ul class="parsley-errors-list filled"  id="parsley-id-19" v-if="errorBag.Nombre"><li class="parsley-required">@{{ errorBag.Nombre }}</li></ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label for="ApPaterno"> Apellido Paterno</label>
                                    <input type="text" class="form-control" name="ApPaterno" v-model="persona.ApPaterno">
                                    <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.ApPaterno"><li class="parsley-required">@{{ errorBag.ApPaterno }}</li></ul>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label for="ApMaterno"> Apellido Materno</label>
                                    <input type="text" class="form-control" name="ApMaterno" v-model="persona.ApMaterno">
                                    <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.ApMaterno"><li class="parsley-required">@{{ errorBag.ApMaterno }}</li></ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="UnidadAcademica">Unidad Academica</label>
                                <select type="text" class="form-control" name="UnidadAcademica" v-model="persona.UnidadAcademica">
                                    <option :value="ua.id" v-for="ua in unidadAcademicas">@{{ ua.UnidadAcademica }}</option>
                                </select>
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.UnidadAcademica"><li class="parsley-required">@{{ errorBag.UnidadAcademica }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="Rol"> Rol</label>
                                <select type="text" class="form-control" name="Rol" v-model="persona.Rol">
                                    <option :value="r.id" v-for="r in roles">@{{ r.Rol }}</option>
                                </select>
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Rol"><li class="parsley-required">@{{ errorBag.Rol }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="Sexo"> Sexo</label> <br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Sexo" id="SexoM" value="M" checked="" v-model="persona.Sexo">
                                    <label class="form-check-label" for="defaultInlineRadio1"><i class="fa fa-male"></i> Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Sexo" id="SexoF" value="F" checked="" v-model="persona.Sexo">
                                    <label class="form-check-label" for="defaultInlineRadio1"><i class="fa fa-female"></i>  Femenino</label>
                                </div>
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Sexo"><li class="parsley-required">@{{ errorBag.Sexo }}</li></ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="CodigoAlumno"> Codigo de Alumno</label>
                                <input type="text" class="form-control" name="CodigoAlumno" v-model="persona.CodigoAlumno">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.CodigoAlumno"><li class="parsley-required">@{{ errorBag.CodigoAlumno }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="CI"> Carnet de Identidad</label>
                                <input type="text" class="form-control" name="CI" v-model="persona.CI">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.CI"><li class="parsley-required">@{{ errorBag.CI }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="FechaNacimiento"> Fecha de Nacimiento </label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" name="FechaNacimiento" id="FechaNacimiento">
                                    <span class="input-group-addon bg-custom b-0"><i class="mdi mdi-calendar"></i></span>
                                    <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.FechaNacimiento"><li class="parsley-required">@{{ errorBag.FechaNacimiento }}</li></ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="Direccion"> Direccion</label>
                                <input type="text" class="form-control" name="Direccion" v-model="persona.Direccion">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Direccion"><li class="parsley-required">@{{ errorBag.Direccion }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="Telefono"> Teléfono</label>
                                <input type="text" class="form-control" name="Telefono" v-model="persona.Telefono">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Telefono"><li class="parsley-required">@{{ errorBag.Telefono }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="Celular"> Celular</label>
                                <input type="text" class="form-control" name="Celular" v-model="persona.Celular">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Celular"><li class="parsley-required">@{{ errorBag.Celular }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="TelefonoReferencia"> Telefono de Referencia</label>
                                <input type="text" class="form-control" name="TelefonoReferencia" v-model="persona.TelefonoReferencia">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.TelefonoReferencia"><li class="parsley-required">@{{ errorBag.TelefonoReferencia }}</li></ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="ColegioEgreso"> Colegio de Egreso</label>
                                <input type="text" class="form-control" name="ColegioEgreso" v-model="persona.ColegioEgreso">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.ColegioEgreso"><li class="parsley-required">@{{ errorBag.ColegioEgreso }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="AnioEgresoColegio"> Año Egreso Colegio</label>
                                <input type="text" class="form-control" name="AnioEgresoColegio" v-model="persona.AnioEgresoColegio">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.AnioEgresoColegio"><li class="parsley-required">@{{ errorBag.AnioEgresoColegio }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="CITutor"> CI Tutor</label>
                                <input type="text" class="form-control" name="CITutor" v-model="persona.CITutor">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.CITutor"><li class="parsley-required">@{{ errorBag.CITutor }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-3 col-lg-3">
                                <label for="Tutor"> Tutor</label>
                                <input type="text" class="form-control" name="Tutor" v-model="persona.Tutor">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Tutor"><li class="parsley-required">@{{ errorBag.Tutor }}</li></ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="OcupacionTutor"> Ocupación Tutor</label>
                                <input type="text" class="form-control" name="OcupacionTutor" v-model="persona.OcupacionTutor">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.OcupacionTutor"><li class="parsley-required">@{{ errorBag.OcupacionTutor }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="TelefonoTutor">Teléfono Tutor</label>
                                <input type="text" class="form-control" name="TelefonoTutor" v-model="persona.TelefonoTutor">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.TelefonoTutor"><li class="parsley-required">@{{ errorBag.TelefonoTutor }}</li></ul>
                            </div>
                            <div class="col-xs-12 col-md-4 col-lg-4">
                                <label for="EmailTutor"> Email Tutor</label>
                                <input type="text" class="form-control" name="EmailTutor" v-model="persona.EmailTutor">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.EmailTutor"><li class="parsley-required">@{{ errorBag.EmailTutor }}</li></ul>
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-lg-6">
                                <label for="email"> Email</label>
                                <input type="text" class="form-control" name="email" v-model="persona.email">
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.email"><li class="parsley-required">@{{ errorBag.email }}</li></ul>
                            </div>
                            <div class="col-lg-6">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" name="password" v-model="persona.password">
                                <ul class="parsley-errors-list filled" id="parsley-|id-19" v-if="errorBag.password"><li class="parsley-required">@{{ errorBag.password }}</li></ul> 
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <div class="row">
                            <div class="col-lg-10">
                                <label for="Observaciones"> Observaciones</label>
                                <textarea type="text" class="form-control" name="Observaciones" v-model="persona.Observaciones"></textarea>
                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Observaciones"><li class="parsley-required">@{{ errorBag.Observaciones }}</li></ul>
                            </div>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>Foto:</p>
                                    <input type="file" class="filestyle" id="Foto" data-buttonname="btn-primary" data-buttontext="Seleccionar..." @change="loadFile('Foto')">
                                    <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Fotografia"><li class="parsley-required">@{{ errorBag.Fotografia }}</li></ul>
                                </div>
                                <span v-show="isLoadingFile" class="text-success"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> Cargando foto<span class="sr-only">Cargando...</span></span>
                                <span v-show="!isLoadingFile && persona.Fotografia" class="text-info"><i class="fa fa-thumbs-o-up"></i> Foto Cargado!</span>                        
                                <div class="col-lg-3">
                                    <label for="Verificado"> Verificado</label>
                                    <input type="checkbox" class="form-control checkox" name="Verificado" v-model="persona.Verificado"></input>
                                    <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Verificado"><li class="parsley-required">@{{ errorBag.Verificado }}</li></ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" @click.prevent="savePersona()" class="btn btn-success">{{ trans('labels.actions.save') }}</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-window-close"></i> {{ trans('labels.actions.cancel')}} </button>
                <!--    <a href="#" @click.prevent="modifyReparticion()" class="btn btn-success">{{ trans('labels.actions.cancel') }}</a> -->
                </div>
            </div>
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- vista de Persona-->
      <div class="modal fade" id="view-persona" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-0" id="custom-width-modalLabel">{{ trans('labels.modules.Persona') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <h4>@{{ persona.Persona }} </h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div><img :src="persona.URLFoto" style="width:250px;"></div>
                        </div>
                        <div class="col-md-8">
                            <div><b>Carnet de Identidad: </b>@{{ persona.CI }}</div>
                            <div><b>Fecha de Nacimiento : </b>@{{ moment(persona.FechaNacimiento).format('D-M-Y') }}</div>
                            <div><b>E-mail: </b>@{{ persona.email }}</div>
                            <div><b>Unidad Académica: </b>@{{ persona.unidad_academica ? persona.unidad_academica.UnidadAcademica : '' }}</div>
                            <div><b>Teléfono: </b>@{{ persona.Telefono }}</div>
                            <div><b>Celular: </b>@{{ persona.Celular }}</div>
                            <div><b>Militar: </b><i v-if="persona.EsMilitar" class="fa fa-check text-success"></i><i v-else class="fa fa-ban text-danger"></i></div>
                            <div><b>Hijo de Militar: </b><i v-if="persona.EsHijoDeMilitar" class="fa fa-check text-success"></i><i v-else class="fa fa-ban text-danger"></i></div>
                            <div><b>Tutor: </b>@{{ persona.Tutor }}</div>
                            <div><b>Teléfono del Tutor: </b>@{{ persona.TelefonoTutor }}</div>
                            <div><b>E-mail del Tutor: </b>@{{ persona.EmailTutor }}</div>
                        </div>                 
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" @click.prevent="cambiopassword" class="btn btn-success waves-effect waves-light"><i class="fab fa-expeditedssl"></i> {{ trans('labels.actions.cambiopassword') }}</a>
                    <a href="#" @click.prevent="editPersona" class="btn btn-warning"><i class="fa fa-edit"></i> {{ trans('labels.actions.edit') }}</a>
                    <a href="#" @click.prevent="deletePersona" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('labels.actions.destroy') }}</a>
                </div>
            </div>
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- sample modal content -->
    <div class="modal fade" id="view-password" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-0" id="custom-width-modalLabel">Cambio de Contraseña</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                {{-- <div class="col-lg-8">
                                    <label for="old">Contraseña Anterior:</label>
                                    <input type="password" class="form-control" name="old" v-model="password.old">
                                    <ul class="parsley-errors-list filled" id="parsley-|id-19" v-if="errorBag.old"><li class="parsley-required">@{{ errorBag.old }}</li></ul> 
                                </div> --}}
                                <div class="col-lg-8">
                                    <label for="new">Nueva Contraseña:</label>
                                    <input type="password" class="form-control" name="new" v-model="password.new">
                                    <ul class="parsley-errors-list filled" id="parsley-|id-19" v-if="errorBag.new"><li class="parsley-required">@{{ errorBag.new }}</li></ul> 
                                </div>
                                <div class="col-lg-8">
                                    <label for="confirm">Confirmar Contraseña:</label>
                                    <input type="password" class="form-control" name="confirm" v-model="password.confirm">
                                    <ul class="parsley-errors-list filled" id="parsley-|id-19" v-if="errorBag.confirm"><li class="parsley-required">@{{ errorBag.confirm }}</li></ul> 
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <a href="#" @click.prevent="changePassword" class="btn btn-primary waves-effect waves-light">{{ trans('labels.actions.save') }}</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script>
    var auth = {!! Auth::user() !!};
    var urlIndexPersona     = '{!! route('Persona.index') !!}';
    var urlShowPersona      = '{!! route('Persona.show') !!}';
    var urlSavePersona      = '{!! route('Persona.store') !!}';
    var urlDestroyPersona   = '{!! route('Persona.destroy') !!}';
    var urlChangePasswordPersona   = '{!! route('Persona.changePassword') !!}';
    var urlUploadFile           = '{!! route('utils.uploadFile') !!}';  
    var urlSaveRequisito      = '{!! route('PersonaEspecialidadRequisito.store') !!}';

    var urlListUnidadAcademica = '{!! route('UnidadAcademica.list')!!}';
    var urlListRol = '{!! route('Rol.list')!!}';
    var urlListEstado = '{!! route('Estado.list')!!}';
    
</script>
{!! Html::script('/js/Persona/Persona.js') !!}
@endsection

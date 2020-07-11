@extends('layouts.app')
@section('content') 
<div id="profile-app">
    <loading v-if="isLoading"></loading>
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">{{ trans('labels.modules.Perfil') }}</h4>
            </div>
        </div>
        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-dark m-0">Información General</h3>
                                <div class="btn-group float-right">
                                    {{-- <a href="#" @click.prevent="newPersonaEspecialidad" class="btn btn-success waves-effect waves-light m-l-10"><i class="fa fa-plus"></i> {{ trans('labels.actions.new')}}</a> --}}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img :src="persona.URLFoto" alt="" class="profile-photo" id="profile-photo">
                                        <br>
                                        <br>
                                        <div class="btn-group" >
                                            {{-- <a href="#" @click.prevent="" class="btn btn-info btn-xs"><i class="fas fa-lock"></i> Cambiar Contraseña</a> --}}
                                            {{-- <a href="#" @click.prevent="isLoadingFile!=isLoadingFile" class="btn btn-success btn-xs"><i class="fas fa-camera"></i> Actualizar Fotografía</a> --}}
                                        </div>
                                        <div class="col-md-12 col-xs-12" >
                                            <div class="form-group m-b-0">
                                                <p>Actualizar Fotografia:</p>
                                                <input type="file" class="filestyle" id="Fotografia" data-buttonname="btn-primary" data-buttontext="Seleccionar..." @change="loadFile('Fotografia')">
                                                <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Fotografia"><li class="parsley-required">@{{ errorBag.Fotografia }}</li></ul>
                                            </div>
                                            <span v-show="isLoadingFile" class="text-success"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i> Cargando Archivo<span class="sr-only">Cargando...</span></span>
                                            <span v-show="!isLoadingFile && !persona.Fotografia" class="text-info"><i class="fa fa-thumbs-o-up"></i> Archivo Cargado!</span>
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div><h4 class="text-info">Información General</h4></div>
                                        <div><b>Nombre Completo:</b> @{{ persona.Persona }}</div>
                                        <div><b>C.I.:</b> @{{ persona.CI }} @{{ persona.departamento ? persona.departamento.Departamento : '' }}</div>
                                        <div><b>Fecha de Nacimiento:</b> @{{ moment(persona.FechaNacimiento).format('DD-MM-Y') }}</div>
                                        <div><b>Celular:</b> @{{ persona.Celular }}</div>
                                        <div><b>Correo Electrónico:</b> @{{ persona.email }}</div>
                                        <div><b>Sexo:</b> @{{ persona.Sexo == 1 ? 'Masculino' : 'Femenino' }}</div>
                                        <div><b>Dirección:</b> @{{ persona.Direccion }}</div>
                                        <div><h4 class="text-info">Información Del Tutor o Apoderado</h4></div>
                                        <div><b>Nombre de Tutor o Apoderado:</b> @{{ persona.Tutor }}</div>
                                        <div><b>Ocupación:</b> @{{ persona.OcupacioTutor }}</div>
                                        <div><b>Teléfono:</b> @{{ persona.TelefonoTutor }}</div>
                                        <div><b>Correo Electrónico:</b> @{{ persona.EmailTutor }}</div>
                                        <div><b>C.I.:</b> @{{ persona.CITutor }}</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var auth = {!! Auth::user() !!};
    var urlShowPersona = '{!! route('Persona.show') !!}';
    var urlSavePersona = '{!! route('Persona.store') !!}';
    var urlSite = "{!! url('/') !!}" ;
    var urlUploadFile = '{!! route('utils.uploadFile') !!}'; 
</script>
{!! Html::script('/js/Persona/Profile.js') !!}
@endsection

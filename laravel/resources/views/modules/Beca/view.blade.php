@extends('layouts.app')
@section('content')
<div id="beca-app">
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">{{ trans('labels.modules.Beca') }}</h4>
            </div>
        </div>
        <div class="page-content-wrapper ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header miClase">
                                <h3 class="card-title text-dark m-0">Registros</h3>
                                <div class="btn-group float-right">
                                    <a href="#" @click.prevent="newBeca" class="btn btn-success waves-effect waves-light m-l-10"><i class="fa fa-plus"></i> {{ trans('labels.actions.new')}}</a>
                                </div>
                            </div>
                            <div class="card-body miClase">
                                <table id="beca-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- formulario de beca-->
    <div class="modal fade" id="frm-beca" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>{{ trans('labels.modules.Beca') }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form role="form">
                       <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" class="form-control" name="Beca" v-model="beca.Beca">
                            <ul class="parsley-errors-list filled" id="parsley-id-19" v-if="errorBag.Beca"><li class="parsley-required">@{{ errorBag.Beca }}</li></ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" @click.prevent="saveBeca()" class="btn btn-success"><i class="fa fa-save"></i> {{ trans('labels.actions.save') }}</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-window-close"></i> {{ trans('labels.actions.cancel')}} </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="view-beca" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-0" id="custom-width-modalLabel">{{ trans('labels.modules.Beca') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h4>Descripcion</h4>
                    <p class="text-muted">@{{ beca.Beca }}</p>
                </div>
                <div class="modal-footer">
                    <a href="#" @click.prevent="editBeca" class="btn btn-warning"><i class="fa fa-edit"></i> {{ trans('labels.actions.edit') }}</a>
                    <a href="#" @click.prevent="deleteBeca" class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('labels.actions.destroy') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //var auth = {!! Auth::user() !!};
    var urlIndexBeca = '{!! route('Beca.index')!!}';
    var urlShowBeca = '{!! route('Beca.show')!!}';
    var urlSaveBeca = '{!! route('Beca.store')!!}';
    var urlDestroyBeca = '{!! route('Beca.destroy')!!}';
</script>
{!! Html::script('/js/Beca/Beca.js') !!}
@endsection

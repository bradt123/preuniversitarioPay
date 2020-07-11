<?php
use Illuminate\Http\Request;

Route::post('login/tokenLoginVerify', ['uses' => 'Auth\LoginController@tokenLoginVerify', 'as' => 'login.tokenLoginVerify'])->middleware('guest');
Route::get('login/microsoft', 'Auth\SocialLoginController@redirectToMicrosoft')->name('login.microsoft')->middleware('guest');
Route::get('login/microsoft/callback', 'Auth\SocialLoginController@handleMicrosoftCallback')->middleware('guest');
Auth::routes();

Route::post('uploadFile', function (Request $request) {
    try {
        if ($request->hasFile('File')) {
            $fileName = md5(uniqid() . \Carbon\Carbon::now()) . '.' . strtolower($request->file('File')->getClientOriginalExtension());
            $path = $request->file('File')->storeAs('documents', $fileName, 'public');
            $data = array(
                'success' => true,
                'data' => $fileName,
                'msg' => trans('messages.file_uplodaded')
            );
        } else {
            $data = array(
                'success' => false,
                'data' => null,
                'msg' => 'Error al guardar archivo.'
            );
        }
    } catch (\Exception $e) {
        $data = array(
            'success' => false,
            'data' => null,
            'msg' => $e->getMessage()
        );
    }
    return response()->json($data);
})->name('utils.uploadFile');

Route::post('/imprimirDatos', ['uses' => 'PersonaController@imprimirDatos', 'as' => 'Persona.print']);
Route::get('/imprimirBoleta', ['uses' => 'PersonaEspecialidadController@imprimirBoletas', 'as' => 'PersonaEspecialidad.printboleta']);
Route::get('/imprimirBoletaEspecial', ['uses' => 'PersonaEspecialidadController@imprimirBoletaEspecial', 'as' => 'PersonaEspecialidad.printboletaespecial']);

Route::group(['prefix' => 'Persona', 'middleware' => 'auth'], function () {
        Route::get('/view', ['uses' => 'PersonaController@view', 'as' => 'Persona.view']);
        Route::get('/list', ['uses' => 'PersonaController@list', 'as' => 'Persona.list']);
        Route::get('/index', ['uses' => 'PersonaController@index', 'as' => 'Persona.index']);
        Route::post('/destroy', ['uses' => 'PersonaController@destroy', 'as' => 'Persona.destroy']);
        Route::post('/store', ['uses' => 'PersonaController@store', 'as' => 'Persona.store']);
        Route::get('/show', ['uses' => 'PersonaController@show', 'as' => 'Persona.show']);
        Route::get('/print', ['uses' => 'PersonaController@print', 'as' => 'Persona.print']);
        Route::get('/select2', ['uses' => 'PersonaController@select2', 'as' => 'Persona.select2']);
        Route::post('/changePassword', ['uses' => 'PersonaController@changePassword', 'as' => 'Persona.changePassword']);
        Route::get('/profile', ['uses' => 'PersonaController@profile', 'as' => 'Persona.profile']);
});

/*=============================== ESPECIALIDAD **************************************************************************/
Route::group(['prefix' => 'Especialidad', 'middleware' => 'auth'], function () {
        Route::get('/view', 		['uses' => 'EspecialidadController@view', 'as' 	=> 'Especialidad.view']);
       // Route::get('/list', 		['uses' => 'EspecialidadController@list', 'as' 	=> 'Especialidad.list']);
        Route::get('/index', 		['uses' => 'EspecialidadController@index', 'as' 	=> 'Especialidad.index']);
        Route::post('/destroy', 	        ['uses' => 'EspecialidadController@destroy', 'as' 	=> 'Especialidad.destroy']);
        Route::post('/store', 		['uses' => 'EspecialidadController@store', 'as' 	=> 'Especialidad.store']);
        Route::post('/show', 		['uses' => 'EspecialidadController@show', 'as' 	=> 'Especialidad.show']);
});

/*============================= BECA ************************************************************************/
Route::group(['prefix' => 'Beca', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'BecaController@view', 'as'     => 'Beca.view']);
        //Route::get('/list',       ['uses' => 'BecaController@list', 'as'     => 'Beca.list']);
        Route::get('/index',       ['uses' => 'BecaController@index', 'as'    => 'Beca.index']);
        Route::post('/destroy',    ['uses' => 'BecaController@destroy', 'as'  => 'Beca.destroy']);
        Route::post('/store',      ['uses' => 'BecaController@store', 'as'    => 'Beca.store']);
        Route::post('/show',       ['uses' => 'BecaController@show', 'as'     => 'Beca.show']);
});


Route::get('Beca/list',             ['uses' => 'BecaController@list', 'as'                => 'Beca.list']);
Route::get('UnidadAcademica/list',  ['uses' => 'UnidadAcademicaController@list', 'as'     => 'UnidadAcademica.list']);
Route::get('NivelAcademico/list',   ['uses' => 'NivelAcademicoController@list', 'as'      => 'NivelAcademico.list']);
Route::get('Especialidad/list',     ['uses' => 'EspecialidadController@list', 'as'        => 'Especialidad.list']);
Route::get('MateriaMonto/list',     ['uses' => 'MateriaMontoController@list', 'as'        => 'MateriaMonto.list']);
Route::get('Departamento/list',     ['uses' => 'DepartamentoController@list', 'as'        => 'Departamento.list']);
Route::get('Grado/list',            ['uses' => 'GradoController@list', 'as'               => 'Grado.list']);
Route::get('Requisito/list',        ['uses' => 'RequisitoController@list', 'as'           => 'Requisito.list']);

/*=============================== UNIDAD ACADEMICA **************************************************************************/
Route::group(['prefix' => 'UnidadAcademica', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'UnidadAcademicaController@view', 'as'     => 'UnidadAcademica.view']);
       // Route::get('/list',       ['uses' => 'UnidadAcademicaController@list', 'as'     => 'UnidadAcademica.list']);
        Route::get('/index',       ['uses' => 'UnidadAcademicaController@index', 'as'    => 'UnidadAcademica.index']);
        Route::post('/destroy',    ['uses' => 'UnidadAcademicaController@destroy', 'as'  => 'UnidadAcademica.destroy']);
        Route::post('/store',      ['uses' => 'UnidadAcademicaController@store', 'as'    => 'UnidadAcademica.store']);
        Route::post('/show',       ['uses' => 'UnidadAcademicaController@show', 'as'     => 'UnidadAcademica.show']);
});

/*============================= REQUISITO ************************************************************************/
Route::group(['prefix' => 'Requisito', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'RequisitoController@view', 'as'     => 'Requisito.view']);
        // Route::post('/list',       ['uses' => 'RequisitoController@list', 'as'     => 'Requisito.list']);
        Route::get('/index',       ['uses' => 'RequisitoController@index', 'as'    => 'Requisito.index']);
        Route::post('/destroy',    ['uses' => 'RequisitoController@destroy', 'as'  => 'Requisito.destroy']);
        Route::post('/store',      ['uses' => 'RequisitoController@store', 'as'    => 'Requisito.store']);
        Route::post('/show',       ['uses' => 'RequisitoController@show', 'as'     => 'Requisito.show']);
   });

/*============================= NIVEL ACADEMICO ************************************************************************/
Route::group(['prefix' => 'NivelAcademico', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'NivelAcademicoController@view', 'as'     => 'NivelAcademico.view']);
       // Route::get('/list',       ['uses' => 'NivelAcademicoController@list', 'as'     => 'NivelAcademico.list']);
        Route::get('/index',       ['uses' => 'NivelAcademicoController@index', 'as'    => 'NivelAcademico.index']);
        Route::post('/destroy',    ['uses' => 'NivelAcademicoController@destroy', 'as'  => 'NivelAcademico.destroy']);
        Route::post('/store',      ['uses' => 'NivelAcademicoController@store', 'as'    => 'NivelAcademico.store']);
        Route::get('/show',       ['uses' => 'NivelAcademicoController@show', 'as'     => 'NivelAcademico.show']);
});

/*============================= ESTADO ********************************************************************************/
Route::group(['prefix' => 'Estado', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'EstadoController@view', 'as'     => 'Estado.view']);
        Route::get('/list',       ['uses' => 'EstadoController@list', 'as'     => 'Estado.list']);
        Route::get('/index',       ['uses' => 'EstadoController@index', 'as'    => 'Estado.index']);
        Route::post('/destroy',    ['uses' => 'EstadoController@destroy', 'as'  => 'Estado.destroy']);
        Route::post('/store',      ['uses' => 'EstadoController@store', 'as'    => 'Estado.store']);
        Route::get('/show',       ['uses' => 'EstadoController@show', 'as'     => 'Estado.show']);
});

/*============================= Departamento ********************************************************************************/
Route::group(['prefix' => 'Departamento', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'DepartamentoController@view', 'as'     => 'Departamento.view']);
        Route::get('/index',       ['uses' => 'DepartamentoController@index', 'as'    => 'Departamento.index']);
        Route::post('/destroy',    ['uses' => 'DepartamentoController@destroy', 'as'  => 'Departamento.destroy']);
        Route::post('/store',      ['uses' => 'DepartamentoController@store', 'as'    => 'Departamento.store']);
        Route::get('/show',       ['uses' => 'DepartamentoController@show', 'as'     => 'Departamento.show']);
});

/*============================= TIPO COLEGIO ***********************************************************************************/
Route::group(['prefix' => 'TipoColegio', 'middleware' => 'auth'], function () {
        Route::get('/view', ['uses' => 'TipoColegioController@view', 'as'     => 'TipoColegio.view']);
        Route::get('/list', ['uses' => 'TipoColegioController@list', 'as'     => 'TipoColegio.list']);
        Route::get('/index', ['uses' => 'TipoColegioController@index', 'as'    => 'TipoColegio.index']);
        Route::post('/destroy', ['uses' => 'TipoColegioController@destroy', 'as'  => 'TipoColegio.destroy']);
        Route::post('/store', ['uses' => 'TipoColegioController@store', 'as'    => 'TipoColegio.store']);
        Route::post('/show', ['uses' => 'TipoColegioController@show', 'as'     => 'TipoColegio.show']);
});

/*============================= TIPO PUBLICIDAD ***********************************************************************************/
Route::group(['prefix' => 'TipoPublicidad', 'middleware' => 'auth'], function () {
        Route::get('/view', ['uses' => 'TipoPublicidadController@view', 'as'     => 'TipoPublicidad.view']);
        Route::get('/list', ['uses' => 'TipoPublicidadController@list', 'as'     => 'TipoPublicidad.list']);
        Route::get('/index', ['uses' => 'TipoPublicidadController@index', 'as'    => 'TipoPublicidad.index']);
        Route::post('/destroy', ['uses' => 'TipoPublicidadController@destroy', 'as'  => 'TipoPublicidad.destroy']);
        Route::post('/store', ['uses' => 'TipoPublicidadController@store', 'as'    => 'TipoPublicidad.store']);
        Route::post('/show', ['uses' => 'TipoPublicidadController@show', 'as'     => 'TipoPublicidad.show']);
});

/*============================= PERSONA ESPECIALIDAD ***********************************************************************************/
Route::group(['prefix' => 'PersonaEspecialidad', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'PersonaEspecialidadController@view', 'as'     => 'PersonaEspecialidad.view']);
        Route::get('/list',       ['uses' => 'PersonaEspecialidadController@list', 'as'     => 'PersonaEspecialidad.list']);
        Route::get('/index',       ['uses' => 'PersonaEspecialidadController@index', 'as'    => 'PersonaEspecialidad.index']);
        Route::post('/destroy',    ['uses' => 'PersonaEspecialidadController@destroy', 'as'  => 'PersonaEspecialidad.destroy']);
        Route::post('/store',      ['uses' => 'PersonaEspecialidadController@store', 'as'    => 'PersonaEspecialidad.store']);
        Route::get('/show',       ['uses' => 'PersonaEspecialidadController@show', 'as'     => 'PersonaEspecialidad.show']);
        Route::post('/state',       ['uses' => 'PersonaEspecialidadController@state', 'as'     => 'PersonaEspecialidad.state']);
        Route::get('/imprimir',       ['uses' => 'PersonaEspecialidadController@imprimir', 'as'     => 'PersonaEspecialidad.imprimir']);
        Route::get('/sendPlanPagos',       ['uses' => 'PersonaEspecialidadController@sendPlanPagos', 'as'     => 'PersonaEspecialidad.sendPlanPagos']);
        Route::get('/viewPlanPagos',       ['uses' => 'PersonaEspecialidadController@viewPlanPagos', 'as'     => 'PersonaEspecialidad.viewPlanPagos']);
        Route::get('/insertInformix',       ['uses' => 'PersonaEspecialidadController@insertInformix', 'as'     => 'PersonaEspecialidad.insertInformix']);
        Route::get('/viewInformix',       ['uses' => 'PersonaEspecialidadController@viewInformix', 'as'     => 'PersonaEspecialidad.viewInformix']);
        Route::get('/sendRequisitos',       ['uses' => 'PersonaEspecialidadController@sendRequisitos', 'as'     => 'PersonaEspecialidad.sendRequisitos']);
        Route::get('/getUrl',       ['uses' => 'PersonaEspecialidadController@getUrl', 'as'     => 'PersonaEspecialidad.getUrl']);
        Route::get('/verifyCode',       ['uses' => 'PersonaEspecialidadController@verifyCode', 'as'     => 'PersonaEspecialidad.verifyCode']);
        Route::get('/insertBill',       ['uses' => 'PersonaEspecialidadController@insertBill', 'as'     => 'PersonaEspecialidad.insertBill']);
        // Route::post('/verifyCodePay',       ['uses' => 'PersonaEspecialidadController@verifyCodePay', 'as'     => 'PersonaEspecialidad.verifyCodePay']);

});

Route::get('PersonaEspecialidad/verify/{token}',       ['uses' => 'PersonaEspecialidadController@verify', 'as'     => 'PersonaEspecialidad.verify']);

/*============================= PERSONA ESPECIALIDAD REQUISITO ***********************************************************************************/
Route::group(['prefix' => 'PersonaEspecialidadRequisito', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'PersonaEspecialidadRequisitoController@view', 'as'     => 'PersonaEspecialidadRequisito.view']);
        Route::get('/list',       ['uses' => 'PersonaEspecialidadRequisitoController@list', 'as'     => 'PersonaEspecialidadRequisito.list']);
        Route::get('/index',       ['uses' => 'PersonaEspecialidadRequisitoController@index', 'as'    => 'PersonaEspecialidadRequisito.index']);
        Route::post('/destroy',    ['uses' => 'PersonaEspecialidadRequisitoController@destroy', 'as'  => 'PersonaEspecialidadRequisito.destroy']);
        Route::post('/store',      ['uses' => 'PersonaEspecialidadRequisitoController@store', 'as'    => 'PersonaEspecialidadRequisito.store']);
        Route::get('/show',       ['uses' => 'PersonaEspecialidadRequisitoController@show', 'as'     => 'PersonaEspecialidadRequisito.show']);
        Route::get('/download',       ['uses' => 'PersonaEspecialidadRequisitoController@download', 'as'     => 'PersonaEspecialidadRequisito.download']);
});

/*============================= Materia ***********************************************************************************/
Route::group(['prefix' => 'Materia', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'MateriaController@view', 'as'     => 'Materia.view']);
        Route::get('/list',       ['uses' => 'MateriaController@list', 'as'     => 'Materia.list']);
        Route::get('/index',       ['uses' => 'MateriaController@index', 'as'    => 'Materia.index']);
        Route::post('/destroy',    ['uses' => 'MateriaController@destroy', 'as'  => 'Materia.destroy']);
        Route::post('/store',      ['uses' => 'MateriaController@store', 'as'    => 'Materia.store']);
        Route::get('/show',       ['uses' => 'MateriaController@show', 'as'     => 'Materia.show']);
});
/*============================= MateriaMonto ***********************************************************************************/
Route::group(['prefix' => 'MateriaMonto', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'MateriaMontoController@view', 'as'     => 'MateriaMonto.view']);
       // Route::get('/list',       ['uses' => 'MateriaMontoController@list', 'as'     => 'MateriaMonto.list']);
        Route::get('/index',       ['uses' => 'MateriaMontoController@index', 'as'    => 'MateriaMonto.index']);
        Route::post('/destroy',    ['uses' => 'MateriaMontoController@destroy', 'as'  => 'MateriaMonto.destroy']);
        Route::post('/store',      ['uses' => 'MateriaMontoController@store', 'as'    => 'MateriaMonto.store']);
        Route::get('/show',       ['uses' => 'MateriaMontoController@show', 'as'     => 'MateriaMonto.show']);
});
/*============================= PersonaEspecialidadMateria************************************************************************/
Route::group(['prefix' => 'PersonaEspecialdadMateria', 'middleware' => 'auth'], function () {
        Route::get('/view',        ['uses' => 'PersonaEspecialdadMateriaController@view', 'as'     => 'PersonaEspecialdadMateria.view']);
        Route::get('/list',       ['uses' => 'PersonaEspecialdadMateriaController@list', 'as'     => 'PersonaEspecialdadMateria.list']);
        Route::get('/index',       ['uses' => 'PersonaEspecialdadMateriaController@index', 'as'    => 'PersonaEspecialdadMateria.index']);
        Route::post('/destroy',    ['uses' => 'PersonaEspecialdadMateriaController@destroy', 'as'  => 'PersonaEspecialdadMateria.destroy']);
        Route::post('/reincorporacion',    ['uses' => 'PersonaEspecialdadMateriaController@reincorporacion', 'as'  => 'PersonaEspecialdadMateria.reincorporacion']);
        Route::post('/adelanto',    ['uses' => 'PersonaEspecialdadMateriaController@adelanto', 'as'  => 'PersonaEspecialdadMateria.adelanto']);
        Route::post('/repite',    ['uses' => 'PersonaEspecialdadMateriaController@repite', 'as'  => 'PersonaEspecialdadMateria.repite']);
        // Route::post('/store',      ['uses' => 'PersonaEspecialdadMateriaController@store', 'as'    => 'PersonaEspecialdadMateria.store']);
        Route::get('/show',       ['uses' => 'PersonaEspecialdadMateriaController@show', 'as'     => 'PersonaEspecialdadMateria.show']);
});
Route::post('/store',      ['uses' => 'PersonaEspecialdadMateriaController@store', 'as'    => 'PersonaEspecialdadMateria.store']);

Route::group(['prefix' => 'TipoReporte', 'middleware' => 'auth'], function () {
        Route::get('/view', ['uses' => 'TipoReporteController@view', 'as' => 'TipoReporte.view']);
        Route::post('/list', ['uses' => 'TipoReporteController@list', 'as' => 'TipoReporte.list']);
        Route::get('/index', ['uses' => 'TipoReporteController@index', 'as' => 'TipoReporte.index']);
        Route::post('/destroy', ['uses' => 'TipoReporteController@destroy', 'as' => 'TipoReporte.destroy']);
        Route::post('/store', ['uses' => 'TipoReporteController@store', 'as' => 'TipoReporte.store']);
        Route::get('/show', ['uses' => 'TipoReporteController@show', 'as' => 'TipoReporte.show']);
});

Route::group(['prefix' => 'Reporte', 'middleware' => 'auth'], function () {
        Route::get('/view', ['uses' => 'ReporteController@view', 'as' => 'Reporte.view']);
        Route::get('/generate', ['uses' => 'ReporteController@generate', 'as' => 'Reporte.generate']);
});

Route::group(['prefix' => 'Dashboard', 'middleware' => 'auth'], function () {
        Route::get('/inscritos', ['uses' => 'DashboardController@inscritos', 'as' => 'Dashboard.inscritos']);
});


/*============================= Estudiantes antiguos ***********************************************************************************/
// Route::group(['prefix' => 'Inscripcion'], function () {
        //         Route::post('/store', ['uses' => 'InscripcionController@store', 'as' => 'Inscripcion.store']);
        //         Route::post('/savepregrado', ['uses' => 'InscripcionController@savepregrado', 'as' => 'Inscripcion.savepregrado']);
        //         Route::post('/savemateria', ['uses' => 'InscripcionController@savemateria', 'as' => 'Inscripcion.savemateria']);
        //         Route::get('/verify/{tokenVerificacion}', ['uses' => 'InscripcionController@verify', 'as' => 'Inscripcion.verify']);
        //         Route::get('/grado/{tokenVerificacion}', ['uses' => 'InscripcionController@verifygrado', 'as' => 'Inscripcion.verifygrado']);
        //     });

        /*============================= Estudiantes nuevos ***********************************************************************************/
// // Route::group(['prefix' => 'Inscripcion'], function () {
        //         Route::get('pre-inscripcion', ['uses' => 'InscripcionController@view', 'as' => 'Inscripcion.view']);
        //         Route::get('pre-registro', ['uses' => 'InscripcionController@preregistro', 'as' => 'Inscripcion.preregistro']);
        //         Route::get('grado', ['uses' => 'InscripcionController@grado', 'as' => 'Inscripcion.grado']);
        //         Route::post('enviacorreogrado', ['uses' => 'InscripcionController@enviacorreogrado', 'as' => 'Inscripcion.enviacorreogrado']);
        //         Route::post('pre-inscripcion', ['uses' => 'InscripcionController@preinscripcion', 'as' => 'Preinscripcion.store']);
        //         Route::get('registro', function() {
                //             return view('modules.Inscripcion.index');
                //         });
                //         Route::get('preinscripcion-grado', function() {
                        //             return view('modules.Inscripcion.Grado.index');
                        //         });
                        //    // });

/*============================= Preuniversitario ***********************************************************************************/
Route::group(['prefix' => 'Inscripcion'], function () {
        Route::get('/preuniversitario', ['uses' => 'Inscripcion\PreuniversitarioController@view', 'as' => 'Preuniversitario.view']);
        Route::post('/store', ['uses' => 'Inscripcion\PreuniversitarioController@store', 'as' => 'Preuniversitario.store']);
        Route::get('/verify/{tokenVerificacion}/{tipo?}', ['uses' => 'Inscripcion\InscripcionController@verify', 'as' => 'Inscripcion.verify']);
});

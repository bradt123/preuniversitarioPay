<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Http\Requests\PersonaEspecialidadRequest;
use App\Models\Persona;
use App\Models\PersonaEspecialidad;
use App\Models\PersonaEspecialidadRequisito;
use App\Models\PersonaEspecialidadMateria;
use GuzzleHttp\Client;

class PersonaEspecialidadController extends Controller {


  /*
  *descripcion: llamda a servicios de pago de online con emision de factura
  *author: breydi vasquez pacheco
  *fecha: 05/06/2020
  */
    public function getUrl (Request $request) {
       // $json_student = json_decode($request->data[0]);
        // dd($json_student);
       $data_array = array();
       $total_monto = 0;
       foreach ($request->data as $value) {
                $json_student = json_decode($value);
                array_push($data_array, array($json_student->Especialidad, $json_student->TotalMonto));
                $total_monto += $json_student->Total;
       }

      $celular = '+591'.$json_student->Celular;
      // dd($total_monto);
      $client = new Client();
      $res = $client->post('http://devkqtest.eastus2.cloudapp.azure.com/KXPaymentTR/HostedService.svc/CreateOrder',
                                  array(
                                      'headers'=>array('Content-Type'=>'application/json'),
                                       'json'=>array('Amount' => round($json_student->Total*100),
                                                    'CurrencyCode'=> 'BOL',
                                                    'SystemTraceAuditNumber' => 'ABC123',
                                                    'EmailShooper' => $json_student->email,
                                                    'signed' => 'ABCD',
                                                    'credentials' => '{EB620479-5A98-4283-8AA3-AA442F62B8F9}{0C421A18-029B-4F17-A7BA-DDD296D22FD3}',
                                                    'AdditionalData' => $data_array,
                                                    'IData' => array(array('client_phone_number', '253452'), array('customer_document_number', $json_student->CI))
                                                  )
                                      )
                          );
      return $res->getBody();

    }
    public function verifyCode(Request $request) {

      $client = new Client();
      $res = $client->post('http://devkqtest.eastus2.cloudapp.azure.com/KXPaymentTR/HostedService.svc/InquiryPayment',
                                  array(
                                      'headers'=>array('Content-Type'=>'application/json'),
                                       'json'=>array('Credentials' => '{EB620479-5A98-4283-8AA3-AA442F62B8F9}{0C421A18-029B-4F17-A7BA-DDD296D22FD3}',
                                                     'OrderCode' => $request->code
                                                  )
                                      )
                          );

      return $res->getBody();
    }

    public function insertBill(Request $request) {
      $json_student = json_decode($request->data);

      $u = array_chunk($json_student->detalleArray,2);
      $pp = array();
      foreach ($u as $t){
          $ll = array('id_concepto' => null, 'descripcion'=> $t[0],
                      'cantidad' => 100, 'precio_unitario'=> $t[1]*100);
          array_push($pp,$ll);
      }
      $js_detalle =json_encode($pp);

      // $js_detalle= '[{"id_concepto":null,"descripcion":"'.$json_student->Especialidad.'","cantidad":100,"precio_unitario":"'.$json_student->TotalMonto.'"}]';
      // dd($js_detalle);
      $client = new Client();
      $res = $client->post('http://172.18.60.15/kerp/pxp/lib/rest/ventas_facturacion/FacturacionExterna/insertarVentaFactura',
                                  array(
                                      'headers'=>array('Content-Type' => 'application/x-www-form-urlencoded',
                                                       'Php-Auth-User' => 'QIdYzBiFo5e2ii17KkqWg4jTfBFk67ciZ+t2/vM2phg=',
                                                       'Pxp-user' => 'breydi.vasquez'),

                                       'form_params' => [
                                                         'nit_entidad' => '1020567023',
                                                         'nit_cliente' => $json_student->Nit,
                                                         'punto_venta' => 'la paz',
                                                         'razon_social' => $json_student->razon_social,
                                                         'json_venta_detalle' => $js_detalle,
                                                         'observaciones' => 'CI: '.$json_student->CI.' - Cod: '.$json_student->Persona.' Cur: '.$json_student->Especialidad.' - Carr: '.$json_student->NivelAcademico.' - TC: 6.96',
                                                         'exento' =>  0,
                                                         'tipo_cambio' => '696',
                                                         'moneda' => 'BOB',
                                                         'enviar_correo' => 'si',
                                                         'correo_electronico' => $json_student->email
                                                        ],
                                        'timeout' => 20.14
                                      )

                          );

      $resp_insert_bill = json_decode($res->getBody()->getContents());

      if (!$resp_insert_bill->ROOT->error){
          $id_proceso_wf = $resp_insert_bill->ROOT->datos->id_proceso_wf;
          $client_bill = new Client();
          $resp_bill = $client_bill->post('http://172.18.60.15/kerp/pxp/lib/rest/ventas_facturacion/FacturacionExterna/reporteFacturaCarta',
                                      array(
                                          'headers'=>array('Content-Type' => 'application/x-www-form-urlencoded',
                                                           'Php-Auth-User' => 'QIdYzBiFo5e2ii17KkqWg4jTfBFk67ciZ+t2/vM2phg=',
                                                           'Pxp-user' => 'breydi.vasquez'),

                                           'form_params' => ['id_proceso_wf' => $id_proceso_wf,
                                                             'plantilla_documento_factura' => 'sin_cantidad'],

                                           'timeout' => 20.14
                                          )
                              );
          $resp_get_bill = json_decode($resp_bill->getBody()->getContents());

          if (!$resp_get_bill->ROOT->error) {
                return $resp_bill->getBody();
          }else{
            $data = array(
                  'success' => true,
                  'data' => $resp_get_bill,
                  'msg' => trans('messages.found')
              );
            return response()->json($data);
          }

      }else{
        return $res->getBody();
      }
    }

    public function verifyCodePay()
    {
      $data = array(
            'success' => true,
            'data' => 'breydi',
            'msg' => trans('messages.found')
        );
      return response()->json($data);
    }
    /*FIN llamda a servicos*/

    public function view(){
        return view('modules.PersonaEspecialidad.view');
    }

    public function index(Request $request) {

        $item = PersonaEspecialidad::from('PersonaEspecialidad as pe')
                            ->join('Persona as p', 'pe.Persona', '=', 'p.id')
                            ->join('Especialidad as e', 'pe.Especialidad', '=', 'e.id')
                            ->leftJoin('Beca as b', 'pe.Beca', '=', 'b.id')
                            ->leftJoin('Estado as es', 'pe.Estado', '=', 'es.id')
                            ->leftJoin('NivelAcademico as nv', 'e.NivelAcademico', '=', 'nv.id')
                            ->leftJoin('UnidadAcademica as ua', 'p.UnidadAcademica', '=', 'ua.id')
                            ->whereNull('pe.deleted_at')
                            ->select('pe.id', 'ua.UnidadAcademica', 'nv.NivelAcademico', 'p.Persona', 'p.CI', 'p.Celular','e.Especialidad', 'p.CodigoAlumno','p.email',
                                'b.Beca', 'pe.ProntoPago', 'p.EsHijoDeMilitar','pe.created_at', 'es.Estado', 'es.id as idEstado', 'p.EsRegular',
                                \DB::raw('(select sum(pem."Monto") from "PersonaEspecialidadMateria" pem where pem."PersonaEspecialidad" = pe.id) as "Total"'));

        if(Auth::user()->Rol != 1)
            $item->whereNull('pe.deleted_at');

        if(Auth::user()->Rol == 2) //operador dare
            $item->where('e.UnidadAcademica', Auth::user()->UnidadAcademica);

        //if(Auth::user()->Rol == 3) //operador informatica
          //  $item->where('e.UnidadAcademica', Auth::user()->UnidadAcademica);

        if(Auth::user()->Rol == 4) //operador cajas
            $item->where('e.UnidadAcademica', Auth::user()->UnidadAcademica);

        if(Auth::user()->Rol == 5) //Operador credenciales
            $item->where('e.UnidadAcademica', Auth::user()->UnidadAcademica);

        if(Auth::user()->Rol == 6) //Estudiante
            $item->where('pe.Persona', Auth::user()->id);

        // if($request->PlanPagosEnviado == 0)
        //     $item->where('pe.PlanPagosEnviado', false);
        // if($request->PlanPagosEnviado == 1)
        //     $item->where('pe.PlanPagosEnviado', true);


        return Datatables::of($item)
            ->addColumn('action', function ($p) {
                return '<a href="#" @click.prevent="showPersonaEspecialidad(' . $p->id . ')" class="btn btn-info btn-xs"><i class="fa fa-bars"></i> ' . trans('labels.actions.details') . '</a> &nbsp;';
            })
            ->editColumn('id', '{{$id}}')
            ->make(true);
    }

    public function show(Request $request) {
        $item = PersonaEspecialidad::where('id', $request->id)
                                        ->with('persona.unidadAcademica', 'personaEspecialidadMateria.materiaMonto.materia', 'estadoInscrito.estado',  'especialidad.nivelAcademico', 'personaEspecialidadRequisito.requisito', 'especialidad.UnidadAcademica', 'beca', 'estado')
                                        ->first();
        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => trans('messages.found')
        );

        return response()->json($data);
    }

    public function state (Request $request) {
        $item = PersonaEspecialidad::find($request->id);
        $item->Estado = $request->Estado;
        $item->save();
        $item->guardaLog($item->Estado, $request->Observaciones);

        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => trans('messages.updated')
        );

        return response()->json($data);
    }

    public function sendPlanPagos(Request $request) {
        $item = PersonaEspecialidad::find($request->id);
        $persona = $item->persona;
        $especialidad = $item->especialidad;
        $personaEspecialidad = $item;

        $sql = 'select count(*) as cantidad
        from inscrito i , alumno a, gestion g,carrera c, curso cu,detpagin d,pago p
        where i.codal=a.ci and i.coduni=a.coduni and i.codcar=c.cod and cu.cod=i.codcur
        and p.cod=d.codpag and d.codal=i.codal  and d.coduni=i.coduni and i.coduni=p.coduni
        and d.coduni=p.coduni and i.codges=d.codges and i.codcar=c.cod and d.codcar=i.codcar
        and (i.codges="'.config('parameters.gestion_actual_siscoin_rep').'" or i.codges="' . config('parameters.gestion_actual_siscoin_rep_alt') . '") and i.codal =  "' . $item->persona->CI . '" and i.codges=g.cod and i.codcur in ("01R","111","20C","21","21C")' ;
        // dd($sql);

        $result = \DB::connection('informix')->select($sql);
        if($result[0]->cantidad <= 0) {
            $data = array(
                'success' => false,
                'data' => null,
                'msg' => 'No se ha generado el plan de pagos en SISCOIN'
            );

            return response()->json($data);
        }

        \Mail::send('emails.planPagos', ['persona' => $persona, 'especialidad' => $especialidad], function ($message) use ($persona, $especialidad, $personaEspecialidad) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($persona->email)->subject('EMI - Plan de Pagos')->bcc([config('mail.user_bcc'), \Auth::user()->email]);
            //adjunta plan de pagos
            $file = $personaEspecialidad->imprimePlanPagos();
            $message->attach($file['uri'], [
                'as' => 'Plan de Pagos ' . $persona->Persona . '.pdf',
                'mime' => 'application/pdf'
            ]);
        });

        $item->PlanPagosEnviado = true;
        $item->save();

        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => 'Generado correctamente'
        );

        return response()->json($data);
    }

    public function viewPlanPagos(Request $request) {
        $item = PersonaEspecialidad::find($request->id);

        $sql = 'select i.codal, a.nome||" "||a.apat||" "||a.amat nombre,i.codges,g.des,i.codcar,c.des descar,cu.cod,cu.des dessem,
                d.codpag,p.des despag,p.moneda,d.monto,d.feclim
                from inscrito i , alumno a, gestion g,carrera c, curso cu, detpagin d,pago p
                where i.codal=a.ci and i.coduni=a.coduni and i.codcar=c.cod and cu.cod=i.codcur
                and p.cod=d.codpag and d.codal=i.codal  and d.coduni=i.coduni and i.coduni=p.coduni
                and d.coduni=p.coduni and i.codges=d.codges and i.codcar=c.cod and d.codcar=i.codcar
                and (i.codges="'.config('parameters.gestion_actual_siscoin_rep').'" or i.codges="' . config('parameters.gestion_actual_siscoin_rep_alt') . '") and i.codal =  "' . $item->persona->CI . '" and i.codges=g.cod and i.codcur in ("01R","111","20C","21","21C")' ;
        //return $sql;
        $result = \DB::connection('informix')->select($sql);

        $data = array(
            'success' => true,
            'data' => $result,
            'msg' => 'Generado correctamente'
        );

        return response()->json($data);
    }

    public function insertInformix(Request $request) {
        $item = PersonaEspecialidad::find($request->id);
        if($item->insertaInformix()) {
            $success = true;
            $msg = trans('messages.added');
        } else {
            $success = false;
            $msg = 'Ocurrió un error guardando la infomación en SISCOIN';
        }

        $data = array(
            'success' => $success,
            'data' => $item,
            'msg' => $msg
        );

        return response()->json($data);
    }

    public function viewInformix(Request $request) {
        $item = PersonaEspecialidad::find($request->id);
        $montos = $item->viewInformix();
        if($montos) {
            $success = true;
            $msg = trans('messages.listed');
        } else {
            $success = false;
            $msg = 'Ocurrió un error consultando información de SISCOIN';
        }

        $data = array(
            'success' => $success,
            'data' => $montos,
            'msg' => $msg
        );

        return response()->json($data);
    }

    public function sendRequisitos(Request $request) {
        $item = PersonaEspecialidad::find($request->id);
        $item->enviaRequisitosPorCorreo();
        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => 'Formularios enviados satisfactoriamente'
        );

        return response()->json($data);

    }

    public function imprimir (Request $request) {
        $persona = PersonaEspecialidad::findOrFail($request->id);
        $item = $persona->imprimeBoletaInscripcion();

        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => 'Generado correctamente'
        );

        return response()->json($data);
    }

    public function imprimirBoletas (Request $request) {
        $persona = PersonaEspecialidad::findOrFail($request->id);
        $item = $persona->imprimeBoletas();

        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => 'Generado correctamente'
        );

        return response()->json($data);
    }

    public function imprimirBoletaEspecial (Request $request) {
        $persona = PersonaEspecialidad::findOrFail($request->id);
        $item = $persona->imprimeBoletaEspecial();

        $data = array(
            'success' => true,
            'data' => $item,
            'msg' => 'Generado correctamente'
        );

        return response()->json($data);
    }

    public function destroy (Request $request) {
        $personaEspecialidad = PersonaEspecialidad::findOrFail($request->id);
        PersonaEspecialidadRequisito::where('PersonaEspecialidad', $personaEspecialidad->id)->whereNull('deleted_at')->update([
            'deleted_at' => Carbon::now(),
            'DeleterUserName' => Auth::user()->Persona,
            'DeleterFullUserName' => Auth::user()->Persona,
            'DeleterIP' => $request->ip()
            ]);
        PersonaEspecialidadMateria::where('PersonaEspecialidad', $personaEspecialidad->id)->whereNull('deleted_at')->update([
            'deleted_at' => Carbon::now(),
            'DeleterUserName' => Auth::user()->Persona,
            'DeleterFullUserName' => Auth::user()->Persona,
            'DeleterIP' => $request->ip()
            ]);
        $personaEspecialidad->deleted_at = Carbon::now();
        $personaEspecialidad->DeleterUserName = Auth::user()->Persona;
        $personaEspecialidad->DeleterFullUserName = Auth::user()->Persona;
        $personaEspecialidad->DeleterIP =  $request->ip();
        $personaEspecialidad->eliminaInformix();
        $personaEspecialidad->save();
        $success = true;
        $msg = trans('messages.deleted');
        $result = array(
            'success' => true,
            'data' => null,
            'msg' => $msg
        );
        return response()->json($result);
    }
    // verifica el codigo QR del estudiante y ver si este esta registrado en el sistema
    public function verify($token) {
        $item = PersonaEspecialidad::where('CodigoVerificacion', $token)->with('persona', 'especialidad','especialidad.unidadAcademica')->first();
        //$persona = Persona::findOrFail($item->Persona);
        // dd($persona);
        if($item) {
            $data = array(
                'success' => true,
                'data' => $item,
                'msg' => trans('messages.found')
            );
        } else {
            $data = array(
                'success' => false,
                'data' => null,
                'msg' => trans('messages.not_found')
            );
        }
        // dd($data['data']);
        // return response()->json($data);
        return view('modules.Inscripcion.verify', compact('data'));
    }
}

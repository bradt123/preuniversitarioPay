<?php

namespace App\Models;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Jobs\ProcessEnvioRequisitos;
use App\Models\Log;

class PersonaEspecialidad extends Model
{
    use DispatchesJobs;

    protected $table = 'PersonaEspecialidad';
    protected $appends = ['TotalMonto'];

    public function persona () {
        return $this->belongsTo(Persona::class, 'Persona');
    }
    
    public function especialidad () {
        return $this->belongsTo(Especialidad::class, 'Especialidad');
    }

    public function beca () {
        return $this->belongsTo(Beca::class, 'Beca');
    }

    public function personaEspecialidadRequisito () {
        return $this->hasMany(PersonaEspecialidadRequisito::class, 'PersonaEspecialidad')->orderBy('PersonaEspecialidadRequisito.id', 'asc');
    }

    public function personaEspecialidadMateria () {
        return $this->hasMany(PersonaEspecialidadMateria::class, 'PersonaEspecialidad')->orderBy('id', 'asc');
    }

    public function estado () {
        return $this->belongsTo(Estado::class, 'Estado');
    }

    public function estadoInscrito () {
        return $this->hasMany(EstadoInscrito::class, 'PersonaEspecialidad')->orderBy('updated_at', 'desc');
    }

    public function generaRequisitos () {
        $requisito = Requisito::where('Especialidad', $this->Especialidad);
        
        if($this->Beca)
            $requisito->orWhere('Beca', $this->Beca);
        
        $requisito = $requisito->get();
        foreach($requisito as $r) {

            $personaEspecialidadRequisito = new PersonaEspecialidadRequisito();

            $personaEspecialidadRequisito->Requisito = $r->id;
            $personaEspecialidadRequisito->PersonaEspecialidad = $this->id;
            $personaEspecialidadRequisito->Presentado = false;
            
            $personaEspecialidadRequisito->CreatorUserName = $this->CreatorUserName;
            $personaEspecialidadRequisito->CreatorFullUserName = $this->ChomereatorFullUserName;
            $personaEspecialidadRequisito->CreatorIP = $this->CreatorIP;
            $personaEspecialidadRequisito->UpdaterUserName = $this->UpdathomeerUserName;
            $personaEspecialidadRequisito->UpdaterFullUserName = $this->UpdaterFullUserName;
            $personaEspecialidadRequisito->UpdaterIP = $this->UpdaterIP;
            $personaEspecialidadRequisito->DeleterUserName = $this->DeleterUserName;
            $personaEspecialidadRequisito->DeleterFullUserName = $this->DeleterFullUserName;
            $personaEspecialidadRequisito->DeleterIP = $this->DeleterIP;
            $personaEspecialidadRequisito->save();
        }
    }

    public function actualizaRequisitosPresentados (array $requisitos) {
        $this->personaEspecialidadRequisito()->syncWithoutDetaching($requisitos);
    }

    public function guardaLog ($estado, $observaciones ) {
        $estadoInscrito = new EstadoInscrito();
        $estadoInscrito->PersonaEspecialidad = $this->id;
        $estadoInscrito->Estado = $estado;
        $estadoInscrito->Observaciones = $observaciones;
        $estadoInscrito->CreatorUserName = \Auth::user() ? \Auth::user()->email : null;
        $estadoInscrito->CreatorFullUserName = \Auth::user() ? \Auth::user()->Persona : null;
        // $estadoInscrito->CreatorIP = $this->CreatorIP;
        $estadoInscrito->UpdaterUserName = \Auth::user() ? \Auth::user()->email : null;
        $estadoInscrito->UpdaterFullUserName = \Auth::user() ? \Auth::user()->Persona : null;
        // $estadoInscrito->UpdaterIP = $this->UpdaterIP;
        $estadoInscrito->save();
    }

    public function imprimeRequisitos () {
        try {
            $basePathJRXML = storage_path('jrxml');
            $basePathGenerated = public_path('tmp/');
    
            $fileName = md5($this->id . Carbon::now());
            
            $basePathJasper = $basePathJRXML . '/PersonaEspecialidad.jasper';
            $basePathGenerated = $basePathGenerated . $fileName;
    
            if(\Storage::exists($basePathGenerated))
                \Storage::delete($basePathGenerated);
    
            $parametros = array (
                'idPersonaEspecialidad' => $this->id,
                'urlLogo' =>  public_path('/images/emi_logo.png'),
                'pathReportPersonaEspecialidadRequisito' => storage_path('/jrxml/PersonaEspecialidadRequisito.jasper')
                
            );
            
            $database = \Config::get('database.connections.pgsql');
            $database['driver'] = 'postgres';
            //return $database;
            $reporteJasper = \JasperPHP::process(
                $basePathJasper,
                $basePathGenerated,
                array("pdf"),
                $parametros,
                $database
            );
            //dd($reporteJasper->output());
            $reporteJasper->execute();
    
            return array(
                'url' => config('parameters.app_url') . '/tmp/' . $fileName . '.pdf',
                'uri' => $basePathGenerated . '.pdf'
            );
        } catch (\Exception $e) {
            return false;
        }    
    }

    public function imprimeBoletaInscripcion () {
        $basePathJRXML = storage_path('jrxml');
        $basePathGenerated = public_path('tmp/');

        $fileName = md5($this->id . Carbon::now());
        
        $basePathJasper = $basePathJRXML . '/BoletaPreinscripcionNuevo.jasper';
        $basePathGenerated = $basePathGenerated . $fileName;

        if(\Storage::exists($basePathGenerated))
            \Storage::delete($basePathGenerated);

        if($this->persona->Fotografia) {
            $urlPhoto = storage_path() . '/app/public/documents/' . $this->persona->Fotografia;
        } else {
            $urlPhoto = public_path() . '/images/default_image_profile.png';
        }
        $parametros = array (
            'idPersonaEspecialidad' => $this->id,
            'urlLogo' =>  public_path('/images/emi_logo.png'),
            'pathMateriaSemestreNuevoSubreport' => storage_path('/jrxml/MateriaSemestreNuevo.jasper'),
            'urlCodigoVerificacion' => config('parameters.app_url'). '/PersonaEspecialidad/verify/',
            'urlPhoto' => $urlPhoto 
        );
        
        $database = \Config::get('database.connections.pgsql');
        $database['driver'] = 'postgres';
        //return $database;
        $reporteJasper = \JasperPHP::process(
            $basePathJasper,
            $basePathGenerated,
            array("pdf"),
            $parametros,
            $database
        );
        //dd($reporteJasper->output());
        $reporteJasper->execute();

        return array(
            'url' => config('parameters.app_url') . '/tmp/' . $fileName . '.pdf',
            'uri' => $basePathGenerated . '.pdf'
        );
    }


    public function enviaRequisitosPorCorreo () {
        //define variables para enviar en el mail
        $persona = $this->persona;
        $especialidad = $this->especialidad;
        $personaEspecialidad =$this;
        $beca = $this->beca;
        $success = false;
        try {
            \Mail::send('emails.preinscripcion', ['persona' => $persona, 'especialidad' => $especialidad,'personaEspecialidad'=>$personaEspecialidad], function ($message) use ($persona, $especialidad, $personaEspecialidad) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to($persona->email)->subject('EMI - Formularios y Requisitos')->bcc([config('mail.user_bcc')]);
                        
                //adjunta Boleta de Inscripcion
                $fileRequisitos = $this->imprimeBoletaInscripcion();
                $message->attach($fileRequisitos['uri'], [ 
                    'as' => 'Hoja de Datos Personales.pdf',
                    'mime' => 'application/pdf'
                ]);
                //adjunta Instructivo
                $fileInstructivo = public_path('/assets/documents/INSTRUCTIVO.pdf');
                $message->attach($fileInstructivo, [ 
                    'as' => 'Pasos a seguir.pdf',
                    'mime' => 'application/pdf'
                ]);

                //adjunta formulario de requisitos
                $fileRequisitos = $this->imprimeRequisitos();
                $message->attach($fileRequisitos['uri'], [ 
                    'as' => 'Requisitos e Instrucciones.pdf',
                    'mime' => 'application/pdf'
                ]);

                foreach($personaEspecialidad->personaEspecialidadRequisito as $requisito) {
                    $documentoGenerado = $requisito->generaDocumento();
                    if($documentoGenerado != null)
                        $message->attach($documentoGenerado['uri'], [ 
                            'as' => $requisito->requisito->Requisito . '.docx',
                            'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ]);
                }

            });
            $success = true;
        } catch(\Exception $e) {
            $success = false;
            Log::error('No se ha podido enviar los requisitos. Tabla: PersonaEspecialidad, id:' . $personaEspecialidad->id. ', Mensaje: '.$e->getMessage());            
        } finally {
            return $success;
        }

    }

    public function getTotalMontoAttribute() {
        return PersonaEspecialidadMateria::where('PersonaEspecialidad', $this->id)->sum('Monto');
    }

    public function insertaInformix () {
        $proccesed = false;
        try {
            //estudiantes nuevos
            if(!$this->persona->EsRegular) {
                /* Inserta en la tabla tabla alumno */
                if(\DB::connection('informix')->table('alumno')->where('ci', $this->persona->CI)->count() <= 0) {
                    $sql = "insert into alumno (cod,ci,apat,amat,nome,dir,telf,email,codrep,fechai,coduni,codbe,porbe,pagext,pagseg,deudor) values
                            (:cod,:ci,:apat,:amat,:nome,:dir,:telf,:email,:codrep,:fechai,:coduni,:codbe,:porbe,:pagext,:pagseg,:deudor)";
                    
                    $data = array(
                        'cod' => $this->persona->CI,
                        'ci' => $this->persona->CI,
                        'apat' => $this->persona->ApPaterno,
                        'amat' => $this->persona->ApMaterno,
                        'nome' => $this->persona->Nombre,
                        'dir' => $this->persona->Direccion,
                        'telf' => $this->persona->Telefono,
                        'codrep' => $this->especialidad->CodigoSiscoin,
                        'email' => $this->persona->email,
                        'fechai' => \Carbon\Carbon::now()->format('Y-m-d'),
                        'coduni' => $this->especialidad->UnidadAcademica,
                        'codbe' => '001',
                        'porbe' => 0,
                        'pagext' => 'N',
                        'pagseg' => 'N',
                        'deudor' => 'N'
                    );
    
                    $result = \DB::connection('informix')->update($sql,$data);
                    //inserta informacion adicional
                    $sql = "insert into alufam (cial,tutor,prof,citu,dirtu,coduni,ref1,ref2,ref3,ref4,ref5,ref6) values
                            (:cial,:tutor,:prof,:citu,:dirtu,:coduni,:ref1,:ref2,:ref3,:ref4,:ref5,:ref6)";
                    
                    $data = array(
                        'cial' => $this->persona->CI,
                        'tutor' => $this->persona->Tutor,
                        'prof' => $this->persona->OcupacionTutor,
                        'citu' => $this->persona->CITutor,
                        'dirtu' => '',
                        'coduni' => $this->persona->UnidadAcademica,
                        'ref1' => $this->persona->TelefonoTutor ,
                        'ref2' => null ,
                        'ref3' => null ,
                        'ref4' => null ,
                        'ref5' => null ,
                        'ref6' => null 
                    );
    
                    $result = \DB::connection('informix')->update($sql,$data);
    
    
                }
                /*inserta en tabla de saga_mont*/
    
                $gestion = config('parameters.gestion_actual_siscoin');
                //primero borra si ya existe
                $sql = "delete from saga_mont where codal = '" . $this->persona->CI . "' and gestion = '" . $gestion . "'" ;
                $result = \DB::connection('informix')->update($sql);
    
                $sql = 'insert into saga_mont (codal,monto, gestion) values ("'.$this->persona->CI.'",' . $this->TotalMonto .', \'' . $gestion . '\')';
                $result = \DB::connection('informix')->update($sql);
                $proccesed = true;
    
                // if(\DB::connection('informix')->table('saga_mont')->where('codal',$this->persona->CI)->where('gestion',$gestion)->count() <= 0) {
                //     $sql = 'insert into saga_mont (codal,monto, gestion) values ("'.$this->persona->CI.'",' . $this->TotalMonto .', \'' . $gestion . '\')';
                //     //dd($sql);
                //     $result = \DB::connection('informix')->update($sql);
                //     if($result)
                //         $proccesed = true;
                //     else 
                //         $proccesed = false;
                // }

            } else {
                $gestion = config('parameters.gestion_actual_siscoin');
                //estudiantes antiguos
                if($this->NumeroSemestre <= config('parameters.semestre_creditaje')) {
                    //es por creditaje
                    $monto = PersonaEspecialidadMateria::where('PersonaEspecialidad', $this->id)->where('Verificado', true)->sum('Monto');
                    //elimina si existe en saga_mont
                    $sql = "delete from saga_mont where codal = '" . $this->persona->CI . "' and gestion = '" . $gestion . "'" ;
                    $result = \DB::connection('informix')->update($sql);

                    $sql = 'insert into saga_mont (codal,monto, gestion) values ("'.$this->persona->CI.'",' . $monto .', \'' . $gestion . '\')';
                    $result = \DB::connection('informix')->update($sql);
                    $proccesed = true;
                    // if(\DB::connection('informix')->table('saga_mont')->where('codal',$this->persona->CI)->where('gestion',$gestion)->count() <= 0) {
                    //     //dd("insertado");
                    //     if($result)
                    //         $proccesed = true;
                    //     else 
                    //         $proccesed = false;
                    // }
                    //dd("saliendo");
                } else {
                    //es monto fijo
                    $sql = "delete from saga_mont where codal = '" . $this->persona->CI . "' and gestion = '" . $gestion . "'" ;
                    $result = \DB::connection('informix')->update($sql);

                    $sql = 'insert into saga_mont (codal,monto, gestion) values ("'.$this->persona->CI.'",' . $this->especialidad->MontoEstudianteRegular .', \'' . $gestion . '\')';
                    $result = \DB::connection('informix')->update($sql);
                    $proccesed = true;

                    // if(\DB::connection('informix')->table('saga_mont')->where('codal',$this->persona->CI)->where('gestion',$gestion)->count() <= 0) {
                    //     $sql = 'insert into saga_mont (codal,monto, gestion) values ("'.$this->persona->CI.'",' . $this->especialidad->MontoEstudianteRegular .', \'' . $gestion . '\')';
                    //     //dd($sql);
                    //     $result = \DB::connection('informix')->update($sql);
                    //     if($result)
                    //         $proccesed = true;
                    //     else 
                    //         $proccesed = false;
                    // }

                }
            }

        } catch (\Exception $e) {
                $proccesed = false;
                Log::error('No se ha podido insertar el registro en SISCOIN. Tabla: PersonaEspecialidad, id:' . $this->id. ', Mensaje: '.$e->getMessage());
        } finally {
            return $proccesed;
        }
      }
    
      public function viewInformix () {
        $result = null;
        try {
            $sql = "select codal, monto, gestion from saga_mont where codal = '" . $this->persona->CI . "'"; 
                $result = \DB::connection('informix')->select($sql);
                //$result = [['codal'=>'1231231','monto'=>23423.3,'gestion'=>'12010'],['codal'=>'1231231','monto'=>23423.3,'gestion'=>'12010']];
                $proccesed = true;
        } catch (\Exception $e) {
                $result = null;
                $proccesed = false;
                Log::error('No se ha podido leer el registro en SISCOIN. Tabla: PersonaEspecialidad, id:' . $this->id. ', Mensaje: '.$e->getMessage());
        } finally {
            return $result;
        }
      }
    
      public function eliminaInformix () {
        $proccesed = false;
        try {
            /*elimina de tabla de montos*/
            $gestion = config('parameters.gestion_actual_siscoin');
            $sql = "delete from saga_mont where codal = '" . $this->persona->CI . "' and gestion = '" . $gestion . "'" ;
                //dd($sql);
            $result = \DB::connection('informix')->update($sql);
            if($result)
                $proccesed = true;
            else 
                $proccesed = false;
        } catch (\Exception $e) {
                $proccesed = false;
                Log::error('No se ha podido eliminar el registro en SISCOIN. Tabla: PersonaEspecialidad, id:' . $this->id. ', Mensaje: '.$e->getMessage());
        } finally {
            return $proccesed;
        }
      }

      public function procesaEnvioRequisitos() {
        $job = (new ProcessEnvioRequisitos($this));
        $this->dispatch($job);
      }

      public function imprimeBoletas () {
        $basePathJRXML = storage_path('jrxml');
        $basePathGenerated = public_path('tmp/');

        $fileName = md5($this->Persona . Carbon::now());
        $persona = Persona::findorfail($this->Persona);

        $basePathJasper = $basePathJRXML . '/BoletaPreinscripcion2.jasper';
        $basePathGenerated = $basePathGenerated . $fileName;

        if(\Storage::exists($basePathGenerated))
            \Storage::delete($basePathGenerated);

        $parametros = array (
            'idPersonaEspecialidad' => $this->id,
            'urlLogo' => public_path('images/emi_logo.png'),  //ruta fisica
            'urlWaterMark' => public_path('images/logo_emi_marca_agua.png'),  //ruta fisica
            'pathMateriaSemestreNuevoSubreport' => storage_path('/jrxml/MateriaSemestreNuevo.jasper'),
            'pathMateriaSemestreActualSubreport' => storage_path('/jrxml/MateriaSemestreActual.jasper'),
            'urlCodigoVerificacion' => url('/').'/PersonaEspecialidad/verify/'
        );
        //dd($parametros);
        $database = \Config::get('database.connections.pgsql');
        $database['driver'] = 'postgres';
        
        $reporteJasper = \JasperPHP::process(
            $basePathJasper,
            $basePathGenerated,
            array("pdf"),
            $parametros,
            $database
        );
       // dd($reporteJasper->output());
        $reporteJasper->execute();

        return array(
            'url' => config('parameters.app_url') . '/tmp/' . $fileName . '.pdf',
            'uri' => $basePathGenerated . '.pdf'
        );
    } 

      public function imprimeBoletaEspecial () {
        $basePathJRXML = storage_path('jrxml');
        $basePathGenerated = public_path('tmp/');

        $fileName = md5($this->Persona . Carbon::now());
        $persona = Persona::findorfail($this->Persona);

        $basePathJasper = $basePathJRXML . '/BoletaPreinscripcionMil.jasper';
        $basePathGenerated = $basePathGenerated . $fileName;

        if(\Storage::exists($basePathGenerated))
            \Storage::delete($basePathGenerated);

        $parametros = array (
            'idPersonaEspecialidad' => $this->id,
            'urlLogo' => public_path('images/emi_logo.png'),  //ruta fisica
            'urlWaterMark' => public_path('images/logo_emi_marca_agua.png'),  //ruta fisica
            'pathMateriaSemestreNuevoSubreport' => storage_path('/jrxml/MateriaSemestreNuevoMil.jasper'),
            'pathMateriaSemestreActualSubreport' => storage_path('/jrxml/MateriaSemestreActual.jasper'),
            'urlCodigoVerificacion' => url('/').'/PersonaEspecialidad/verify/'
        );
        //dd($parametros);
        $database = \Config::get('database.connections.pgsql');
        $database['driver'] = 'postgres';
        
        $reporteJasper = \JasperPHP::process(
            $basePathJasper,
            $basePathGenerated,
            array("pdf"),
            $parametros,
            $database
        );
       // dd($reporteJasper->output());
        $reporteJasper->execute();

        return array(
            'url' => config('parameters.app_url') . '/tmp/' . $fileName . '.pdf',
            'uri' => $basePathGenerated . '.pdf'
        );
    }    


      public function imprimePlanPagos () {
        // try {
        //     $basePathJRXML = storage_path('jrxml');
        //     $basePathGenerated = public_path('tmp/');
    
        //     $fileName = md5($this->id . Carbon::now());
            
        //     $basePathJasper = $basePathJRXML . '/PlanDePagos.jasper';
        //     $basePathGenerated = $basePathGenerated . $fileName;
    
        //     if(\Storage::exists($basePathGenerated))
        //         \Storage::delete($basePathGenerated);
    
        //     $parametros = array (

        //         'codges' => config('parameters.gestion_actual_siscoin_rep'),
        //         'codal' => $this->persona->CI,
        //         'urlLogo' => config('parameters.url_logo'),
        //         'prontoPago' => $this->ProntoPago
        //     );
            
        //     $database = \Config::get('informix.informix');
        //     $database['driver'] = 'generic';
        //     //return $database;
        //     $reporteJasper = \JasperPHP::process(
        //         $basePathJasper,
        //         $basePathGenerated,
        //         array("pdf"),
        //         $parametros,
        //         $database
        //     );
        //     //dd($reporteJasper->output());
        //     $reporteJasper->execute();
    
        //     return array(
        //         'url' => config('parameters.app_url') . '/tmp/' . $fileName . '.pdf',
        //         'uri' => $basePathGenerated . '.pdf'
        //     );
        // } catch (\Exception $e) {
        //     return false;
        // }    
        
        $basePathGenerated = public_path('tmp/');
        $fileName = md5($this->id . Carbon::now());
        $basePathGenerated = $basePathGenerated . $fileName;
    
        if(\Storage::exists($basePathGenerated))
            \Storage::delete($basePathGenerated);
        
        $sql = 'select i.codal, a.nome||" "||a.apat||" "||a.amat nombre,i.codges,g.des,i.codcar,c.des descar,cu.cod,cu.des dessem,
                d.codpag,p.des despag,p.moneda,d.monto,d.feclim
                from inscrito i , alumno a, gestion g,carrera c, curso cu, detpagin d,pago p
                where i.codal=a.ci and i.coduni=a.coduni and i.codcar=c.cod and cu.cod=i.codcur
                and p.cod=d.codpag and d.codal=i.codal  and d.coduni=i.coduni and i.coduni=p.coduni
                and d.coduni=p.coduni and i.codges=d.codges and i.codcar=c.cod and d.codcar=i.codcar
                and (i.codges="'.config('parameters.gestion_actual_siscoin_rep').'" or i.codges="' . config('parameters.gestion_actual_siscoin_rep_alt') . '") and i.codal =  "' . $this->persona->CI . '" and i.codges=g.cod and i.codcur in ("01R","111","20C","21","21C")' ;

        
        $data = \DB::connection('informix')->select($sql);
        //dd($data);
        $pdf = \PDF::loadView('modules.PersonaEspecialidad.planPagos', ['personaEspecialidad' => $this, 'data' => $data])->save( $basePathGenerated . '.pdf');
        return array(
            'url' => config('parameters.app_url') . '/tmp/' . $fileName . '.pdf',
            'uri' => $basePathGenerated . '.pdf'
        );
      }

    public function generaCodigoVerificacion() {
        $this->CodigoVerificacion = md5(bcrypt($this->id.Carbon::now()));
        $this->timestamps = null;
        $this->save();
    }

}
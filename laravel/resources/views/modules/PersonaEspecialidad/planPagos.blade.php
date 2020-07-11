<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{ Html::favicon( url('/') . '/ICON.png' ) }}
        <title>{{ config('app.name') }}</title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
    </head>
    <body >
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <span class="text-center">
                        <div class="invoice-title"><h2>Plan de Pagos</h2></div>
                        <div class="invoice-title"><h5><b><i>(Expresado en Bs.)</i></b></h5></div>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <b>Nombre Completo: </b>{{$data[0]->nombre}}
                </div>
                <div class="col-xs-4">
                    <b>CI: </b>{{$data[0]->codal}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <b>Especialidad: </b>{{$data[0]->descar}}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <b>Gestión: </b>{{$data[0]->codges}}
                </div>
                <div class="col-xs-8">
                    <b>Semestre Académico: </b>{{$data[0]->dessem}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">Detalle</th>
                            <th scope="col">Monto</th>
                            <th scope="col">Fecha Límite de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; $montoObligatorio = 0; $mensualidad = 0; @endphp
                            @foreach($data as $d)
                            <tr>
                                @php $total += $d->monto @endphp
                                <td>
                                    @if(($personaEspecialidad->especialidad->UnidadAcademica == 2 && ($d->codpag == '1250' || $d->codpag == '1251' || $d->codpag == '7602' || $d->codpag == '7601' || $d->codpag == '7212')) ||
                                        ($personaEspecialidad->especialidad->UnidadAcademica == 3 && ($d->codpag == '1201' || $d->codpag == '1202' || $d->codpag == '1702' || $d->codpag == '1703' || $d->codpag == '1707')) ||
                                        ($personaEspecialidad->especialidad->UnidadAcademica == 4 && ($d->codpag == '1250' || $d->codpag == '1251' || $d->codpag == '2301' || $d->codpag == '2303' || $d->codpag == '9171')) ||
                                        ($personaEspecialidad->especialidad->UnidadAcademica == 5 && ($d->codpag == '1250' || $d->codpag == '1251' || $d->codpag == '7207' || $d->codpag == '7603')) 
                                    ) 
                                        @php $montoObligatorio += $d->monto; @endphp
                                        * 
                                    @endif 
                                    @if($d->codpag == '1252' || $d->codpag == '1202' ) 
                                        @php $mensualidad += $d->monto; @endphp
                                        * 
                                    @endif 
                                    {{ $d->despag }}</td>
                                <td>{{ number_format($d->monto, 2, '.', ',') }}</td>
                                <td>{{ \Carbon\Carbon::parse($d->feclim)->format('d-m-Y') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-right"><b>Total</b></td>
                                <td>{{ number_format($total, 2, '.', ',') }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-10 col-md-offset-1 small">
                    @if($personaEspecialidad->ProntoPago)
                    <p class="text-justify"><b>El estudiante deberá cancelar Bs. {{ number_format($total, 2, '.', ',') }}, éste monto incluye el descuento del 8% por pronto pago aplicado solamente a las mensualidades.</b></p>
                    @else
                        @if($personaEspecialidad->especialidad->UnidadAcademica == 5)
                            <p class="text-justify"><b>El estudiante deberá pagar en su primer depósito Bs. {{ number_format($montoObligatorio, 2, '.', ',') }} , éste monto incluye la Cuota Inicial, Primera Cuota, Extensión Universitaria y Certificación automática de calificaciónes. Posteriormente cada mes debe pagar Bs. {{ number_format($mensualidad, 2, '.', ',') }} antes de la fecha límite.</b></p>
                        @else
                        <p class="text-justify"><b>El estudiante deberá pagar en su primer depósito Bs. {{ number_format($montoObligatorio, 2, '.', ',') }} , éste monto incluye la Cuota Inicial, Primera Cuota, Extensión Universitaria, Seguro Médico y Certificación automática de calificaciónes. Posteriormente cada mes debe pagar Bs. {{ number_format($mensualidad, 2, '.', ',') }} antes de la fecha límite.</b></p>
                        @endif
                    @endif
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <p class="text-justify">
                        El estudiante puede realizar el pago las siguientes modalidades:
                        <ul>
                            <li>Depósito Bancario a la cuenta corriente fiscal de la Escuela Militar de Ingeniería. Banco Unión S.A. 10000006036144. </li>
                            <li>Servicio de ATC en cajas de la EMI, usando tarjeta de débito o crédito en ventanillas de cada Unidad Académica. </li>
                        </ul>
                    </p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <p class="small"><b><i>La información contenida en este documento está sujeta a verificación y/o cambios sin previo aviso y no 
                    representa un compromiso por parte de la Escuela Militar de Ingeniería “Mcal. Antonio José de Sucre”</i></b></p>
                </div>
            </div>

        </div>
    </body>
</html>
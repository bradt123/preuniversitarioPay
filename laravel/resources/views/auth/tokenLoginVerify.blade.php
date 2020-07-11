<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{ Html::favicon( '/ICON.png' ) }}
        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        @include('partials.css')
    </head>

<body class="widescreen">

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">
            <div class="card card-pages">

                <div class="card-body">
                    <h3 class="text-center m-t-0 m-b-15">
                        <a href="#" class="logo logo-admin"><img src="/images/emi_logo.png" alt="" style="width:250px;"></a>
                    </h3>
                    <h4 class="text-muted text-center m-t-0"><b>Inicio de Sesión</b></h4>
                    <form class="form-horizontal m-t-20" method="post" action="{{ route('login.tokenLoginVerify') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <div class="form-group">
                            <input type="text" class="form-control {{ $errors->has('tokenLogin') ? 'parsley-error' : '' }}" name="tokenLogin" placeholder="Código de Inicio de sesión">
                            @if ($errors->has('tokenLogin'))
                                <ul class="parsley-errors-list filled" id="parsley-id-9">
                                    <li class="parsley-required">{{ $errors->first('tokenLogin') }}</li>
                                </ul>
                            @endif
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Ingresar <i class="fas fa-sign-in-alt"></i></button>
                            </div>
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-xs-12 pull-left">
                                <a href="{{url('login')}}" class="">Volver a Inicio de Sesión</a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/js/app.js"></script>
</body>
</html>

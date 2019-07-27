<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('node_modules/bootstrap/dist/css/bootstrap.min.css')}}">
    <!--<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">-->

    <link rel="stylesheet" href="{{asset('css/solicitud.css')}}">
    <link rel="stylesheet" href="{{asset('css/consulta.css')}}">

    <title>Desposte</title>
  </head>
  <body>
    
    <div class="container-fluid">
        <div class="card mt-3">
            @yield('content')
        </div>
    </div>

    <a href="{{url('/')}}" class="btn btn-danger mt-2 ml-3" id="back">Regresar a intranet</a>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('node_modules/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('node_modules/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <script>
    
    

    </script>


  </body>
</html>
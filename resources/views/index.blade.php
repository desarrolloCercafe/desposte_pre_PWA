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

    <link rel="stylesheet" href="{{asset('css/inicio.css')}}">

    <title>Desposte</title>
  </head>
  <body>

    <div class="containerInit">

      <div class="background">

        <section class="contentBackground">
            <p class="titleInicial">Desposte</p>
        </section>
        
        <section class="contentBackground2">
            <p class="followUs">Siguenos en</p>
        </section>

        <section class="contentBackground2">
            <img src="{{asset('svg/btnfacebook.svg')}}" alt="Facebook" class="iconFacebook">
            <img src="{{asset('svg/btninstagram.svg')}}" alt="Facebook" class="iconInstagram">
        </section>

        <img src="{{asset('svg/logoBlanco.png')}}" alt="logo blanco" class="logoCercafe">

      </div>

      <div class="formInicio">

        <section class="ContentForm">

          <h2 class="titleLogin">Iniciar Sesión</h2>

              <p class="labelUsername">Nombre de usuario:</p>
              <input type="text" class="styleInput" placeholder="Nombre de usuario" name="usuario" id="usuario">
    
              <p class="labelPassword">Contraseña:</p>
              <input type="password" class="styleInput" placeholder="Contraseña" name="password" id="password">
              <br>
              <label><input type="checkbox" id="checkBoxRemember"></label><span class="labelCheckbox" id="labelCheckbox">Recordar</span>
              <br>
              <p class="forgotPassword" id="forgotPassword">¿Olvidaste tu contraseña?</p>
              <br>
              <button class="btnInicioSesion" id="inicioSesionUsuario">Iniciar Sesión</button>

        </section>

      </div>


    </div>

    <style>
    
      .labelCheckbox:hover{
        cursor: pointer;
      }

    </style>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="botoErrorInicioSesion">
        Modal de error
    </button>
      
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title" id="modalHeaderInicio"><b>Error de inicio</b></h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="bodyModalContentInicio">
                <p id="contentErrorModalInicio">Debes ingresar un usuario y contraseña para ingresar.</p>
            </div>
            <div class="modal-footer" id="footerModalInicio">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
          </div>
        </div>
      </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('node_modules/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('node_modules/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/inicio.js')}}"></script>

    <script>
    
    document.getElementById('labelCheckbox').addEventListener('click', function(){

      if(document.getElementById('checkBoxRemember').checked == true){
        document.getElementById('checkBoxRemember').checked = false;
      }else{
        document.getElementById('checkBoxRemember').checked = true;
      }

    });

    /*function GuardarEnLocalStorage(parametro1, parametro2, parametro3){
      if(localStorage.getItem('recordar')){
        localStorage.setItem('usuario', parametro1);
        localStorage.setItem('password', parametro2);
      }

      localStorage.setItem('nombre', parametro3);

      return true;

    }*/
    
    </script>


  </body>
</html>
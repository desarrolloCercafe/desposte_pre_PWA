<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/solicitud.css')}}">
    <link rel="stylesheet" href="{{asset('css/consulta.css')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('node_modules/bootstrap/dist/css/bootstrap.min.css')}}">
    <!--<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">-->

    <title>Desposte</title>
  </head>
  <body>
    
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="btnNewPassword">
        Launch demo modal
      </button>

      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Cambio de contraseña</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="contentModalRestorePassword">
                <p>Correo: <b id="correoUser">{{$usuario[0]->email}}</b></p>
                <p>Nueva Contraseña:</p>
                <input type="password" class="form-control" id="newPassword1" placeholder="Ingresa tu nueva Contraseña">

                <p>Nueva Contraseña:</p>
                <input type="password" class="form-control" id="newPassword2" placeholder="Confirmar nueva contraseña">

            </div>
            <div class="modal-footer" id="footerModalRestorePassword">
              <button type="button" class="btn btn-success" id="sendPasswordVerifcation">Cambiar contraseña</button>
            </div>
          </div>
        </div>
      </div>

      <style>
          #btnNewPassword{
              display: none;
          }
      </style>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{asset('node_modules/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('node_modules/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    <script>    

        window.onload = inicio;

        function inicio(){
            document.getElementById('btnNewPassword').click();
        }

        document.getElementById('sendPasswordVerifcation').addEventListener('click', function(){
            var password1 = document.getElementById('newPassword1').value;
            var password2 = document.getElementById('newPassword2').value;

            var contentModal = document.getElementById('contentModalRestorePassword');
            var informativoMessage = document.querySelector("p#messageNotification");
            if(informativoMessage){
                contentModal.removeChild(informativoMessage);
            }

            if(password1.length == 0 || password2.length == 0){

                contentModal.innerHTML += `
                    <p style="font-weight:bold; font-size:1rem; color:red" id="messageNotification">Debes de diligenciar ambos campos.</p>
                `;

            }else if(password1 != password2){
                contentModal.innerHTML += `
                    <p style="font-weight:bold; font-size:1rem; color:red" id="messageNotification">Las contraseñas no coinciden.</p>
                `;
            }else if(password1.length > 0 && password1.length <= 6){
                contentModal.innerHTML += `
                    <p style="font-weight:bold; font-size:1rem; color:red" id="messageNotification">La nueva contraseña debe ser de almenos 7 caracteres.</p>
                `;
            }
            else{

                var email = document.getElementById('correoUser');

                var objeto = {
                    newPassword: password1,
                    email:email.innerHTML
                };

                SendModifyPassword(objeto);
            }
        });

        function SendModifyPassword(objeto){
            var ajax = new XMLHttpRequest();
            ajax.open("GET","/updatePassword?data="+ encodeURIComponent(JSON.stringify(objeto)),true);
            ajax.setRequestHeader("Content-Type", "application/json");
            ajax.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {

                    var json = ajax.responseText;
                    if(json == "Ok"){
                        borrarCredenciales();
                        window.location.href="http://127.0.0.1:8000/";
                    }

                }
            }
            ajax.send();
        }

        function borrarCredenciales(){
          if(localStorage.getItem("recordar")){
            localStorage.removeItem("recordar");
            localStorage.removeItem("password");
            localStorage.removeItem("usuario");
          }

          if(localStorage.getItem("PedidoSeleccionado")){
            localStorage.removeItem("PedidoSeleccionado");
          }
        }

    </script>


  </body>
</html>
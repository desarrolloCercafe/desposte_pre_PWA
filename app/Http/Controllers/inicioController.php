<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Mail\emailDesposte;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\usuario;
use Mail;


class inicioController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        header("Content-Type: application/json");

        $filtros = json_decode(stripslashes(file_get_contents("php://input")));
        // build a PHP variable from JSON sent using GET method
        $filtros = json_decode(stripslashes($request->data));

        $usuario = strtolower($filtros->usuario);
        $password = $filtros->password;

        $data = DB::select(
                    'SELECT nombre FROM usuario WHERE usuario = ? AND contrasena = ?', 
                    [$usuario, $password]
                );

        $cantidad = Count($data);

        if($cantidad){

            /*echo "<script>";
            echo "localStorage.setItem('nombre', ".$data[0]->nombre.");";
            echo "</script>";*/
            return json_encode($data[0]->nombre);
        }else{
            return json_encode("No");
        }

        //return json_encode($password);
    }

    public function ReestablecerUsuario(Request $request){
        header("Content-Type: application/json");

        $filtros = json_decode(stripslashes(file_get_contents("php://input")));
        // build a PHP variable from JSON sent using GET method
        $filtros = json_decode(stripslashes($request->data));

        $datos = DB::select('SELECT token FROM usuario WHERE email = ?', [$filtros->correo]);



        if(Count($datos)){
            Mail::send('mail.mail_plano',$data = ["token" => $datos[0]->token],function($message) use($filtros) {

                $message->from('intranet2.0@cercafe.com.co');
                $message->to($filtros->correo);
                $message->subject("Cambio de contraseña");
           
            });
    
            return "OK";
        }else{
            return "Wrong";
        }
    }

    public function changePassword($token){

        $usuario = DB::select('SELECT email FROM usuario WHERE token = ?', [$token]);

        return view('change.changePassword', compact('usuario'));

    }

    public function updatePassword(Request $request){

        header("Content-Type: application/json");

        $filtros = json_decode(stripslashes(file_get_contents("php://input")));
        // build a PHP variable from JSON sent using GET method
        $filtros = json_decode(stripslashes($request->data));

        DB::update('UPDATE usuario SET contrasena = ? WHERE email = ?', [$filtros->newPassword,$filtros->email]);

        return "Ok";
    }
}

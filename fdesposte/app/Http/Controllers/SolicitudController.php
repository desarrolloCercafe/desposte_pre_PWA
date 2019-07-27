<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Cliente;
use App\Producto;
use App\Solicitud;
//use DB;

class SolicitudController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('solicitud.index', compact('clientes','productos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $solicitud = new Solicitud();

        $solicitud->productos = json_decode(request('productos'));
        $solicitud->cliente = request('codCliente');

        $codCliente = DB::select('SELECT Nit FROM cliente WHERE Nombre = ?', [$solicitud->cliente]);

        //var_dump($codCliente[0]->Nit);
        //echo $solicitud->cliente;

        $solicitud->fechaEntrega = request('fechaEntrega');
        $solicitud->fechaSolicitud = request('fechaSolicitud');

        DB::insert(
            'insert into pedido (codCliente, fSolicitud, fEntrega, estado, codVendedor) values (?, ?, ?, ?)',
            [$codCliente[0]->Nit, $solicitud->fechaSolicitud, $solicitud->fechaEntrega, 1]
        );

        $consecutivo = DB::select(
                        'select Consecutivo from pedido where FechaSolicitud = ? AND CodCliente = ?',
                        [$solicitud->fechaSolicitud,$codCliente[0]->Nit]);

        //echo $consecutivo[0]->Consecutivo;

        $cantidad = Count($solicitud->productos);

        //echo $cantidad;

        for ($i=0; $i < $cantidad; $i++) { 
            DB::insert(
                    'insert into solicitud (CodPedido, CodProducto, CantidadSolicitada, UnidadMedida) values (?, ?, ?, ?)',
                    [$consecutivo[0]->Consecutivo, $solicitud->productos[$i]->codigo, $solicitud->productos[$i]->cantidad, $solicitud->productos[$i]->radio]);
        }

        return redirect()->route('solicitud.index');

        
    }

    public function ChangeInput(Request $request){

        $usuario = $request->data;

        $filtrado = strtolower($usuario);

        $username = DB::select("select Nombre from cliente where Nombre LIKE '%".$filtrado."%'");

        return json_encode($username);
    }
}

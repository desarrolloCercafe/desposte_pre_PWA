<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Cliente;
use App\Pedido;
use App\Solicitud;
use DB;
use PDF;


class ConsultaController extends Controller
{
    /*public function home(){
        return view('consulta.index');
    }*/

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::all();
        //$pedidos = Pedido::all();
        $pedidos = DB::table('pedido')
        ->join('cliente','pedido.CodCliente','=', 'cliente.Nit')
        ->select('pedido.Consecutivo', 'pedido.FechaSolicitud', 'cliente.Nombre', 'pedido.Estado', 'pedido.CodCliente')
        ->where('pedido.Estado','=', 1)
        ->orderBy('pedido.Consecutivo')
        ->paginate(5, ['*'], 'pedidos');

        $pedidos2 = DB::table('pedido')
        ->join('cliente','pedido.CodCliente','=', 'cliente.Nit')
        ->select('pedido.Consecutivo', 'pedido.FechaSolicitud', 'pedido.FechaEntrega', 'cliente.Nombre', 'pedido.Estado', 'pedido.CodCliente')
        ->where('pedido.Estado','=', 2)
        ->orderBy('pedido.Consecutivo')
        ->paginate(5, ['*'], 'historial');

        return view('consulta.index', compact('clientes', 'pedidos', 'pedidos2'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function consulta(Request $request)
    {
        if($request->ajax()){

            /*$solicitud = Solicitud::where('CodPedido', $request->search)->get();*/

            $solicitud = "";
            
            $ProductosSolicitados = DB::select('SELECT 
                                        solicitud.CodProducto,
                                        producto.Nombre,
                                        solicitud.CantidadSolicitada,
                                        solicitud.UnidadMedida
                                        FROM solicitud, producto 
                                        WHERE producto.Codigo = solicitud.CodProducto
                                        AND solicitud.CodPedido = ? ', [$request->search]);

            if($ProductosSolicitados){

                $i = 0;

                foreach ($ProductosSolicitados as $ProductoSolicitado) {

                    $solicitud.='<tr>'.
            
                    '<th scope="row" value='.$ProductoSolicitado->CodProducto.' id="codProducto'.$i.'">'.$ProductoSolicitado->CodProducto.'</th>'.
                    
                    '<td>'.$ProductoSolicitado->Nombre.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->CantidadSolicitada.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->UnidadMedida.'</td>'.

                    '<td><input type="number" id="cantidadDespachada'.$i.'" name="cantidadDespachada'.$i.'" class="form-control"></td>'.
                    
                    '</tr>';

                    $i++;
                }

            }

            return $solicitud;
            
        }
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showProductHistorial(Request $request)
    {
        if($request->ajax()){

            /*$solicitud = Solicitud::where('CodPedido', $request->search)->get();*/

            $solicitud = "";
            
            $ProductosSolicitados = DB::select('SELECT 
                                        solicitud.CodProducto,
                                        producto.Nombre,
                                        solicitud.CantidadSolicitada,
                                        solicitud.UnidadMedida,
                                        solicitud.CantidadDespachada
                                        FROM solicitud, producto 
                                        WHERE producto.Codigo = solicitud.CodProducto
                                        AND solicitud.CodPedido = ? ', [$request->search]);

            if($ProductosSolicitados){

                $i = 0;

                foreach ($ProductosSolicitados as $ProductoSolicitado) {

                    $solicitud.='<tr>'.
            
                    '<th scope="row" value='.$ProductoSolicitado->CodProducto.' id="codProducto'.$i.'">'.$ProductoSolicitado->CodProducto.'</th>'.
                    
                    '<td>'.$ProductoSolicitado->Nombre.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->CantidadSolicitada.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->UnidadMedida.'</td>'.

                    '<td>'.$ProductoSolicitado->CantidadDespachada.'</td>'.
                    
                    '</tr>';

                    $i++;
                }

            }

            return $solicitud;
            
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $solicitud = new Solicitud();

        $solicitud->despacho = json_decode(request('DespachoCantidad'));
        $json = $solicitud->despacho;
        $solicitud->pedido = request('CodPedido');

        $cantidad = Count($solicitud->despacho);

        for ($i=0; $i < $cantidad; $i++) { 
            DB::update(
                'UPDATE solicitud SET CantidadDespachada = ? WHERE CodPedido = ? AND CodProducto = ?', 
                [$json[$i]->cantidad, $solicitud->pedido, $json[$i]->codigo]
            );
        }

        DB::update(
            'UPDATE pedido SET Estado = 2 where Consecutivo = ?',
            [$solicitud->pedido]
        );

        return redirect()->route('consulta.index');
    }


    public function FiltrarTabla(Request $request){

        header("Content-Type: application/json");

        $filtros = json_decode(stripslashes(file_get_contents("php://input")));
        // build a PHP variable from JSON sent using GET method
        $filtros = json_decode(stripslashes($request->data));
        // encode the PHP variable to JSON and send it back on client-side
        //echo json_encode($filtros);

        $datosFiltrados = "";

        if(isset($filtros->fechaInicio) && isset($filtros->cliente) && isset($filtros->presentacion)){

            //Consulta ejecutada normalmente
            if($filtros->presentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit 
                    AND ped.CodCliente = ? AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                    [$filtros->cliente, $filtros->fechaInicio, $filtros->fechaFinal]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.UnidadMedida,
                    cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE 
                    cl.Nit = ? AND ped.FechaSolicitud BETWEEN ? AND ? AND ped.Estado = 1 ', 
                    [$filtros->cliente, $filtros->fechaInicio, $filtros->fechaFinal]
                );
            }

        }elseif(isset($filtros->fechaInicio) && isset($filtros->cliente)){

            $datosFiltrados = DB::select(
                'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit 
                AND ped.CodCliente = ? AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                [$filtros->cliente, $filtros->fechaInicio, $filtros->fechaFinal]
            );

        }elseif(isset($filtros->fechaInicio) && isset($filtros->presentacion)){

            //Consulta ejecutada normalmente
            if($filtros->presentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit 
                    AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                    [$filtros->fechaInicio, $filtros->fechaFinal]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.UnidadMedida,
                    cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE
                    ped.FechaSolicitud BETWEEN ? AND ? AND ped.Estado = 1 ', 
                    [$filtros->fechaInicio, $filtros->fechaFinal]
                );
            }

        }elseif(isset($filtros->cliente) && isset($filtros->presentacion)){
            
            //Consulta ejecutada normalmente
            if($filtros->presentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit 
                    AND ped.CodCliente = ?', 
                    [$filtros->cliente]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.UnidadMedida,
                    cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE 
                    cl.Nit = ? AND ped.Estado = 1  ', 
                    [$filtros->cliente]
                );
            }


        }elseif(isset($filtros->fechaInicio)){

            $datosFiltrados = DB::select(
                'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit 
                AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                [$filtros->fechaInicio, $filtros->fechaFinal]
            );

        }elseif(isset($filtros->cliente)){

            $datosFiltrados = DB::select(
                'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit 
                AND ped.CodCliente = ?', 
                [$filtros->cliente]
            );

        }elseif(isset($filtros->presentacion)){

            //Consulta ejecutada normalmente
            if($filtros->presentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 1 AND ped.CodCliente = cl.Nit ', 
                    []
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.UnidadMedida,
                    cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod 
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE
                    ped.Estado = 1 ', 
                    []
                );
            }
        }

        return json_encode($datosFiltrados);

    }

    public function GenerarCSV(Request $request){

        $pedido = new Pedido();

        $pedido->consecutivo = request('consecutivo');

        //echo $pedido->consecutivo;
        $salida = fopen('php://output', 'w');

        $resultados = DB::select('SELECT ped.Consecutivo, ped.CodCliente, 
                                    ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre FROM
                                    pedido AS ped, cliente AS cl WHERE 
                                    ped.Consecutivo = ? AND ped.CodCliente = cl.Nit', 
                                    [$pedido->consecutivo]);

        $separador = ";";

        header('Content-Type:text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Reporte_Fechas_Ingreso.csv"');

        fputcsv($salida, array('Consecutivo', 'Cliente', 'FechaSolicitud', 'FechaEntrega'), $separador);

        foreach ($resultados as $key) {
            fputcsv($salida, array($key->Consecutivo,
                                    $key->Nombre,
                                    $key->FechaSolicitud,
                                    $key->FechaEntrega), $separador);
        }

    }

    public function GenerarPDF($consecutivo){
        
        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($this->loadTable($consecutivo));

        return $pdf->stream();
    }

    function loadTable($consecutivo){

        $DetallePedido = DB::select(
                                    'SELECT ped.FechaSolicitud, ped.FechaEntrega, cliente.Nombre
                                    FROM pedido as ped, cliente WHERE cliente.Nit = ped.CodCliente 
                                    AND ped.Consecutivo = ?', [$consecutivo]);

        $DescripcionPedido = DB::select(
                                    'SELECT sol.CodProducto, prod.Nombre, sol.CantidadSolicitada,
                                    sol.CantidadDespachada, sol.UnidadMedida FROM solicitud as sol,
                                    producto as prod WHERE sol.CodProducto = prod.Codigo 
                                    AND sol.CodPedido = ? ', [$consecutivo]);

        $TotalesPedidos = DB::select(
                                    'SELECT COUNT(solicitud.CodProducto) as sumSolicitud, 
                                    SUM(solicitud.CantidadSolicitada) as sumCantSol,
                                    SUM(solicitud.CantidadDespachada) as sumCantDes
                                    FROM solicitud WHERE solicitud.CodPedido = ?', [$consecutivo]);

        $output = '
        <style>    
            .tittle{
                float: right;
                margin-top: 25px;
                margin-bottom: 35px;
            }
        
            .bottom{
                margin-top: 35px;
            }
        
            .img{
                float: left;
            }
        
            .content{
                margin-left: 210px;
            }
        
            .tableContent{
                width: 100%;
                border: 1px solid black;
                border-collapse: collapse;
            }
        
            th{
                text-align: left;
                border: 1px solid black;
                background-color: red;
                color: white;
                height: 25px;
            }
        
            .codProducto{
                width: 12%;
            }
        
            .NombreProducto{
                width: 65%;
            }
        
            .CantSolicitada, .CantDespachada{
                width: 9%;
            }
        
            .UnidadMedida{
                width: 7%;
            }
        
            td{
                border: 1px solid black;
                text-align: right;
            }
        
            .right{
                text-align:right;
            }

            .productName{
                text-align: left;
            }
        </style>
    
                <img src="svg/logo.png" width="250px" height="170px" class="img">
                <span class="tittle">
                    <h1>Informe sobre Pedidos del desposte</h1>
                    <p>Cliente: <b> '.strtoupper($DetallePedido[0]->Nombre).'</b></p>
                </span>
            
            <p>Código Pedido: <b>'.$consecutivo.'</b></p>
            <p>Fecha de la solicitud: <b>'.$DetallePedido[0]->FechaSolicitud.'</b></p>
            <p>Fecha de la entrega: <b>'.$DetallePedido[0]->FechaEntrega.'</b></p>
    
            <table class="tableContent">
                <thead>
                    <tr>
                        <th class="codProducto">Cod.Prod.</th>
                        <th class="NombreProducto">Nombre Producto</th>
                        <th class="CantSolicitada">Cant.Sol</th>
                        <th class="CantDespachada">Cant.Des</th>
                        <th class="UnidadMedida">U/M</th>
                    </tr>
                </thead>
                <tbody>';

        foreach($DescripcionPedido as $itemPedido){
            $output .= '
                    <tr>
                        <td>'.$itemPedido->CodProducto.'</td>
                        <td class="productName">'.$itemPedido->Nombre.'</td>
                        <td>'.$itemPedido->CantidadSolicitada.'</td>
                        <td>'.$itemPedido->CantidadDespachada.'</td>
                        <td>'.$itemPedido->UnidadMedida.'</td>
                    </tr>
            ';
        }

        $output .= '
                </tbody>
            </table>

        <span class="right">
            <p>Cantidad total de productos: <b>'.$TotalesPedidos[0]->sumSolicitud.'</b></p>
            <p>Total unidades solicitadas: <b>'.$TotalesPedidos[0]->sumCantSol.'</b></p>
            <p>Total unidades despachadas: <b>'.$TotalesPedidos[0]->sumCantDes.'</b></p>
        </span>';
        
        return $output;
    }

}

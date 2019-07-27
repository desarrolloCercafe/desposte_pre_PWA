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
        ->join('cliente','pedido.codCliente','=', 'cliente.id')
        ->select('pedido.id', 'pedido.fechaEntrega', 'cliente.razonSocial', 'pedido.estado', 'pedido.codCliente')
        ->where('pedido.estado','=', 1)
        ->orderBy('pedido.id', 'DESC')
        ->paginate(5, ['*'], 'pedidos');

        $pedidos2 = DB::table('pedido')
        ->join('cliente','pedido.codCliente','=', 'cliente.id')
        ->select('pedido.id', 'pedido.fechaSolicitud', 'pedido.fechaEntrega', 'cliente.razonSocial', 'pedido.estado', 'pedido.codCliente')
        ->where('pedido.estado','=', 2)
        ->orderBy('pedido.id', 'DESC')
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
                                        solicitud.codProducto,
                                        producto.nombre,
                                        solicitud.cantidadSolicitada,
                                        solicitud.unidadMedida
                                        FROM solicitud, producto 
                                        WHERE producto.codigo = solicitud.codProducto
                                        AND solicitud.codPedido = ? ', [$request->search]);

            if($ProductosSolicitados){

                $i = 0;

                foreach ($ProductosSolicitados as $ProductoSolicitado) {

                    $solicitud.='<tr>'.
            
                    '<th scope="row" value='.$ProductoSolicitado->codProducto.' id="codProducto'.$i.'">'.$ProductoSolicitado->codProducto.'</th>'.
                    
                    '<td>'.$ProductoSolicitado->nombre.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->cantidadSolicitada.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->unidadMedida.'</td>'.

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
                                        solicitud.codProducto,
                                        producto.nombre,
                                        solicitud.cantidadSolicitada,
                                        solicitud.unidadMedida,
                                        solicitud.cantidadDespachada
                                        FROM solicitud, producto 
                                        WHERE producto.codigo = solicitud.codProducto
                                        AND solicitud.codPedido = ? ', [$request->search]);

            if($ProductosSolicitados){

                $i = 0;

                foreach ($ProductosSolicitados as $ProductoSolicitado) {

                    $solicitud.='<tr>'.
            
                    '<th scope="row" value='.$ProductoSolicitado->codProducto.' id="codProducto'.$i.'">'.$ProductoSolicitado->codProducto.'</th>'.
                    
                    '<td>'.$ProductoSolicitado->nombre.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->cantidadSolicitada.'</td>'.
                    
                    '<td>'.$ProductoSolicitado->unidadMedida.'</td>'.

                    '<td>'.$ProductoSolicitado->cantidadDespachada.'</td>'.
                    
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
                'UPDATE solicitud SET cantidadDespachada = ? WHERE codPedido = ? AND codProducto = ?', 
                [$json[$i]->cantidad, $solicitud->pedido, $json[$i]->codigo]
            );
        }

        DB::update(
            'UPDATE pedido SET estado = 2 where id = ?',
            [$solicitud->pedido]
        );

        return redirect()->route('consulta.index');
    }


    public function FiltrarTabla(Request $request){

        header("Content-Type: application/json");

        $filtros = json_decode(stripslashes(file_get_contents("php://input")));
        // build a PHP variable from JSON sent using GET method
        $filtros = json_decode(stripslashes($request->data));
        //$tipoBusqueda = $request->opcion;

        $datosFiltrados = "";
        //$datosFiltrados = $filtros->cliente;
        if(isset($filtros->fechaInicio) && isset($filtros->cliente) && isset($filtros->tipoPresentacion)){

            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                    FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id 
                    AND ped.codCliente = ? AND ped.fechaEntrega BETWEEN ? AND ? ', 
                    [$filtros->cliente, $filtros->fechaInicio, $filtros->fechaFin]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.codigo, prod.nombre as NombreProd, sol.cantidadSolicitada, sol.unidadMedida,
                    cl.razonSocial as NombreCl, ped.id FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.codProducto = prod.codigo 
                    INNER JOIN pedido as ped ON ped.id = sol.codPedido 
                    INNER JOIN cliente as cl ON cl.id = ped.codCliente WHERE 
                    cl.id = ? AND ped.fechaEntrega BETWEEN ? AND ? AND ped.estado = 1 ', 
                    [$filtros->cliente, $filtros->fechaInicio, $filtros->fechaFin]
                );
            }

        }elseif(isset($filtros->fechaInicio) && isset($filtros->cliente)){

            $datosFiltrados = DB::select(
                'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id 
                AND ped.codCliente = ? AND ped.fechaEntrega BETWEEN ? AND ? ', 
                [$filtros->cliente, $filtros->fechaInicio, $filtros->fechaFin]
            );

        }elseif(isset($filtros->fechaInicio) && isset($filtros->tipoPresentacion)){

            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                    FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id 
                    AND ped.fechaEntrega BETWEEN ? AND ? ', 
                    [$filtros->fechaInicio, $filtros->fechaFin]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.codigo, prod.nombre as NombreProd, sol.cantidadSolicitada, sol.unidadMedida,
                    cl.razonSocial as NombreCl, ped.id FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.codProducto = prod.codigo
                    INNER JOIN pedido as ped ON ped.id = sol.codPedido
                    INNER JOIN cliente as cl ON cl.id = ped.codCliente WHERE
                    ped.fechaEntrega BETWEEN ? AND ? AND ped.estado = 1 ', 
                    [$filtros->fechaInicio, $filtros->fechaFin]
                );
            }

        }elseif(isset($filtros->cliente) && isset($filtros->tipoPresentacion)){
            
            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                    FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id 
                    AND ped.CodCliente = ?', 
                    [$filtros->cliente]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.codigo, prod.nombre as NombreProd, sol.cantidadSolicitada, sol.unidadMedida,
                    cl.razonSocial as NombreCl, ped.id FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.codProducto = prod.codigo 
                    INNER JOIN pedido as ped ON ped.id = sol.codPedido 
                    INNER JOIN cliente as cl ON cl.id = ped.codCliente WHERE 
                    cl.id = ? AND ped.estado = 1  ', 
                    [$filtros->cliente]
                );
            }


        }elseif(isset($filtros->fechaInicio)){

            $datosFiltrados = DB::select(
                'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id 
                AND ped.fechaEntrega BETWEEN ? AND ? ', 
                [$filtros->fechaInicio, $filtros->fechaFin]
            );

        }elseif(isset($filtros->cliente)){

            $datosFiltrados = DB::select(
                'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id 
                AND ped.codCliente = ?', 
                [$filtros->cliente]
            );

        }elseif(isset($filtros->tipoPresentacion)){

            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.id, ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial, ped.estado
                    FROM pedido as ped, cliente as cl WHERE ped.estado = 1 AND ped.codCliente = cl.id ', 
                    []
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.codigo, prod.nombre as NombreProd, sol.cantidadSolicitada, sol.unidadMedida,
                    cl.razonSocial as NombreCl, ped.id FROM producto as prod 
                    INNER JOIN solicitud as sol ON sol.codProducto = prod.codigo 
                    INNER JOIN pedido as ped ON ped.id = sol.codPedido 
                    INNER JOIN cliente as cl ON cl.id = ped.codCliente WHERE
                    ped.estado = 1 ', 
                    []
                );
            }
        }

        if(Count($datosFiltrados) == 0){
            $datosFiltrados = "no hay resultados";
        }

        return json_encode($datosFiltrados);
    }

    public function GenerarCSV(Request $request){

        $pedido = new Pedido();

        $pedido->consecutivo = request('consecutivo');

        //echo $pedido->consecutivo;
        $salida = fopen('php://output', 'w');

        $resultados = DB::select('SELECT ped.id, ped.codCliente, 
                                    ped.fechaSolicitud, ped.fechaEntrega, cl.razonSocial FROM
                                    pedido AS ped, cliente AS cl WHERE 
                                    ped.id = ? AND ped.codCliente = cl.id', 
                                    [$pedido->consecutivo]);

        $separador = ";";

        header('Content-Type:text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Reporte_Fechas_Ingreso.csv"');

        fputcsv($salida, array('Consecutivo', 'Cliente', 'FechaSolicitud', 'FechaEntrega'), $separador);

        foreach ($resultados as $key) {
            fputcsv($salida, array($key->id,
                                    $key->razonSocial,
                                    $key->fechaSolicitud,
                                    $key->fechaEntrega), $separador);
        }

    }

    public function GenerarPDF($consecutivo){
        
        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadHTML($this->loadTable($consecutivo));

        return $pdf->stream();
    }

    function loadTable($consecutivo){

        $DetallePedido = DB::select(
                                    'SELECT ped.fechaSolicitud, ped.fechaEntrega, cliente.razonSocial
                                    FROM pedido as ped, cliente WHERE cliente.id = ped.codCliente 
                                    AND ped.id = ?', [$consecutivo]);

        $DescripcionPedido = DB::select(
                                    'SELECT sol.codProducto, prod.nombre, sol.cantidadSolicitada,
                                    sol.cantidadDespachada, sol.unidadMedida FROM solicitud as sol,
                                    producto as prod WHERE sol.codProducto = prod.codigo 
                                    AND sol.codPedido = ? ', [$consecutivo]);

        $TotalesPedidos = DB::select(
                                    'SELECT COUNT(solicitud.codProducto) as sumSolicitud, 
                                    SUM(solicitud.cantidadSolicitada) as sumCantSol,
                                    SUM(solicitud.cantidadDespachada) as sumCantDes
                                    FROM solicitud WHERE solicitud.codPedido = ?', [$consecutivo]);

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
                    <p>Cliente: <b> '.strtoupper($DetallePedido[0]->razonSocial).'</b></p>
                </span>
            
            <p>Código Pedido: <b>'.$consecutivo.'</b></p>
            <p>Fecha de la solicitud: <b>'.$DetallePedido[0]->fechaSolicitud.'</b></p>
            <p>Fecha de la entrega: <b>'.$DetallePedido[0]->fechaEntrega.'</b></p>
    
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
                        <td>'.$itemPedido->codProducto.'</td>
                        <td class="productName">'.$itemPedido->nombre.'</td>
                        <td>'.$itemPedido->cantidadSolicitada.'</td>
                        <td>'.$itemPedido->cantidadDespachada.'</td>
                        <td>'.$itemPedido->unidadMedida.'</td>
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

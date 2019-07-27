<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\DateTime;
use Illuminate\Http\Request;
use App\Cliente;
use App\Pedido;
use App\Solicitud;
use DB;
use PDF;
use DateTime;

class HistorialController extends Controller
{
    public function FiltrarHistorial(Request $request){

        header("Content-Type: application/json");
        $filtros = json_decode(stripslashes(file_get_contents("php://input")));
        // build a PHP variable from JSON sent using GET method
        $filtros = json_decode(stripslashes($request->data));
        // encode the PHP variable to JSON and send it back on client-side
        $datosFiltrados = "nada";

        if(isset($filtros->fechaInicioH) && isset($filtros->codCliente) && isset($filtros->tipoPresentacion)){

            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit 
                    AND ped.CodCliente = ? AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                    [$filtros->codCliente, $filtros->fechaInicioH, $filtros->fechaFin]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE 
                    cl.Nit = ? AND ped.FechaSolicitud BETWEEN ? AND ? AND ped.Estado = 2 ', 
                    [$filtros->codCliente, $filtros->fechaInicioH, $filtros->fechaFin]
                );
            }

        }elseif(isset($filtros->fechaInicioH) && isset($filtros->codCliente)){

            $datosFiltrados = DB::select(
                'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit 
                AND ped.CodCliente = ? AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                [$filtros->codCliente, $filtros->fechaInicioH, $filtros->fechaFin]
            );

        }elseif(isset($filtros->fechaInicioH) && isset($filtros->tipoPresentacion)){

            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit 
                    AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                    [$filtros->fechaInicioH, $filtros->fechaFin]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE
                    ped.FechaSolicitud BETWEEN ? AND ? AND ped.Estado = 2 ', 
                    [$filtros->fechaInicioH, $filtros->fechaFin]
                );
            }

        }elseif(isset($filtros->codCliente) && isset($filtros->tipoPresentacion)){
            
            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit 
                    AND ped.CodCliente = ?', 
                    [$filtros->codCliente]
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE 
                    cl.Nit = ? AND ped.Estado = 2  ', 
                    [$filtros->codCliente]
                );
            }


        }elseif(isset($filtros->fechaInicioH)){

            $datosFiltrados = DB::select(
                'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit 
                AND ped.FechaSolicitud BETWEEN ? AND ? ', 
                [$filtros->fechaInicioH, $filtros->fechaFin]
            );

        }elseif(isset($filtros->codCliente)){

            $datosFiltrados = DB::select(
                'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit 
                AND ped.CodCliente = ?', 
                [$filtros->codCliente]
            );

        }elseif(isset($filtros->tipoPresentacion)){

            //Consulta ejecutada normalmente
            if($filtros->tipoPresentacion == 1){
                $datosFiltrados = DB::select(
                    'SELECT ped.Consecutivo, ped.FechaSolicitud, ped.FechaEntrega, cl.Nombre, ped.Estado
                    FROM pedido as ped, cliente as cl WHERE ped.Estado = 2 AND ped.CodCliente = cl.Nit ', 
                    []
                );
            }else{
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod 
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE
                    ped.Estado = 2 ', 
                    []
                );
            }
        }

        return json_encode($datosFiltrados);
        //return json_encode($filtros);
        /*$cliente = $filtros->codCliente;
        return $cliente;*/
    }

    public function CSVGeneral(Request $request){

        if(request('fechaInicioH')){
            $fechaInicioH = request('fechaInicioH');
            $fechaFin = request('fechaFin');
        }

        if(request('codCliente')){
            $codCliente = request('codCliente');
        }

        $datosFiltrados = "nada";

        if(isset($fechaInicioH) && isset($codCliente)){

                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE 
                    cl.Nit = ? AND ped.FechaSolicitud BETWEEN ? AND ? AND ped.Estado = 2 ', 
                    [$codCliente, $fechaInicioH, $fechaFin]
                );

        }elseif(isset($fechaInicioH)){

                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE
                    ped.FechaSolicitud BETWEEN ? AND ? AND ped.Estado = 2 ', 
                    [$fechaInicioH, $fechaFin]
                );

        }elseif(isset($codCliente)){
            
                $datosFiltrados = DB::select(
                    'SELECT prod.Codigo, prod.Nombre as NombreProd, sol.CantidadSolicitada, sol.CantidadDespachada,
                    sol.UnidadMedida, cl.Nombre as NombreCl, ped.Consecutivo FROM producto as prod
                    INNER JOIN solicitud as sol ON sol.CodProducto = prod.Codigo 
                    INNER JOIN pedido as ped ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente WHERE 
                    cl.Nit = ? AND ped.Estado = 2  ', 
                    [$codCliente]
                );
        }

            $salida = fopen('php://output', 'w');

            $separador = ";";

            header('Content-Type:text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="Reporte_Fechas_Ingreso.csv"');

            foreach ($datosFiltrados as $key) {
                fputcsv($salida, array($key->Codigo,
                                        $key->NombreProd,
                                        $key->CantidadSolicitada,
                                        $key->CantidadDespachada,
                                        $key->UnidadMedida,
                                        $key->NombreCl,
                                        $key->Consecutivo), $separador);
            }

        
    }

    public function PDFGeneral(Request $request){

        if(request('fechaInicioH')){
            $fechaInicioH = request('fechaInicioH');
            $fechaFin = request('fechaFin');
        }

        if(request('codCliente')){
            $codCliente = request('codCliente');
        }

        //datosFiltrados = "nada";

        if(isset($fechaInicioH) && isset($codCliente)){

            $pdf = \App::make('dompdf.wrapper');

            $pdf->loadHTML($this->LoadTableGeneral($fechaInicioH, $fechaFin, $codCliente, 0));
    
            return $pdf->stream();

        }elseif(isset($fechaInicioH)){

            $pdf = \App::make('dompdf.wrapper');

            $pdf->loadHTML($this->LoadTableGeneral($fechaInicioH, $fechaFin, null, 1));
    
            return $pdf->stream();

        }elseif(isset($codCliente)){
            
            $pdf = \App::make('dompdf.wrapper');

            $pdf->loadHTML($this->LoadTableGeneral(null, null, $codCliente, 2));
    
            return $pdf->stream();
        }
    }

    public function LoadTableGeneral($fechaInicioH, $fechaFin, $codCliente, $tipoPDF){

        //Con código cliente y rango de fechas
        if($tipoPDF == 0){
            $DetallePedido = DB::select(
                'SELECT Nombre FROM cliente WHERE Nit = ?', [$codCliente]);
    
            $DescripcionPedido = DB::select(
                        'SELECT sol.CodPedido, prod.Nombre, sol.CantidadSolicitada,
                        sol.CantidadDespachada, sol.UnidadMedida, ped.FechaSolicitud, 
                        ped.FechaEntrega FROM pedido as ped 
                        INNER JOIN solicitud as sol ON ped.Consecutivo = sol.CodPedido 
                        INNER JOIN producto as prod ON prod.Codigo = sol.CodProducto 
                        WHERE ped.CodCliente = ? AND ped.FechaSolicitud 
                        BETWEEN ? AND ? AND ped.Estado = 2 ORDER BY (ped.Consecutivo)', [$codCliente, $fechaInicioH, $fechaFin]);
    
            $TotalesPedidos = DB::select(
                        'SELECT COUNT(solicitud.CodProducto) as sumSolicitud, 
                        SUM(solicitud.CantidadSolicitada) as sumCantSol,
                        SUM(solicitud.CantidadDespachada) as sumCantDes
                        FROM solicitud, pedido WHERE pedido.CodCliente = ?
                        AND pedido.FechaSolicitud BETWEEN ? AND ?
                        AND pedido.Estado = 2', [$codCliente, $fechaInicioH, $fechaFin]);
    
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

            .centrar{
                text-align:center;
            }
            </style>
    
            <img src="svg/logo.png" width="250px" height="200px" class="img">
            <span class="tittle">
            <h1>Informe sobre Pedidos del desposte</h1>
            <p>-Cliente: <b> '.strtoupper($DetallePedido[0]->Nombre).'</b></p>
            <p>-Desde: <b>'.$fechaInicioH.'</b> // Hasta: <b>'.$fechaFin.'</b></p>
            </span>

            <p>
            ----------------------------------------------------------------------------------------------------------------------------------------
            </p>
            <h4>Detalle pedidos discriminados:</h4>
    
            <table class="tableContent">
            <thead>
                <tr>
                <th>Cons. Ped.</th>
                <th>Nombre Producto</th>
                <th>Cant.Sol</th>
                <th>Cant.Des</th>
                <th>U/M</th>
                <th>F. Solicitud</th>
                <th>F. Entrega</th>
                </tr>
            </thead>
            <tbody>';
    
            foreach($DescripcionPedido as $itemPedido){

                $fecha = date_format(new DateTime($itemPedido->FechaSolicitud),'d/m/Y');
                $fecha2 = date_format(new DateTime($itemPedido->FechaEntrega),'d/m/Y');

            $output .= '
            <tr>
            <td class="centrar">'.$itemPedido->CodPedido.'</td>
            <td class="productName">'.$itemPedido->Nombre.'</td>
            <td>'.$itemPedido->CantidadSolicitada.'</td>
            <td>'.$itemPedido->CantidadDespachada.'</td>
            <td>'.$itemPedido->UnidadMedida.'</td>
            <td>'.$fecha.'</td>
            <td>'.$fecha2.'</td>
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

        //Sólo con un rango de fechas
        }else if($tipoPDF == 1){
    
            $DescripcionPedido = DB::select(
                'SELECT sol.CodPedido, cl.Nombre AS NombreCliente, prod.Nombre AS NombreProducto, 
                    sol.CantidadSolicitada, sol.CantidadDespachada, sol.UnidadMedida, ped.FechaSolicitud, 
                    ped.FechaEntrega FROM pedido as ped 
                    INNER JOIN solicitud as sol ON ped.Consecutivo = sol.CodPedido 
                    INNER JOIN producto as prod ON prod.Codigo = sol.CodProducto 
                    INNER JOIN cliente as cl ON cl.Nit = ped.CodCliente 
                    WHERE ped.FechaSolicitud BETWEEN ? AND ? 
                    AND ped.Estado = 2 ORDER BY (ped.Consecutivo)', [$fechaInicioH, $fechaFin]);
    
            $TotalesPedidos = DB::select(
                            'SELECT COUNT(solicitud.id) as sumSolicitud, 
                            SUM(solicitud.CantidadSolicitada) as sumCantSol, 
                            SUM(solicitud.CantidadDespachada) as sumCantDes 
                            FROM solicitud, pedido WHERE pedido.FechaSolicitud 
                            BETWEEN ? AND ? AND pedido.Estado = 2 
                            AND pedido.Consecutivo = solicitud.CodPedido', [$fechaInicioH, $fechaFin]);
    
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

            .centrar{
                text-align:center;
            }
            </style>
    
            <img src="svg/logo.png" width="250px" height="170px" class="img">
            <span class="tittle">
            <h1>Informe sobre Pedidos del desposte</h1>
            <p>Desde: <b>'.$fechaInicioH.'</b> // Hasta: <b>'.$fechaFin.'</b></p>
            </span>

            <p>
            ----------------------------------------------------------------------------------------------------------------------------------------
            </p>

            <h4>Detalle pedidos discriminados:</h4>
    
            <table class="tableContent">
            <thead>
            <tr>
                <th class="centrar">Cons. Ped.</th>
                <th class="centrar">Nombre Cliente</th>
                <th class="centrar">Nombre Producto</th>
                <th class="centrar">Cant.Sol</th>
                <th class="centrar">Cant.Des</th>
                <th class="centrar">U/M</th>
                <th class="centrar">F. Solicitud</th>
                <th class="centrar">F. Entrega</th>
            </tr>
            </thead>
            <tbody>';
    
            foreach($DescripcionPedido as $itemPedido){
                
                $fecha = date_format(new DateTime($itemPedido->FechaSolicitud),'d/m/Y');
                $fecha2 = date_format(new DateTime($itemPedido->FechaEntrega),'d/m/Y');

            $output .= '
            <tr>
                <td class="centrar">'.$itemPedido->CodPedido.'</td>
                <td class="productName">'.$itemPedido->NombreCliente.'</td>
                <td class="productName">'.$itemPedido->NombreProducto.'</td>
                <td>'.$itemPedido->CantidadSolicitada.'</td>
                <td>'.$itemPedido->CantidadDespachada.'</td>
                <td>'.$itemPedido->UnidadMedida.'</td>
                <td>'.$fecha.'</td>
                <td>'.$fecha2.'</td>
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

        //Solamente con el código del cliente
        }else if($tipoPDF == 2){

            $DetallePedido = DB::select(
                'SELECT Nombre FROM cliente WHERE Nit = ?', [$codCliente]);

            $DescripcionPedido = DB::select(
                        'SELECT sol.CodPedido, prod.Nombre, sol.CantidadSolicitada,
                        sol.CantidadDespachada, sol.UnidadMedida, ped.FechaSolicitud, 
                        ped.FechaEntrega FROM pedido as ped 
                        INNER JOIN solicitud as sol ON ped.Consecutivo = sol.CodPedido 
                        INNER JOIN producto as prod ON prod.Codigo = sol.CodProducto 
                        WHERE ped.CodCliente = ? AND ped.Estado = 2 
                        ORDER BY (ped.Consecutivo)', [$codCliente]);
    
            $TotalesPedidos = DB::select(
                        'SELECT COUNT(solicitud.CodProducto) as sumSolicitud,
                        SUM(solicitud.CantidadSolicitada) as sumCantSol, 
                        SUM(solicitud.CantidadDespachada) as sumCantDes FROM solicitud, pedido 
                        WHERE pedido.CodCliente = ? AND pedido.Estado = 2 
                        AND solicitud.CodPedido = pedido.Consecutivo', [$codCliente]);
    
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

            .centrar{
                text-align:center;
            }
            </style>
    
            <img src="svg/logo.png" width="250px" height="170px" class="img">
            <span class="tittle">
            <h1>Informe sobre Pedidos del desposte</h1>
            <p>Cliente: <b> '.strtoupper($DetallePedido[0]->Nombre).'</b></p>
            </span>
               
            <p>
            ----------------------------------------------------------------------------------------------------------------------------------------
            </p>

            <h4>Detalle pedidos discriminados:</h4>

            <table class="tableContent">
            <thead>
            <tr>
                <th class="centrar">Cons.Ped.</th>
                <th class="centrar">Nombre Producto</th>
                <th class="centrar">Cant.Sol</th>
                <th class="centrar">Cant.Des</th>
                <th class="centrar">U/M</th>
                <th class="centrar">F. Solicitud</th>
                <th class="centrar">F. Entrega</th>
            </tr>
            </thead>
            <tbody>';
    
            foreach($DescripcionPedido as $itemPedido){
            
                $fecha = date_format(new DateTime($itemPedido->FechaSolicitud),'d/m/Y');
                $fecha2 = date_format(new DateTime($itemPedido->FechaEntrega),'d/m/Y');

                $output .= '
                <tr>
                <td class="centrar">'.$itemPedido->CodPedido.'</td>
                <td class="productName">'.$itemPedido->Nombre.'</td>
                <td>'.$itemPedido->CantidadSolicitada.'</td>
                <td>'.$itemPedido->CantidadDespachada.'</td>
                <td>'.$itemPedido->UnidadMedida.'</td>
                <td>'.$fecha.'</td>
                <td>'.$fecha2.'</td>
                </tr>
                ';
            }
    
            $output .= '
            </tbody>
            </table>

            <p>
            ----------------------------------------------------------------------------------------------------------------------------------------
            </p>
    
            <span class="right">
            <p>Cantidad total de productos: <b>'.$TotalesPedidos[0]->sumSolicitud.'</b></p>
            <p>Total unidades solicitadas: <b>'.$TotalesPedidos[0]->sumCantSol.'</b></p>
            <p>Total unidades despachadas: <b>'.$TotalesPedidos[0]->sumCantDes.'</b></p>
            </span>';
        }

        return $output;
    }
}

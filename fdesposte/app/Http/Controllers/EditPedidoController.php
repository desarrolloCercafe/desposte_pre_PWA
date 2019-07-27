<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cliente;
use App\Pedido;
use App\Solicitud;
use App\Producto;

class EditPedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($ConsecutivoPedido)
    {
        $productos = Producto::all();
        $pedidos = DB::select('SELECT ped.FechaEntrega, cl.Nombre, ped.CodCliente, ped.FechaSolicitud
                                FROM pedido AS ped, cliente AS cl 
                                WHERE ped.Consecutivo = ? AND ped.CodCliente = cl.Nit', 
                                [$ConsecutivoPedido]
                            );
        $clientes = DB::select('SELECT cliente.Nombre, cliente.Nit 
                                FROM cliente 
                                WHERE cliente.Nit != ? ', [$pedidos[0]->CodCliente]);
        //$pedidoProductos = DB::select('select * from users where active = ?', [1])

        $productosPedidos = DB::select('SELECT prod.Codigo, prod.Nombre, sol.CantidadSolicitada,
                                        sol.CantidadDespachada, sol.UnidadMedida 
                                        FROM producto as prod, solicitud as sol, pedido as ped 
                                        WHERE ped.Consecutivo = sol.CodPedido 
                                        AND sol.CodProducto = prod.Codigo AND ped.Consecutivo = ?', 
                                        [$ConsecutivoPedido]);

        return view('consulta.editPedido', compact('ConsecutivoPedido', 'productos', 'pedidos','clientes', 'productosPedidos'));
    }

    public function UpdatePedido(Request $request){
        
        $pedido = new Pedido();
        
        $pedido->consecutivo = request('consecutivoPedido');
        $pedido->fechaSolicitud = request('fechaSolicitud');
        $pedido->productos = json_decode(request('productosPedido'));

        $productos = $pedido->productos;

        $cantidadCiclos = Count($pedido->productos);

        DB::delete('DELETE FROM solicitud WHERE CodPedido = ?', [$pedido->consecutivo]);

        for ($i=0; $i < $cantidadCiclos; $i++) { 
            DB::insert(
                'INSERT INTO solicitud (CodPedido, CodProducto, CantidadSolicitada,
                CantidadDespachada, UnidadMedida) VALUES (?, ?, ?, ?, ?)', 
                [$pedido->consecutivo, $productos[$i]->consecutivoProducto,
                    $productos[$i]->cantidadSolicitada, $productos[$i]->cantidadDespachada, 
                    $productos[$i]->unidad
                ]);
        }

        return redirect('/consulta');

    }
}

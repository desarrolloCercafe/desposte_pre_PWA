@extends('layout.layout')

@section('content')


<!--Modal para la agregación de productos-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2>Selecciona El producto que deseas añadir:</h2>
                        <select name="producto" id="selectProducto" class="custom-select d-block w-100">
                                <option selected value="0" disabled>Seleccionar Producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{$producto->Codigo}}">{{$producto->Nombre}}</option>
                                @endforeach
                        </select>
                </div>
                <div id="form"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cerrarAgregarProductoEditar">Cerrar</button>
                    <button type="button" class="btn btn-success" id="addProductoEdit" >Agregar</button>
                </div>
            </div>
        </div>
    </div>

<!--Interfaz general del sistema-->
<h5 class="card-header" id="head">Editando el pedido: PD{{$ConsecutivoPedido}}</h5>
<input type="hidden" id="ConsecutivoPedido" value="{{$ConsecutivoPedido}}">
<input type="hidden" value="{{$pedidos[0]->fechaSolicitud}}" id="FechaSolicitud">
<div class="card-body">
    <div class="row">
        <div class="col-md-3 mb-3">
                <select name="codCliente" id="selectCliente" class="custom-select d-block w-100">
                    <option selected value="0" disabled>{{$pedidos[0]->razonSocial}}</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{$cliente->id}}">{{$cliente->razonSocial}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-md-3 mb-3">
            <input type="date" class="form-control" id="fechaEntrega" value="{{$pedidos[0]->fechaEntrega}}">
        </div>

        <div class="col-md-3 mb-3">
            <button type="button" class="btn btn-success" data-toggle="modal"
            data-target="#exampleModal" id="addDesplegable" ><b>+</b>Agregar Producto</button>
        </div>
    </div>
</div>
<div class="table-responsive-xl">
    <table class="table table-bordered table-hover text-center" id="borde" >
        <thead class="thead-dark" id="color">
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Producto</th>
                <th scope="col">Cantidad Sol</th>
                <th scope="col">Cantidad Des</th>
                <th scope="col">Unidad</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
            <tbody id="tbodyEditProductos">
                <?php $i = 0; ?>
                @foreach ($productosPedidos as $productoPedido)
                    <tr>
                        <th scope="row"><p id="codigo<?php echo $i; ?>">{{$productoPedido->codigo}}</p></th>
                        <td><p id="nombre<?php echo $i; ?>">{{$productoPedido->nombre}}</p></td>
                        <td>
                            <input type="number" name="CantidadSolicitada" value="{{$productoPedido->cantidadSolicitada}}" class="form-control" min="0" id="CantidadSolicitada<?php echo $i; ?>">
                        </td>
                        <td>
                            <input type="number" name="CantidadDespachada" value="{{$productoPedido->cantidadDespachada}}" class="form-control" min="0" id="CantidadDespachada<?php echo $i; ?>">
                        </td>
                        <td class="text-left">
                                @if ($productoPedido->unidadMedida == "kg")
                                    <input type="radio" name="unidad<?php echo $i; ?>" value="kg" class="mr-2" checked><span>KG</span>
                                    <br>
                                    <input type="radio" name="unidad<?php echo $i; ?>" value="un" class="mr-2"><span>UN</span>
                                @else
                                    <input type="radio" name="unidad<?php echo $i; ?>" value="kg" class="mr-2"><span>KG</span>
                                    <br>
                                    <input type="radio" name="unidad<?php echo $i; ?>" value="un" class="mr-2" checked><span>UN</span>
                                @endif
                        </td>
                        <td><button type="button" class="btn btn-danger" value="<?php echo $i; ?>" onclick="Eliminar(this)">Eliminar</button></td>
                    </tr>
                    <?php $i++; ?>
                @endforeach
            </tbody>
    </table>
</div>

<div class="float-sm-right" id="PieDePagina">
    <form action="{{route('edit.updatePedido')}}" method="post" id="formUpdatePedido">
        @csrf

    </form>
    <button type="button" class="btn btn-warning float-sm-right mr-2 mb-2 ml-2" id="botonEditarPedido"><b>Editar Pedido</b></button>
</div>


<!--Modal de la eliminación de un producto-->
<div class="modal fade" id="ModalDeleteEditPedido" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" onclick="DropInputDeleteModal()">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="DropInputDeleteModal()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="bodyDeleteEditPedido">
          <h2>¿Deseas eliminar el producto?</h2>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrarDelete" onclick="DropInputDeleteModal()">Cancelar</button>
            <button type="button" class="btn btn-danger" id="deleteButtonEditPedido">Eliminar</button>
        </div>
      </div>
    </div>
</div>

<!--Modal de notificaciones sobre errores en las validaciones-->
<div class="modal fade" id="ValidationModal" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Error en el pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="bodyDelete">
          <h2 id="MessageValidation"></h2>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="BtnNotificacion" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
</div>

<script src="{{asset('js/editarPedido.js')}}"></script>
<script src="{{asset('js/ConsultaAjax.js')}}"></script>

@endsection
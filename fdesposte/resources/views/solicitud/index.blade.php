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
                        <input type="text" class="form-control" placeholder="Seleccionar Producto" list="datalistProductos" id="selectProducto">
                        <datalist id="datalistProductos">
                            @foreach ($productos as $producto)
                                <option value="{{$producto->codigo}},{{$producto->nombre}}">{{$producto->nombre}}</option>
                            @endforeach
                        </datalist>
                </div>
                <div id="form">
                    <form action="{{route('solicitud.store')}}" method="post" id="formInsert">
                        @csrf
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cerrar">Cerrar</button>
                    <button type="button" class="btn btn-success" id="addProducto" >Agregar</button>
                </div>
            </div>
        </div>
    </div>

<!--Interfaz general del sistema-->
<h5 class="card-header" id="head"> Nuevo Pedido de Desposte</h5>
<div class="card-body">
    <div class="row">

        <div class="col-md-3 mb-3" id="contentInput">
            <input type="text" class="form-control" placeholder="Seleccionar cliente" list="datalistClientes" id="selectCliente">


            <datalist id="datalistClientes">
                @foreach ($clientes as $item)
                    <option value="{{$item->razonSocial}}">{{$item->razonSocial}}</option>
                @endforeach
            </datalist>

        </div>

        <div class="col-md-3 mb-3">
            <input type="date" class="form-control" id="fechaEntrega">
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
                <th scope="col">Cantidad</th>
                <th scope="col">Unidad</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
            <tbody id="tbodyProductos">
            </tbody>
    </table>
</div>

<div class="float-sm-right" id="PieDePagina">
    <button type="button" class="btn btn-warning float-sm-right mr-2 mb-2 ml-2" id="botonGenerarPedido"><b>Generar Pedido</b></button>

</div>


<!--Modal de la eliminación de un producto-->
<div class="modal fade" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="bodyDelete">
          <h2>¿Deseas eliminar el producto?</h2>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cerrarDelete">Cancelar</button>
            <button type="submit" class="btn btn-danger" id="deleteButton">Eliminar</button>
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

    <script src="{{asset('js/script.js')}}"></script>
    <script src="{{asset('js/snippet.js')}}"></script>
@endsection
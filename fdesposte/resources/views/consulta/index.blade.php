@extends('layout.layout')

@section('content')
            <div id="headRow">
                    <article class="headerCard">
                    <img src="{{asset('svg/historial.png')}}" class="IconoAlusivoPedido">
                        <h5 id="head">Pedidos de Desposte</h5>
                    </article>
                    <button type="button" id="botonHistorial" data-toggle="modal" data-target=".bd-example-modal-xl">
                        <img src="{{asset('svg/historial.png')}}" class="iconoHistorial">
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-1">
                            <input type="date" class="form-control" id="fechaInicio">
                        </div>
    
                        <div class="col-md-2 mb-3">
                            <input type="date" class="form-control" id="fechaFinal">
                        </div>
    
                        <div class="col-md-2 mb-3">
                            <!--<select class="custom-select d-block w-100" name="codCliente" id="codCliente">
                                <option selected value="0" >Seleccionar cliente</option>
                                @foreach ($clientes as $item)
                                    <option value="{{$item->Nit}}">{{$item->Nombre}}</option>
                                @endforeach
                            </select>-->
                            <input type="text" id="codCliente" name="codCliente" list="dataCliente" class="form-control" placeholder="Seleccionar cliente">

                            <datalist id="dataCliente">
                                <option selected value="Seleccionar cliente">Seleccionar cliente</option>
                                @foreach ($clientes as $item)
                                    <option value="{{$item->Nombre}}">{{$item->Nombre}}</option>
                                @endforeach
                            </datalist>

                            <datalist id="dataCliente2">
                                    <option selected value="0" >Seleccionar cliente</option>
                                    @foreach ($clientes as $item)
                                        <option value="{{$item->Nombre}}">{{$item->Nit}}</option>
                                    @endforeach
                            </datalist>
                        </div>
    
    
                        <div class="col-md-2 mb-3">

                            <select class="custom-select d-block w-100" id="tipoPresentacion">
                                <option selected value="0">Filtrar por:</option>
                                <option value="1">Pedido</option>
                                <option value="2">Producto</option>
                            </select>
                        </div>
    
                        <div class="col-md-2 mb-3">
                            <button type="button" class="btn btn-success" id="filtrarPedidos">Buscar</button>
                        </div>
                    </div>
                </div>
    
                <div class="table-responsive-xl">
                    <table class="table table-bordered table-hover text-center generarBorde" id="generarBorde">

                        <thead class="thead-dark" id="CambiarColor">
                            <tr>
                                <th scope="col">Consecutivo</th>
                                <th scope="col">Fecha solicitud</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="BodyTablePedidos">
                            <?php $i = 0; ?>
                            @if ($pedidos[0] != null)                            
                                @foreach ($pedidos as $pedido)
                                    @if ($pedido->Estado == 1)
                                        <tr>
                                            <th scope="row">
                                                <button type="button" id="botonHistorial2<?php echo $i; ?>" data-toggle="modal"
                                            data-target=".bd-modal-example-xl" value="{{$pedido->Consecutivo}}" onclick="SendQuery(this)">
                                                    <p style="color: rgb(223, 1, 1);"><b>PD{{$pedido->Consecutivo}}</b></p>
                                                </button>
                                            </th>
                                            <td id="fecha<?php echo $i; ?>">{{$pedido->FechaSolicitud}}
                                            
                                                <script type="text/javascript">
                                                    var fecha = '<?php echo $pedido->FechaSolicitud; ?>';
                                                    var contador = '<?php echo $i; ?>';
                                                
                                                    var fechaCortada = fecha.substring(0, 10);
                                                
                                                    document.getElementById("fecha"+contador).innerHTML = fechaCortada;
                                                </script>
    
                                            </td>
                                            <td>{{$pedido->Nombre}}</td>
                                            <td>Por listar</td>
                                        </tr>
                                    
                                        <?php $i++; ?>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    
                    <div id="livesearch">
                        
                    </div>

                    <div class="d-flex justify-content-end mr-2" id="PaginacionConsulta">
                        {{$pedidos->links()}}
                    </div>
                        <button type="button" id="DesplegarErrorModal" class="btn btn-danger">
                                Error
                        </button>
                </div>

    <!--Modal de descripción del pedido Por alistar-->

    <div class="modal fade bd-modal-example-xl" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Confirmar Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                    <div class="table-responsive-xl">
                        <table class="table table-bordered table-hover text-center generarBorde" id="generarBorde">
                            <thead class="thead-dark" id="CambiarColor">
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Cantidad Solicitada</th>
                                    <th scope="col">Unidad de medida</th>
                                    <th scope="col">Cantidad Disponible</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProductos">

                            </tbody>
                        </table>
                    </div>
            </div>


            <div class="modal-footer">
                <form action="{{route('updateSolicitud')}}" method="post" id="updateData">
                    @csrf
                </form>
                <button type="button" class="btn btn-success" id="ProductoAlistado">Alistado</button>
            </div>
        </div>
    </div>
</div>


<!--Modal del historial de pedidos-->

<div class="modal fade bd-example-modal-xl" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Historial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 mb-1">
                        <input type="date" class="form-control" id="fechaInicioHistorial" >
                    </div>

                    <div class="col-md-2 mb-3">
                        <input type="date" class="form-control" id="fechaFinalHistorial">
                    </div>

                    <div class="col-md-3 mb-3">
                        <!--<select class="custom-select d-block w-100" name="codCliente" id="codClienteHistorial">
                            <option selected value="0" >Seleccionar cliente</option>
                            @foreach ($clientes as $item)
                                <option value="{{$item->Nit}}">{{$item->Nombre}}</option>
                            @endforeach
                        </select>-->

                        <input type="text" id="codClienteHistorial" name="codCliente" list="dataClientesHistorial" class="form-control" placeholder="Seleccionar cliente">

                        <datalist id="dataClientesHistorial">
                            <option value="Seleccionar cliente">Seleccionar cliente</option>
                            @foreach ($clientes as $item)
                                <option value="{{$item->Nombre}}">{{$item->Nombre}}</option>
                            @endforeach
                        </datalist>

                        <datalist id="dataClientesHistorial2">
                                <option value="Seleccionar cliente">Seleccionar cliente</option>
                                @foreach ($clientes as $item)
                                    <option value="{{$item->Nombre}}">{{$item->Nit}}</option>
                                @endforeach
                            </datalist>
                    </div>


                    <div class="col-md-2 mb-3">

                        <select class="custom-select d-block w-100" id="tipoPresentacionHistorial">
                            <option selected value="0">Filtrar por:</option>
                            <option value="1">Pedido</option>
                            <option value="2">Producto</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <button type="button" class="btn btn-success" id="filtrarPedidosHistorial">Buscar</button>
                    </div>
                </div>

                    <div class="table-responsive-xl">
                        <table class="table table-bordered table-hover text-center generarBorde" id="HistorialTable">
                            <thead class="thead-dark" id="CambiarColor">
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Fecha solicitud</th>
                                    <th scope="col">Fecha Entrega</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tBodyHistorial">
                                @if ($pedidos2[0] != null)
                                        @foreach ($pedidos2 as $pedido2)
                                        @if ($pedido2->Estado == 2)
                                            <tr>
                                                <th scope="row">
                                                    <button type="button" id="botonHistorial2<?php echo $i; ?>" data-toggle="modal"
                                                    data-target="#descipcionHistorial" value="{{$pedido2->Consecutivo}}" onclick="ShowInfoHistorial(this)">
                                                        <p style="color: rgb(223, 1, 1)"><b>PD{{$pedido2->Consecutivo}}</b></p>
                                                    </button>
                                                </th>
                                                <td id="fecha<?php echo $i; ?>">{{$pedido2->FechaSolicitud}}
                                                
                                                    <script type="text/javascript">
                                                        var fecha = '<?php echo $pedido2->FechaSolicitud; ?>';
                                                        var contador = '<?php echo $i; ?>';
                                                    
                                                        var fechaCortada = fecha.substring(0, 10);
                                                    
                                                        document.getElementById("fecha"+contador).innerHTML = fechaCortada;
                                                    </script>

                                                </td>
                                                <td>{{$pedido2->FechaEntrega}}</td>
                                                <td>{{$pedido2->Nombre}}</td>
                                                <td>Alistado</td>
                                            
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                          Acciones
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <a class="dropdown-item" href="{{ url('/editPedido', ['ConsecutivoPedido' => $pedido2->Consecutivo]) }}">Editar pedido</a>
                                                            
                                                            <form action="{{route('GenerarCSV')}}" method="post">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="consecutivo" value="{{$pedido2->Consecutivo}}">
                                                                <input type="submit" class="dropdown-item" value="Generar CSV">
                                                            </form>
                                                            
                                                            <a class="dropdown-item" href="{{ url('/GenerarPDF', ['ConsecutivoPedido' => $pedido2->Consecutivo]) }}">Generar PDF</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        
                                            <?php $i++; ?>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-end mr-2" id="footerHistorial">
                            {{$pedidos2->links()}}
                        </div>

                        <div class="row d-flex justify-content-end mr-1">
                            <div class="ml-3 mb-2">

                                <form action="{{route('PDFGeneral')}}" method="post" id="pdfGeneral">
                                    {{ csrf_field() }}
                                </form>
                                <form action="{{route('CSVGeneral')}}" method="post" id="csvGeneral">
                                    {{ csrf_field() }}
                                </form>
                                <button type="button" class="btn btn-secondary btn-lg" id="GlobalGenerarPDF">Generar PDF</button>
                                <button type="button" class="btn btn-secondary btn-lg" id="GlobalGenerarCSV">Generar CSV</button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

    <!--Modal de descripción del pedido Alistado-->

<div class="modal fade bd-modal-example-xl" id="descipcionHistorial" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Detalles del pedido Historial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                    <div class="table-responsive-xl">
                        <table class="table table-bordered table-hover text-center" id="generarBorde">
                            <thead class="thead-dark" id="CambiarColor">
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Cantidad Solicitada</th>
                                    <th scope="col">Unidad de medida</th>
                                    <th scope="col">Cantidad Despachada</th>
                                </tr>
                            </thead>
                            <tbody id="tablaHistorial">

                            </tbody>
                        </table>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--Modal para errores-->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h2 id="textInfo"></h2>
                </div>
                <div id="form"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cerrar">Ok</button>
                </div>
            </div>
        </div>
    </div>

<script>


function SendQuery(obj){
    localStorage.setItem("PedidoSeleccionado", obj.value);
    getContent(obj);
}

function getContent(obj){
    
    var tableBody = document.getElementById('tablaProductos');

var searchValue = obj.value;

    const xhr = new XMLHttpRequest();
    xhr.open('GET','{{route('sendConsulta')}}/?search=' + searchValue ,true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function() {
        
        if(xhr.readyState == 4 && xhr.status == 200)
        {   
            tableBody.innerHTML = xhr.responseText;
        }
    }
    xhr.send();
}

function ShowInfoHistorial(obj){
    getData(obj);
}

function getData(obj){
    
    var tableBody = document.getElementById('tablaHistorial');

var searchValue = obj.value;

    const xhr = new XMLHttpRequest();
    xhr.open('GET','{{route('showProductHistorial')}}/?search=' + searchValue ,true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function() {
        
        if(xhr.readyState == 4 && xhr.status == 200)
        {   
            tableBody.innerHTML = xhr.responseText;
        }
    }
    xhr.send();
}



</script>

<script src="{{asset('js/scriptConsulta.js')}}"></script>
<script src="{{asset('js/ajaxConsulta.js')}}"></script>
<script src="{{asset('js/historial.js')}}"></script>

@endsection
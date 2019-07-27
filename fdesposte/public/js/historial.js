//Generamos el evento de escucha del boton
document.getElementById('filtrarPedidosHistorial').addEventListener('click', function () {
    if (verificarFiltros()) {
        var data = SaveData();
        SendQueryHistorialFiltrar(data);
    }
});

//Generamos el evento de escucha del boton
document.getElementById('GlobalGenerarCSV').addEventListener('click', function () {
    if (verificarFiltros()) {
        var data = SaveData();
        SendQueryHistorialCSV(data);
    }
});

//Generamos el evento de escucha del boton
document.getElementById('GlobalGenerarPDF').addEventListener('click', function () {
    if (verificarFiltros()) {
        var data = SaveData();
        SendQueryHistorialPDF(data);
    }
});

//Método de verificación de los filtros 
function verificarFiltros() {

    var fechaInicioH = "";
    var fechaFin = "";
    var codCliente = "";
    var tipoPresentacion = "";

    document.getElementById('footerHistorial').innerHTML = "";

    //console.log(document.getElementById('codClienteHistorial').value);

    var messageError = document.getElementById('textInfo');

    fechaInicioH = document.getElementById('fechaInicioHistorial');
    fechaFin = document.getElementById('fechaFinalHistorial');
    codCliente = document.getElementById('codClienteHistorial');
    tipoPresentacion = document.getElementById('tipoPresentacionHistorial');


    if (document.getElementById('fechaInicioHistorial').value.length == 0 &&
        document.getElementById('fechaFinalHistorial').value.length == 0 &&
        document.getElementById('codClienteHistorial').value == 0 &&
        document.getElementById('tipoPresentacionHistorial').value == 0) {

        messageError.innerHTML = "No haz seleccionado ningún tipo de filtro";
        document.getElementById('DesplegarErrorModal').click();
        return false;

    } else {
        return true;
    }

}

//Función para obtener los valores de los filtros y enviarlos al método SendQuery
function SaveData() {

    var fechaInicioH = document.getElementById('fechaInicioHistorial');
    var fechaFin = document.getElementById('fechaFinalHistorial');
    var codCliente = document.getElementById('codClienteHistorial');
    var tipoPresentacion = document.getElementById('tipoPresentacionHistorial');
    var messageError = document.getElementById('textInfo');

    //var array = [];
    var objeto;

    if (fechaInicioH.value.length == 0 && fechaFin.value.length == 0) {

        objeto = {
            codCliente: codCliente.value,
            tipoPresentacion: tipoPresentacion.value
        }

    } else if (fechaInicioH.value.length != 0 && fechaFin.value.length != 0) {

        objeto = {
            fechaInicioH: fechaInicioH.value,
            fechaFin: fechaFin.value,
            codCliente: codCliente.value,
            tipoPresentacion: tipoPresentacion.value
        }

    } else {
        messageError.innerHTML = "No haz especificado el rango de búsqueda";
        document.getElementById('DesplegarErrorModal').click();
    }

    if (objeto.codCliente == 0) {
        delete objeto.codCliente;
    }

    if (objeto.tipoPresentacion == 0) {
        delete objeto.tipoPresentacion;
    }

    //array.push(objeto);

    return objeto;

}

function SendQueryHistorialFiltrar(objeto) {

    if(objeto.hasOwnProperty('codCliente')){
        var data = document.querySelector('#dataCliente2 > option[value="'+objeto.codCliente+'"]');
        objeto.codCliente = data.innerHTML;
    }

    var xhr = new XMLHttpRequest();
    var url = "/filtrarHistorial?data=" + encodeURIComponent(JSON.stringify(objeto));
    xhr.open("GET", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            //var json = xhr.responseText;
            //console.log(Object.keys(json[0]).length);
            if (Object.keys(json[0]).length == 5) {
                TablePedidoHistorial(json);
            } else {
                TableProductoHistorial(json);
            }
        }
    };
    xhr.send();
}

//Función para lanzar los filtros obtenidos hacia laravel en forma oculta
function SendQueryHistorialCSV(objeto) {

    var form = document.getElementById('csvGeneral');

    if(objeto.hasOwnProperty('codCliente')){
        var data = document.querySelector('#dataCliente2 > option[value="'+objeto.codCliente+'"]');
        objeto.codCliente = data.innerHTML;
    }

    var contentForm = `
        ${objeto.hasOwnProperty('fechaInicioH') ?
            `<input type="hidden" name="fechaInicioH" value="${objeto.fechaInicioH}">
            <input type="hidden" name="fechaFin" value="${objeto.fechaFin}">` : ''
        }

        ${objeto.hasOwnProperty('codCliente') ?
            `<input type="hidden" name="codCliente" value="${objeto.codCliente}">` : ""
        }

        ${objeto.hasOwnProperty('tipoPresentacion') ?
            `<input type="hidden" name="tipoPresentacion" value="${objeto.tipoPresentacion}">` : ""
        }
    `;

    form.innerHTML += contentForm;

    form.submit();

    document.querySelectorAll("#csvGeneral > input").forEach(e => e.parentNode.removeChild(e));

}

//Función para lanzar los filtros obtenidos hacia laravel en forma oculta
function SendQueryHistorialPDF(objeto) {

    var form = document.getElementById('pdfGeneral');

    if(objeto.hasOwnProperty('codCliente')){
        var data = document.querySelector('#dataCliente2 > option[value="'+objeto.codCliente+'"]');
        objeto.codCliente = data.innerHTML;
    }

    var contentForm = `
        ${objeto.hasOwnProperty('fechaInicioH') ?
            `<input type="hidden" name="fechaInicioH" value="${objeto.fechaInicioH}">
            <input type="hidden" name="fechaFin" value="${objeto.fechaFin}">` : ''
        }

        ${objeto.hasOwnProperty('codCliente') ?
            `<input type="hidden" name="codCliente" value="${objeto.codCliente}">` : ""
        }

        ${objeto.hasOwnProperty('tipoPresentacion') ?
            `<input type="hidden" name="tipoPresentacion" value="${objeto.tipoPresentacion}">` : ""
        }
    `;

    form.innerHTML += contentForm;

    form.submit();

    document.querySelectorAll("#csvGeneral > input").forEach(e => e.parentNode.removeChild(e));

}

function TablePedidoHistorial(objeto) {

    var tableHistorial = document.getElementById('HistorialTable');
    tableHistorial.innerHTML = "";

    tableHeader = `
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
    <tbody id="tBodyHistorial">`;

    tableHistorial.innerHTML += tableHeader;
    var i = 0;

    objeto.forEach(function (item) {

        tableContentHistorial = `
    <tr>
        <th scope="row">
            <button type="button" id="botonHistorial2<?php echo $i; ?>" data-toggle="modal"
            data-target="#descipcionHistorial" value="${item.Consecutivo}" onclick="ShowInfoHistorial(this)">
                PD${item.Consecutivo}
            </button>
        </th>
        <td id="fecha${i}">${item.FechaSolicitud}

            <script type="text/javascript">
                var fecha = '<?php echo $pedido2->FechaSolicitud; ?>';
                var contador = '<?php echo $i; ?>';

                var fechaCortada = fecha.substring(0, 10);

                document.getElementById("fecha"+contador).innerHTML = fechaCortada;
            </script>

        </td>
        <td>${item.FechaEntrega}</td>
        <td>${item.Nombre}</td>
        <td>Alistado</td>

        <td>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="/editPedido/${item.Consecutivo}">Editar pedido</a>

                    <form action="{{route('GenerarCSV')}}" method="post">
                        <input type="hidden" name="consecutivo" value="${item.Consecutivo}">
                        <input type="submit" class="dropdown-item" value="Generar CSV">
                    </form>

                    <a class="dropdown-item" href="/GenerarPDF/${item.Consecutivo}">Generar PDF</a>
                </div>
            </div>
        </td>
    </tr>
    `;

        tableHistorial.innerHTML += tableContentHistorial;
        i++;

    });

    tableHistorial.innerHTML += `
        </tbody>
    </table>
`;

}

function TableProductoHistorial(objeto) {

    var tableHistorial = document.getElementById('HistorialTable');
    tableHistorial.innerHTML = "";

    tableHeader = `
    <thead class="thead-dark" id="CambiarColor">
        <tr>
            <th scope="col">Código Producto</th>
            <th scope="col">Nombre Producto</th>
            <th scope="col">Cant. Solicitada</th>
            <th scope="col">Cant. Despachada</th>
            <th scope="col">Unidad/Medida</th>
            <th scope="col">Nombre Cliente</th>
            <th scope="col">Consec. Pedido</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody id="tBodyHistorial">`;

    tableHistorial.innerHTML += tableHeader;
    var i = 0;

    objeto.forEach(function (item) {

        tableContentHistorial = `
        <tr>
            <th scope="row">${item.Codigo}</th>
            <td>${item.NombreProd}</td>
            <td>${item.CantidadSolicitada}</td>
            <td>${item.CantidadDespachada}</td>
            <td>${item.UnidadMedida}</td>
            <td>${item.NombreCl}</td>
            <td>${item.Consecutivo}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/editPedido/${item.Consecutivo}">Editar pedido</a>

                        <form action="{{route('GenerarCSV')}}" method="post">
                            <input type="hidden" name="consecutivo" value="${item.Consecutivo}">
                            <input type="submit" class="dropdown-item" value="Generar CSV">
                        </form>

                        <a class="dropdown-item" href="/GenerarPDF/${item.Consecutivo}">Generar PDF</a>

                    </div>
                </div>
            </td>
        </tr>
        `;

        tableHistorial.innerHTML += tableContentHistorial;
        i++;

    });

    tableHistorial.innerHTML += `
            </tbody>
        </table>
    `;

}
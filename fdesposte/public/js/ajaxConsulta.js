if(document.getElementById('filtrarPedidos')){

    document.getElementById('filtrarPedidos').addEventListener('click', function () {

        //document.getElementById('PaginacionConsulta').style.display = "none";
        validacionesFiltrar();
    
    });

}


function validacionesFiltrar() {

    document.getElementById('PaginacionConsulta').innerHTML = "";

    var fechaInicio = "";
    var fechaFinal = "";
    var presentacion = "";
    var cliente = "";


    var filtrar = document.getElementById('filtrarPedidos');
    filtrar.removeAttribute("data-toggle");
    filtrar.dataset.target = "";
    var ErrorMessage = document.getElementById('textInfo');


    if (document.getElementById("fechaInicio").value.length != 0 &&
        document.getElementById("fechaFinal").value.length != 0) {

        fechaInicio = document.getElementById("fechaInicio").value;
        fechaFinal = document.getElementById("fechaFinal").value;

        if (document.getElementById("codCliente").value != 0) {
            //cliente = document.getElementById("codCliente").value;
            var data = document.querySelector('#dataCliente2 > option[value="'+document.getElementById('codCliente').value+'"]');
            cliente = data.innerHTML;
        } else {
            cliente = 0;
        }

        if (document.getElementById("tipoPresentacion").value != 0) {
            presentacion = document.getElementById("tipoPresentacion").value;
        } else {
            presentacion = 0;
        }

        var objeto = {
            fechaInicio: fechaInicio,
            fechaFinal: fechaFinal,
            cliente: cliente,
            presentacion: presentacion
        };

        if (objeto.cliente == 0) {
            delete objeto.cliente;
        }

        if (objeto.presentacion == 0) {
            delete objeto.presentacion;
        }

        SaveFiltros(objeto);

        if (Object.keys(objeto).length == 0) {
            ErrorMessage.innerHTML = "No haz seleccionado ningún criterio de búsqueda";
            filtrar.setAttribute("data-toggle", "modal");
            filtrar.dataset.target = "#errorModal";
            filtrar.click();
        } else {
            SaveFiltros(objeto);
        }

    } else if (document.getElementById("fechaInicio").value.length == 0 &&
        document.getElementById("fechaFinal").value.length == 0) {

        if (document.getElementById("codCliente").value != 0) {
            var data = document.querySelector('#dataCliente2 > option[value="'+document.getElementById('codCliente').value+'"]');
            cliente = data.innerHTML;
        } else {
            cliente = 0;
        }

        if (document.getElementById("tipoPresentacion").value != 0) {
            presentacion = document.getElementById("tipoPresentacion").value;
        } else {
            presentacion = 0;
        }

        var objeto = {
            cliente: cliente,
            presentacion: presentacion
        };

        if (objeto.cliente == 0) {
            delete objeto.cliente;
        }

        if (objeto.presentacion == 0) {
            delete objeto.presentacion;
        }

        if (Object.keys(objeto).length == 0) {
            ErrorMessage.innerHTML = "No haz seleccionado ningún criterio de búsqueda";
            filtrar.setAttribute("data-toggle", "modal");
            filtrar.dataset.target = "#errorModal";
            filtrar.click();
        } else {
            SaveFiltros(objeto);
        }
        SaveFiltros(objeto);

    } else {
        ErrorMessage.innerHTML = "No especifico bien el rango de búsqueda";
        filtrar.setAttribute("data-toggle", "modal");
        filtrar.dataset.target = "#errorModal";
        filtrar.click();
    }

}


function SaveFiltros(objeto) {

    //http://192.168.9.139/julioCercafe/despostes/public

    var ajax = new XMLHttpRequest();
    ajax.open("GET","/filtrarTable?data="+ encodeURIComponent(JSON.stringify(objeto)),true);
    ajax.setRequestHeader("Content-Type", "application/json");
    ajax.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            var json = JSON.parse(ajax.responseText);
            //var json = xhr.responseText;
            //console.log(Object.keys(json[0]).length);
            if (Object.keys(json[0]).length == 4) {
                TablePedido(json);
            } else {
                TableProducto(json);
            }
        }
    }
    ajax.send();
}

function TableProducto(objeto) {

    //console.log(objeto);

    var table = document.getElementById('generarBorde');

    table.innerHTML = "";

    var thead = document.createElement("thead");
    thead.id = "CambiarColor";
    thead.className = "thead-dark";

    table.appendChild(thead);

    var tr = document.createElement("tr");

    thead.appendChild(tr);

    var thCod = document.createElement("th");
    thCod.scope = "col";
    thCod.innerHTML = "Código Producto";

    var thNombreProd = document.createElement("th");
    thNombreProd.scope = "col";
    thNombreProd.innerHTML = "Nombre Producto";

    var thCantidad = document.createElement("th");
    thCantidad.scope = "col";
    thCantidad.innerHTML = "Cant. Solicitada";

    var thUnidad = document.createElement("th");
    thUnidad.scope = "col";
    thUnidad.innerHTML = "Unidad/Medida";

    var thNombreCliente = document.createElement("th");
    thNombreCliente.scope = "col";
    thNombreCliente.innerHTML = "Nombre cliente";

    var thConsecutivo = document.createElement("th");
    thConsecutivo.scope = "col";
    thConsecutivo.innerHTML = "Consec. Pedido";

    tr.appendChild(thCod);
    tr.appendChild(thNombreProd);
    tr.appendChild(thCantidad);
    tr.appendChild(thUnidad);
    tr.appendChild(thNombreCliente);
    tr.appendChild(thConsecutivo);

    var tbody = document.createElement("tbody");
    tbody.id = "BodyTablePedidos";

    table.appendChild(tbody);

    objeto.forEach(function (item) {

        var contenidoBody = `
                <tr>
                    <th scope="row">${item.Codigo}</th>
                    <td id="fecha">${item.NombreProd}</td>
                    <td>${item.CantidadSolicitada}</td>
                    <td>${item.UnidadMedida}</td>
                    <td>${item.NombreCl}</td>
                    <td>${item.Consecutivo}</td>
                </tr>
            </tbody>`;

        tbody.innerHTML += contenidoBody;
    });

}

function TablePedido(objeto) {

    var table = document.getElementById('generarBorde');

    table.innerHTML = "";

    var thead = document.createElement("thead");
    thead.id = "CambiarColor";
    thead.className = "thead-dark";

    table.appendChild(thead);

    var tr = document.createElement("tr");

    thead.appendChild(tr);

    var thCod = document.createElement("th");
    thCod.scope = "col";
    thCod.innerHTML = "Consecutivo";

    var thFechaSolicitud = document.createElement("th");
    thFechaSolicitud.scope = "col";
    thFechaSolicitud.innerHTML = "FechaSolicitud";

    var thCliente = document.createElement("th");
    thCliente.scope = "col";
    thCliente.innerHTML = "Cliente";

    var thEstado = document.createElement("th");
    thEstado.scope = "col";
    thEstado.innerHTML = "Estado";

    tr.appendChild(thCod);
    tr.appendChild(thFechaSolicitud);
    tr.appendChild(thCliente);
    tr.appendChild(thEstado);

    var tbody = document.createElement("tbody");
    tbody.id = "BodyTablePedidos";

    table.appendChild(tbody);

    objeto.forEach(function (item) {

        var fechaCortada = item.FechaSolicitud.substring(0, 10);

        var contenidoBody = `
        <tr>
            <th scope="row">
                <button type="button" id="botonHistorial2<?php echo $i; ?>" data-toggle="modal"
            data-target=".bd-modal-example-xl" value="${item.Consecutivo}" onclick="SendQuery(this)">
                    <p style="color: rgb(223, 1, 1);"><b>PD${item.Consecutivo}</b></p>
                </button>
            </th>
            <td id="fecha<?php echo $i; ?>">${fechaCortada}</td>
            <td>${item.Nombre}</td>
            <td>${item.Estado == 1 ? "Por listar" : "se escapo"}</td>
        </tr>`;

        tbody.innerHTML += contenidoBody;
    });

}
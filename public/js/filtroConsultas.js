//Evento de despliegue de la modal del rango de fechas; el evento se activa una vez seleccionado
//la opción "Otro" en el select de fechas
/*function DespliegueModal(){
	setTimeout(function(){
		if(document.getElementById('rangoFechas').value == 3){
			var btnModal = document.getElementById('btnDespliegueRangoFechas');
			btnModal.click()
		}
	},1500);
   /* var btnModal = document.getElementById('btnDespliegueRangoFechas');
        btnModal.click();*/
   // alert('fdjhlkj');
//}

/*setTimeout(
	function(){
	 /* if(document.getElementById('rangoFechas').value == 3){
	    var btnModal = document.getElementById('btnDespliegueRangoFechas');
            btnModal.click();
	  }*/
/*	  alert('fkld');
	},1500);*/

document.getElementById('rangoFechas').addEventListener('change', function(){
	var select = document.getElementById('rangoFechas');
	value = select.value;
	if(value == 3){
		var btnModal = document.getElementById('btnDespliegueRangoFechas');
		btnModal.click();
		/*alert('fjkgfkj');*/
	}
	/*if(document.getElementById('rangoFechas').value == 3){
		DespliegueModal();
	}*/
	//alert('jkshkgjhf');
})

//Evento listener para la validación del rango de fechas
document.getElementById('RangoFechasFiltroSuccess').addEventListener('click', function(){

    var fechaInicio = document.getElementById('fechaInicio');
    var fechaFin = document.getElementById('fechaFin');

    if(fechaInicio.value.length == 0 && fechaFin.value.length == 0){
        if(document.getElementById('alertErrorRangoFechas')){
            document.getElementById('contentRangoFechasModal').removeChild(document.getElementById('alertErrorRangoFechas'));
        }
        document.getElementById('contentRangoFechasModal').innerHTML += 
        `<p style="color:red; font-size:1rem; font-weight:bold;" id="alertErrorRangoFechas">Se debe de especificar el rango de fechas.</p>`;
    }else if(fechaFin.value < fechaInicio.value){
        if(document.getElementById('alertErrorRangoFechas')){
            document.getElementById('contentRangoFechasModal').removeChild(document.getElementById('alertErrorRangoFechas'));
        }
        document.getElementById('contentRangoFechasModal').innerHTML += 
        `<p style="color:red; font-size:1rem; font-weight:bold;" id="alertErrorRangoFechas">La fecha de inicio no puede ser mayor a la fecha limite.</p>`; 
    }else{
        document.getElementById('closeModalRangoFechas').click();
    }
});

//Evento de escucha para la eliminación de la alerta una vez es cerrado el modal de rango de fechas
document.getElementById('closeModalRangoFechas').addEventListener('click', function(){
    if(document.getElementById('alertErrorRangoFechas')){
        document.getElementById('contentRangoFechasModal').removeChild(document.getElementById('alertErrorRangoFechas'));
    }
})

document.getElementById('filtrarPedidos').addEventListener('click', function(){
    
    var selectFecha, selectCliente, tipoPresentacion;
    var valorFecha;
    var valorFecha1 = 0;
    var valorFecha2 = 0;

    selectFecha = document.getElementById('rangoFechas').value;
    selectCliente = document.getElementById('codCliente');
    tipoPresentacion = document.getElementById('tipoPresentacion').value;

    var fecha = new Date();
                dia = fecha.getDate();
                mes = fecha.getMonth()+1;
                year = fecha.getFullYear();

    if(selectFecha != 0){
        if(selectFecha == 1){
            valorFecha1 = year+"-"+mes+"-"+(dia-1);
            valorFecha2 = year+"-"+mes+"-"+(dia+1);
        }else if(selectFecha == 2){
            valorFecha1 = year+"-"+mes+"-"+(dia-1);
            valorFecha2 = year+"-"+mes+"-"+dia;
        }else if(selectFecha == 3){
            valorFecha1 = document.getElementById('fechaInicio').value;
            valorFecha2 = document.getElementById('fechaFin').value;
        }
    }

    if(selectCliente.value.length > 0){
        var data = document.querySelector('#dataCliente2 > option[value="'+selectCliente.value+'"]');
        selectCliente.value = data.innerHTML;
    }

    if(selectCliente.value.length > 0 && tipoPresentacion == 1){

        if(valorFecha1 != 0 && valorFecha2 != 0){
            var objeto = {
                cliente: selectCliente.value,
                tipoPresentacion: 1,
                fechaInicio: valorFecha1,
                fechaFin: valorFecha2
            }
        }else{
            var objeto = {
                cliente: selectCliente.value,
                tipoPresentacion: 1
            }
        }

    }else if(selectCliente.value.length == 0 && tipoPresentacion == 2){

        if(valorFecha1 != 0 && valorFecha2 != 0){
            var objeto = {
                tipoPresentacion: 2,
                fechaInicio: valorFecha1,
                fechaFin: valorFecha2
            }
        }else{
            var objeto = {
                tipoPresentacion: 2
            }
        }

    }else if(selectCliente.value.length > 0 && tipoPresentacion == 2){

        if(valorFecha1 != 0 && valorFecha2 != 0){
            var objeto = {
                cliente: selectCliente.value,
                tipoPresentacion: 2,
                fechaInicio: valorFecha1,
                fechaFin: valorFecha2
            }
        }else{
            var objeto = {
                cliente: selectCliente.value,
                tipoPresentacion: 2
            }
        }

    }else if(selectCliente.value.length == 0 && tipoPresentacion == 1){

        if(valorFecha1 != 0 && valorFecha2 != 0){
            var objeto = {
                fechaInicio: valorFecha1,
                fechaFin: valorFecha2
            }
        }else{
            /*var objeto = {
                tipoPresentacion:1
            }*/
            location.reload();
        }

    }

    document.getElementById('rangoFechas').value = 0;
    document.getElementById('codCliente').value = "";
    document.getElementById('tipoPresentacion').value = 1;

    sendQueryFiltrosConsulta(objeto);
})

function sendQueryFiltrosConsulta(objeto) {

    //alert("llegó");
    //http://192.168.9.139/julioCercafe/despostes/public

    var ajax = new XMLHttpRequest();
    ajax.open("GET","/filtrarTable?data="+ encodeURIComponent(JSON.stringify(objeto)),true);
    ajax.setRequestHeader("Content-Type", "application/json");
    ajax.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {

            var json = JSON.parse(ajax.responseText);
            if(json == "no hay resultados"){
                NotFoundResults();
            }else if(Object.keys(json[0]).length == 5){
                TablePedido(json);
            }else{
                TableProducto(json);
            }

            //console.log(json);

        }
    }
    ajax.send();
}

function NotFoundResults(){
    var table = document.getElementById('BodyTablePedidos');
    table.innerHTML = "";

    var contenidoBody = `
        <tr>
            <td colspan="6"><p style="font-weight: bold; font-size: 1.5rem;">No hay resultados.</p></td>
        </tr>
    `;

    table.innerHTML = contenidoBody;
}

function TableProducto(objeto) {

    //alert("llegó");

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
                    <th scope="row">${item.codigo}</th>
                    <td id="fecha">${item.NombreProd}</td>
                    <td>${item.cantidadSolicitada}</td>
                    <td>${item.unidadMedida}</td>
                    <td>${item.NombreCl}</td>
                    <td>${item.id}</td>
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
    thFechaSolicitud.innerHTML = "Fecha Entrega";

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

        var fechaCortada = item.fechaSolicitud.substring(0, 10);

        var contenidoBody = `
        <tr>
            <th scope="row">
                <button type="button" id="botonHistorial2<?php echo $i; ?>" data-toggle="modal"
            data-target=".bd-modal-example-xl" value="${item.id}" onclick="SendQuery(this)">
                    <p style="color: rgb(223, 1, 1);"><b>PD${item.id}</b></p>
                </button>
            </th>
            <td id="fecha<?php echo $i; ?>">${item.fechaEntrega}</td>
            <td>${item.razonSocial}</td>
            <td>${item.estado == 1 ? "Por listar" : "se escapo"}</td>
        </tr>`;

        tbody.innerHTML += contenidoBody;
    });

}

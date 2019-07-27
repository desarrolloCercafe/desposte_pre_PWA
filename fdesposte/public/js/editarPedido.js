if(document.getElementById('botonEditarPedido')){
    document.getElementById('botonEditarPedido').addEventListener('click', function(){

        document.getElementById('botonEditarPedido').removeAttribute("data-toggle");
        document.getElementById('botonEditarPedido').dataset.target = "";
    
        if(validarTableEditar()){
            var contenidoTable;
            contenidoTable = SaveProducts();
            SendQueryUpdate(contenidoTable);
        }
        //validarTableEditar();
    
    });
}


function validarTableEditar(){

    var messageValidation = document.getElementById('MessageValidation');
    var botonEditarPedido = document.getElementById('botonEditarPedido');

    if(fechaEntrega.value.length == 0){
        
        messageValidation.innerHTML = "No haz seleccionado una fecha de entrega";
        botonEditarPedido.setAttribute("data-toggle", "modal");
        botonEditarPedido.dataset.target = "#ValidationModal";
        botonEditarPedido.click();

    }else{

        var error = 0;
        var contentTableEditar = document.getElementById('tbodyEditProductos');

        for (let index = 0; index < contentTableEditar.rows.length; index++) {

            var cantidadSolicitada = document.getElementById('CantidadSolicitada'+index);
            var cantidadDespachada = document.getElementById('CantidadDespachada'+index);
            var radio = document.getElementsByName("unidad"+index);

            if(cantidadSolicitada.value <= 0 || cantidadDespachada.value <= 0){
                ErroresEditarPedido('Cantidades');
                error = 1;
                break;
            }

            if(radio[0].checked){
                continue;
            }else if(radio[1].checked){
                continue;
            }else{
                Errores('radio');
                error = 1;
                break;
            }
        
            error = 0;

        }

        if(error == 0){
            return true;
        }
    }
}

function ErroresEditarPedido(error){

    var messageValidation = document.getElementById('MessageValidation');
    var botonEditarPedido = document.getElementById('botonEditarPedido');

    switch (error) {
        case 'Cantidades':
            messageValidation.innerHTML = "Existen Cantidades vacías";
            botonEditarPedido.setAttribute("data-toggle", "modal");
            botonEditarPedido.dataset.target = "#ValidationModal";
            botonEditarPedido.click();
            break;
        case 'radio':
            messageValidation.innerHTML = "Existen Cantidades vacías";
            botonEditarPedido.setAttribute("data-toggle", "modal");
            botonEditarPedido.dataset.target = "#ValidationModal";
            botonEditarPedido.click();
            break;
            
    }
}

function SaveProducts(){
    
    var array = [];

    var contentTableEditar = document.getElementById('tbodyEditProductos');

    for (let index = 0; index < contentTableEditar.rows.length; index++) {
        
        var consecutivoProducto = document.getElementById('codigo'+index);
        //var NombrePedido = document.getElementById('nombre'+index);
        var cantidadSolicitada = document.getElementById('CantidadSolicitada'+index);
        var cantidadDespachada = document.getElementById('CantidadDespachada'+index);
        var radio = document.getElementsByName("unidad"+index);

        var valueRadio;

        if(radio[0].checked){
            valueRadio = radio[0].value;
        }else if(radio[1].checked){
            valueRadio = radio[1].value;
        }

        var productosEditado = {
            consecutivoProducto: consecutivoProducto.innerHTML,
            cantidadSolicitada: cantidadSolicitada.value,
            cantidadDespachada: cantidadDespachada.value,
            unidad: valueRadio
        }

        array.push(productosEditado);
    }

    return array;

}

function SendQueryUpdate(objeto){

    //console.log(objeto);

    var consecutivoPedido = document.getElementById('ConsecutivoPedido').value;
    var fechaSolicitud = document.getElementById('FechaSolicitud').value;
    /*var url = "/UpdatePedido";
    var parametros = "productos=" + encodeURIComponent(JSON.stringify(objeto)) + "&consecutivo="+ encodeURIComponent(consecutivoPedido) + "&fechaSolicitud="+encodeURIComponent(fechaSolicitud);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    xhr.send(parametros);*/

    var formElements = `
        <textarea name="productosPedido" style="display:none;">${JSON.stringify(objeto)}</textarea>
        <input type="hidden" value="${consecutivoPedido}" name="consecutivoPedido">
        <input type="hidden" value="${fechaSolicitud}" name="fechaSolicitud">
    `;
    
    document.getElementById('formUpdatePedido').innerHTML += formElements;

    localStorage.clear();

    document.getElementById('formUpdatePedido').submit();


    /*document.getElementById('formUpdatePedido').appendChild(JSON.stringify(objeto));
    document.getElementById('formUpdatePedido').appendChild(consecutivoPedido);
    document.getElementById('formUpdatePedido').appendChild(fechaSolicitud);*/

}
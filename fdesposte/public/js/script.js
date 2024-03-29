window.onload = ListarLocalStorage;

function ListarLocalStorage(){

    document.getElementById('selectProducto').value = "";

    var data = [];
    var dataInLocalStorage = localStorage.getItem("productos");
    var tbody = document.getElementById('tbodyProductos');

    if(dataInLocalStorage !== null){
        data = JSON.parse(dataInLocalStorage);
    }

    tbody.innerHTML = '';

    data.forEach(function(x, i){
        console.log(x.cantidad);
        var plantilla = `
        <tr>
            <th id="codigo${i}">${x.codigo}</th>
            <td id="nombreProducto${i}">${x.nombreProducto}</td>
            <td>
                ${x.cantidad > 0 ?
                    `
                    <input type="number" min="0" name="cantidad${i}" class="form-control" value="${x.cantidad}" id="CantidadSolicitada${i}">
                    `:`
                    <input type="number" min="0" name="cantidad${i}" class="form-control" value="" id="CantidadSolicitada${i}">
                    `
                }
            </td>
            <td>
            ${x.unidadSeleccionada == 0 ? 
                `
                <input type="radio" name="unidad${i}" value="kg" class="mr-2"><span>KG</span>
                <br>
                <input type="radio" name="unidad${i}" value="un" class="mr-2"><span>UN</span>`:
                `
                <input type="radio" name="unidad${i}" value="kg" class="mr-2" ${x.unidadSeleccionada == "kg"? `checked`:""}><span>KG</span>
                <br>
                <input type="radio" name="unidad${i}" value="un" class="mr-2" ${x.unidadSeleccionada == "un"? `checked`:""}><span>UN</span>`
            }
            </td>
            <td>
                <button type="button" class="btn btn-danger" value='${i}' data-target="#ModalDelete" data-toggle="modal" onclick="eliminarProducto(this)">
                    <img src="http://192.168.9.139/17-07-2019/fdesposte/public/svg/delete.svg" id="${i}"></img>
                </button>
            </td>
        </tr>
        `;

        tbody.innerHTML += plantilla;
    });

    document.getElementById('cerrar').click();
}



var botonAdd = document.getElementById('addProducto');

botonAdd.addEventListener('click', ValidarData);

//Función para validar la información enviada por el usuario
function ValidarData() {

    var valueSelect = document.getElementById('selectProducto').value;
    var value = valueSelect.split(",");
    var array = [];

    var bodyTable = document.getElementById('tbodyProductos');

    for (let index = 0; index < bodyTable.rows.length; index++) {

        var codigo = document.getElementById('codigo'+index);
        var nombreProducto = document.getElementById('nombreProducto'+index);
        var cantidad = document.getElementById('CantidadSolicitada'+index);
        var radio = document.getElementsByName("unidad"+index);
        
        
        var valueRadio;

        if(radio[0].checked){
            valueRadio = radio[0].value;
        }else if(radio[1].checked){
            valueRadio = radio[1].value;
        }

        var product = {
            codigo: codigo.innerHTML,
            nombreProducto: nombreProducto.innerHTML,
            cantidad: cantidad.value,
            unidadSeleccionada: valueRadio
        }

        array.push(product);

    }

    var product = {
        codigo: value[0],
        nombreProducto: value[1],
        cantidad: 0,
        unidadSeleccionada: 0
    }

    array.push(product);

    localStorage.setItem("productos", JSON.stringify(array));
    ListarLocalStorage();
}

function eliminarProducto(boton){
    localStorage.setItem("borrar", boton.value);
}

document.getElementById('cerrarDelete').addEventListener('click', function(){
    localStorage.removeItem("borrar");
})

document.getElementById('deleteButton').addEventListener('click', function(){
    
    var posicion = localStorage.getItem("borrar");

    var contenidoTable = [];
        contenidoTable = JSON.parse(localStorage.getItem("productos"));
        contenidoTable.splice(posicion, 1);
        localStorage.setItem("productos", JSON.stringify(contenidoTable));
    
    document.getElementById('cerrarDelete').click();

    ListarLocalStorage();

});
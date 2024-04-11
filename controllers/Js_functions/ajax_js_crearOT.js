// Variable global para almacenar el nombre de la empresa seleccionada o validada
var nombreEmpresaSeleccionada = '';

// Función para buscar empresas
function buscarEmpresa(query) {
    document.getElementById('listaEmpresas').style.display = 'block';
    if (query.length < 1) {
        document.getElementById('listaEmpresas').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscar_empresa.php', // Nombre del archivo que buscará empresas
        type: 'POST',
        data: {
            texto: query
        },
        success: function (response) {
            document.getElementById('listaEmpresas').innerHTML = response;
        }
    });
}
function seleccionarEmpresa(nombreEmpresa, rutEmpresa) {
    cargarClientes(rutEmpresa);

    // Actualizar el nombre de la empresa seleccionada
    nombreEmpresaSeleccionada = nombreEmpresa;

    // Resetear campos y actualizar valores según la empresa seleccionada
    var campos = ['cliente', 'rut_cliente', 'listaClientes', 'empresa'];
    campos.forEach(function (id) {
        var elemento = document.getElementById(id);
        if (elemento) {
            elemento.value = id === 'empresa' ? nombreEmpresa : '';
            if (elemento.innerHTML) elemento.innerHTML = '';
        }
    });

    document.getElementById('listaEmpresas').style.display = 'none';
    document.getElementById('rutEmpresaHidden').value = rutEmpresa;

    validarSeleccionEmpresa();
}
document.getElementById('empresa').addEventListener('blur', function () {
    var listaEmpresas = document.getElementById('listaEmpresas');
    var empresaInput = this; // Usar 'this' para referirse al elemento que disparó el evento
    var empresas = listaEmpresas.getElementsByTagName('p');
    var rutEmpresaHiddenElement = document.getElementById('rutEmpresaHidden');

    if (nombreEmpresaSeleccionada !== empresaInput.value.trim()) {
        console.log('El nombre de la empresa ha cambiado. Era ' + nombreEmpresaSeleccionada + ', ahora es: ' + empresaInput.value.trim());
    } else if (empresas.length === 1 && empresaInput.value.trim() !== '' && rutEmpresaHiddenElement.value !== '') {
        // Si el nombre no ha cambiado y hay una empresa previamente validada, no hacer nada
        return;
    }

    if (empresas.length === 1 && empresaInput.value.trim() !== '') {
        var nombreEmpresa = empresas[0].getAttribute('data-nombre');
        var rutEmpresa = empresas[0].getAttribute('data-rut');
        seleccionarEmpresa(nombreEmpresa, rutEmpresa);
    } else {
        rutEmpresaHiddenElement.value = '';
        validarSeleccionEmpresa();
    }
});


document.addEventListener('click', function (event) {
    var listaEmpresas = document.getElementById('listaEmpresas');
    if (!document.getElementById('empresa').contains(event.target) && !listaEmpresas.contains(event.target)) {
        listaEmpresas.style.display = 'none';
        validarSeleccionEmpresa();
    }
});
function validarSeleccionEmpresa() {
    var rutEmpresaHiddenElement = document.getElementById('rutEmpresaHidden');
    var mensajeErrorEmpresa = document.getElementById('mensajeErrorEmpresa');
    var empresaInput = document.getElementById('empresa');

    // Verificar si el campo de empresa está vacío
    if (rutEmpresaHiddenElement.value === '' && empresaInput.value.trim() !== '') {
        // Asignar el texto del mensaje de error
        mensajeErrorEmpresa.textContent = 'Referencia no válida.';
        // Mostrar el mensaje de error
        mensajeErrorEmpresa.style.display = 'block';
        mensajeErrorEmpresa.style.border = '1px solid #f95050';
        mensajeErrorEmpresa.style.color = '#9d0010';
        mensajeErrorEmpresa.style.backgroundColor = '#fae6e6';
        mensajeErrorEmpresa.style.maxWidth = '85%';
        mensajeErrorEmpresa.style.marginTop = '5px';
        mensajeErrorEmpresa.style.paddingLeft = '5px';
        empresaInput.style.backgroundColor = '#fae6e6';
    } else {
        // Ocultar el mensaje de error si el campo no está vacío
        mensajeErrorEmpresa.style.display = 'none';
        empresaInput.style.backgroundColor = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var empresaInput = document.getElementById('empresa');

    // Maneja el evento de enfoque en el campo de entrada de la empresa
    empresaInput.addEventListener('focus', function () {
        // Llama a buscarEmpresa con el valor actual del campo de entrada
        buscarEmpresa(empresaInput.value.trim());
    });

    // Maneja el evento de tecla levantada en el campo de entrada de la empresa
    empresaInput.addEventListener('keyup', function () {
        // Llama a buscarEmpresa con el valor actual del campo de entrada
        buscarEmpresa(empresaInput.value.trim());
    });
});










// Función para cargar clientes
function cargarClientes(rutEmpresa) {
    $.ajax({
        url: '../controllers/cargar_clientes.php', // Nombre del archivo que cargará clientes
        type: 'POST',
        data: {
            rut_empresa: rutEmpresa
        },
        success: function (response) {
            // Actualiza el contenido del campo de selección de clientes
            $('#cliente').html(response);
            $('#cliente').prop('disabled', false); // Habilita el campo cliente
        }
    });
}

// Otras funciones necesarias


// Función para buscar clientes
function buscarCliente(query) {
    const rutEmpresa = document.getElementById('rutEmpresaHidden').value; // Obtén el rut de la empresa seleccionada
    document.getElementById('listaClientes').style.display = 'block';
    if (query.length < 1) {
        document.getElementById('listaClientes').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscar_cliente.php', // Nombre del archivo que buscará clientes
        type: 'POST',
        data: {
            rut_empresa: rutEmpresa,
            texto: query
        },
        success: function (response) {
            document.getElementById('listaClientes').innerHTML = response;
        }
    });
}

// Función para seleccionar un cliente
function seleccionarCliente(nombreCliente, rutCliente) {
    document.getElementById('cliente').value = nombreCliente;
    document.getElementById('rut_cliente').value = rutCliente;
    document.getElementById('listaClientes').innerHTML = ''; // Limpiar la lista de sugerencias

    // Ocultar la lista de sugerencias después de seleccionar un cliente
    document.getElementById('listaClientes').style.display = 'none';

}







// Función para buscar usuarios
function buscarUsuario(query) {
    document.getElementById('listaUsuarios').style.display = 'block';
    if (query.length < 1) {
        document.getElementById('listaUsuarios').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscar_usuario.php', // Nombre del archivo que buscará usuarios
        type: 'POST',
        data: {
            texto: query
        },
        success: function (response) {
            document.getElementById('listaUsuarios').innerHTML = response;
        }
    });
}

function seleccionarUsuario(nombreUsuario, rutUsuario) {
    // Aquí asumo que quieres cargar alguna información basada en el usuario seleccionado
    // cargarInfoUsuario(rutUsuario); // Esta sería una función hipotética que debes implementar

    var usuarioElement = document.getElementById('asignado');
    if (usuarioElement) {
        usuarioElement.value = nombreUsuario;
    }

    var rutUsuarioElement = document.getElementById('rut_user_asignado');
    if (rutUsuarioElement) {
        rutUsuarioElement.value = rutUsuario;
    }

    var listaUsuariosElement = document.getElementById('listaUsuarios');
    if (listaUsuariosElement) {
        listaUsuariosElement.style.display = 'none';
    }

    validarSeleccionUsuario(); // Asumiendo que implementarás una validación similar
}




document.getElementById('asignado').addEventListener('blur', function () {
    var listaUsuarios = document.getElementById('listaUsuarios');
    var usuarios = listaUsuarios.getElementsByTagName('p');
    var rutUsuarioHiddenElement = document.getElementById('rut_user_asignado');
    var asignado = document.getElementById('asignado');

    if (usuarios.length === 1) {
        var nombreUsuario = usuarios[0].getAttribute('data-nombre');
        var rutUsuario = usuarios[0].getAttribute('data-rut');
        seleccionarUsuario(nombreUsuario, rutUsuario);
        validarSeleccionUsuario();
    } else if (asignado.value === '') {
       
    } else {
        rutUsuarioHiddenElement.value = '';
        validarSeleccionUsuario();
    }
});




document.addEventListener('click', function (event) {
    var listaUsuarios = document.getElementById('listaUsuarios');
    var rutUsuarioHiddenElement = document.getElementById('rut_user_asignado');

    // Verifica si el clic fue fuera del campo de entrada `asignado` y de la lista de usuarios
    if (!document.getElementById('asignado').contains(event.target) && !listaUsuarios.contains(event.target)) {
        // Si hay más de un usuario en la lista, limpia el campo oculto y oculta la lista

        if (listaUsuarios.getElementsByTagName('p').length > 1) {
            rutUsuarioHiddenElement.value = '';
            listaUsuarios.style.display = 'none';
            validarSeleccionUsuario();
        }

        if (rutUsuarioHiddenElement.value === '') {
            validarSeleccionUsuario();
         

        }
    }
});



function validarSeleccionUsuario() {
    var rutUsuarioHiddenElement = document.getElementById('rut_user_asignado');
    var mensajeErrorUsuario = document.getElementById('mensajeErrorUsuario'); // Asegúrate de tener este elemento en tu HTML
    var asignadoElement = document.getElementById('asignado');

    if (rutUsuarioHiddenElement.value === '' && asignadoElement.value !== '') {
        mensajeErrorUsuario.textContent = 'Referencia no válida.';
        mensajeErrorUsuario.style.display = 'block';
        mensajeErrorUsuario.style.border = '1px solid #f95050';
        mensajeErrorUsuario.style.color = '#9d0010';
        mensajeErrorUsuario.style.backgroundColor = '#fae6e6';
        mensajeErrorUsuario.style.maxWidth = '85%';
        mensajeErrorUsuario.style.marginTop = '5px';
        mensajeErrorUsuario.style.paddingLeft = '5px';

        asignadoElement.style.backgroundColor = '#fae6e6';
    } else {
        mensajeErrorUsuario.style.display = 'none';
        asignadoElement.style.backgroundColor = ''; // Restaura el color de fondo original
    }
}



document.getElementById('asignado').addEventListener('focus', function () {
    // Obtener el valor actual del campo de entrada
    var query = this.value;

    // Llamar a la función buscarUsuario con el valor actual del campo de entrada
    buscarUsuario(query);
});

document.getElementById('asignado').addEventListener('keyup', function () {
    // Obtener el valor actual del campo de entrada
    var query = this.value;

    // Llamar a la función buscarUsuario con el valor actual del campo de entrada
    buscarUsuario(query);
});

























// Función para buscar marcas
function buscarMarca(query) {
    if (query.length < 1) {
        document.getElementById('listaMarcas').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscar_marca.php', // Nombre del archivo que buscará marcas
        type: 'POST',
        data: {
            texto: query
        },
        success: function (response) {
            document.getElementById('listaMarcas').innerHTML = response;
        }
    });
}

// Función para seleccionar una marca
function seleccionarMarca(nombreMarca, idMarca) {
    document.getElementById('marca').value = nombreMarca;
    document.getElementById('listaMarcas').innerHTML = '';
    cargarModelos(idMarca); // Cargar modelos basados en la marca seleccionada
}

// Función para cargar modelos basados en la marca seleccionada
function cargarModelos(idMarca) {

    $.ajax({
        url: '../controllers/cargar_modelos.php', // Nombre del archivo que cargará modelos
        type: 'POST',
        data: {
            id_marca: idMarca
        },
        success: function (response) {
            // Actualizar el contenido del campo de selección de modelos
            $('#Modelo').html(response);
            $('#Modelo').prop('disabled', false); // Habilitar el campo Modelo
        }
    });
}




// Obtener la URL actual
const url = window.location.href;

// Buscar el parámetro "id" en la URL
const urlParams = new URLSearchParams(new URL(url).search);
const rutUsuario = urlParams.get("id");
let permisosAEliminar = []; // Array para almacenar los IDs de los permisos a eliminar

if (rutUsuario) {
    // El parámetro "id" se encontró en la URL
    console.log("RUT del usuario:", rutUsuario);
} else {
    // El parámetro "id" no se encontró en la URL
    console.error("RUT del usuario no encontrado en la URL");
}



document.addEventListener('DOMContentLoaded', () => {
    // Agregar evento a cada botón de eliminar en la tabla de permisos seleccionados
    document.getElementById('permisos-seleccionados').addEventListener('click', function (event) {
        if (event.target.classList.contains('eliminar')) {
            const fila = event.target.closest('tr');
            const idPermiso = fila.querySelector('td:first-child').textContent; // Asumiendo que el ID está en la primera celda

            // Añadir el ID del permiso al array global
            permisosAEliminar.push(idPermiso);

            // Opcional: Mover la fila a la tabla de permisos disponibles
            // document.getElementById('permisos-disponibles').querySelector('tbody').appendChild(fila);

            // Eliminar la fila de la tabla actual
            fila.remove();

            // Llamar a la función para eliminar el permiso de la base de datos
            moverFilaAPermisosDisponibles(fila);
            console.log(idPermiso); // Imprimir para depuración
        }
    });
});

// Función para cargar los datos del usuario en el formulario
function cargarDatosUsuarioEnFormulario(usuario) {
    // Obtener referencias a los elementos del formulario
    const nombreInput = document.getElementById('nombre');
    const sNombreInput = document.getElementById('s_nombre');
    const apPaternoInput = document.getElementById('a_paterno');
    const apMaternoInput = document.getElementById('a_materno');
    const emailInput = document.getElementById('email');
    const fechaExpiracionInput = document.getElementById('fecha_expiracion'); // Agregar esta línea
    const telefonoInput = document.getElementById('telefono');
    const idCodigoInput = document.getElementById('codigo_area');

    // Llenar los campos del formulario con los datos del usuario
    nombreInput.value = usuario.nombre;
    sNombreInput.value = usuario.s_nombre;
    apPaternoInput.value = usuario.ap_paterno;
    apMaternoInput.value = usuario.ap_materno;
    emailInput.value = usuario.correo;
    telefonoInput.value = usuario.telefono;
    idCodigoInput.value = usuario.id_codigo;
    fechaExpiracionInput.value = usuario.fecha_expiracion;

}


// Obtener el RUT del usuario actual (reemplaza esto con tu lógica para obtener el RUT)
const rutUsuarioActual = rutUsuario; // Reemplaza con el RUT real del usuario actual

// Realizar una solicitud AJAX para obtener los datos del usuario
fetch('../controllers/controlador_obtener_usuario_edit_list.php?rut_user=' + rutUsuarioActual)
    .then(response => response.json())
    .then(usuario => {
        // Llamar a la función para cargar los datos del usuario en el formulario
        cargarDatosUsuarioEnFormulario(usuario);
        console.log(usuario); // Imprimir los datos del usuario en la consola
    })
    .catch(error => {
        console.error('Error al cargar datos del usuario:', error); // Imprimir el error en la consola
    });







// Función para cargar permisos del usuario en "Permisos Seleccionados"
function cargarPermisosUsuario() {
    console.log("se llamo a cargarPermisosUsuario ");
    console.log("cargarPermisosUsuario:" + rutUsuario);
    fetch('../controllers/controlador_cargar_permisos_usuario.php?rut_user=' + rutUsuario)
        .then(response => response.json())
        .then(data => {
            const tablaSeleccionadosBody = document.getElementById('permisos-seleccionados').querySelector('tbody');
            tablaSeleccionadosBody.innerHTML = '';
            data.forEach(permiso => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${permiso.id_permiso_indi}</td>
                    <td>${permiso.nombre}</td>
                    <td>${permiso.tipo}</td>
                    <td>${permiso.detalle}</td>
                    <td><button class="eliminar">Eliminar</button></td>
                `;
                tablaSeleccionadosBody.appendChild(fila);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Asegúrate de que 'rutUsuario' esté definido con el RUT del usuario actual
cargarPermisosUsuario();

// Función para cargar datos desde la base de datos
function cargarPermisosDisponibles() {
    // Realizar una solicitud AJAX para obtener los permisos disponibles desde PHP
    fetch('../controllers/controlador_cargar_permisos.php?rut_user=' + rutUsuario, {
        credentials: 'include'
    })
        .then(response => response.json())
        .then(data => {
            // Limpiar la tabla de permisos disponibles
            const tablaDisponiblesBody = document.getElementById('permisos-disponibles').querySelector('tbody');
            tablaDisponiblesBody.innerHTML = '';

            // Agregar los permisos a la tabla
            data.forEach(permiso => {
                const fila = document.createElement('tr');
                fila.draggable = true;
                fila.innerHTML =
                    `<td>${permiso.id_permiso_indi}</td>
                    <td>${permiso.nombre}</td>
                    <td>${permiso.tipo}</td>
                    <td>${permiso.detalle}</td>
                    <td><button class="seleccionar">Seleccionar</button></td>`;
                tablaDisponiblesBody.appendChild(fila);
            });

            // Agregar eventos de clic para seleccionar permisos
            const seleccionarBotones = document.querySelectorAll('.seleccionar');
            seleccionarBotones.forEach(boton => {
                boton.addEventListener('click', () => {
                    moverPermisoSeleccionado(boton);
                });
            });
        })
        .catch(error => console.error('Error al cargar permisos disponibles:', error));
}

// Llamar a la función para cargar permisos disponibles al cargar la página
window.addEventListener('load', cargarPermisosDisponibles);



function moverFilaAPermisosDisponibles(fila) {
    // Crear un nuevo botón "Seleccionar" y agregarlo a la fila
    const botonSeleccionar = document.createElement('button');
    botonSeleccionar.textContent = 'Seleccionar';
    botonSeleccionar.className = 'seleccionar';
    botonSeleccionar.addEventListener('click', () => {
        moverPermisoSeleccionado(botonSeleccionar);
    });

    // Reemplazar el botón "Eliminar" por el botón "Seleccionar"
    fila.querySelector('td:last-child').innerHTML = '';
    fila.querySelector('td:last-child').appendChild(botonSeleccionar);

    // Mover la fila a la tabla de permisos disponibles
    const tablaDisponiblesBody = document.getElementById('permisos-disponibles').querySelector('tbody');
    tablaDisponiblesBody.appendChild(fila);
}


// Función para mover permisos seleccionados a la tabla de permisos seleccionados
function moverPermisoSeleccionado(botonSeleccionar) {
    const fila = botonSeleccionar.closest('tr');
    const tablaSeleccionadosBody = document.getElementById('permisos-seleccionados').querySelector('tbody');

    // Crear un nuevo botón "Eliminar" y agregarlo a la fila
    const botonEliminar = document.createElement('button');
    botonEliminar.textContent = 'Eliminar';
    botonEliminar.className = 'eliminar';
    botonEliminar.addEventListener('click', () => {

    });

    // Eliminar el botón "Seleccionar" y agregar el botón "Eliminar"
    fila.querySelector('td:last-child').innerHTML = '';
    fila.querySelector('td:last-child').appendChild(botonEliminar);

    // Mover la fila a la tabla de permisos seleccionados
    tablaSeleccionadosBody.appendChild(fila);
}


function guardarPermisosSeleccionados() {
    return new Promise((resolve, reject) => {
    const permisosSeleccionados = document.querySelectorAll('#permisos-seleccionados tbody tr');

    // Crear un array para almacenar los IDs de los permisos seleccionados
    const idsPermisosSeleccionados = [];
    permisosSeleccionados.forEach(fila => {
        const idPermiso = fila.querySelector('td:first-child').textContent;
        idsPermisosSeleccionados.push(idPermiso);
    });

    // Obtener los valores de los campos del formulario
    const nombreInput = nombre.value;
    const sNombreInput = s_nombre.value;
    const apPaternoInput = a_paterno.value;
    const apMaternoInput = a_materno.value;
    const emailInput = email.value;
    const fechaExpiracionInput = fecha_expiracion.value;
    const telefonoInput = telefono.value;
    const idCodigoInput = codigo_area.value;

    // Crear un objeto con los datos del formulario
    const datosFormulario = {
        nombre: nombreInput,
        s_nombre: sNombreInput,
        ap_paterno: apPaternoInput,
        ap_materno: apMaternoInput,
        correo: emailInput,
        fecha_expiracion: fechaExpiracionInput,
        telefono: telefonoInput,
        id_codigo: idCodigoInput
    };




    // Preparar los datos para enviar, incluyendo los permisos a eliminar
    const datos = {
        rutUsuario: rutUsuario, // Asegúrate de que rutUsuario esté definido correctamente
        idsPermisos: idsPermisosSeleccionados,
        idsPermisosEliminar: permisosAEliminar,
        datosFormulario: datosFormulario // Agregar el objeto de datos del formulario
    };
    
        // El resto del código se mantiene igual hasta el fetch

        fetch('../controllers/controlador_guardar_permisos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datos),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error de red. Status: ' + response.status);
            }
            // Verificar el tipo de contenido de la respuesta
            const contentType = response.headers.get('Content-Type');
            if (contentType.includes('application/json')) {
                return response.json(); // Procesar como JSON si la respuesta es JSON
            } else if (contentType.includes('text/html')) {
                return response.text(); // Procesar como texto/HTML si la respuesta es HTML
            } else {
                throw new Error('Tipo de contenido no soportado: ' + contentType);
            }
        })
        .then(data => {
            if (typeof data === 'object' && data.success) { // Asumiendo que es un objeto JSON
                permisosAEliminar = [];
                resolve(data); // Resolver la promesa en caso de éxito
                console.log("Permisos guardados correctamente");
            } else if (typeof data === 'string') { // Asumiendo que es HTML como texto plano
                console.log("Respuesta HTML:", data); 
                console.log("Respuesta HTML procesada");
                resolve(data); // Podrías necesitar una lógica adicional aquí para manejar el éxito/fallo basado en el contenido HTML
            }
        })
        .catch(error => {
            reject(error); // Rechazar la promesa en caso de error
        });
        
        console.log(Promise);
    });

  
}

document.addEventListener('DOMContentLoaded', () => {
    // Agrega un manejador de eventos al botón externo
    document.getElementById('guardar-permisos-externo').addEventListener('click', () => {
        guardarPermisosSeleccionados().then(data => {
            // Muestra el SweetAlert de éxito solo si la promesa se resuelve
            Swal.fire({
                title: 'Cambios guardados exitosamente',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            }).then(() => {
                window.location.href = 'users.php';
            });
        }).catch(error => {
            // Muestra el SweetAlert de error si la promesa es rechazada
            Swal.fire({
                title: 'Error al guardar permisos',
                text: error.message,
                icon: 'error'
            });
        });
    });
    
});




// Agregar eventos de arrastrar y soltar para mover permisos
const tablaDisponibles = document.getElementById('permisos-disponibles');
const tablaSeleccionados = document.getElementById('permisos-seleccionados');


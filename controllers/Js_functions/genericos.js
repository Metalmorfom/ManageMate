
document.addEventListener('DOMContentLoaded', function () {

    // Función para manejar el evento input y limitar la longitud del texto
    function limitarLongitudTexto2() {
        const descripcion = document.getElementById('descripcion');
        const maxLength = 4000; // Cambia esto al número máximo de caracteres permitidos

        if (descripcion.value.length > maxLength) {
            descripcion.value = descripcion.value.slice(0, maxLength); // Trunca el texto al maxLength
        }

        actualizarContador();
    }

    // Función para actualizar el contador de caracteres de "Descripción"
    function actualizarContador() {
        const descripcion = document.getElementById('descripcion');
        const contador = document.getElementById('contador-caracteres');
        const maxLength = 4000; // Cambia esto al número máximo de caracteres permitidos

        const caracteresRestantes = maxLength - descripcion.value.length;
        contador.textContent = `Caracteres restantes: ${caracteresRestantes}`;
    }

    // Agregar eventos de escucha al textarea de "Descripción"
    const textareaDescripcion = document.getElementById('descripcion');
    textareaDescripcion.addEventListener('input', limitarLongitudTexto2);
    textareaDescripcion.addEventListener('paste', limitarLongitudTexto2);

    // Llama a la función inicialmente para que el contador refleje el valor inicial si se carga la página con texto preexistente.
    actualizarContador();



    // Función para actualizar el contador de caracteres de "Notas del Trabajo"
    // Función para manejar el evento input y limitar la longitud del texto
    function limitarLongitudTexto() {
        const notas = document.getElementById('Notas_del_Trabajo');
        const maxLength = 1000; // Cambia esto al número máximo de caracteres permitidos

        if (notas.value.length > maxLength) {
            notas.value = notas.value.slice(0, maxLength); // Trunca el texto al maxLength
        }

        actualizarContadorNotas();
    }

    // Función para actualizar el contador de caracteres de "Notas del Trabajo"
    function actualizarContadorNotas() {
        const notas = document.getElementById('Notas_del_Trabajo');
        const contador = document.getElementById('contador-notas');
        const maxLength = 1000; // Cambia esto al número máximo de caracteres permitidos

        const caracteresRestantes = maxLength - notas.value.length;
        contador.textContent = `Caracteres restantes: ${caracteresRestantes}`;
    }

    // Agregar eventos de escucha al textarea de "Notas del Trabajo"
    const textareaNotas = document.getElementById('Notas_del_Trabajo');
    textareaNotas.addEventListener('input', limitarLongitudTexto);
    textareaNotas.addEventListener('paste', limitarLongitudTexto);

    // Llama a la función inicialmente para que el contador refleje el valor inicial si se carga la página con texto preexistente.
    actualizarContadorNotas();









    // Obtén el valor del input del número de ticket
    const numeroTicketInput = document.getElementById('numero');
    const numeroTicket = numeroTicketInput.value;



    // Verificar si la función cargarArchivosAdjuntos existe
    if (typeof cargarArchivosAdjuntos === 'function') {
        // La función existe, puedes llamarla
        cargarArchivosAdjuntos(numeroTicket);
    } else {

    }



    // Función para manejar el evento input y limitar la longitud del texto
    function limitarLongitudResumen() {
        const resumen = document.getElementById('resumen');
        const maxLength = 150; // Cambia esto al número máximo de caracteres permitidos

        if (resumen.value.length > maxLength) {
            resumen.value = resumen.value.slice(0, maxLength); // Trunca el texto al maxLength
        }


    }


    // Agregar eventos de escucha al input de "Resumen"
    const inputResumen = document.getElementById('resumen');
    inputResumen.addEventListener('input', limitarLongitudResumen);
    inputResumen.addEventListener('paste', limitarLongitudResumen);







    // Función para manejar el evento input y limitar la longitud del texto de múltiples campos
    function limitarLongitudCampo(inputId, maxLength) {
        const input = document.getElementById(inputId);

        function limitarLongitud() {
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength); // Trunca el texto al maxLength
            }
        }

        // Agregar eventos de escucha al input
        input.addEventListener('input', limitarLongitud);
        input.addEventListener('paste', limitarLongitud);
    }

    // Aplicar la limitación de longitud a cada campo específico
    limitarLongitudCampo('Nombre_user_completo', 50); // Límite para el nombre completo del usuario
    limitarLongitudCampo('Cargo_afectado', 50); // Límite para el cargo
    limitarLongitudCampo('Mandante_afectado', 50); // Límite para el mandante
    limitarLongitudCampo('Modelo', 50); // Límite para el mandante



});












const crearTicketButton = document.getElementById('crear_ticket');
const rutEmpresaHidden = document.getElementById('rutEmpresaHidden');
const mensajeEmergente = document.getElementById('mensajeEmergente');
const mensajeErrorEmpresa = document.getElementById('mensajeErrorEmpresa');
const mensajeErrorUsuario = document.getElementById('mensajeErrorUsuario');
const mensajeErrorCampos = document.getElementById('mensajeErrorCampos');
const empresaInput = document.getElementById('empresa'); // Agrega esto si no está definido en tu código
const usuarioInput = document.getElementById('asignado'); // Agrega esto si no está definido en tu código
const resumenInput = document.getElementById('resumen'); // Agrega referencia al campo de resumen
const descripcionInput = document.getElementById('descripcion'); // Agrega referencia al campo de descripción



if (crearTicketButton && rutEmpresaHidden) {
    crearTicketButton.addEventListener('click', async function () {
        try {
            // Restablecer mensajes de error
            mensajeErrorEmpresa.textContent = '';
            mensajeErrorUsuario.textContent = '';
            mensajeErrorCampos.textContent = '';
            mensajeEmergente.style.display = 'none';

            // Validación de existencia de empresa
            const existeEmpresa = await valid_buscarEmpresa(rutEmpresaHidden.value);
            if (!existeEmpresa) {
                // Mostrar mensaje de error de empresa no encontrada
                mensajeErrorEmpresa.textContent = 'Referencia no válida.';
                mensajeErrorEmpresa.style.display = 'block';
                mensajeErrorEmpresa.style.border = '1px solid #f95050';
                mensajeErrorEmpresa.style.color = '#9d0010';
                mensajeErrorEmpresa.style.backgroundColor = '#fae6e6';
                mensajeErrorEmpresa.style.maxWidth = '85%';
                mensajeErrorEmpresa.style.marginTop = '5px';
                mensajeErrorEmpresa.style.paddingLeft = '5px';
                empresaInput.style.backgroundColor = '#fae6e6'; // Establecer el color de fondo del input de empresa
                return; // Detener el proceso de validación
            }

            // Restablecer estilos de mensaje de error de empresa
            mensajeErrorEmpresa.style.display = 'none';
            empresaInput.style.backgroundColor = ''; // Restablecer el color de fondo del input de empresa

            // Validación de existencia de usuario
            const asignadoInput = document.getElementById('asignado');
            const rutUserHidden = document.getElementById('rut_user_asignado');
            if (asignadoInput && rutUserHidden) {
                const existeUsuario = await validarUsuario(rutUserHidden.value);
                if (!existeUsuario && asignadoInput.value !== '') {
                    // Mostrar mensaje de error de usuario no encontrado
                    mensajeErrorUsuario.textContent = 'Referencia no válida.';
                    mensajeErrorUsuario.style.display = 'block';
                    mensajeErrorUsuario.style.border = '1px solid #f95050';
                    mensajeErrorUsuario.style.color = '#9d0010';
                    mensajeErrorUsuario.style.backgroundColor = '#fae6e6';
                    mensajeErrorUsuario.style.maxWidth = '85%';
                    mensajeErrorUsuario.style.marginTop = '5px';
                    mensajeErrorUsuario.style.paddingLeft = '5px';
                    usuarioInput.style.backgroundColor = '#fae6e6'; // Establecer el color de fondo del input de usuario
                    mensajeEmergente.style.display = 'block'; // Mostrar el mensaje emergente
                    return; // Detener el proceso de validación
                }
            }


            const clienteSelect = document.getElementById('cliente');
            const mensajeErrorCliente = document.getElementById('mensajeErrorCliente');

            // Validación de existencia de cliente
            if (clienteSelect.value === '') { // Aquí deberías comparar con el valor correcto de la opción predeterminada del select
                mensajeErrorCliente.textContent = 'seleccione un cliente.';





                mensajeErrorCliente.style.display = 'block';
                mensajeEmergente.style.display = 'block';
                return; // Detener el proceso de validación
            }


            // Restablecer estilos de mensaje de error de cliente
            mensajeErrorCliente.style.display = 'none';


            // Restablecer estilos de mensaje de error de usuario
            mensajeErrorUsuario.style.display = 'none';
            usuarioInput.style.backgroundColor = ''; // Restablecer el color de fondo del input de usuario

            // Validación de campo resumen
            if (resumenInput.value.trim() === '') {
                // Mostrar mensaje de error de resumen vacío
                mensajeErrorCampos.textContent = 'Por favor, complete el resumen.';
                mensajeErrorCampos.style.display = 'block';
                descripcionInput.style.border = '1px solid red'; // Establecer borde rojo al campo de resumen
                mensajeEmergente.style.display = 'block';
                return; // Detener el proceso de validación
            }

            // Validación de campo descripción
            if (descripcionInput.value.trim() === '') {
                // Mostrar mensaje de error de descripción vacía
                mensajeErrorCampos.textContent = 'Por favor, complete la descripción.';
                mensajeErrorCampos.style.display = 'block';
                descripcionInput.style.border = '1px solid red'; // Establecer borde rojo al campo de descripción
                mensajeEmergente.style.display = 'block';
                return; // Detener el proceso de validación
            }

            // Si todas las validaciones son exitosas, enviar el formulario
            document.getElementById('creaOTForm').submit();
        } catch (error) {
            console.error('Error en la validación:', error.message);
            mensajeEmergente.style.display = 'block';
        }
    });
}



async function valid_buscarEmpresa(rutEmpresa) {
    // Realizar la solicitud AJAX solo si el parámetro no está vacío

    try {
        const response = await fetch('../controllers/validations/valid_empresa.php', {
            method: 'POST', // Método de la solicitud
            headers: {
                'Content-Type': 'application/json' // Tipo de contenido que se enviará en la solicitud
            },
            body: JSON.stringify({ rutEmpresa: rutEmpresa }) // Convertimos el valor a JSON y lo enviamos en el cuerpo de la solicitud
        });

        // Verificamos si la respuesta es exitosa (código de estado 200)
        if (response.ok) {
            // Convertimos la respuesta a JSON
            const data = await response.json();
            // Devolvemos la propiedad 'existe' de los datos recibidos
            return data.existe;
        } else {
            // Si la respuesta no es exitosa, lanzamos un error
            throw new Error('Error en la solicitud AJAX');
        }
    } catch (error) {
        // Si ocurre un error durante la solicitud AJAX, mostramos un mensaje de error
        document.getElementById('mensajeErrorEmpresa').style.display = 'block';
        return false; // Devolvemos false para indicar que no se encontró la empresa
    }

}

async function validarUsuario(nombreUsuario) {
    // Realizar la solicitud AJAX solo si el parámetro no está vacío
    if (nombreUsuario.trim() !== '') {
        try {
            const response = await fetch('../controllers/validations/valid_usuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ nombreUsuario: nombreUsuario })
            });

            if (response.ok) {
                const data = await response.json();
                return data.usuario; // Suponiendo que el servidor devuelve un objeto con la información del usuario
            } else {
                throw new Error('Error en la solicitud AJAX');
            }
        } catch (error) {
            // Si ocurre un error durante la solicitud AJAX, mostramos un mensaje de error
            document.getElementById('mensajeErrorUsuario').style.display = 'block';
            return false; // Devolvemos false para indicar que no se encontró el usuario
        }
    } else {
        // Si el campo está vacío, mostramos un mensaje de error y devolvemos false
        document.getElementById('mensajeErrorUsuario').style.display = 'block';
        return false;
    }
}









function cerrarMensaje() {
    // Ocultar el mensaje emergente estableciendo su estilo de visualización a "none"
    document.getElementById('mensajeEmergente').style.display = 'none';
}

















// Luego, activar la función enviarIdTicketAlControlador()
// enviarIdTicketAlControlador();


// Función para abrir el modal
function openFileModal() {
    var fileModal = document.getElementById("fileModal");
    fileModal.style.display = "block";
}

// Función para cerrar el modal
function closeFileModal() {
    var fileModal = document.getElementById("fileModal");
    fileModal.style.display = "none";
}

// Agrega un evento al botón "Administrar Archivos Adjuntos" para abrir el modal
var openFileModalBtn = document.getElementById("openFileModal");
openFileModalBtn.addEventListener("click", openFileModal);

// Agrega un evento al botón de cierre para cerrar el modal
var closeFileModalBtn = document.getElementById("closeFileModal");
closeFileModalBtn.addEventListener("click", closeFileModal);

// Cierra el modal cuando se hace clic fuera del contenido del modal
window.onclick = function (event) {
    var fileModal = document.getElementById("fileModal");
    if (event.target == fileModal) {
        fileModal.style.display = "none";
    }
};


function OrdenTabs(evt, OrdenTabs_nombre) {
    // Declaración de todas las variables
    var i, tabcontent, tablinks;

    // Ocultar todos los elementos con clase "tabcontent_Notas" y "tabcontent_series"
    tabcontent = document.getElementsByClassName("tabcontent_Notas");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tabcontent = document.getElementsByClassName("tabcontent_series");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Ocultar todos los elementos con clase "tab-content" para la pestaña "Actividades"
    var tabcontentActividades = document.getElementsByClassName("tab-content_actividades");
    for (i = 0; i < tabcontentActividades.length; i++) {
        tabcontentActividades[i].style.display = "none";
    }

    // Quitar la clase "active" de todos los elementos con clase "tablinks"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Mostrar la pestaña actual y agregar la clase "active" al botón correspondiente
    if (OrdenTabs_nombre === "Notas") {
        document.getElementById(OrdenTabs_nombre).style.display = "block";
    } else if (OrdenTabs_nombre === "Series") {
        document.getElementById(OrdenTabs_nombre).style.display = "flex";
    } else if (OrdenTabs_nombre === "Actividades") {
        document.getElementById(OrdenTabs_nombre).style.display = "block";
    }
    evt.currentTarget.className += " active";

    // Desplazar el scroll a la pestaña activa
    evt.currentTarget.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}







$(document).ready(function () {
    $('#cliente').select2();
});




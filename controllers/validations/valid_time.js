// Variables para el contador de inactividad
var tiempoLimiteMilisegundos = 1800000 ; 
// 5 minutos (300000 milisegundos) 10 minutos (600000 milisegundos) 30 minutos (1800000 milisegundos): 10 segundos 10000 milisegundos



// Variables para el contador del modal
var tiempoLimiteModalMilisegundos = 30000; // 30 segundos 
var contadorRegresivoModal;
var intervaloContadorModal;

// Elementos HTML
var contadorElementoModal = document.getElementById("contador-regresivo-modal");
var mensajeContadorElemento = document.getElementById("mensaje-contador");
var btnAceptar = document.getElementById("btn-aceptar");
var btnCancelar = document.getElementById("btn-cancelar");

// Función para iniciar el contador regresivo del modal
function iniciarContadorModal() {
    intervaloContadorModal = setInterval(function() {
        contadorRegresivoModal -= 1000;
        if (contadorRegresivoModal <= 0) {
            clearInterval(intervaloContadorModal);
            // Cerrar sesión automáticamente cuando se agote el tiempo del modal
            window.location.href = '../controllers/logout.php'; // Cambia la URL según tu necesidad
        } else {
            var segundosRestantes = Math.ceil(contadorRegresivoModal / 1000);
            contadorElementoModal.textContent = "Continuar en " + segundosRestantes + " segundos";
        }
        
    }, 1000);
}

// Función para mostrar el mensaje de confirmación personalizado
function mostrarMensajeContinuar() {
    // Mostrar el mensaje en el modal
    mensajeContadorElemento.innerHTML = "Tu sesión ha expirado debido a inactividad. ¿Deseas continuar?";

    // Iniciar el contador regresivo del modal
    contadorRegresivoModal = tiempoLimiteModalMilisegundos;
    iniciarContadorModal();

    // Mostrar los botones "Aceptar" y "Cancelar"
    document.getElementById("botones-accion").style.display = "block";
}

// Función para cerrar la sesión con confirmación
function cerrarSesionConConfirmacion() {
    // Realizar otras acciones para cerrar la sesión aquí si es necesario
    // ...

    // Redirigir al usuario a la página de cierre de sesión o ejecutar la acción correspondiente
    window.location.href = '../controllers/logout.php'; 

    // Ocultar el modal
    $('#modal-mensaje').modal('hide');

    // Reiniciar el contador regresivo del modal
    contadorElementoModal.textContent = "";
}

// Evento para mostrar el modal y empezar a contar
$('#modal-mensaje').on('shown.bs.modal', function() {
    mostrarMensajeContinuar();
});

// Evento para el botón "Aceptar"
btnAceptar.addEventListener("click", function() {
    // Reiniciar el contador regresivo del modal
    contadorRegresivoModal = tiempoLimiteModalMilisegundos;
    contadorElementoModal.textContent = "";

    // Detener el intervalo del contador del modal
    clearInterval(intervaloContadorModal);

    // Ocultar el modal
    $('#modal-mensaje').modal('hide');
});

// Evento para el botón "Cancelar"
btnCancelar.addEventListener("click", function() {
    cerrarSesionConConfirmacion();
});

// Verificar la inactividad inicialmente y luego cada segundo
function verificarInactividad() {
    var ultimaActividad = Date.now();

    // Registra eventos de movimiento del mouse y pulsaciones de teclas
    document.addEventListener('mousemove', function() {
        ultimaActividad = Date.now();
    });
    document.addEventListener('keydown', function() {
        ultimaActividad = Date.now();
    });

    // Función para verificar la inactividad y mostrar el modal
    function verificar() {
        var tiempoInactivo = Date.now() - ultimaActividad;

        if (tiempoInactivo >= tiempoLimiteMilisegundos) {
            // El usuario ha estado inactivo durante demasiado tiempo, mostrar el modal
            $('#modal-mensaje').modal('show');
        }
       // console.log(tiempoInactivo); // tiempo continuo 
    }

    // Verificar la inactividad inicialmente
    verificar();

    // Verificar la inactividad cada segundo
    setInterval(verificar, 1000);

}


// Evento para clic fuera del modal
$(document).on('click', function(e) {
    if ($(e.target).is('#modal-mensaje')) { // Si el clic fue en el fondo del modal
        reiniciarModal();
        
    }
});

// Función para reiniciar el contador del modal y ocultarlo
function reiniciarModal() {
    // Reiniciar el contador regresivo del modal
    contadorRegresivoModal = tiempoLimiteModalMilisegundos;
    contadorElementoModal.textContent = "";

    // Detener el intervalo del contador del modal
    clearInterval(intervaloContadorModal);

    // Ocultar el modal
    $('#modal-mensaje').modal('hide');
}

// Iniciar la verificación de inactividad al cargar la página
verificarInactividad();


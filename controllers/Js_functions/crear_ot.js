function enviarIdTicketAlControlador(idTicket) {

    console.log(idTicket);


    // Realizar una solicitud AJAX para enviar el idTicket al controlador
    $.ajax({
        type: 'POST',
        url: '../controllers/controlador_storage.php', // Reemplaza con la URL correcta del controlador
        data: {
            id_ticket: idTicket
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Aquí puedes manejar la respuesta del controlador si es necesario
            console.log('Respuesta del controlador:', response);

            // Después de completar la solicitud AJAX, enviar el formulario

        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud AJAX: ' + error);
        }
    });

    // Evitar que el formulario se envíe automáticamente al hacer clic en el botón

}




<?php
 // Inicia la sesión si aún no está iniciada
 session_start();

 if (!isset($_SESSION["usuario"])) {
     // Si el usuario no está autenticado, redirige a la página de inicio de sesión
      // Guarda la URL actual en la sesión
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
     header("Location: ../index.php");
     
     exit();
 }

 if (isset($_POST["cerrar_sesion"])) {
     // Si se hizo clic en el botón "Cerrar Sesión", destruye la sesión y redirige a la página de inicio de sesión
     session_destroy();
     header("Location: ../logout.php");
     exit();
 }



?>

<!-- Modal para mostrar el mensaje de confirmación -->
<div class="modal fade" id="modal-mensaje" tabindex="-1" role="dialog" aria-labelledby="modal-mensaje-label" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin: 0 auto; max-width: 500px;">
        <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); max-width: none;">
            <div class="modal-body">
                <!-- Agrega el contador regresivo dentro del modal -->
                <span id="contador-regresivo-modal" style="color: #333; font-size: 1.2em; font-weight: bold; display: block;"></span>
                <!-- El mensaje de confirmación personalizado se mostrará aquí -->
                <div id="mensaje-contador" style="text-align: center; color: #333; font-size: 1.1em; margin-top: 10px;"></div>
                <!-- Botones para aceptar o cancelar -->
                <div id="botones-accion" style="text-align: center; margin-top: 20px;">
                    <button id="btn-aceptar" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; border-radius: 5px; padding: 8px 20px; font-size: 1.1em; font-weight: bold;">Continuar</button>
                    <button id="btn-cancelar" class="btn btn-secondary" style="background-color: #6c757d; border-color: #6c757d; border-radius: 5px; padding: 8px 20px; font-size: 1.1em; font-weight: bold; margin-left: 10px;">Cerrar ahora</button>
                </div>
            </div>
        </div>
    </div>
</div>

    
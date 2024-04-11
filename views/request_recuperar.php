<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/forgot_pwd.css">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>

<?php
    include("../config/conex_db.php");
    include("../controllers/controlador_recuperar.php");
    
    ?>

       <!-- Contenedor de alertas en la parte superior del body -->
       <div id="alert-container" class="alert-container">
        <!-- PHP incluirá los mensajes de alerta aquí si los hay -->
        <?php
            if (isset($mensaje_alerta)) {
                echo $mensaje_alerta; // Asume que $mensaje_alerta contiene el HTML del mensaje de alerta
            }
        ?>
    </div>
<div class="container">
    <div class="logo-column">
        <!-- Reemplaza 'path_to_logo.png' con la ruta a tu logo -->
        
    </div>
    <div class="form-column">
        <h2>Recuperar Contraseña</h2>
        
        <form method="post" action="">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="correo" value="elincreiblecorreo@hotmail.com"  maxLength="40 "required><br>
            
            <input name="btnRecuperar" type="submit" value="Recuperar Contraseña">
            <a href="../index.php" class="btn-accion" style="text-decoration: none; color: white;">Regresar</a>


        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../controllers/validations/valid_forgot.js"> </script> 
<script>
window.onload = function() {
    setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000); // Oculta la alerta después de 5 segundos
};

    </script>
</body>
</html>

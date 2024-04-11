<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ManageMate inicio de Sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/login2.css">
</head>

<body>
    <div class="separate"></div>
    <div class="login-container">
        <div class="image_container">
            <img src="../images/logomenu.png" class="logomenu">
        </div>
        <h2>¡Bienvenido! Ingresa tus datos.</h2>
        <form action="" method="POST">
            <?php
            include("../config/conexion_bd.php");
            include("../controllers/controlador.php");
            ?>
            <div class="form-group">
                <label for="usuario">Correo:</label>
                <input type="text" id="usuario" name="usuario" value="elincreiblecorreo@hotmail.com" onkeyup="validarCorreo()">
                <div id="errorCorreo" class="error-message"></div> <!-- Mensaje de error para el correo -->
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="password" autocomplete="no" value="asd123" onkeyup="validarContrasena()">
                <div id="errorContrasena" class="error-message"></div> <!-- Mensaje de error para la contraseña -->
            </div>
            <div class="form-group">
                <a href="request_recuperar.php">¿Olvidaste tu contraseña?</a>
            </div>
            <button name="bntingresar" type="submit">Iniciar Sesión</button>


        </form>
    </div>


    <script src="../controllers/validations/valid_form_login.js"> </script>

</body>

</html>
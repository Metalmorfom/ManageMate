<!DOCTYPE html>
<html>

<head>
    <title>Recuperar usuario</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/reset_pwd.css">
    <style>
        .valid {
            border: 2px solid green;
        }
    </style>
</head>

<body>
    <h1>Nueva Contraseña</h1>

    <?php include('../controllers/Recuperar.php'); ?>

    <?php if ($showForm) : ?>
        <form method="post" onsubmit="return validateForm();">
            <label for="contrasenia">Ingresa tu nueva contraseña:</label>
            <input type="password" name="contrasenia" id="contrasenia" required oninput="checkPassword()" value="">
            <br>
            <span id="passwordExample" style="color: red;"></span>
            <br>
            <label for="contraseniarepetir">Repite nueva contraseña:</label>
            <input type="password" name="contraseniarepetir" id="contraseniarepetir" required oninput="checkPassword()" value="">
            <br>
            <span id="passwordMatchMessage"></span>
            <br>
            <input type="submit" value="Restablecer contraseña">
        </form>
    <?php else : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <script type="text/javascript" src="../controllers/validations/valid_reset_pwd.js">
     
    </script>
</body>

</html>
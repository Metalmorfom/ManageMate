<!DOCTYPE html>
<html>

<head>
    <title>Crear Usuario</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">


    <!-- Hojas de estilo CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="/path/to/select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

    <link rel="stylesheet" type="text/css" href="../css/registro.css">
</head>

<body>


    <?php
    include("menu.php");
    include("../config/conexion_bd.php"); // Incluye el archivo de conexión a la base de datos.
    include("../controllers/controlador_registro.php"); // Incluye el archivo del controlador.



    // Consulta los códigos de teléfono desde la base de datos.
    $query_codigos = "SELECT id_codigo, codigo FROM cod_telefono";
    $result_codigos = $conexion->query($query_codigos);

    // Consulta los roles desde la base de datos.
    $query_roles = "SELECT id_rol, nombre_rol FROM rol";
    $result_roles = $conexion->query($query_roles);

    ?>
<?php include("side_menu_top.php");?>


    
    <form action="" method="POST" id="crearuserformi"onsubmit="return validarFormulario()">
        <h2>Gestion de usuario</h2>
        <h2></h2>
        <div class="form-group">
            <label for="rut_user">Rut usuario:</label>
            <input type="text" name="rut_user" id="rut_user" maxlength="12" value="">
            <span id="rut_user-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" class="limitar-caracteres" required value="">
            <span id="nombre-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="s_nombre">Segundo nombre:</label>
            <input type="text" name="s_nombre" id="s_nombre" class="limitar-caracteres" required value="">
            <span id="s_nombre-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="ap_paterno">Apellido paterno:</label>
            <input type="text" name="ap_paterno" id="ap_paterno" class="limitar-caracteres" required value="">
            <span id="ap_paterno-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="ap_materno">Apellido materno:</label>
            <input type="text" name="ap_materno" id="ap_materno" class="limitar-caracteres" required value="">
            <span id="ap_materno-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" value="" required>
            <span id="correo-error" style="color: red;"></span>
        </div>
        <div class="form-group">
            <label for="contrasenia">Contraseña:</label>
            <input type="password" name="contrasenia" id="contrasenia" required>
            <span id="contrasenia-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="repite_contrasenia">Repite Contraseña:</label>
            <input type="password" name="repite_contrasenia" id="repite_contrasenia" required>
            <span id="contrasenia-error" style="color: red;"></span>
        </div>

        <div class="form-group">
        <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" class="limitar-caracteres" required value="">
            <span id="usuario-error" style="color: red;"></span>

        </div>

       




        <div class="form-group phone-inputs">
            <div class="input-group">
                <label for="telefono">Teléfono:(opcional)</label>
            </div>
            <div class="input-group">
                <select name="id_codigo" id="id_codigo">
                    <?php
                    // Genera opciones del select con datos de la tabla de códigos de teléfono.
                    while ($row = $result_codigos->fetch_assoc()) {
                        echo '<option value="' . $row['id_codigo'] . '">' . $row['codigo'] . '</option>';
                    }
                    ?>
                </select>
                <input type="text" name="telefono" id="telefono">
            </div>
            <span id="telefono-error" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="fecha_expiracion">Fecha de Expiración:</label>
            <input type="date" name="fecha_expiracion" id="fecha_expiracion" onchange="validarFechaExpiracion()">
            <span id="fecha_expiracion-error" style="color: red;"></span>
        </div>
        <div class="form-group">
            <label for="id_rol">Rol:</label>
            <select name="id_rol" id="id_rol" class="select2">
                <option value="">-seleccione-</option>
                <?php
                // Genera opciones del select con datos de la tabla de roles.
                while ($row = $result_roles->fetch_assoc()) {
                    echo '<option value="' . $row['id_rol'] . '">' . $row['nombre_rol'] . '</option>';
                }
                ?>
            </select>
            <span id="id_rol-error" style="color: red;"></span>
        </div>
        <div class="form-group" style="margin-top: 20px;">
           
            <input type="checkbox" id="miCheckbox" name="miCheckbox" class="custom-checkbox">
            <label for="miCheckbox">Confirma registro?</label>
            <span id="miCheckbox-error" style="color: red;"></span>
        </div>

        

        <div class="full-width">
            <button name="btnRegistrarse" type="submit" class="button-like" id="btnSubmit" disabled>Crear usuario</button>
        </div>

        <div class="full-width">
            <a href="inicio.php" class="button-like">Cancelar</a>
        </div>
    </form>


  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#id_rol').select2();
        });
    </script>











    <script src="../controllers/validations/valid_register.js"> </script>


    <?php include("side_menu_bot.php");?>


</body>

</html>
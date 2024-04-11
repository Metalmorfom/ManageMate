<!DOCTYPE html>
<html>

<head>
    <title>Perfil de Usuario</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/edit_perfil.css">
</head>

<body>
    <?php
    include("menu.php");
    include("../controllers/controlador_perfil.php");
    ?>

    <div class="header">
        <h1>Perfil de Usuario</h1>
    </div>
    <!-- Contenedor de alertas en la parte superior del body -->
    <div id="alert-container" class="alert-container">
        <!-- PHP incluirá los mensajes de alerta aquí si los hay -->
        <?php
        if (isset($mensaje_alerta)) {
            echo $mensaje_alerta; // Asume que $mensaje_alerta contiene el HTML del mensaje de alerta
        }
        ?>
    </div>

    <div class="form_container">
        <form method="post" id="perfilForm" action="">
            <div class="input_group">
                <label for="nuevoNombre">Nombre:</label>
                <input type="text" id="nuevoNombre" name="nuevoNombre" value="<?php echo $nombre; ?>">
            </div>

            <div class="input_group">
                <label for="nuevoSNombre">Segundo Nombre:</label>
                <input type="text" id="nuevoSNombre" name="nuevoSNombre" value="<?php echo $s_nombre; ?>">
                <input type="hidden" id="nuevoSNombre" name="nuevoSNombre" value="<?php echo $s_nombre; ?>">
            </div>

            <div class="input_group">
                <label for="nuevoApPaterno">Apellido Paterno:</label>
                <input type="text" id="nuevoApPaterno" name="nuevoApPaterno" value="<?php echo $ap_paterno; ?>">
            </div>

            <div class="input_group">
                <label for="nuevoApMaterno">Apellido Materno:</label>
                <input type="text" id="nuevoApMaterno" name="nuevoApMaterno" value="<?php echo $ap_materno; ?>">
            </div>

            <div class="input_group">
                <label for="nuevoCorreo">Correo Electrónico:</label>
                <input type="email" id="nuevoCorreo" name="nuevoCorreo" class="" value="<?php echo $correo; ?>">
            </div>




            <div class="form-group phone-inputs">
                <div class="input-group">
                    <label for="nuevoTelefono">Teléfono:(opcional)</label>
                </div>
                <div class="input-group">
                    <select name="id_codigo" id="id_codigo">
                        <?php
                        while ($row = $result_codigos->fetch_assoc()) {
                            $selected = ($row['id_codigo'] == $id_selec_code) ? 'selected' : '';
                            echo '<option value="' . $row['id_codigo'] . '" ' . $selected . '>' . $row['codigo'] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="text" name="nuevoTelefono" id="telefono" value="<?php echo $telefono; ?>">
                </div>
                <span id="telefono-error" style="color: red;"></span>
            </div>

            <div class="input_group">
                <input type="submit" value="Actualizar Información">
                
            </div>
        </form>
      

    </div>
    <!-- boostrap 5-->
   
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- boostrap 5-->

    <script src="../controllers/validations/valid_edit_perfil.js"></script>
</body>

</html>
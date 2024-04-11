<!DOCTYPE html>
<html>

<head>
    <title>menu nav</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../css/menu.css">

   
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php
   
    include('../controllers/validations/valid_time.php');
    include('../controllers/validations/controlador_permisos.php');
    

    ?>

    <script src="../controllers/validations/valid_time.js"> </script>
</head>

<body>


    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand logomenu" href="#" style=" border-radius:5% ;width:150px; height:55px">
                <img src="../images/logonav.png" alt="Logo" style="height: 100%; width :100%; border-radius:5%"> <!-- Ajusta la altura como necesites -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="inicio.php">Home</a>
                    </li>
                    <!-- más elementos de la lista -->
                </ul>
                <div class="d-flex align-items-center">
                    <!-- Contenedor del nombre de usuario y la tuerca -->
                    <div class="d-flex align-items-center me-2">
                        <span class="nombre-usuario"><?= $_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["ap_paterno"] . " " . $_SESSION["usuario"]["ap_materno"]; ?></span>

                        <div class="dropdown ms-2">
                            <button class="btn btn-link btn-tuerca " type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none; ">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                <?php
                                // Define los permisos requeridos como un array
                                $request = [11];
                                // Llama a la función para verificar los permisos
                                if (verificarPermisos($request)) {
                                    echo '<li><a class="dropdown-item" href="editar_usuario.php">Perfil</a></li>';
                                } ?>


                                <?php
                                // Define los permisos requeridos como un array
                                $request = [4];
                                // Llama a la función para verificar los permisos
                                if (verificarPermisos($request)) {
                                    echo '<li><a class="dropdown-item" href="registro.php">Crear usuarios</a></li>';
                                } ?>

                                <?php
                                // Define los permisos requeridos como un array
                                $request = [2];
                                // Llama a la función para verificar los permisos
                                if (verificarPermisos($request)) {
                                    echo ' <li><a class="dropdown-item" href="makeot.php">Crear ticket</a></li>';
                                } ?>

                                <?php
                                // Define los permisos requeridos como un array
                                $request = [1];
                                // Llama a la función para verificar los permisos
                                if (verificarPermisos($request)) {
                                    echo '<li><a class="dropdown-item" href="Listar_OT.php">Listar tickets</a></li>';
                                } ?>

                                <?php
                                // Define los permisos requeridos como un array
                                $request = [4];
                                // Llama a la función para verificar los permisos
                                if (verificarPermisos($request)) {
                                    echo ' <li><a class="dropdown-item" href="users.php">Listar usuarios</a></li>';
                                } else echo ' <li><a class="dropdown-item" href="users.php">Listar usuarios</a></li>'; ?>

                                <!--<li><a class="dropdown-item" href="busqueda.php">Busqueda</a></li>-->

                                <?php
                                // Define los permisos requeridos como un array
                                $request = [12];
                                // Llama a la función para verificar los permisos
                                if (verificarPermisos($request)) {
                                    echo '<li> <button class="dropdown-item" type="button" onclick="abrirVentanaEmergente();">Test SMTP</button></li>';
                                } ?>

                                <li><a class="dropdown-item" href="../controllers/logout.php">Cerrar Sesión</a></li>
                                <!-- Agrega más elementos aquí si es necesario -->
                            </ul>
                        </div>
                    </div>
                    <div class="formulario_busqueda">


                        <form id="filtroForm" action="busqueda.php" method="GET" style="display: flex; align-items: center; gap: 10px;">
                            <div class="select2" style="width: 150px; overflow:hidden; height:30px; width :200px; line-height:none;">

                                <select class="form-select" name="filtro_cliente" style="width: 100%; overflow-x: hidden;" id="selectCliente">
                                    <?php
                                    // Incluye el controlador_menu.php para obtener las opciones del menú de empresas
                                    include("../controllers/controlador_Menu_nav.php");

                                    // Imprime las opciones del menú obtenidas del controlador_menu.php
                                    echo $opcionesMenu;
                                    ?>
                                </select>
                            </div>

                            <input class="form-control me-2" type="text" name="filtro_numero_ticket" placeholder="Buscar" style="height:28px; width :200px; border-radius: 2px; padding: 0px; margin: 0px; outline: none; box-shadow: none;" value="<?php echo isset($_GET['filtro_numero_ticket']) ? $_GET['filtro_numero_ticket'] : ''; ?>" style="flex: 1;">
                            <button class="btn btn-primary" type="submit" style="min-width: auto; width: 80px; height: 30px; border-radius: 5px; text-align: center; padding: 0px; margin: 0px;">Buscar</button>


                        </form>


                    </div>





                    <script>
                        // Verifica si hay datos almacenados en localStorage
                        if (localStorage.getItem('filtro_numero_ticket')) {
                            // Si hay datos, establece el valor del campo de número de ticket
                            document.querySelector("input[name='filtro_numero_ticket']").value = localStorage.getItem('filtro_numero_ticket');

                        }

                        if (localStorage.getItem('filtro_cliente')) {

                            // Si hay datos, establece el valor del campo select de cliente
                            document.querySelector("select[name='filtro_cliente']").value = localStorage.getItem('filtro_cliente');
                        }


                        document.addEventListener("DOMContentLoaded", function() {
                            var botonBuscar = document.querySelector("button[type='submit']");
                            botonBuscar.addEventListener("click", function(e) {
                                e.preventDefault(); // Cancela el envío del formulario

                                // Obtén los valores de los campos
                                var numeroTicket = document.querySelector("input[name='filtro_numero_ticket']").value;
                                var clienteSeleccionado = document.querySelector("select[name='filtro_cliente']").value;

                                // Realiza la solicitud AJAX
                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "../controllers/validations/validar_busqueda.php", true);
                                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                xhr.onreadystatechange = function() {
                                    if (xhr.readyState == 4 && xhr.status == 200) {
                                        if (xhr.responseText.trim() === 'true') {
                                            // El ticket existe, enviar el formulario
                                            localStorage.setItem('filtro_numero_ticket', document.querySelector("input[name='filtro_numero_ticket']").value);
                                            localStorage.setItem('filtro_cliente', document.querySelector("select[name='filtro_cliente']").value);

                                            // Codifica los valores en Base64 y redirige
                                            var encodedNumeroTicket = btoa(numeroTicket);
                                            var encodedClienteSeleccionado = btoa(clienteSeleccionado);
                                            window.location.href = "busqueda.php?filtro_cliente=" + encodedClienteSeleccionado + "&filtro_numero_ticket=" + encodedNumeroTicket;
                                        } else {
                                            alert("Ticket no encontrado");
                                        }
                                    }
                                };

                                // Envía los datos al archivo PHP
                                var data = "filtro_cliente=" + clienteSeleccionado + "&filtro_numero_ticket=" + numeroTicket;
                                xhr.send(data);
                            });
                        });
                    </script>

                    <script>
                        // Aplicar Select2 al elemento select
                        $('#selectCliente').select2();
                    </script>
                </div>
            </div>
        </div>
    </nav>
    <script>
        var popup = null;

        function abrirVentanaEmergente() {
            if (popup && !popup.closed) {
                popup.close(); // Cierra la ventana emergente si está abierta
            }

            var ventanaAncho = 600; // Ancho de la ventana emergente
            var ventanaAlto = 300; // Alto de la ventana emergente

            // Calcula las coordenadas para centrar la ventana en el navegador
            var ventanaX = (window.innerWidth - ventanaAncho) / 2;
            var ventanaY = (window.innerHeight - ventanaAlto) / 2;

            // Abre una nueva ventana emergente centrada
            popup = window.open('', 'Resultado de la prueba SMTP', 'width=' + ventanaAncho + ',height=' + ventanaAlto + ',left=' + ventanaX + ',top=' + ventanaY + ',scrollbars=yes');

            // Llama a la función PHP para probar la conexión SMTP a través de AJAX
            $.post('../controllers/TEST_SMTP.php', {
                probar_conexion: true
            }, function(data) {
                popup.document.write('<html><body>' + data + '</body></html>');
                popup.focus();
            });
        }
    </script>
</body>

</html>
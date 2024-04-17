<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">


    <style>
        /* Estilos CSS para el side menu */
        .sidebar {
            height: 150vh;
            width: 250px;
            /* Ancho del side menu */
            position: absolute;
            top: 70px;
            left: -230px;
            /* Inicialmente oculto */
            background-color: #343a40 !important;
            padding-top: 20px;
            transition: left 0.3s, width 0.3s 0.1s;
            /* Transición suave para el movimiento ojaaa */
            z-index: 100;
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            list-style-type: none;
            /* Quita el "IL marker" */
        }

        .sidebar a:hover {
            background-color: #444;
        }

        /* Estilo para el botón de mostrar cuando el sidebar está oculto */
        #showSidebarBtn {
            position: fixed;
            top: 95%;
            left: 0;
            transform: translateY(-50%);
            background-color: #333;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: left 0.3s;
            /* Transición suave para el movimiento */
            z-index: 200;
            margin-left: 10px;
            /* Margen izquierdo añadido */
        }

        #showSidebarBtn:hover {
            background-color: #555;
            z-index: 200;
        }

        .side-menu-top-container,
        .side-menu-bot-container {
            height: 100%;
            /* Ajustar la altura al 100% del cuerpo si es necesario */
        }

        /* Nuevos estilos adicionales */
        .sidebar li {
            margin-bottom: 10px;
            /* Agrega espacio entre elementos de la lista */
        }

        .sidebar li:last-child {
            margin-bottom: 0;
            /* Quita el margen inferior del último elemento de la lista */
        }

        li:active {
            background-color: transparent !important;
            /* Hace que el fondo de la lista sea transparente durante el clic */
        }

        li::marker {
            content: "";
            /* Contenido vacío para quitar el marcador de lista */
        }

        .sidebar a:focus {
            outline: none;
            /* Quita el borde al enfocar */
            background-color: transparent;
            /* Hace que el fondo sea transparente cuando se enfoca */
        }

        .sidebar a:active {
            color: white;
            /* Mantiene el color del texto blanco cuando se hace clic */
        }
    </style>



    <div class="side-menu-top-container">
        <!-- Side menu -->
        <div id="sidebar" class="sidebar">



            <?php
            // Define los permisos requeridos como un array
            $request = [2];
            // Llama a la función para verificar los permisos
            if (verificarPermisos($request)) {
                echo ' <li><a class="item" href="makeot.php">Crear ticket</a></li>';
            } ?>



            <?php
            // Define los permisos requeridos como un array
            $request = [1];
            // Llama a la función para verificar los permisos
            if (verificarPermisos($request)) {
                echo '<li><a class="item" href="Listar_OT.php">Listar tickets</a></li>';
            } ?>


            <?php
            // Define los permisos requeridos como un array
            $request = [4];
            // Llama a la función para verificar los permisos
            if (verificarPermisos($request)) {
                echo ' <li><a class="item" href="users.php">Listar usuarios</a></li>';
            } else echo ' <li><a class="item" href="users.php">Listar usuarios</a></li>'; ?>



            <?php
            // Define los permisos requeridos como un array
            $request = [12];
            // Llama a la función para verificar los permisos
            if (verificarPermisos($request)) {
                echo '<li> <button class="item" type="button" onclick="abrirVentanaEmergente();">Test SMTP</button></li>';
            } ?>
            <?php
            // Define los permisos requeridos como un array
            $request = [4];
            // Llama a la función para verificar los permisos
            if (verificarPermisos($request)) {
                echo '<li><a class="item" href="registro.php">Crear usuarios</a></li>';
            } ?>



          
            <a href="../controllers/logout.php">Cerrar Sesión</a>
        </div>

        <!-- Botón para mostrar el side menu cuando está oculto -->
        <button id="showSidebarBtn" onclick="toggleSidebar()">
            <<< /button>
    </div>
    <!-- Contenido principal -->
    <div id="main-content" style="margin-left: 0;">
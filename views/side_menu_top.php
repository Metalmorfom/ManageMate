<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
 
    <style>
        /* Estilos CSS para el side menu */
        .sidebar {
            height: 150vh;
            width: 250px; /* Ancho del side menu */
            position: absolute;
            top: 70px;
            left: -230px; /* Inicialmente oculto */
            background-color: #343a40!important;
            padding-top: 20px;
            transition: left 0.3s, width 0.3s 0.1s; /* Transición suave para el movimiento ojaaa */
            z-index: 100;
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
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
            transition: left 0.3s; /* Transición suave para el movimiento */
            z-index: 200;
        }

        #showSidebarBtn:hover {
            background-color: #555;
            z-index: 200;
        }

        .side-menu-top-container, .side-menu-bot-container {
    height: 100%; /* Ajustar la altura al 100% del cuerpo si es necesario */
}
    </style>

<div class="side-menu-top-container">
<!-- Side menu -->
<div id="sidebar" class="sidebar">
  
    <a href="#">Inicio</a>
    <a href="#">Perfil</a>
    <a href="#">Configuración</a>
    <a href="#">Cerrar Sesión</a>
</div>

<!-- Botón para mostrar el side menu cuando está oculto -->
<button id="showSidebarBtn" onclick="toggleSidebar()"><<</button>
</div>
<!-- Contenido principal -->
<div id="main-content" style="margin-left: 0;">
 
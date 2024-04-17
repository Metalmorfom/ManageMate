<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuarios</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">

    <!-- Hojas de estilo CSS de Select2 y Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

    <!-- Hoja de estilo personalizada -->
    <link rel="stylesheet" href="../css/users_list.css">
</head>

<body>
    <?php
    include("menu.php");
    include("../config/conexion_bd.php");
    ?>
<?php include("side_menu_top.php");?>

    <nav class="menu-horizontal">
        <ul class="menu-items">
            <li>
                <label class="lb_subtitlo">Usuarios</label>
            </li>
        </ul>

        <ul>
            <li>
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Buscar por nombre" onkeyup="searchOnEnter(event)">
                    <button onclick="searchUsers()">Buscar</button>
                    <button onclick="resetSearch()">Reset</button> <!-- Botón de Reset -->
                </div>
            </li>
        </ul>

    </nav>

    
    <div class="contenedor">
        <!-- Botón de exportar a Excel -->
        <button type="button" id="exportToExcel">Exportar a Excel</button>

        <!-- Tabla de usuarios -->
        <table id="userTable" border="1" style="border-radius: 5%;">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Rut <span class="sort-icon"></span></th>
                    <th onclick="sortTable(1)">Nombre <span class="sort-icon"></span></th>
                    <th onclick="sortTable(2)">Seg. Nombre <span class="sort-icon"></span></th>
                    <th onclick="sortTable(3)">A.Paterno <span class="sort-icon"></span></th>
                    <th onclick="sortTable(4)">A. Materno <span class="sort-icon"></span></th>
                    <th onclick="sortTable(5)">Correo <span class="sort-icon"></span></th>
                    <th onclick="sortTable(6)">Telf. <span class="sort-icon"></span></th>
                    <th onclick="sortTable(7)">Cód Telf. <span class="sort-icon"></span></th>
                    <th onclick="sortTable(8)">Rol <span class="sort-icon"></span></th>
                    <th onclick="sortTable(9)">Estado <span class="sort-icon"></span></th>
                    <th onclick="sortTable(10)">F. de Creación <span class="sort-icon"></span></th>
                    <th onclick="sortTable(11)">F. de Expiración <span class="sort-icon"></span></th>
                    <th>Acciones</th>
                </tr>
            </thead>


            <tbody>
                <?php
                include("../controllers/controlador_user_list.php");

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['rut_user'] . '</td>';
                        echo '<td>' . $row['nombre'] . '</td>';
                        echo '<td>' . $row['s_nombre'] . '</td>';
                        echo '<td>' . $row['ap_paterno'] . '</td>';
                        echo '<td>' . $row['ap_materno'] . '</td>';
                        echo '<td>' . $row['correo'] . '</td>';
                        echo '<td>' . $row['telefono'] . '</td>';
                        echo '<td>' . $row['codigo'] . '</td>'; // Código Teléfono
                        echo '<td>' . $row['nombre_rol'] . '</td>'; // Rol
                        echo '<td>' . $row['nombre_estado'] . '</td>'; // Estado
                        echo '<td>' . $row['fecha_creacion'] . '</td>';
                        echo '<td>' . $row['fecha_expiracion'] . '</td>';
                        // echo '<td><button onclick="editarUsuario(' . $row['rut_user'] . ')">Editar</button></td>';
                        echo '<td><a href="users_edit.php?id=' . $row['rut_user'] . '">Editar</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="12">No hay usuarios registrados.</td></tr>';
                }
                ?>
            </tbody>

        </table>
        <div id="pagination-wrapper"></div>


    </div>





    <!-- Agrega tus scripts adicionales aquí -->

    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

    <script src="../controllers/Js_functions/users_list.js"> </script>
    <?php include("side_menu_bot.php");?>
</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Tickets</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/list.css">
</head>

<body>

    <?php include("Menu.php"); ?>
    <?php include("side_menu_top.php");?>


    <nav class="menu-horizontal">
        <ul class="menu-items">
            <li>
                <label class="lb_subtitlo">Elementos</label>
            </li>
        </ul>
        <ul>
            <li>
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Buscar tickets..." onkeyup="searchOnEnter(event)">
                    <button onclick="searchTickets()">Buscar</button>
                    <button onclick="resetSearch()">Resetear</button>
                    <button id="crear_ticket" class="btn_nuevo">
                        <i class="fas fa-plus-circle"></i> Nuevo
                    </button>
                </div>


            </li>
        </ul>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var botonNuevo = document.getElementById('crear_ticket');
            botonNuevo.addEventListener('click', function() {
                window.location.href = 'makeot.php';
            });
        });
    </script>

    <div class="contenedor">
        <!-- Contenedor de búsqueda -->


        <!-- Botón para exportar a Excel -->
        <button id="exportToExcel">Exportar a Excel</button>

        <!-- Tabla de Tickets -->
        <table id="ticketTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Nro <span class="sort-icon"></span></th>
                    <th onclick="sortTable(1)">Resumen<span class="sort-icon"></span></th>
                    <th onclick="sortTable(2)">Descripción<span class="sort-icon"></span></th>
                    <th onclick="sortTable(3)">F. Creación<span class="sort-icon"></span></th>
                    <th onclick="sortTable(4)">Abierto<span class="sort-icon"></span></th>
                    <th onclick="sortTable(5)">Asignado<span class="sort-icon"></span></th>
                    <th onclick="sortTable(6)">Local<span class="sort-icon"></span></th>
                    <th onclick="sortTable(7)">Solicitante<span class="sort-icon"></span></th>
                    <th onclick="sortTable(8)">Estado<span class="sort-icon"></span></th>
                    <th onclick="sortTable(9)">Prioridad<span class="sort-icon"></span></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("../controllers/controlador_list.php");
                if ($resultado) {
                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_assoc()) {
                            $filtro_cliente_encoded = base64_encode('Servicios Gestionados'); // Encripta el valor del cliente
                            $filtro_numero_ticket_encoded = base64_encode($fila['numero']); // Encripta el número de ticket
                
                            $url = "busqueda.php?filtro_cliente=" . urlencode($filtro_cliente_encoded) . "&filtro_numero_ticket=" . urlencode($filtro_numero_ticket_encoded);
                
                            echo '<tr>';
                            echo '<td><a href="' . $url . '">' . $fila['numero'] . '</a></td>'; // Hace que solo el número sea un enlace
                            echo '<td>' . $fila['resumen'] . '</td>';
                            echo '<td>' . $fila['descripcion'] . '</td>';
                            echo '<td>' . $fila['fecha_creacion'] . '</td>';
                            echo '<td>' . $fila['abierto_por'] . '</td>';
                            echo '<td>' . ($fila['nombre_usuario'] === "Comodin Comodin" ? "No Asignado" : $fila['nombre_usuario']) . '</td>';

                            echo '<td>' . $fila['nombre_empresa'] . '</td>';
                            echo '<td>' . $fila['nombre_cliente'] . '</td>';
                            echo '<td>' . $fila['nombre_estado'] . '</td>';
                            echo '<td>' . $fila['nombre_prioridad'] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10">No se encontraron existancias.</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="10">Error en la consulta: ' . $conexion->error . '</td></tr>';
                }
                ?>

            </tbody>
        </table>

        <!-- Paginación -->
        <div id="pagination-wrapper"></div>
    </div>
    <?php include("side_menu_bot.php");?>

    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <script src="../controllers/Js_functions/list_ot.js"> </script>
</body>

</html>
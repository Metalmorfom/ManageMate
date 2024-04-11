<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">

    <!-- Incluye aquí cualquier otro script o biblioteca que necesites -->
    <!-- Incluye Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <!-- Incluye el plugin datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
</head>

<body>
    <?php
    include("menu.php");
    include("side_menu_top.php");
    include("../config/conexion_bd.php");
    include("../controllers/controlador_inicio.php")
    // Asegúrate de que el archivo menu.php maneja el inicio de sesión y redirecciona si es necesario.
    ?>



    <div class="dashboard">
        <div class="dashboard_in">
            <!-- Tarjetas de resumen -->
            <div class="card-container">

                <div class="card">
                    <div class="header_card">
                        <button id="btn_actualizar_sin_asignar" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- asin_asignar -->
                        <span class="card-title" style="user-select: none;">Sin Asignar</span>
                    </div>
                    <?php
                    $nuevo_encoded = base64_encode("Nuevo");
                    ?>
                    <?php if (isset($nuevo_encoded)) : ?>
                        <span class="card_wrapper">
                            <span class="card-value" id="card_sin_asignar" style="transition: color 0.2s; user-select: none;"></span>
                            <div id="cargando_message_sin_asignar" style="display: none; user-select: none;">Cargando...</div>
                        </span>
                    <?php endif; ?>
                </div>




                <div class="card">
                    <div class="header_card">
                        <button id="btn_actualizar_Progreso" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- Progreso -->
                        <span class="card-title" style="user-select: none;">Progreso</span>
                    </div>
                    <?php
                    $progre_encoded = base64_encode("En Progreso");

                    ?>
                    <?php if (isset($progre_encoded)) : ?>
                        <span class="card_wrapper">
                            <span class="card-value" id="card_Progreso" style="transition: color 0.2s; user-select: none;"></span>
                            <div id="cargando_message_Progreso" style="display: none; user-select: none;">Cargando...</div>
                        </span>
                    <?php
                    endif;
                    ?>
                </div>


                <div class="card">
                    <div class="header_card">
                        <button id="btn_actualizar_Pendiente" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- Pendiente -->
                        <span class="card-title" style="user-select: none;">Pendiente</span>
                    </div>
                    <?php
                    $pendi_encoded = base64_encode("Pendiente");

                    ?>
                    <?php if (isset($pendi_encoded)) : ?>
                        <span class="card_wrapper">
                            <span class="card-value" id="card_Pendiente" style="transition: color 0.2s; user-select: none;"></span>
                            <div id="cargando_message_Pendiente" style="display: none; user-select: none;">Cargando...</div>
                        </span>
                    <?php
                    endif;
                    ?>
                </div>


                <div class="card">
                    <div class="header_card">
                        <button id="btn_actualizar_Asignados" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- Asignados -->
                        <span class="card-title" style="user-select: none;">Asignados</span>
                    </div>
                    <?php
                    $asing_encoded = base64_encode("asignado");
                    $rut_encoded = base64_encode($rut_usuario_activo);

                    ?>
                    <?php if (isset($pendi_encoded)) : ?>
                        <span class="card_wrapper">
                            <span class="card-value" id="card_asignado" style="transition: color 0.2s; user-select: none;"></span>
                            <div id="cargando_message_asignado" style="display: none; user-select: none;">Cargando...</div>
                        </span>
                    <?php
                    endif;
                    ?>
                </div>


                <div class="card">
                    <div class="header_card">
                        <button id="btn_actualizar_Resueltos" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- Resueltos -->
                        <span class="card-title" style="user-select: none;">Resueltos</span>
                    </div>
                    <?php
                    $Resuelto_encoded = base64_encode("Resuelto");

                    ?>
                    <?php if (isset($Resuelto_encoded)) : ?>
                        <span class="card_wrapper">
                            <span class="card-value" id="card_Resuelto" style="transition: color 0.2s;user-select: none;" ></span>
                            <div id="cargando_message_Resuelto" style="display: none; user-select: none;">Cargando...</div>
                        </span>
                    <?php
                    endif;
                    ?>
                </div>




                <div class="card">
                    <div class="header_card">
                        <button id="btn_actualizar_Totales" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- Totales -->
                        <span class="card-title" style="user-select: none;">Totales</span>
                    </div>
                    <?php
                    $total_encoded = base64_encode("total");

                    ?>
                    <?php if (isset($total_encoded)) : ?>
                        <span class="card_wrapper">
                            <span class="card-value" id="card_total" style="transition: color 0.2s; user-select: none;"></span>
                            <div id="cargando_message_Total" style="display: none; user-select: none;">Cargando...</div>
                        </span>

                    <?php
                    endif;
                    ?>
                </div>

                <!-- Repite la estructura de la tarjeta para las demás tarjetas -->
            </div>

            <style>
                a:hover {
                    color: orange !important;
                }
            </style>

            <!-- Gráficos y estadísticas -->
            <!-- Gráficos y estadísticas -->
            <div class="charts-wrapper">

                <div class="chart-container">
                    <button id="btnActualizarPieChart" class="btn-actualizar"><i class="fas fa-sync"></i></button> <!-- Actualizar Pie Chart -->

                    <div class="cuerca">
                        <i class="fas fa-cog btnConfiguracion" id="btnConfiguracion"></i>
                    </div>

                    <div id="configuracionPopup">
                        <label for="tiempoActualizacion">Tiempo de Actualización (segundos):</label>
                        <input type="number" id="tiempoActualizacion" min="1" value="30">
                        <div class="fechas">
                            <!-- Nuevos elementos para los selectores de fecha -->
                            <input type="checkbox" id="checkboxFechas" />
                            <label for="checkboxFechas" style="user-select: none;">Buscar por fechas</label>

                        </div>

                        <label for="fechaInicial">Fecha Inicial:</label>
                        <input type="date" id="fechaInicial" class="fecha-inicial" disabled value="<?php echo date('Y-m-d'); ?>">
                        <label for="fechaTermino">Fecha Término:</label>
                        <input type="date" id="fechaTermino" class="fecha-termino" disabled value="<?php echo date('Y-m-d'); ?>">


                        <!-- Fin de los nuevos elementos -->
                        <button id="btnGuardarConfiguracion">Guardar</button>
                    </div>




                    <canvas id="pieChart"></canvas>
                    <div id="loadingMessage" style="display: none; user-select: none;">Cargando...</div>

                </div>

                <div class="chart-container">
                    <button id="btnActualizarResolvedTicketsChart" class="btn-actualizar"><i class="fas fa-sync"></i></button>


                    <div class="cuerca">
                        <i class="fas fa-cog btnConfiguracionResolved"></i>
                    </div>


                    <div id="configuracionPopupResolved">
                        <label for="tiempoActualizacionResolved">Tiempo de Actualización (segundos):</label>
                        <input type="number" id="tiempoActualizacionResolved" min="1" value="30">
                        <div class="fechas">
                            <!-- Nuevos elementos para los selectores de fecha -->
                            <input type="checkbox" id="checkboxFechasResolved" />
                            <label for="checkboxFechasResolved" style="user-select: none;">Buscar por fechas</label>
                        </div>
                        <label for="fechaInicialResolved">Fecha Inicial:</label>
                        <input type="date" id="fechaInicialResolved" class="fecha-inicial" disabled value="<?php echo date('Y-m-d'); ?>">
                        <label for="fechaTerminoResolved">Fecha Término:</label>
                        <input type="date" id="fechaTerminoResolved" class="fecha-termino" disabled value="<?php echo date('Y-m-d'); ?>">

                        <button id="btnGuardarConfiguracionResolved">Guardar</button>
                    </div>




                    <canvas id="resolvedTicketsChart"></canvas>
                    <div id="loadingMessageResolved" style="display: none; user-select: none;">Cargando...</div>
                </div>

                <div class="chart-container">
                    <button id="btnActualizarHorizontalBarChart" class="btn-actualizar"><i class="fas fa-sync"></i></button>
                    <div class="cuerca">
                        <i class="fas fa-cog btnConfiguracionHorizontal"></i>
                    </div>
                    <div id="configuracionPopupHorizontal">
                        <label for="tiempoActualizacionHorizontal">Tiempo de Actualización (segundos):</label>
                        <input type="number" id="tiempoActualizacionHorizontal" min="1" value="30">
                        <div class="fechas">
                            <!-- Nuevos elementos para los selectores de fecha -->
                            <input type="checkbox" id="checkboxFechasHorizontal" />
                            <label for="checkboxFechasHorizontal" style="user-select: none;">Buscar por fechas</label>
                        </div>
                        <label for="fechaInicialHorizontal">Fecha Inicial:</label>
                        <input type="date" id="fechaInicialHorizontal" class="fecha-inicial" disabled value="<?php echo date('Y-m-d'); ?>">
                        <label for="fechaTerminoHorizontal">Fecha Término:</label>
                        <input type="date" id="fechaTerminoHorizontal" class="fecha-termino" disabled value="<?php echo date('Y-m-d'); ?>">

                        <button id="btnGuardarConfiguracionHorizontal">Guardar</button>
                    </div>

                    <canvas id="horizontalBarChart"></canvas>
                    <div id="loadingMessageHorizontal" style="display: none; user-select: none;">Cargando...</div>
                </div>


            </div>



            <script>
                $(document).ready(function() {
                    // Función para realizar la solicitud AJAX y actualizar los datos
                    function actualizarSinAsignar() {
                        // Mostrar mensaje de carga
                        $('#cargando_message_sin_asignar').show();


                        // Realiza la solicitud AJAX
                        $.ajax({
                            url: '../controllers/cards_update/sin_asignar.php', // El archivo PHP que obtiene el conteo
                            type: 'GET', // Método de solicitud
                            dataType: 'json', // Tipo de datos esperados
                            success: function(response) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_sin_asignar').hide();

                                // Maneja la respuesta del servidor
                                if (response.success) {
                                    // Actualiza el valor del conteo en el span con la clase card-value
                                    $('#card_sin_asignar').text(response.sin_asignar);

                                    // Verifica si el valor es mayor que cero y agrega el enlace correspondiente
                                    if (response.sin_asignar > 0) {
                                        var nuevo_encoded = '<?php echo $nuevo_encoded; ?>';
                                        $('#card_sin_asignar').wrap('<a href="Listar_OT.php?' + nuevo_encoded + '" style="text-decoration: none; color: inherit;"></a>');
                                    }
                                } else {
                                    console.error('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_sin_asignar').hide();

                                // Maneja los errores de la solicitud AJAX
                                console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                            }
                        });
                    }
                    // Llama a la función de actualización al hacer clic en el botón de actualizar
                    $('#btn_actualizar_sin_asignar').click(function() {
                        actualizarSinAsignar();
                    });

                    // Llama a la función de actualización inicialmente
                    actualizarSinAsignar();

                    // Establece una actualización automática cada 30 segundos
                    setInterval(actualizarSinAsignar, 30000); // 30 segundos
                });






                $(document).ready(function() {
                    // Función para realizar la solicitud AJAX y actualizar los datos
                    function actualizarProgreso() {
                        // Mostrar mensaje de carga
                        $('#cargando_message_Progreso').show();

                        // Realiza la solicitud AJAX
                        $.ajax({
                            url: '../controllers/cards_update/progreso.php', // El archivo PHP que obtiene el conteo
                            type: 'GET', // Método de solicitud
                            dataType: 'json', // Tipo de datos esperados
                            success: function(response) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Progreso').hide();

                                // Maneja la respuesta del servidor
                                if (response.success) {
                                    // Actualiza el valor del conteo en el span con la clase card-value
                                    $('#card_Progreso').text(response.progreso);

                                    // Verifica si el valor es mayor que cero y agrega el enlace correspondiente
                                    if (response.progreso > 0) {
                                        var progreso_encoded = '<?php echo $progre_encoded; ?>';
                                        $('#card_Progreso').wrap('<a href="Listar_OT.php?' + progreso_encoded + '" style="text-decoration: none; color: inherit;"></a>');
                                    }
                                } else {
                                    console.error('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Progreso').hide();

                                // Maneja los errores de la solicitud AJAX
                                console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                            }
                        });
                    }
                    // Llama a la función de actualización al hacer clic en el botón de actualizar
                    $('#btn_actualizar_Progreso').click(function() {
                        actualizarProgreso();
                    });

                    // Llama a la función de actualización inicialmente
                    actualizarProgreso();

                    // Establece una actualización automática cada 30 segundos
                    setInterval(actualizarProgreso, 30000); // 30 segundos
                });






                $(document).ready(function() {
                    // Función para realizar la solicitud AJAX y actualizar los datos
                    function actualizarPendiente() {
                        // Mostrar mensaje de carga
                        $('#cargando_message_Pendiente').show();

                        // Realiza la solicitud AJAX
                        $.ajax({
                            url: '../controllers/cards_update/Pendiente.php', // El archivo PHP que obtiene el conteo
                            type: 'GET', // Método de solicitud
                            dataType: 'json', // Tipo de datos esperados
                            success: function(response) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Pendiente').hide();

                                // Maneja la respuesta del servidor
                                if (response.success) {
                                    // Actualiza el valor del conteo en el span con la clase card-value
                                    $('#card_Pendiente').text(response.pendiente);

                                    // Verifica si el valor es mayor que cero y agrega el enlace correspondiente
                                    if (response.pendiente > 0) {
                                        var pendi_encoded = '<?php echo $pendi_encoded; ?>';
                                        $('#card_Pendiente').wrap('<a href="Listar_OT.php?' + pendi_encoded + '" style="text-decoration: none; color: inherit;"></a>');
                                    }
                                } else {
                                    console.error('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Pendiente').hide();

                                // Maneja los errores de la solicitud AJAX
                                console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                            }
                        });
                    }
                    // Llama a la función de actualización al hacer clic en el botón de actualizar
                    $('#btn_actualizar_Pendiente').click(function() {
                        actualizarPendiente();
                    });

                    // Llama a la función de actualización inicialmente
                    actualizarPendiente();

                    // Establece una actualización automática cada 30 segundos
                    setInterval(actualizarPendiente, 30000); // 30 segundos
                });




                $(document).ready(function() {
                    // Función para realizar la solicitud AJAX y actualizar los datos
                    function actualizarResueltos() {
                        // Mostrar mensaje de carga
                        $('#cargando_message_Resuelto').show();

                        // Realiza la solicitud AJAX
                        $.ajax({
                            url: '../controllers/cards_update/resueltos.php', // El archivo PHP que obtiene el conteo
                            type: 'GET', // Método de solicitud
                            dataType: 'json', // Tipo de datos esperados
                            success: function(response) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Resuelto').hide();

                                // Maneja la respuesta del servidor
                                if (response.success) {
                                    // Actualiza el valor del conteo en el span con la clase card-value
                                    $('#card_Resuelto').text(response.resuelto);

                                    // Verifica si el valor es mayor que cero y agrega el enlace correspondiente
                                    if (response.resuelto > 0) {
                                        var Resuelto_encoded = '<?php echo $Resuelto_encoded; ?>';
                                        $('#card_Resuelto').wrap('<a href="Listar_OT.php?' + Resuelto_encoded + '" style="text-decoration: none; color: inherit;"></a>');
                                    }
                                } else {
                                    console.error('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Resuelto').hide();

                                // Maneja los errores de la solicitud AJAX
                                console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                            }
                        });
                    }
                    // Llama a la función de actualización al hacer clic en el botón de actualizar
                    $('#btn_actualizar_Resueltos').click(function() {
                        actualizarResueltos();
                    });

                    // Llama a la función de actualización inicialmente
                    actualizarResueltos();

                    // Establece una actualización automática cada 30 segundos
                    setInterval(actualizarResueltos, 30000); // 30 segundos
                });




                $(document).ready(function() {
                    // Función para realizar la solicitud AJAX y actualizar los datos
                    function actualizarTotal() {
                        // Mostrar mensaje de carga
                        $('#cargando_message_Total').show();

                        // Realiza la solicitud AJAX
                        $.ajax({
                            url: '../controllers/cards_update/total.php', // El archivo PHP que obtiene el conteo
                            type: 'GET', // Método de solicitud
                            dataType: 'json', // Tipo de datos esperados
                            success: function(response) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Total').hide();

                                // Maneja la respuesta del servidor
                                if (response.success) {
                                    // Actualiza el valor del conteo en el span con la clase card-value
                                    $('#card_total').text(response.total);

                                    // Verifica si el valor es mayor que cero y agrega el enlace correspondiente
                                    if (response.total > 0) {
                                        var total_encoded = '<?php echo $total_encoded; ?>';
                                        $('#card_total').wrap('<a href="Listar_OT.php?' + total_encoded + '" style="text-decoration: none; color: inherit;"></a>');
                                    }
                                } else {
                                    console.error('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_Total').hide();

                                // Maneja los errores de la solicitud AJAX
                                console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                            }
                        });
                    }
                    // Llama a la función de actualización al hacer clic en el botón de actualizar
                    $('#btn_actualizar_Totales').click(function() {
                        console.log("ejecutado total");
                        actualizarTotal();
                    });

                    // Llama a la función de actualización inicialmente
                    actualizarTotal();

                    // Establece una actualización automática cada 30 segundos
                    setInterval(actualizarTotal, 30000); // 30 segundos
                });




                $(document).ready(function() {
                    // Función para realizar la solicitud AJAX y actualizar los datos
                    function actualizarAsignado() {
                        // Mostrar mensaje de carga
                        $('#cargando_message_asignado').show();

                        // Realiza la solicitud AJAX
                        $.ajax({
                            url: '../controllers/cards_update/asignado.php', // El archivo PHP que obtiene el conteo
                            type: 'GET', // Método de solicitud
                            dataType: 'json', // Tipo de datos esperados
                            success: function(response) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_asignado').hide();

                                // Maneja la respuesta del servidor
                                if (response.success) {
                                    // Actualiza el valor del conteo en el span con la clase card-value
                                    $('#card_asignado').text(response.ami);

                                    // Verifica si el valor es mayor que cero y agrega el enlace correspondiente
                                    if (response.ami > 0) {
                                        var asing_encoded = '<?php echo $asing_encoded; ?>';
                                        var rut_encoded = '<?php echo $rut_encoded; ?>';
                                        $('#card_asignado').wrap('<a href="Listar_OT.php?' + asing_encoded + '=' + rut_encoded + '" style="text-decoration: none; color: inherit;"></a>');

                                    }
                                } else {
                                    console.error('Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Ocultar mensaje de carga
                                $('#cargando_message_asignado').hide();

                                // Maneja los errores de la solicitud AJAX
                                console.error('Error en la solicitud AJAX: ' + status + ' - ' + error);
                            }
                        });
                    }
                    // Llama a la función de actualización al hacer clic en el botón de actualizar
                    $('#btn_actualizar_Asignados').click(function() {
                        console.log("ejecutado actualizarAsignado");
                        actualizarAsignado();
                    });

                    // Llama a la función de actualización inicialmente
                    actualizarAsignado();

                    // Establece una actualización automática cada 30 segundos
                    setInterval(actualizarAsignado, 30000); // 30 segundos
                });
            </script>


































            <script>
                // Función para el primer conjunto de checkbox y fechas
                function toggleDates() {
                    var checkbox = document.getElementById("checkboxFechas");
                    var fechaInicial = document.getElementById("fechaInicial");
                    var fechaTermino = document.getElementById("fechaTermino");

                    if (checkbox.checked) {
                        fechaInicial.disabled = false;
                        fechaTermino.disabled = false;
                    } else {
                        fechaInicial.disabled = true;
                        fechaTermino.disabled = true;
                    }
                }

                // Función para el segundo conjunto de checkbox y fechas
                function toggleDatesResolved() {
                    var checkbox = document.getElementById("checkboxFechasResolved");
                    var fechaInicial = document.getElementById("fechaInicialResolved");
                    var fechaTermino = document.getElementById("fechaTerminoResolved");

                    if (checkbox.checked) {
                        fechaInicial.disabled = false;
                        fechaTermino.disabled = false;
                    } else {
                        fechaInicial.disabled = true;
                        fechaTermino.disabled = true;
                    }
                }

                // Función para el tercer conjunto de checkbox y fechas
                function toggleDatesHorizontal() {
                    var checkbox = document.getElementById("checkboxFechasHorizontal");
                    var fechaInicial = document.getElementById("fechaInicialHorizontal");
                    var fechaTermino = document.getElementById("fechaTerminoHorizontal");

                    if (checkbox.checked) {
                        fechaInicial.disabled = false;
                        fechaTermino.disabled = false;
                    } else {
                        fechaInicial.disabled = true;
                        fechaTermino.disabled = true;
                    }
                }

                // Agregar event listeners
                document.getElementById("checkboxFechas").addEventListener("change", toggleDates);
                document.getElementById("checkboxFechasResolved").addEventListener("change", toggleDatesResolved);
                document.getElementById("checkboxFechasHorizontal").addEventListener("change", toggleDatesHorizontal);
            </script>


            <style>
                #configuracionPopup {
                    display: none;
                    position: absolute;
                    background-color: #fefefe;
                    border: 1px solid #888;
                    padding: 10px;
                    z-index: 1;
                }

                #configuracionPopup label {
                    display: block;
                }

                #configuracionPopup input {
                    width: 50px;

                }

                #configuracionPopup button {
                    background-color: #007BFF;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    cursor: pointer;
                }

                #configuracionPopup button:hover {
                    background-color: #0056b3;
                }

                #configuracionPopupResolved,
                #configuracionPopupHorizontal {
                    display: none;
                    position: absolute;
                    background-color: #fefefe;
                    border: 1px solid #888;
                    padding: 10px;
                    z-index: 1;
                }

                #configuracionPopupResolved label,
                #configuracionPopupHorizontal label {
                    display: block;
                }

                #configuracionPopupResolved input,
                #configuracionPopupHorizontal input {
                    width: 50px;

                }

                #configuracionPopupResolved button,
                #configuracionPopupHorizontal button {
                    background-color: #007BFF;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    cursor: pointer;
                }

                #configuracionPopupResolved button:hover,
                #configuracionPopupHorizontal button:hover {
                    background-color: #0056b3;
                }
            </style>

            <style>
                /* Estilos para los botones de actualización y configuración */
                .btn-actualizar,
                .btnConfiguracionResolved:hover,
                .btnConfiguracion:hover,
                .btnConfiguracionHorizontal:hover {
                    background-color: transparent;
                    /* Fondo transparente */
                    border: none;
                    /* Sin borde */
                    padding: 0;
                    /* Sin relleno */
                    cursor: pointer;
                    /* Cursor de puntero */
                    font-size: 16px;
                    /* Tamaño de fuente personalizado */
                }

                /* Elimina cualquier subrayado o estilo de enlace */
                .btn-actualizar:focus,
                .btnConfiguracionResolved:focus,
                .btnConfiguracion:focus,
                .btnConfiguracionHorizontal:focus {
                    outline: none;
                    text-decoration: none;
                }

                /* Estilos para la animación cuando se hace clic */
                .btn-actualizar:active,
                .btnConfiguracionResolved:active,
                .btnConfiguracion:active,
                .btnConfiguracionHorizontal:active {
                    animation: spin 0.2s linear;
                    /* Aplica la animación 'spin' durante 0.2 segundos de manera lineal al hacer clic */
                }

                /* Agrega una rotación al ícono cuando se hace hover */
                .btn-actualizar:hover,
                .btnConfiguracionResolved:hover,
                .btnConfiguracion:hover,
                .btnConfiguracionHorizontal:hover {
                    color: #007bff;
                    /* Cambia el color del texto al pasar el mouse */
                    transform: rotate(360deg);
                    /* Aplica una rotación de 360 grados en sentido horario */
                }

                /* Definición de la animación 'spin' */
                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                .chart-container {
                    display: flex;
                    justify-content: space-between;
                    /* Alinea los elementos a los extremos */
                    align-items: center;
                    /* Alinea verticalmente los elementos */
                }



                /* Estilos para los botones de actualización */
                .chart-container button.btn-actualizar {
                    background-color: transparent;
                    border: none;
                    padding: 0;
                    cursor: pointer;
                    font-size: 16px;
                }

                /* Estilos para la animación cuando se hace clic */
                .chart-container button.btn-actualizar:active {
                    animation: spin 0.2s linear;
                }

                /* Estilos para la rotación del ícono al hacer hover */
                .chart-container button.btn-actualizar:hover {
                    color: #007bff;
                    transform: rotate(360deg);
                }

                /* Definición de la animación 'spin' */
                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* Estilos para el contenido de los popups de configuración */
                #configuracionPopup,
                #configuracionPopupResolved,
                #configuracionPopupHorizontal {
                    background-color: #fefefe;
                    border: 1px solid #888;
                    padding: 10px;
                    z-index: 1;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                #configuracionPopup label,
                #configuracionPopupResolved label,
                #configuracionPopupHorizontal label {
                    display: block;
                    margin-bottom: 5px;
                }

                /* Estilos para los inputs (excluyendo checkboxes) dentro de los popups de configuración */
                #configuracionPopup input:not([type="checkbox"]),
                #configuracionPopupResolved input:not([type="checkbox"]),
                #configuracionPopupHorizontal input:not([type="checkbox"]) {
                    width: calc(100% - 22px);
                    /* Se ajusta al ancho del contenedor */
                    padding: 5px;
                    margin-bottom: 10px;
                    border: 1px solid #ccc;
                    border-radius: 3px;
                }


                #configuracionPopup button,
                #configuracionPopupResolved button,
                #configuracionPopupHorizontal button {
                    background-color: #007BFF;
                    color: white;
                    border: none;
                    padding: 8px 12px;
                    cursor: pointer;
                    border-radius: 3px;
                }

                #configuracionPopup button:hover,
                #configuracionPopupResolved button:hover,
                #configuracionPopupHorizontal button:hover {
                    background-color: #0056b3;
                }

                .configuracion-container {
                    display: flex;
                    align-items: flex-start;
                    /* Alinea los elementos al inicio verticalmente */
                }

                #configuracionPopup {
                    flex-grow: 1;
                    /* Permite que este elemento crezca para ocupar el espacio restante */
                    margin-right: 10px;
                    /* Espacio entre los elementos */
                }

                .fechas {
                    display: flex;

                    align-content: space-around;

                    align-items: center;

                }
            </style>

            <script>
                // Función para mostrar la ventana emergente de configuración
                function mostrarConfiguracion(btnConfiguracion, configuracionPopup, tiempoActualizacionInput) {
                    // Obtener las dimensiones y posición del botón
                    var botonRect = btnConfiguracion.getBoundingClientRect();

                    // Asegurarse de que el popup ya tiene dimensiones para calcular su posición correctamente
                    configuracionPopup.style.display = 'block';
                    var popupRect = configuracionPopup.getBoundingClientRect();

                    // Calcular la posición para que el botón quede centrado respecto al popup
                    var leftPosition = botonRect.left + botonRect.width / 2 - popupRect.width / 2 + window.scrollX;
                    var topPosition = botonRect.top + botonRect.height + window.scrollY; // Posición justo debajo del botón

                    // Ajustar si el popup se desborda a la derecha
                    if (leftPosition + popupRect.width > window.innerWidth) {
                        leftPosition = window.innerWidth - popupRect.width;
                    }
                    // Ajustar si el popup se desborda a la izquierda
                    if (leftPosition < 0) {
                        leftPosition = 0;
                    }

                    // Ajustar si el popup se desborda en la parte inferior
                    if (topPosition + popupRect.height > window.innerHeight) {
                        // Posicionar encima del botón si no hay espacio debajo
                        topPosition = botonRect.top - popupRect.height + window.scrollY;
                    }

                    // Aplicar los estilos calculados al popup
                    configuracionPopup.style.left = leftPosition + 'px';
                    configuracionPopup.style.top = topPosition + 'px';

                    // Configurar el valor del tiempo de actualización
                    tiempoActualizacionInput.value = obtenerTiempoActualizacion(tiempoActualizacionInput);

                    // Cerrar el popup si se hace clic fuera de él
                    function cerrarPopup(event) {
                        var clickedInsidePopup = event.target.closest('#' + configuracionPopup.id) !== null;
                        if (!clickedInsidePopup) {
                            configuracionPopup.style.display = 'none';
                            document.removeEventListener('click', cerrarPopup); // Eliminar el listener después de cerrar el popup
                        }
                    }

                    document.addEventListener('click', cerrarPopup);
                }

                // Función para obtener el tiempo de actualización guardado
                function obtenerTiempoActualizacion(tiempoActualizacionInput) {
                    return parseInt(tiempoActualizacionInput.value);
                }

                // Función para manejar los eventos de los popups
                function manejarEventosPopup(btnConfiguracion, configuracionPopup, btnGuardarConfiguracion, tiempoActualizacionInput) {
                    btnConfiguracion.addEventListener('click', function(event) {
                        mostrarConfiguracion(btnConfiguracion, configuracionPopup, tiempoActualizacionInput);
                        event.stopPropagation(); // Evitar que el evento se propague al hacer clic en el botón
                    });
                }

                // Configuración para el Pie Chart
                var btnConfiguracionPie = document.getElementById('btnConfiguracion');
                var configuracionPopupPie = document.getElementById('configuracionPopup');
                var btnGuardarConfiguracionPie = document.getElementById('btnGuardarConfiguracion');
                var tiempoActualizacionPie = document.getElementById('tiempoActualizacion');

                manejarEventosPopup(btnConfiguracionPie, configuracionPopupPie, btnGuardarConfiguracionPie, tiempoActualizacionPie);

                // Configuración para el Resolved Tickets Chart
                var btnConfiguracionResolved = document.querySelector('.btnConfiguracionResolved');
                var configuracionPopupResolved = document.getElementById('configuracionPopupResolved');
                var btnGuardarConfiguracionResolved = document.getElementById('btnGuardarConfiguracionResolved');
                var tiempoActualizacionResolved = document.getElementById('tiempoActualizacionResolved');
                var checkboxFechasResolved = document.getElementById('checkboxFechasResolved');
                var fechaInicialResolved = document.getElementById('fechaInicialResolved');
                var fechaTerminoResolved = document.getElementById('fechaTerminoResolved');

                manejarEventosPopup(btnConfiguracionResolved, configuracionPopupResolved, btnGuardarConfiguracionResolved, tiempoActualizacionResolved);

                // Configuración para el Horizontal Bar Chart
                var btnConfiguracionHorizontal = document.querySelector('.btnConfiguracionHorizontal');
                var configuracionPopupHorizontal = document.getElementById('configuracionPopupHorizontal');
                var btnGuardarConfiguracionHorizontal = document.getElementById('btnGuardarConfiguracionHorizontal');
                var tiempoActualizacionHorizontal = document.getElementById('tiempoActualizacionHorizontal');
                var checkboxFechasHorizontal = document.getElementById('checkboxFechasHorizontal');
                var fechaInicialHorizontal = document.getElementById('fechaInicialHorizontal');
                var fechaTerminoHorizontal = document.getElementById('fechaTerminoHorizontal');

                manejarEventosPopup(btnConfiguracionHorizontal, configuracionPopupHorizontal, btnGuardarConfiguracionHorizontal, tiempoActualizacionHorizontal, checkboxFechasHorizontal, fechaInicialHorizontal, fechaTerminoHorizontal);
            </script>


        </div>



    </div>


    <!-- ... Resto de tu script de Chart.js ... -->



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tiempoActualizacion = 30; // Example: Set the update interval to 30 seconds
            var fechaInicial = "";
            var fechaTermino = "";
            var buscarPorFechas = false;

            var btnGuardarConfiguracion = document.getElementById('btnGuardarConfiguracion');
            btnGuardarConfiguracion.addEventListener('click', function() {
                // Obtiene el valor de tiempoActualizacion desde el input y lo actualiza
                tiempoActualizacion = parseInt(document.getElementById('tiempoActualizacion').value);
                console.log('Tiempo de actualización guardado:', tiempoActualizacion);

                // Obtener valores de las fechas inicial y de término y guardarlos
                fechaInicial = document.getElementById('fechaInicial').value;
                fechaTermino = document.getElementById('fechaTermino').value;

                // Obtener el estado del checkbox y guardarlo
                buscarPorFechas = document.getElementById('checkboxFechas').checked;

                console.log('Fecha Inicial guardada popup:', fechaInicial);
                console.log('Fecha Término guardada popup:', fechaTermino);
                console.log('Fecha Término guardada popup:', buscarPorFechas);

                configuracionPopup.style.display = 'none';
                actualizarPieChart(myChart, fechaInicial, fechaTermino);

            });

            var ctx = document.getElementById('pieChart').getContext('2d');
            var myChart; // Definir myChart en un ámbito más amplio

            myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)', // Rojo
                            'rgba(54, 162, 235, 0.2)', // Azul
                            'rgba(255, 206, 86, 0.2)', // Amarillo
                            'rgba(75, 192, 192, 0.2)' // Verde
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        text: 'Solicitudes por servicio',
                        fontSize: 18
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var valorActual = data.datasets[0].data[tooltipItem.index];
                                return data.labels[tooltipItem.index] + ': ' + valorActual;
                            }
                        }
                    },
                    plugins: {
                        datalabels: {
                            color: '#000000',
                            formatter: function(value, context) {
                                var dataset = context.chart.data.datasets[context.datasetIndex];
                                var total = dataset.data.reduce(function(a, b) {
                                    return parseFloat(a) + parseFloat(b); // Parsea los valores como números
                                }, 0);
                                if (total === 0) {
                                    return '0.00%';
                                }
                                var porcentaje = ((value / total) * 100).toFixed(2) + "%";

                                // console.log("value : " + value);
                                // console.log("total :" + total);
                                //console.log("porcentaje: " + porcentaje)
                                return porcentaje;
                            }



                        }
                    }

                }
            });

            // Agrega un evento de clic al botón de actualización manual del Pie Chart
            document.getElementById('btnActualizarPieChart').addEventListener('click', function() {
                actualizarPieChart(myChart, fechaInicial, fechaTermino); // Pasar myChart como argumento
            });

            function actualizarPieChart(myChart, fechaInicial, fechaTermino) {
                // Muestra el mensaje de carga
                document.getElementById('loadingMessage').style.display = 'block';

                console.log('Fecha Inicial guardada:', fechaInicial);
                console.log('Fecha Término guardada:', fechaTermino);
                console.log('Fecha Término guardada:', buscarPorFechas);
                // Realiza una solicitud AJAX para obtener los datos actualizados desde tu controlador PHP
                $.ajax({
                    type: "GET",
                    url: "../controllers/graph_ajaxs/pie.php",
                    dataType: "json",
                    data: {
                        fechaInicial: fechaInicial,
                        fechaTermino: fechaTermino,
                        buscarPorFechas: buscarPorFechas
                    },
                    success: function(data) {
                        // Oculta el mensaje de carga
                        document.getElementById('loadingMessage').style.display = 'none';

                        // Actualiza las variables con los nuevos datos obtenidos de la respuesta JSON
                        myChart.data.labels = data.etiquetas;
                        myChart.data.datasets[0].data = data.conteos;
                        myChart.update();
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores si es necesario
                        console.error(xhr.responseText);

                        // Oculta el mensaje de carga en caso de error
                        document.getElementById('loadingMessage').style.display = 'none';
                    }
                });
            }

            function actualizarAutomaticamente() {

                actualizarPieChart(myChart, fechaInicial, fechaTermino);
                console.log("Actualización automática para pie Bar Chart con intervalo de " + tiempoActualizacion + " segundos");
                setTimeout(actualizarAutomaticamente, tiempoActualizacion * 1000); // Llama a la función después del tiempo de actualización
            }

            // Llama a la función para cargar el gráfico inicialmente
            actualizarPieChart(myChart, fechaInicial, fechaTermino);
            // Inicia la actualización automática
            actualizarAutomaticamente();
        });
    </script>






    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tiempoActualizacionResolved = 10; // Intervalo de actualización para Resolved Tickets Chart en segundos
            var tiempoActualizacionHorizontal = 10; // Intervalo de actualización para Horizontal Bar Chart en segundos



            var fechaInicialResolved = "";
            var fechaTerminoResolved = "";
            var buscarPorFechasResolved = false;

            var btnGuardarConfiguracionResolved = document.getElementById('btnGuardarConfiguracionResolved');
            btnGuardarConfiguracionResolved.addEventListener('click', function() {
                // Obtiene el valor de tiempoActualizacion desde el input y lo actualiza
                tiempoActualizacionResolved = parseInt(document.getElementById('tiempoActualizacionResolved').value);
                console.log('Tiempo de actualización guardado Resolved:', tiempoActualizacionResolved);

                // Obtener valores de las fechas inicial y de término y guardarlos
                fechaInicialResolved = document.getElementById('fechaInicialResolved').value;
                fechaTerminoResolved = document.getElementById('fechaTerminoResolved').value;

                // Obtener el estado del checkbox y guardarlo
                buscarPorFechasResolved = document.getElementById('checkboxFechasResolved').checked;

                console.log('Fecha Inicial guardada popup Resolved:', fechaInicialResolved);
                console.log('Fecha Término guardada popup Resolved:', fechaTerminoResolved);
                console.log('Fecha Término guardada popup Resolved:', buscarPorFechasResolved);

                configuracionPopupResolved.style.display = 'none';

                // Llama a la función para cargar el gráfico de Resolved Tickets inicialmente
                actualizarResolvedTicketsChart();

            });
            fechaInicialHorizontal = "";
            fechaTerminoHorizontal = "";
            buscarPorFechasHorizontal = false;

            var btnGuardarConfiguracionHorizontal = document.getElementById('btnGuardarConfiguracionHorizontal');
            btnGuardarConfiguracionHorizontal.addEventListener('click', function() {
                // Obtiene el valor de tiempoActualizacion desde el input y lo actualiza
                tiempoActualizacionHorizontal = parseInt(document.getElementById('tiempoActualizacionHorizontal').value);
                console.log('Tiempo de actualización guardado Horizontal:', tiempoActualizacionHorizontal);

                // Obtener valores de las fechas inicial y de término y guardarlos
                fechaInicialHorizontal = document.getElementById('fechaInicialHorizontal').value;
                fechaTerminoHorizontal = document.getElementById('fechaTerminoHorizontal').value;

                // Obtener el estado del checkbox y guardarlo
                buscarPorFechasHorizontal = document.getElementById('checkboxFechasHorizontal').checked;

                console.log('Fecha Inicial guardada popup Horizontal:', fechaInicialHorizontal);
                console.log('Fecha Término guardada popup Horizontal:', fechaTerminoHorizontal);
                console.log('Fecha Término guardada popup Horizontal:', buscarPorFechasHorizontal);

                configuracionPopupHorizontal.style.display = 'none';

                // Llama a la función para cargar el gráfico de Horizontal Bar inicialmente
                actualizarHorizontalBarChart();
            });



            // Función para actualizar Resolved Tickets Chart
            function actualizarResolvedTicketsChart(usarFechas, fechaInicial, fechaTermino) {
                document.getElementById('loadingMessageResolved').style.display = 'block';

                $.ajax({
                    type: "GET",
                    url: "../controllers/graph_ajaxs/bar_resueltos.php",
                    data: {
                        usarFechas: buscarPorFechasResolved,
                        fechaInicial: fechaInicialResolved,
                        fechaTermino: fechaTerminoResolved
                    },
                    dataType: "json",

                    success: function(data) {
                        document.getElementById('loadingMessageResolved').style.display = 'none';
                        resolvedTicketsChart.data.labels = data.labels;
                        resolvedTicketsChart.data.datasets[0].data = data.data;
                        // Calcula el nuevo valor máximo para el eje y
                        var maxValue = Math.max(...data.data) + 3; // Agrega 5 al valor más alto
                        resolvedTicketsChart.options.scales.yAxes[0].ticks.suggestedMax = maxValue;
                        resolvedTicketsChart.update();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        document.getElementById('loadingMessageResolved').style.display = 'none';
                    }
                });
            }

            // Función para actualizar Resolved Tickets Chart automáticamente
            function actualizarAutomaticamenteResolvedTickets() {
                // Obtener el tiempo de actualización específico para Resolved Tickets Chart
                var tiempoActualizacionResolved = parseInt(document.getElementById('tiempoActualizacionResolved').value);
                console.log("Actualización automática para Resolved Tickets Chart con intervalo de " + tiempoActualizacionResolved + " segundos");

                // Actualizar el Resolved Tickets Chart
                actualizarResolvedTicketsChart();

                // Configurar el siguiente intervalo de actualización
                setTimeout(actualizarAutomaticamenteResolvedTickets, tiempoActualizacionResolved * 1000);
            }

            // Inicializar Resolved Tickets Chart
            var ctxResolvedTickets = document.getElementById('resolvedTicketsChart').getContext('2d');
            var resolvedTicketsChart;

            resolvedTicketsChart = new Chart(ctxResolvedTickets, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Tickets Resueltos',
                        data: [],
                        backgroundColor: 'rgba(0, 123, 255, 0.5)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontSize: 14
                        }
                    },
                    title: {
                        display: true,
                        text: 'Tickets Resueltos',
                        fontSize: 16
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontSize: 12
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1,
                                fontSize: 12
                            }
                        }]
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    }
                }
            });

            // Agrega un evento de clic al botón de actualización manual del Resolved Tickets Chart
            document.getElementById('btnActualizarResolvedTicketsChart').addEventListener('click', function() {
                actualizarResolvedTicketsChart(); // Llama a la función de actualización
            });

            // Llama a la función para cargar el gráfico de Resolved Tickets inicialmente
            actualizarResolvedTicketsChart();
            // Inicia la actualización automática para Resolved Tickets Chart
            actualizarAutomaticamenteResolvedTickets();



            // Función para actualizar Horizontal Bar Chart
            function actualizarHorizontalBarChart() {
                document.getElementById('loadingMessageHorizontal').style.display = 'block';

                $.ajax({
                    type: "GET",
                    url: "../controllers/graph_ajaxs/horizontal_asignados.php",

                    data: {
                        usarFechas: buscarPorFechasHorizontal,
                        fechaInicial: fechaInicialHorizontal,
                        fechaTermino: fechaTerminoHorizontal
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data); // Agrega esta línea para verificar la respuesta
                        document.getElementById('loadingMessageHorizontal').style.display = 'none';
                        horizontalBarChart.data.labels = data.usuarios;
                        horizontalBarChart.data.datasets[0].data = data.cantidadTickets;

                        // Calcular el nuevo valor máximo para el eje x
                        var maxValue = Math.max(...data.cantidadTickets) + 3; // Agrega 3 al valor más alto
                        horizontalBarChart.options.scales.xAxes[0].ticks.suggestedMax = maxValue;
                        horizontalBarChart.update();
                    },

                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        document.getElementById('loadingMessageHorizontal').style.display = 'none';
                    }
                });
            }

            // Función para actualizar Horizontal Bar Chart automáticamente
            function actualizarAutomaticamenteHorizontalBarChart() {
                // Obtener el tiempo de actualización específico para Horizontal Bar Chart
                var tiempoActualizacionHorizontal = parseInt(document.getElementById('tiempoActualizacionHorizontal').value);
                console.log("Actualización automática para Horizontal Bar Chart con intervalo de " + tiempoActualizacionHorizontal + " segundos");

                // Actualizar el Horizontal Bar Chart
                actualizarHorizontalBarChart();

                // Configurar el siguiente intervalo de actualización
                setTimeout(actualizarAutomaticamenteHorizontalBarChart, tiempoActualizacionHorizontal * 1000);
            }


            // Inicializar Horizontal Bar Chart
            var ctxHorizontalBar = document.getElementById('horizontalBarChart').getContext('2d');
            var horizontalBarChart;

            horizontalBarChart = new Chart(ctxHorizontalBar, {
                type: 'horizontalBar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Cantidad de Tickets',
                        data: [],
                        backgroundColor: 'rgba(0, 123, 255, 0.5)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1,
                                userCallback: function(label, index, labels) {
                                    if (Math.floor(label) === label) {
                                        return label;
                                    }
                                }
                            }
                        }]
                    },
                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: 'Tickets asignados no resueltos',
                        fontSize: 18
                    }
                }
            });

            // Agrega un evento de clic al botón de actualización manual del Horizontal Bar Chart
            document.getElementById('btnActualizarHorizontalBarChart').addEventListener('click', function() {
                actualizarHorizontalBarChart(); // Llama a la función de actualización
            });
            // Llama a la función para cargar el gráfico de Horizontal Bar inicialmente
            actualizarHorizontalBarChart();
            // Inicia la actualización automática para Horizontal Bar Chart
            actualizarAutomaticamenteHorizontalBarChart();
        });
    </script>


<?php

include("side_menu_bot.php");
?>



</body>

</html>
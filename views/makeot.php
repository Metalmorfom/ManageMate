<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación de Ticket</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">

    <!-- Hojas de estilo CSS de Select2 y Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script> <!-- Select2 JS -->
    <!-- Hoja de estilo personalizada -->
    <link rel="stylesheet" href="../css/makeot.css">
    <script src="../controllers/Js_functions/crear_ot.js"></script> <!-- Script para crear OT -->
    <script src="../controllers/validations/valid_makeOT.js"></script> <!-- Scripts genéricos de la aplicación -->
</head>



<body>

    <?php
    include("menu.php");
    include("../config/conexion_bd.php");
    include("../controllers/controlador_makeOT.php");
    ?>


    <nav class="menu-horizontal">
        <ul class="menu-items">
            <li>
                <button id="openFileModal" class="submit_mininav">
                    <i class="fas fa-paperclip"></i> <!-- Icono de adjuntar -->
                </button>
            </li>
        </ul>
        <ul class="menu-items">
            <li>
                <button type="submit" value="Crear Ticket" id="crear_ticket" class="submit_mininav">
                    <i class="fas fa-paper-plane"></i> Enviar Orden
                </button>
            </li>
        </ul>
        <ul class="menu-items" id="archivoListNav"> <!-- Elemento para mostrar archivos adjuntos -->
            <!-- Aquí se mostrarán los archivos adjuntos -->
        </ul>
    </nav>


    <!-- Agrega el modal -->
    <div id="fileModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeFileModal">&times;</span>
            <h2>Archivos Adjuntos</h2>
            <!-- Formulario para subir archivos -->
            <form id="fileUploadForm" enctype="multipart/form-data">
                <label for="archivoInput" id="archivoLabel">Cargar Elementos :</label>
                <input type="file" id="archivoInput" name="archivoTemporal" onchange="subirArchivoTemporalmente(this.files[0])">

            </form>
            <!-- Lista de archivos adjuntos -->
            <table>
                <thead>
                    <tr>
                        <th>Nombre del Archivo:</th>
                        <th>Accion:</th>
                    </tr>
                </thead>
                <tbody id="fileList" class="elementos">

                    <!-- Aquí se agregarán los archivos adjuntos -->
                </tbody>
            </table>
            <div id="progressContainer" style="position: relative; width: 100%;">
                <progress value="0" max="100" id="progressBar" style="width: 100%;"></progress>
                <div id="progressPercentage" style="position: absolute; right: 0; top: 0;">0%</div>
            </div>

        </div>
    </div>


    <!-- Tu código HTML existente -->




    <h2>Crear requerimiento</h2>

    <div id="mensajeEmergente" class="mensaje-emergente">
        <span id="mensajeTexto">Revisar campos</span>
        <button id="cerrarMensaje" class="cerrar-mensaje" onclick="cerrarMensaje()">X</button>
    </div>




    <style>
        .mensaje-emergente {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ffcccc;
            padding: 20px;
            text-align: center;
            display: none;
            z-index: 9999;
        }

        .cerrar-mensaje {
            position: absolute;
            top: 5px;
            right: 5px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }
    </style>





    <form id="creaOTForm" method="post">
        <div class="columnas">
            <div class="columna">

                <label for="Numero">Numero</label>
                <input type="hidden" id="id_numero" name="id_numero" value="">
                <input type="text" id="numero" name="numero" value="<?php echo $formattedId ?>" readonly="readonly" style="background-color: #f0f0f0; border: 1px solid #ccc; opacity: 0.7; cursor: not-allowed;">



                <!-- Campo de búsqueda de Empresa -- local  -->
                <div class="busqueda-container">
                    <label for="empresa">Local:</label>
                    <input type="search" id="empresa" name="empresa" onkeyup="buscarEmpresa(this.value)" autocomplete="off">
                    <input type="hidden" id="rutEmpresaHidden" name="rutEmpresaHidden" autocomplete="off">
                    <div id="listaEmpresas" class="lista-sugerencias"></div> <!-- Aquí se mostrarán los resultados -->
                    <div id="mensajeErrorEmpresa" style="color: red; display: none;"></div>

                </div>




                <!-- Campo de búsqueda de Cliente-solicitante, inicialmente desactivado -->
                <div class="busqueda-container">
                    <label for="cliente">Solicitante:</label>
                    <select id="cliente" name="cliente" class="selectdos">
                        <option value="Seleccione"></option>
                        <div id="listaClientes" class="lista-empresas clientelist"></div>
                    </select>
                    <div id="mensajeErrorCliente" class="mensaje-error"></div>

                </div>

                <div class="busqueda-container">
                    <label for="asignado">Asignado:</label>
                    <input type="search" id="asignado" name="asignado" value="" onkeyup="buscarUsuario(this.value)">
                    <input type="hidden" id="rut_user_asignado" name="rut_user_asignado" value="">
                    <div id="listaUsuarios" class="lista-sugerencias"></div>
                    <div id="mensajeErrorUsuario" style="color: red; display: none;"></div>


                </div>




            </div>
            <!-- Inicio de la segunda columna -->
            <div class="columna">
                <label for="rut_user_generador">Abierto por:</label>
                <input type="text" id="rut_user_generador" name="rut_user_generador" required value="<?php echo "$usuario_activo" ?> " style="background-color: #f0f0f0; border: 1px solid #ccc; opacity: 0.7; cursor: not-allowed;">

                <label for="fecha_creacion">Fecha de Creación:</label>
                <input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $fecha_creacion; ?>" readonly style="background-color: #f0f0f0; border: 1px solid #ccc; opacity: 0.7; cursor: not-allowed;">
                <label for="estado_tk">Estado</label>
                <select id="estado_tk" name="estado_tk">
                    <option value="1">Nuevo</option>
                    <option value="2">En Progreso</option>
                    <!-- Agregar más estados según sea necesario -->
                </select>

                <!-- Fin de la primera columna -->




                <label for="prioridad">Prioridad:</label>
                <select name="prioridad" id="prioridad">
                    <?php
                    if ($result_prioridades->num_rows > 0) {
                        while ($row = $result_prioridades->fetch_assoc()) {
                            $id_prioridad = $row["correl"];
                            $nombre = $row["atrib"];
                            echo "<option value='$id_prioridad'>$nombre</option>";
                        }
                        // Cerrar la consulta después de usarla
                        $result_prioridades->close();
                    } else {
                        echo "<option value=''>No se encontraron elementos</option>";
                    }
                    ?>
                </select>




            </div>
            <!-- Fin de la segunda columna -->
        </div>
        <!-- Fin de las columnas -->



        <div id="mensajeErrorCampos" class="mensajeErrorCampos">
            <span id="mensajeTexto2"></span>
            <button id="cerrarMensaje" class="cerrar-mensaje" onclick="cerrarMensaje()">X</button>
        </div>

        <label for="resumen">Resumen:</label>
        <input type="text" id="resumen" name="resumen" maxlength="150" required><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" required class="descripcion"></textarea>
        <div id="contador-caracteres">Caracteres restantes: 4000</div><br>












        <script>
            // RUT validation functions
            var Fn = {
                validaRut: function(rutCompleto) {
                    if (!/^[0-9]+-[0-9kK]{1}$/.test(rutCompleto))
                        return false;
                    var tmp = rutCompleto.split('-');
                    var digv = tmp[1];
                    var rut = tmp[0];
                    if (digv == 'K') digv = 'k';
                    return (Fn.dv(rut) == digv);
                },
                dv: function(T) {
                    var M = 0,
                        S = 1;
                    for (; T; T = Math.floor(T / 10))
                        S = (S + T % 10 * (9 - M++ % 6)) % 11;
                    return S ? S - 1 : 'k';
                },
                mostrarError: function(mensaje) {
                    $("#rut_usuario_afectado-error").text(mensaje);
                },
                limpiarError: function() {
                    $("#rut_usuario_afectado-error").text("");
                }
            };

            // Document ready function for jQuery
            $(document).ready(function() {
                // Keyup event for RUT validation
                $("#Rut_usuario_afectado").keyup(function() {
                    var rut = $(this).val();
                    if (Fn.validaRut(rut)) {
                        Fn.limpiarError();
                    } else {
                        Fn.mostrarError("Formato de RUT debe ser xxxxxxxx-x sin puntos");
                    }
                });
            });
        </script>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var inputFields = [
                    document.getElementById('Nombre_user_completo_afectado'),
                    document.getElementById('Modelo'),
                    document.getElementById('Cargo_afectado'),
                    document.getElementById('Mandante_afectado')
                ];

                inputFields.forEach(function(field) {
                    field.addEventListener('keydown', function(event) {
                        if (this.value.length >= 50 && event.key !== "Backspace" && event.key !== "Delete") {
                            event.preventDefault();
                        }
                    });
                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var rutField = document.getElementById('Rut_usuario_afectado');

                rutField.addEventListener('keydown', function(event) {
                    if (this.value.length >= 12 && event.key !== "Backspace" && event.key !== "Delete") {
                        event.preventDefault();
                    }
                });
            });
        </script>





        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var resumenField = document.getElementById('resumen');

                resumenField.addEventListener('keydown', function(event) {
                    if (this.value.length >= 130 && event.key !== "Backspace" && event.key !== "Delete") {
                        event.preventDefault();
                    }
                });
            });
        </script>


        <!-- Encabezado de las pestañas -->



        <!-- Tab links -->
        <div class="tab">
            <span class="tab-Notas">
                <button type="button" class="tablinks active" onclick="OrdenTabs(event, 'Notas')">Notas</button>
            </span>
            <span class="tab-Componentes">
                <button type="button" class="tablinks" onclick="OrdenTabs(event, 'Series')">Informacion del usuario</button>
            </span>
            <span class="tab-Actividades">
                <button type="button" class="tablinks" onclick="OrdenTabs(event, 'Actividades')">Actividades</button>
            </span>
        </div>



        <div id="Notas" class="tabcontent_Notas ">
            <div class="columna_notas">
                <!-- Coloca aquí los campos requeridos para la pestaña "Notas" -->
                <label for="Notas_del_Trabajo">Notas de Trabajo:</label>
                <textarea type="textarea" id="Notas_del_Trabajo" name="Notas_del_Trabajo"></textarea>
                <div id="contador-notas">Caracteres restantes: 500</div>
            </div>
        </div>

        <div id="Series" class="tabcontent_series">
            <div class="columna">
                <!-- Primera columna -->
                <label for="Nombre_user_completo">Nombre completo del usuario:</label>
                <input type="text" id="Nombre_user_completo" name="Nombre_user_completo_afectado" required>




                <!-- Campo de búsqueda de Marca -->
                <div class="busqueda-container">
                    <label for="marca">Rut del usuario:</label>
                    <input type="text" id="Rut_usuario_afectado" name="Rut_usuario_afectado" autocomplete="off">
                    <div id="listaMarcas" class="lista-marcas"></div> <!-- Mostrar resultados para Marca -->
                    <div id="rut_usuario_afectado-error"></div>
                </div>

                <!-- Campo de búsqueda de Modelo, inicialmente desactivado -->
                <div class="busqueda-container">
                    <label for="Modelo">Usuario Modelo:</label>
                    <input type="text" id="Modelo" name="Modelo">
                </div>
            </div>




            <div class="columna">
                <!-- Segunda columna -->
                <label for="Cargo_afectado">Cargo:</label>
                <input type="text" id="Cargo_afectado" name="Cargo_afectado" required>

                <label for="Mandante_afectado">Mandante:</label>
                <input type="text" id="Mandante_afectado" name="Mandante_afectado" required>
            </div>
        </div>


        <div id="Actividades" class="tab-content_actividades">

            <table>
                <tr>
                    <th>Recepción</th>
                    <th>Ambiente</th>
                    <th>Accion</th>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" id="interno" name="actividades[recepcion][]" value="interno">
                        <label for="interno">Colaborador interno</label><br>
                        <input type="checkbox" id="lavadoEquipo" name="actividades[recepcion][]" value="Lavado Equipo">
                        <label for="lavadoEquipo">COY</label> <!-- lavado equipo-->
                    </td>
                    <td>
                        <input type="checkbox" id="UPW" name="actividades[ambiente][]" value="UPW">
                        <label for="UPW">UPW</label><br>
                        <input type="checkbox" id="PRB" name="actividades[ambiente][]" value="PRB">
                        <label for="PRB">PRB</label><br>
                        <input type="checkbox" id="PRD" name="actividades[ambiente][]" value="PRD">
                        <label for="PRD">PRD</label><br>
                        <input type="checkbox" id="QAS" name="actividades[ambiente][]" value="QAS">
                        <label for="QAS">QAS</label>
                    </td>
                    <td>
                        <input type="checkbox" id="Creacion" name="actividades[accion][]" value="Creacion">
                        <label for="Creacion">Creacion</label><br>
                        <input type="checkbox" id="desvinculacion" name="actividades[accion][]" value="desvinculacion">
                        <label for="desvinculacion">Desvinculacion</label><br>
                        <input type="checkbox" id="homologacion" name="actividades[accion][]" value="homologacion">
                        <label for="homologacion">Homologacion</label><br>
                        <input type="checkbox" id="reseteo" name="actividades[accion][]" value="reseteo">
                        <label for="reseteo">Reset / Modificacion</label>
                    </td>
                </tr>
                <!-- Puedes continuar agregando más filas y columnas si es necesario -->
            </table>

        </div>


    </form>


    <!--
    <div id="aviso-cookies" class="aviso-cookies">
        <p>Este sitio web utiliza cookies para asegurarse de brindarte la mejor experiencia en nuestro sitio web.</p>
        <button id="aceptar-cookies" class="btn-aceptar">Aceptar cookies</button>
        <button id="rechazar-cookies" class="btn-rechazar">Rechazar cookies</button>
    </div>

                -->



    <!-- Agrega jQuery y Select2 JS -->


    <!-- Scripts específicos de la aplicación -->
    <script src="../controllers/Js_functions/ajax_js_crearOT.js"></script> <!-- Script AJAX para crear OT -->
    <script src="../controllers/Js_functions/adjunto_ticket.js"></script> <!-- Script para adjuntar ticket -->

    <script src="../controllers/Js_functions/genericos.js"></script> <!-- Scripts genéricos de la aplicación -->




</body>

</html>
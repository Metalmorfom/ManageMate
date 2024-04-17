<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busqueda de Ticket</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">

    <!-- Hojas de estilo CSS de Select2 y Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Hoja de estilo personalizada -->
    <link rel="stylesheet" href="../css/busqueda.css">
    <script src="../controllers/Js_functions/crear_ot.js"></script> <!-- Script para crear OT -->
    <script src="../controllers/validations/valid_busqueda.js"></script>

    <script src="../controllers/Js_functions/genericos.js"></script> <!-- Scripts genéricos de la aplicación -->


</head>



<body>



    <?php
    include("menu.php");
    // Verifica si las variables están definidas y tienen valores válidos
    if (isset($_GET['filtro_cliente']) && isset($_GET['filtro_numero_ticket'])) {
        $filtroCliente = $_GET['filtro_cliente'];
        $filtroNumeroTicket = $_GET['filtro_numero_ticket'];

        // Realiza tus validaciones adicionales aquí

        // Si las validaciones son exitosas, muestra el contenido de la página
    } else {
        // Si falta alguna variable, redirige al usuario a una página de error o a la página de inicio
        header("Location: pagina_de_error.php");
        exit();
    }

    include("../config/conexion_bd.php");
    include("../controllers/controlador_busqueda.php");
    include("../controllers/load_notas.php")

    ?>

    <?php include("side_menu_top.php"); ?>
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
                <button type="submit" value="actualizar_ticket" id="actualizar_ticket" class="submit_mininav" name="guardarCambios">
                    <i class="fas fa-paper-plane"></i> Enviar Orden
                </button>
            </li>
        </ul>
        <ul class="menu-items" id="archivoListNav"> <!-- Elemento para mostrar archivos adjuntos -->
            <!-- Aquí se mostrarán los archivos adjuntos -->
        </ul>
    </nav>

    <script>
        // Obtén el botón por su ID
        var botonActualizarTicket = document.getElementById("actualizar_ticket");

        // Agrega un evento click al botón
        botonActualizarTicket.addEventListener("click", function() {
            // Obtén el formulario por su ID
            var formulario = document.getElementById("actualizarOT_FORM");

            // Verifica si el formulario existe
            if (formulario) {
                // Agrega un campo oculto al formulario para indicar que se envió de forma programática
                var campoEnviadoProgramaticamente = document.createElement("input");
                campoEnviadoProgramaticamente.type = "hidden";
                campoEnviadoProgramaticamente.name = "form_enviado";
                campoEnviadoProgramaticamente.value = "1";
                formulario.appendChild(campoEnviadoProgramaticamente);

                // Envía el formulario de forma programática
                formulario.submit();
                console.log('se envió el formulario update');
            }
        });
    </script>




    <!-- Agrega el modal -->
    <div id="fileModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeFileModal">&times;</span>
            <h2>Archivos Adjuntos</h2>
            <!-- Formulario para subir archivos -->
            <form id="fileUploadForm" enctype="multipart/form-data">
                <label for="archivoInput" id="archivoLabel">Cargar Elementos :</label>
                <input type="file" id="archivoInput" name="archivoTemporal" onchange="subirArchivodirecto(this.files[0])">

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


    <script>
        const archivosAdjuntos = [];
        const RUTAS_GCP_STORAGE = [];
        // Función para cargar el archivo temporalmente
        // Función para cargar el archivo temporalmente
        function subirArchivodirecto(archivo) {



            if (!archivo) {
                return; // No hacer nada si no hay archivo seleccionado
            }

            // Reinicia el progreso
            var progressBar = document.getElementById('progressBar');
            var progressPercentage = document.getElementById('progressPercentage');
            progressBar.value = 0;
            progressPercentage.textContent = '0%';




            // Establece la variable accionRealizada en true
            accionRealizada = true;
            console.log(" se cambio a " + accionRealizada);
            console.log(archivosAdjuntos);
            console.log('Función subirArchivoTemporalmente llamada con archivo:', archivo);
            console.log('Subiendo archivo temporal...');

            return new Promise((resolve, reject) => {
                const formDataTemporal = new FormData();
                formDataTemporal.append('archivoTemporal', archivo); // Asegúrate de que coincida con el nombre en el controlador PHP

                // Agrega el valor del campo 'numero' al formDataTemporal
                const numeroInput = document.getElementById('numero');
                const valorNumero = numeroInput.value;
                formDataTemporal.append('numero', valorNumero);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../controllers/controlador_storage_temp_directo.php', true);

                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        const porcentaje = Math.floor((e.loaded / e.total) * 100);
                        animarProgreso(porcentaje);
                    }
                };


                xhr.onload = function() {
                    console.log('Respuesta del servidor:', xhr.responseText);
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            const nombreArchivo = response.nombreArchivo;
                            // Realiza las operaciones necesarias con el nombre del archivo
                            console.log('Nombre de archivo:', nombreArchivo);

                            // Agregar el nombre del archivo a la lista de archivos adjuntos
                            archivosAdjuntos.push(nombreArchivo);
                            // Actualiza la lista de archivos en el modal
                            actualizarListaArchivos_directo();
                        } else {
                            console.error('Error al subir el archivo temporal:', response.mensaje);
                            // Muestra un mensaje de error en la interfaz
                        }
                    } catch (error) {
                        console.error('Error al analizar la respuesta JSON:', error);
                        // Maneja el error en la respuesta JSON
                    }
                };


                xhr.send(formDataTemporal);
            });
        }


        function animarProgreso(objetivo) {
            let progressBar = document.getElementById('progressBar');
            let valorActual = progressBar.value;
            let animacion = setInterval(function() {
                let diferencia = objetivo - valorActual;

                // Si la diferencia es muy pequeña, la establecemos directamente y detenemos la animación
                if (diferencia < 0.1) {
                    progressBar.value = objetivo;
                    document.getElementById('progressPercentage').textContent = objetivo.toFixed(2) + '%';
                    clearInterval(animacion);
                } else {
                    // Incrementamos el valor de forma proporcional a la diferencia
                    valorActual += diferencia / 10;
                    progressBar.value = valorActual;
                    document.getElementById('progressPercentage').textContent = valorActual.toFixed(2) + '%';
                }
            }, 10); // Intervalo de la animación
        }

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const porcentaje = (e.loaded / e.total) * 100;
                animarProgreso(porcentaje);
            }
        };






        // Event listener para el cambio de archivo en el input
        const archivoInput = document.getElementById('archivoInput');
        archivoInput.addEventListener('change', function() {
            const archivo = archivoInput.files[0];
            if (archivo) {
                subirArchivoTemporalmente(archivo)
                    .then((respuesta) => {
                        console.log('Respuesta exitosa:', respuesta);
                        // Actualiza la interfaz o muestra un mensaje de éxito
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        // Muestra un mensaje de error en la interfaz
                    });
            }
        });


        function actualizarListaArchivos_directo() {
            const fileList = document.getElementById('fileList');
            const fileListNav = document.getElementById('archivoListNav');
            fileList.innerHTML = ''; // Vacía la lista para evitar duplicados
            fileListNav.innerHTML = ''; // Vacía la lista del menú de navegación

            archivosAdjuntos.forEach(nombreArchivo => {
                const nombreFormateado = formatearNombreArchivo(nombreArchivo);
                const rutaLocal = `../temp/${nombreArchivo}`; // Asegúrate de que esta ruta sea correcta

                // Crear fila y columnas para la tabla
                const fila = document.createElement('tr');
                const nombre = document.createElement('td');
                nombre.id = 'nombre-td';

                // Crear un enlace para descargar el archivo en la tabla
                const downloadLinkTabla = document.createElement('a');
                downloadLinkTabla.download = nombreArchivo;
                const iconoTabla = document.createElement('i');
                iconoTabla.className = 'fas fa-file-alt';
                iconoTabla.style.marginRight = '5px';
                const textSpanTabla = document.createElement('span');
                textSpanTabla.textContent = nombreFormateado;

                // Agregar icono y texto al enlace de descarga
                downloadLinkTabla.appendChild(iconoTabla);
                downloadLinkTabla.appendChild(textSpanTabla);
                nombre.appendChild(downloadLinkTabla);

                // Crear botón de eliminar y agregarlo a la fila
                const acciones = document.createElement('td');
                const eliminar = document.createElement('button');
                eliminar.textContent = 'Eliminar';
                eliminar.className = 'eliminar_adjuntos';
                eliminar.addEventListener('click', () => {
                    eliminarArchivo(nombreArchivo);
                });
                acciones.appendChild(eliminar);
                fila.appendChild(nombre);
                fila.appendChild(acciones);
                fileList.appendChild(fila);

                // Crear contenedores y enlace para la lista de navegación
                const listItemNav = document.createElement('li');
                const iconoContenedor = document.createElement('span');
                iconoContenedor.className = 'icono-contenedor';
                const iconoNav = document.createElement('i');
                iconoNav.className = 'fas fa-file-alt notita';
                const nombreContenedor = document.createElement('a');
                nombreContenedor.className = 'nombre-contenedor';
                nombreContenedor.download = nombreFormateado;
                nombreContenedor.textContent = nombreFormateado;
                iconoContenedor.appendChild(iconoNav);

                verificarArchivoExistente(nombreArchivo).then(existeEnDB => {
                    if (existeEnDB) {
                        // Si el archivo existe en la base de datos, usar la ruta local
                        downloadLinkTabla.href = rutaLocal;
                        nombreContenedor.href = rutaLocal;
                    } else {
                        // Si el archivo no está en la base de datos, buscar en GCP
                        obtenerRutaDescargaDesdeGCPStorage(nombreArchivo)
                            .then(rutaGCP => {
                                downloadLinkTabla.href = rutaGCP;
                                nombreContenedor.href = rutaGCP;
                            })
                            .catch(error => {
                                console.error("Error al obtener la ruta de GCP:", error);
                            });
                    }

                    // Agregar contenedores al elemento de la lista y a la navegación
                    listItemNav.appendChild(iconoContenedor);
                    listItemNav.appendChild(nombreContenedor);
                    fileListNav.appendChild(listItemNav);
                }).catch(error => {
                    console.error("Error al verificar el archivo:", error);
                    // Manejar el error como sea apropiado
                });
            });

            const archivoInput = document.getElementById('archivoInput');
            archivoInput.value = ""; // Establece el valor del input a una cadena vacía
        }





        function verificarArchivoExistente(nombreArchivo) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: 'GET', // Puede ser 'POST' si tu script PHP lo requiere
                    url: '../controllers/verificar_temp.php', // Cambia a la ruta real de tu script PHP
                    data: {
                        'nombreArchivo': nombreArchivo
                    }, // Envía el nombre del archivo como parámetro
                    success: function(response) {
                        // El script PHP debería devolver 'true' si el archivo existe, 'false' si no
                        if (response.trim() === "true") {
                            resolve(true); // El archivo existe en la base de datos
                        } else {
                            resolve(false); // El archivo no existe en la base de datos
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al verificar el archivo en la base de datos:", error);
                        reject(error); // Rechaza la promesa con el error
                    }
                });
            });
        }





        // Función para obtener la ruta de descarga desde GCP Storage mediante una solicitud AJAX
        function obtenerRutaDescargaDesdeGCPStorage(nombreArchivo) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '../controllers/ctrl_storage.php', // Reemplaza con la URL correcta de tu script PHP
                    method: 'POST', // Puedes usar POST u otro método según corresponda
                    data: {
                        nombreArchivo: nombreArchivo
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Ruta obtenida con éxito desde GCP Storage
                            resolve(response.rutaDescarga);
                            console.log(response);
                        } else {
                            // Maneja el caso de error o no encontrado según tus necesidades
                            reject('Error al obtener la ruta desde GCP');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Maneja el error de la solicitud AJAX según tus necesidades
                        reject(error);
                    }
                });
            });
        }






        // Función para cargar la lista de archivos adjuntos desde el servidor
        function cargarArchivosAdjuntos(numeroTicket) {
            console.log('La función cargarArchivosAdjuntos se ha llamado con el número de ticket:', numeroTicket);
            // Realizar una solicitud AJAX para obtener los nombres y rutas de archivo
            $.ajax({
                url: '../controllers/controlador_recupera_GCP_TICKET.php', // Reemplaza con la URL correcta de tu script PHP que consulta la base de datos
                method: 'GET', // Utiliza GET o POST según corresponda
                data: {
                    id_ticket: numeroTicket // Utiliza el número de ticket obtenido del input
                },
                dataType: 'json', // Especifica el tipo de datos que esperas recibir (JSON en este caso)
                success: function(response) {
                    if (response.success) {
                        // Vacía el array archivosAdjuntos
                        archivosAdjuntos.length = 0;

                        // Agrega solo los nombres de archivo al array archivosAdjuntos
                        for (var i = 0; i < response.archivos.length; i++) {
                            archivosAdjuntos.push(response.archivos[i].nombreArchivo);
                            RUTAS_GCP_STORAGE.push(response.archivos[i]);
                            console.log("ruta GCP  " + RUTAS_GCP_STORAGE);
                        }

                        // Actualiza la lista de archivos en la interfaz
                        actualizarListaArchivos_directo();
                    } else {
                        console.error('Error al cargar archivos adjuntos desde el servidor.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        }



        function formatearNombreArchivo(nombreOriginal) {
            // Verificar si el nombre del archivo ya está formateado
            if (nombreOriginal.startsWith('Adjuntos_ticket/')) {
                // Devolver el nombre formateado
                return nombreOriginal.substring(14);
            } else {
                // El nombre ya está formateado, devolverlo sin cambios
                return nombreOriginal;
            }
        }




























        function eliminarArchivo(nombreArchivo) {
            console.log("se ejecuto EliminarArchivo_ temp");
            // Confirmar con el usuario antes de eliminar el archivo
            const confirmacion = confirm(`¿Seguro que deseas eliminar el archivo "${nombreArchivo}"?`);

            if (confirmacion) {
                // Eliminar el archivo del servidor PHP
                $.ajax({
                    url: '../controllers/eliminar_archivo_adjunto.php', // Reemplaza con la URL correcta de tu script PHP
                    type: 'POST',
                    data: {
                        nombreArchivo: nombreArchivo
                    },
                    success: function(response) {
                        // Verificar la respuesta del servidor
                        var respuestaTrimmed = response.trim();
                        if (respuestaTrimmed === 'OK') {
                            // Eliminación exitosa del archivo en el servidor
                            alert('Archivo eliminado exitosamente: ' + nombreArchivo);

                            // Eliminar el archivo de la lista de archivos adjuntos en la página
                            const indice = archivosAdjuntos.indexOf(nombreArchivo);
                            if (indice !== -1) {
                                archivosAdjuntos.splice(indice, 1);
                                actualizarListaArchivos_directo(); // Actualiza la lista en la página
                            }
                        } else {
                            alert('Error al eliminar el archivo en el servidor: ' + nombreArchivo);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud AJAX: ' + error);
                        alert('Error en la solicitud AJAX al eliminar el archivo.');
                    }
                });
            }
        }
    </script>


    <script>
        // Función para abrir el modal
        function openFileModal() {
            var fileModal = document.getElementById("fileModal");
            fileModal.style.display = "block";
        }

        // Función para cerrar el modal
        function closeFileModal() {
            var fileModal = document.getElementById("fileModal");
            fileModal.style.display = "none";
        }

        // Agrega un evento al botón "Administrar Archivos Adjuntos" para abrir el modal
        var openFileModalBtn = document.getElementById("openFileModal");
        openFileModalBtn.addEventListener("click", openFileModal);

        // Agrega un evento al botón de cierre para cerrar el modal
        var closeFileModalBtn = document.getElementById("closeFileModal");
        closeFileModalBtn.addEventListener("click", closeFileModal);

        // Cierra el modal cuando se hace clic fuera del contenido del modal
        window.onclick = function(event) {
            var fileModal = document.getElementById("fileModal");
            if (event.target == fileModal) {
                fileModal.style.display = "none";
            }
        };




        function animarProgreso(objetivo) {
            let progressBar = document.getElementById('progressBar');
            let valorActual = progressBar.value;
            let animacion = setInterval(function() {
                let diferencia = objetivo - valorActual;

                // Si la diferencia es muy pequeña, la establecemos directamente y detenemos la animación
                if (diferencia < 0.1) {
                    progressBar.value = objetivo;
                    document.getElementById('progressPercentage').textContent = objetivo.toFixed(2) + '%';
                    clearInterval(animacion);
                } else {
                    // Incrementamos el valor de forma proporcional a la diferencia
                    valorActual += diferencia / 10;
                    progressBar.value = valorActual;
                    document.getElementById('progressPercentage').textContent = valorActual.toFixed(2) + '%';
                }
            }, 10); // Intervalo de la animación
        }

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const porcentaje = (e.loaded / e.total) * 100;
                animarProgreso(porcentaje);
            }
        };
    </script>







    <h2>Busqueda Requerimiento</h2>


    <form id="actualizarOT_FORM" method="post" action="" name="actualizarOT_FORM">
        <div class="columnas">
            <div class="columna">
                <label for="Numero">Numero</label>
                <input type="hidden" id="id_numero" name="id_numero" value="<?php echo  $ticketencontrado; ?>">
                <input type="text" id="numero" name="numero" value="<?php echo $ticketencontrado; ?>" readonly="readonly" style="background-color: #f0f0f0; border: 1px solid #ccc; opacity: 0.7; cursor: not-allowed;">

                <div class="busqueda-container">
                    <label for="empresa">Local:</label>

                    <input type="search" id="empresa" name="empresa" onkeyup="buscarEmpresa(this.value)" autocomplete="off" value="<?php echo htmlspecialchars($nombre_empresa); ?>">
                    <input type="hidden" id="rutEmpresaHidden" name="rut_empresa" value="<?php echo htmlspecialchars($rut_empresa); ?>">
                    <button type="button" onclick="mostrarDatosEmpresa(this)">
                        <i class="fas fa-info-circle"></i> <!-- Icono de información -->
                    </button>
                    <div id="listaEmpresas" class="lista-sugerencias"></div>

                    <div id="mensajeErrorEmpresa" style="color: red; display: none;"></div>

                </div>


                <div id="ventanaEmergenteEmpresa" style="display: none; position: absolute; background-color: white; border: 1px solid #ccc; padding: 10px; z-index: 999; transform: translateY(10px); transition: transform 0.3s;">
                    <!-- Título de la ventana -->
                    <h3 style="margin-bottom: 10px;">Información de la Empresa</h3>

                    <!-- Contenido de la ventana emergente organizado en tres columnas -->
                    <div id="contenidoVentanaEmergenteEmpresa" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-column-gap: 10px;">
                        <div class="campo-formulario">
                            <label for="nombreEmpresa">Nombre:</label>
                            <div id="nombreEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="direccionEmpresa">Dirección:</label>
                            <div id="direccionEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="ciudadEmpresa">Ciudad:</label>
                            <div id="ciudadEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="paisEmpresa">País:</label>
                            <div id="paisEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="telefonoEmpresa">Teléfono:</label>
                            <div id="telefonoEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="correoEmpresa">Correo:</label>
                            <div id="correoEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="sitioWebEmpresa">Sitio Web:</label>
                            <div id="sitioWebEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="descripcionEmpresa">Descripción:</label>
                            <div id="descripcionEmpresa" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                    </div>
                    <!-- Botón de cierre -->
                    <button type="button" id="cerrarVentanaEmergenteEmpresa" style="position: absolute; top: 5px; right: 5px; cursor: pointer; visibility: hidden;">X</button>
                </div>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    function mostrarDatosEmpresa(boton) {
                        // Obtener el valor del input hidden
                        var empresa_user_generador = $('#empresa_user_generador').val();

                        // Enviar solicitud AJAX
                        $.ajax({
                            url: '../controllers/ajax_mini/empresa_por.php', // Reemplaza con la URL correcta de tu script PHP
                            type: 'POST',
                            data: {
                                empresa_usuario: empresa_user_generador
                            },
                            success: function(response) {
                                // Parsear los datos JSON recibidos
                                var datosEmpresa = JSON.parse(response);

                                // Mostrar la ventana emergente
                                var ventanaEmergenteEmpresa = $('#ventanaEmergenteEmpresa');
                                ventanaEmergenteEmpresa.css('display', 'block');

                                // Mostrar el contenido en la ventana emergente
                                $('#nombreEmpresa').text(datosEmpresa.nombre);
                                $('#direccionEmpresa').text(datosEmpresa.direccion);
                                $('#ciudadEmpresa').text(datosEmpresa.ciudad);
                                $('#paisEmpresa').text(datosEmpresa.pais);
                                $('#telefonoEmpresa').text(datosEmpresa.telefono);
                                $('#correoEmpresa').text(datosEmpresa.correo);
                                $('#sitioWebEmpresa').text(datosEmpresa.sitio_web);
                                $('#descripcionEmpresa').text(datosEmpresa.descripcion);

                                // Obtener la posición original del botón
                                var botonRect = boton.getBoundingClientRect();

                                // Ajustar la posición de la ventana emergente
                                var ventanaTop = botonRect.bottom + 10;
                                var ventanaLeft = botonRect.left - 50;

                                // Obtener el ancho y alto de la ventana emergente
                                var ventanaWidth = ventanaEmergenteEmpresa.outerWidth();
                                var ventanaHeight = ventanaEmergenteEmpresa.outerHeight();

                                // Verificar si la ventana emergente se sale del lado derecho del body
                                if (ventanaLeft + ventanaWidth > document.body.clientWidth) {
                                    ventanaLeft = document.body.clientWidth - ventanaWidth;
                                }

                                // Verificar si la ventana emergente se sale del lado inferior del body
                                if (ventanaTop + ventanaHeight > document.body.clientHeight) {
                                    ventanaTop = document.body.clientHeight - ventanaHeight;
                                }

                                // Ajustar la posición de la ventana emergente
                                ventanaEmergenteEmpresa.css('top', ventanaTop + 'px');
                                ventanaEmergenteEmpresa.css('left', ventanaLeft + 'px');

                                // Mostrar el botón de cierre
                                var cerrarBoton = $('#cerrarVentanaEmergenteEmpresa');
                                cerrarBoton.css('visibility', 'visible');

                                // Agregar evento de clic al botón de cierre
                                cerrarBoton.on('click', function() {
                                    ventanaEmergenteEmpresa.css('display', 'none');
                                });

                                // Agregar evento de clic al documento para cerrar la ventana emergente al hacer clic fuera de ella
                                $(document).on('click', function(event) {
                                    if (!ventanaEmergenteEmpresa.is(event.target) && ventanaEmergenteEmpresa.has(event.target).length === 0 && !$(boton).is(event.target) && $(boton).has(event.target).length === 0 && !cerrarBoton.is(event.target) && cerrarBoton.has(event.target).length === 0) {
                                        ventanaEmergenteEmpresa.css('display', 'none');
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX: ' + error);
                                alert('Error en la solicitud AJAX al obtener los datos de la empresa.');
                            }
                        });
                    }
                </script>


                <label for="cliente">Solicitante:</label>
                <input type="search" id="cliente" name="cliente" value="<?php echo htmlspecialchars($nombre_cliente); ?>" autocomplete="off" onkeyup="buscarCliente(this.value)">
                <input type="hidden" id="rut_cliente" name="rut_cliente" value="<?php echo htmlspecialchars($cliente_rut_cliente); ?>">

                <div id="listaClientes" class="lista-sugerencias"></div>
                <button type="button" onclick="mostrarDatosCliente(this)">
                    <i class="fas fa-info-circle"></i> <!-- Icono de información -->
                </button>

                <div id="ventanaEmergenteCliente" style="display: none; position: absolute; background-color: white; border: 1px solid #ccc; padding: 10px; z-index: 999; transform: translateY(10px); transition: transform 0.3s;">
                    <!-- Título de la ventana -->
                    <h3 style="margin-bottom: 10px;">Información del Cliente</h3>

                    <!-- Contenido de la ventana emergente organizado en tres columnas -->
                    <div id="contenidoVentanaEmergenteCliente" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-column-gap: 10px;">
                        <div class="campo-formulario">
                            <label for="nombreCliente">Nombre:</label>
                            <div id="nombreCliente" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="segundoNombreCliente">Segundo Nombre:</label>
                            <div id="segundoNombreCliente" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="apellidoPaternoCliente">Apellidos:</label>
                            <div id="apellidoPaternoCliente" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>


                        </div>
                        <div class="campo-formulario">
                            <label for="correoCliente">Correo:</label>
                            <div id="correoCliente" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="telefonoCliente">Teléfono:</label>
                            <div id="telefonoCliente" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                    </div>
                    <!-- Botón de cierre -->
                    <button type="button" id="cerrarVentanaEmergenteCliente" style="position: absolute; top: 5px; right: 5px; cursor: pointer; visibility: hidden;">X</button>
                </div>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    function mostrarDatosCliente(boton) {
                        // Obtener el valor del input hidden
                        var rut_cliente = $('#rut_cliente').val();

                        // Enviar solicitud AJAX
                        $.ajax({
                            url: '../controllers/ajax_mini/cliente_por.php', // Reemplaza con la URL correcta de tu script PHP
                            type: 'POST',
                            data: {
                                cliente_usuario: rut_cliente
                            },
                            success: function(response) {
                                // Parsear los datos JSON recibidos
                                var datosCliente = JSON.parse(response);

                                // Mostrar la ventana emergente
                                var ventanaEmergenteCliente = $('#ventanaEmergenteCliente');
                                ventanaEmergenteCliente.css('display', 'block');

                                // Mostrar el contenido en la ventana emergente
                                $('#nombreCliente').text(datosCliente.nombre);
                                $('#segundoNombreCliente').text(datosCliente.s_nombre);
                                $('#apellidoPaternoCliente').text(datosCliente.apellidos);

                                $('#correoCliente').text(datosCliente.correo);
                                $('#telefonoCliente').text(datosCliente.telefono);

                                // Obtener la posición original del botón
                                var botonRect = boton.getBoundingClientRect();

                                // Ajustar la posición de la ventana emergente
                                var ventanaTop = botonRect.bottom + 10;
                                var ventanaLeft = botonRect.left - 50;

                                // Obtener el ancho y alto de la ventana emergente
                                var ventanaWidth = ventanaEmergenteCliente.outerWidth();
                                var ventanaHeight = ventanaEmergenteCliente.outerHeight();

                                // Verificar si la ventana emergente se sale del lado derecho del body
                                if (ventanaLeft + ventanaWidth > document.body.clientWidth) {
                                    ventanaLeft = document.body.clientWidth - ventanaWidth;
                                }

                                // Verificar si la ventana emergente se sale del lado inferior del body
                                if (ventanaTop + ventanaHeight > document.body.clientHeight) {
                                    ventanaTop = document.body.clientHeight - ventanaHeight;
                                }

                                // Ajustar la posición de la ventana emergente
                                ventanaEmergenteCliente.css('top', ventanaTop + 'px');
                                ventanaEmergenteCliente.css('left', ventanaLeft + 'px');

                                // Mostrar el botón de cierre
                                var cerrarBoton = $('#cerrarVentanaEmergenteCliente');
                                cerrarBoton.css('visibility', 'visible');

                                // Agregar evento de clic al botón de cierre
                                cerrarBoton.on('click', function() {
                                    ventanaEmergenteCliente.css('display', 'none');
                                });

                                // Agregar evento de clic al documento para cerrar la ventana emergente al hacer clic fuera de ella
                                $(document).on('click', function(event) {
                                    if (!ventanaEmergenteCliente.is(event.target) && ventanaEmergenteCliente.has(event.target).length === 0 && !$(boton).is(event.target) && $(boton).has(event.target).length === 0 && !cerrarBoton.is(event.target) && cerrarBoton.has(event.target).length === 0) {
                                        ventanaEmergenteCliente.css('display', 'none');
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX: ' + error);
                                alert('Error en la solicitud AJAX al obtener los datos del cliente.');
                            }
                        });
                    }
                </script>







                <div class="busqueda-container">
                    <label for="asignado">Asignado:</label>
                    <input type="search" id="asignado" name="asignado" value="<?php echo htmlspecialchars($usuarios_rut_user); ?>" autocomplete="off" onkeyup="buscarUsuario(this.value)">
                    <input type="hidden" id="rut_user_asignado" name="rut_user_asignado" value="<?php echo htmlspecialchars($usuarios_rut_user_rut); ?>">

                    <button type="button" onclick="mostrarDatosAsignado(this)">
                        <i class="fas fa-info-circle"></i> <!-- Icono de información -->
                    </button>
                    <div id="listaUsuarios" class="lista-sugerencias"></div>
                    <div id="mensajeErrorUsuario" style="color: red; display: none;"></div>

                </div>


                <div id="ventanaEmergenteAsignado" style="display: none; position: absolute; background-color: white; border: 1px solid #ccc; padding: 10px; z-index: 999; transform: translateY(10px); transition: transform 0.3s;">
                    <!-- Título de la ventana -->
                    <h3 style="margin-bottom: 10px;">Información de Asignado</h3>

                    <!-- Contenido de la ventana emergente organizado en tres columnas -->
                    <div id="contenidoVentanaEmergenteAsignado" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-column-gap: 10px;">
                        <div class="campo-formulario">
                            <label for="nombreAsignado">Nombre:</label>
                            <div id="nombreAsignado" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="segundoNombreAsignado">Segundo Nombre:</label>
                            <div id="segundoNombreAsignado" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="apellidoPaternoAsignado">Apellido Paterno:</label>
                            <div id="apellidoPaternoAsignado" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="apellidoMaternoAsignado">Apellido Materno:</label>
                            <div id="apellidoMaternoAsignado" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="correoAsignado">Correo:</label>
                            <div id="correoAsignado" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="telefonoAsignado">Teléfono:</label>
                            <div id="telefonoAsignado" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                    </div>
                    <!-- Botón de cierre -->
                    <button type="button" id="cerrarVentanaEmergenteAsignado" style="position: absolute; top: 5px; right: 5px; cursor: pointer; visibility: hidden;">X</button>
                </div>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    function mostrarDatosAsignado(boton) {
                        // Obtener el valor del input hidden
                        var asignado_user_generador = $('#rut_user_asignado').val();

                        // Enviar solicitud AJAX
                        $.ajax({
                            url: '../controllers/ajax_mini/asignado_por.php', // Reemplaza con la URL correcta de tu script PHP
                            type: 'POST',
                            data: {
                                asignado_usuario: asignado_user_generador
                            },
                            success: function(response) {
                                // Parsear los datos JSON recibidos
                                var datosAsignado = JSON.parse(response);

                                // Mostrar la ventana emergente
                                var ventanaEmergenteAsignado = $('#ventanaEmergenteAsignado');
                                ventanaEmergenteAsignado.css('display', 'block');

                                // Mostrar el contenido en la ventana emergente
                                $('#nombreAsignado').text(datosAsignado.nombre);
                                $('#segundoNombreAsignado').text(datosAsignado.s_nombre);
                                $('#apellidoPaternoAsignado').text(datosAsignado.ap_paterno);
                                $('#apellidoMaternoAsignado').text(datosAsignado.ap_materno);
                                $('#correoAsignado').text(datosAsignado.correo);
                                $('#telefonoAsignado').text(datosAsignado.telefono);

                                // Obtener la posición original del botón
                                var botonRect = boton.getBoundingClientRect();

                                // Ajustar la posición de la ventana emergente
                                var ventanaTop = botonRect.bottom + 10;
                                var ventanaLeft = botonRect.left - 50;

                                // Obtener el ancho y alto de la ventana emergente
                                var ventanaWidth = ventanaEmergenteAsignado.outerWidth();
                                var ventanaHeight = ventanaEmergenteAsignado.outerHeight();

                                // Verificar si la ventana emergente se sale del lado derecho del body
                                if (ventanaLeft + ventanaWidth > document.body.clientWidth) {
                                    ventanaLeft = document.body.clientWidth - ventanaWidth;
                                }

                                // Verificar si la ventana emergente se sale del lado inferior del body
                                if (ventanaTop + ventanaHeight > document.body.clientHeight) {
                                    ventanaTop = document.body.clientHeight - ventanaHeight;
                                }

                                // Ajustar la posición de la ventana emergente
                                ventanaEmergenteAsignado.css('top', ventanaTop + 'px');
                                ventanaEmergenteAsignado.css('left', ventanaLeft + 'px');

                                // Mostrar el botón de cierre
                                var cerrarBoton = $('#cerrarVentanaEmergenteAsignado');
                                cerrarBoton.css('visibility', 'visible');

                                // Agregar evento de clic al botón de cierre
                                cerrarBoton.on('click', function() {
                                    ventanaEmergenteAsignado.css('display', 'none');
                                });

                                // Agregar evento de clic al documento para cerrar la ventana emergente al hacer clic fuera de ella
                                $(document).on('click', function(event) {
                                    if (!ventanaEmergenteAsignado.is(event.target) && ventanaEmergenteAsignado.has(event.target).length === 0 && !$(boton).is(event.target) && $(boton).has(event.target).length === 0 && !cerrarBoton.is(event.target) && cerrarBoton.has(event.target).length === 0) {
                                        ventanaEmergenteAsignado.css('display', 'none');
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX: ' + error);
                                alert('Error en la solicitud AJAX al obtener los datos del asignado.');
                            }
                        });
                    }
                </script>


            </div>

            <div class="columna">
                <label for="rut_user_generador">Abierto por:</label>
                <div style="display: flex; align-items: center;">
                    <input type="text" id="rut_user_generador" name="rut_user_generador" value="<?php echo htmlspecialchars($nombre_generador); ?>" readonly style="background-color: #f0f0f0; border: 1px solid #ccc; opacity: 0.7; cursor: not-allowed; margin-right: 10px;">
                    <input type="hidden" id="rut_user_generador2" name="rut_user_generador2" value="<?php echo htmlspecialchars($rut_user_generador); ?>">
                    <button type="button" onclick="mostrarDatosUsuario(this)">
                        <i class="fas fa-info-circle"></i> <!-- Icono de información -->
                    </button>

                </div>


                <div id="ventanaEmergente" style="display: none; position: absolute; background-color: white; border: 1px solid #ccc; padding: 10px; z-index: 999; transform: translateY(10px); transition: transform 0.3s;">
                    <!-- Título de la ventana -->
                    <h3 style="margin-bottom: 10px;">Contacto</h3>

                    <!-- Contenido de la ventana emergente organizado en tres columnas -->
                    <div id="contenidoVentanaEmergente" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-column-gap: 10px;">
                        <div class="campo-formulario">
                            <label for="nombreUsuario">Nombre:</label>
                            <div id="nombreUsuario" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="segundoNombreUsuario">Segundo Nombre:</label>
                            <div id="segundoNombreUsuario" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="apellidoPaternoUsuario">Apellido Paterno:</label>
                            <div id="apellidoPaternoUsuario" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>

                            <label for="apellidoMaternoUsuario">Apellido Materno:</label>
                            <div id="apellidoMaternoUsuario" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="correoUsuario">Correo:</label>
                            <div id="correoUsuario" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                        <div class="campo-formulario">
                            <label for="telefonoUsuario">Teléfono:</label>
                            <div id="telefonoUsuario" class="valor-campo" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 5px;"></div>
                        </div>
                    </div>
                    <!-- Botón de cierre -->
                    <button type="button" id="cerrarVentanaEmergente" style="position: absolute; top: 5px; right: 5px; cursor: pointer; visibility: hidden;">X</button>
                </div>


                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    function mostrarDatosUsuario(boton) {
                        // Obtener el valor del input hidden
                        var rut_user_generador = $('#rut_user_generador2').val();

                        // Enviar solicitud AJAX
                        $.ajax({
                            url: '../controllers/ajax_mini/abierto_por.php', // Reemplaza con la URL correcta de tu script PHP
                            type: 'POST',
                            data: {
                                rut_usuario: rut_user_generador
                            },
                            success: function(response) {
                                // Parsear los datos JSON recibidos
                                var datosUsuario = JSON.parse(response);

                                // Mostrar la ventana emergente
                                var ventanaEmergente = $('#ventanaEmergente');
                                ventanaEmergente.css('display', 'block');

                                // Mostrar el contenido en la ventana emergente
                                $('#nombreUsuario').text(datosUsuario.nombre);
                                $('#segundoNombreUsuario').text(datosUsuario.s_nombre);
                                $('#apellidoPaternoUsuario').text(datosUsuario.ap_paterno);
                                $('#apellidoMaternoUsuario').text(datosUsuario.ap_materno);
                                $('#correoUsuario').text(datosUsuario.correo);
                                $('#telefonoUsuario').text(datosUsuario.telefono);

                                // Obtener la posición original del botón
                                var botonRect = boton.getBoundingClientRect();

                                // Ajustar la posición del botón para desplazarlo 50px a la izquierda
                                var botonLeftAdjusted = botonRect.left - 50;

                                // Calcular la posición de la ventana emergente basada en la posición ajustada del botón
                                var ventanaTop = botonRect.bottom + 10;
                                var ventanaLeft = botonLeftAdjusted;

                                // Obtener el ancho y alto del body
                                var bodyWidth = document.body.clientWidth;
                                var bodyHeight = document.body.clientHeight;

                                // Obtener el ancho y alto de la ventana emergente
                                var ventanaWidth = ventanaEmergente.outerWidth();
                                var ventanaHeight = ventanaEmergente.outerHeight();

                                // Verificar si la ventana emergente se sale del lado derecho del body
                                if (ventanaLeft + ventanaWidth > bodyWidth) {
                                    ventanaLeft = bodyWidth - ventanaWidth;
                                }

                                // Verificar si la ventana emergente se sale del lado inferior del body
                                if (ventanaTop + ventanaHeight > bodyHeight) {
                                    ventanaTop = bodyHeight - ventanaHeight;
                                }

                                // Ajustar la posición de la ventana emergente
                                ventanaEmergente.css('top', ventanaTop + 'px');
                                ventanaEmergente.css('left', ventanaLeft + 'px');


                                // Mostrar el botón de cierre
                                var cerrarBoton = $('#cerrarVentanaEmergente');
                                cerrarBoton.css('visibility', 'visible');

                                // Agregar evento de clic al botón de cierre
                                cerrarBoton.on('click', function() {
                                    ventanaEmergente.css('display', 'none');
                                });

                                // Agregar evento de clic al documento para cerrar la ventana emergente al hacer clic fuera de ella
                                $(document).on('click', function(event) {
                                    if (!ventanaEmergente.is(event.target) && ventanaEmergente.has(event.target).length === 0 && !$(boton).is(event.target) && $(boton).has(event.target).length === 0 && !cerrarBoton.is(event.target) && cerrarBoton.has(event.target).length === 0) {
                                        ventanaEmergente.css('display', 'none');
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en la solicitud AJAX: ' + error);
                                alert('Error en la solicitud AJAX al obtener los datos del usuario.');
                            }
                        });
                    }
                </script>

                <label for="fecha_creacion">Fecha de Creación:</label>
                <input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo htmlspecialchars($fecha_creacion); ?>" readonly style="background-color: #f0f0f0; border: 1px solid #ccc; opacity: 0.7; cursor: not-allowed;">

                <label for="estado_tk">Estado</label>
                <select id="estado_tk" name="estado_tk">
                    <?php
                    if ($query_estado->num_rows > 0) {
                        // Recorrer las prioridades y generar las opciones
                        while ($row_Estado = $query_estado->fetch_assoc()) {
                            $id_estadoTicket = $row_Estado['id_estado_tk'];
                            $nombre_estado = $row_Estado['nombre'];

                            // Verificar si esta es la prioridad del ticket actual y marcarla como seleccionada
                            $selected = ($id_estadoTicket == $id_estado_tk) ? 'selected' : '';

                            // Imprimir la opción en el campo de selección
                            echo '<option value="' . $id_estadoTicket . '" ' . $selected . '>' . $nombre_estado . '</option>';
                        }
                    } else {
                        echo '<option value="">No se encontraron prioridades.</option>';
                    }
                    ?>
                    <!-- Agregar más estados según sea necesario -->
                </select>
                <label for="prioridad">Prioridad:</label>
                <select name="prioridad" id="prioridad">
                    <!-- Suponiendo que tienes una lista de prioridades en $query_prioridades -->
                    <?php
                    if ($query_prioridades->num_rows > 0) {
                        // Recorrer las prioridades y generar las opciones
                        while ($row_prioridad = $query_prioridades->fetch_assoc()) {
                            $id_prioridad = $row_prioridad['id_prioridad'];
                            $nombre_prioridad = $row_prioridad['nombre'];

                            // Verificar si esta es la prioridad del ticket actual y marcarla como seleccionada
                            $selected = ($id_prioridad == $prioridad_del_ticket) ? 'selected' : '';

                            // Imprimir la opción en el campo de selección
                            echo '<option value="' . $id_prioridad . '" ' . $selected . '>' . $nombre_prioridad . '</option>';
                        }
                    } else {
                        echo '<option value="">No se encontraron prioridades.</option>';
                    }
                    ?>
                </select>




            </div>
        </div>
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

        <!-- Fin de las columnas -->

        <label for="resumen">Resumen:</label>
        <input type="text" id="resumen" name="resumen" value="<?php echo htmlspecialchars($resumen); ?>"><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($descripcion); ?></textarea>
        <div id="contador-caracteres">Caracteres restantes: 1000</div><br>




        <!-- Encabezado de las pestañas -->



        <!-- Tab links -->
        <div class="tab">
            <span class="tab-Notas">
                <button type="button" class="tablinks active" onclick="OrdenTabs(event, 'Notas')">Notas</button>
            </span>

            <!-- <span class="tab-Componentes">
                <button type="button" class="tablinks" onclick="OrdenTabs(event, 'Series')">Informacion del usuario</button>
            </span>
          
            <span class="tab-Actividades">
                <button type="button" class="tablinks" onclick="OrdenTabs(event, 'Actividades')">Actividades</button>
            </span>-->
        </div>



        <div id="Notas" class="tabcontent_Notas ">
            <div class="columna_notas">
                <!-- Coloca aquí los campos requeridos para la pestaña "Notas" -->
                <label for="Notas_del_Trabajo">Notas de Trabajo:</label>
                <textarea type="textarea" id="Notas_del_Trabajo" name="Notas_del_Trabajo" required></textarea>
                <div id="contador-notas">Caracteres restantes: 500</div>


                <button type="button" id="btn_notas">Publicar</button>


            </div>

            <?php


            // Comprueba si el usuario está conectado antes de enviar los datos
            if (isset($_SESSION['usuario'])) {
                $usuarioNombre = $_SESSION['usuario']['nombre'];
                $usuarioApPaterno = $_SESSION['usuario']['ap_paterno'];
                $usuarioApMaterno = $_SESSION['usuario']['ap_materno'];
            } else {
                // Maneja el caso en el que el usuario no esté conectado
                // Puedes redirigir o mostrar un mensaje de error aquí
                exit("Usuario no conectado");
            }
            ?>

            <script>
                $(document).ready(function() {
                    // Selecciona el botón "Publicar" por su ID
                    $('#btn_notas').click(function() {
                        // Obtiene los valores del formulario
                        var idTicket = $('#numero').val();
                        var usuario = "<?php echo isset($_SESSION['usuario']['rut_user']) ? $_SESSION['usuario']['rut_user'] : ''; ?>";
                        var notasDelTrabajo = $('#Notas_del_Trabajo').val();

                        console.log("idTicket:", idTicket);
                        console.log("usuario:", usuario);
                        console.log("notasDelTrabajo:", notasDelTrabajo);

                        // Crea un objeto con los valores que deseas enviar
                        var datos = {
                            id_ticket: idTicket,
                            usuario: usuario,
                            Notas_del_Trabajo: notasDelTrabajo // Corregido el nombre de la propiedad
                        };

                        // Realiza la solicitud AJAX
                        $.ajax({
                            type: 'POST',
                            url: '../controllers/procesar_datos.php',
                            data: datos,
                            success: function(response) {
                                // Maneja la respuesta del servidor
                                console.log("Respuesta del servidor:", response);

                                // Recarga la página para actualizar los datos
                                location.reload();
                            },
                        });
                    });
                });
            </script>

            <?php

            // Incluir el archivo de configuración de GCP Storage
            require '../vendor/autoload.php';

            use Google\Cloud\Storage\StorageClient;
            // Función para verificar si un archivo es una imagen
            function esImagen($archivo)
            {
                $extensionesImagen = array('jpg', 'jpeg', 'png', 'gif', 'bmp'); // Extensiones de imagen válidas

                // Obtener la extensión del archivo
                $extension = pathinfo($archivo, PATHINFO_EXTENSION);

                // Verificar si la extensión está en la lista de extensiones de imagen
                return in_array(strtolower($extension), $extensionesImagen);
            }

            // Función para obtener la ruta de descarga desde GCP Storage mediante una solicitud AJAX
            function obtenerRutaDescargaDesdeGCPStorage($nombreArchivo, $idHistorico)
            {


                // Configuración de Google Cloud Storage
                $projectId = 'tiketeraacis';
                $bucketName = 'repositorioacis';
                $keyFilePath = '../tiketeraacis-082c54670f64.json';

                // Crear una instancia de StorageClient con la configuración
                $storage = new StorageClient([
                    'projectId' => $projectId,
                    'keyFilePath' => $keyFilePath
                ]);

                // Obtener el bucket usando el StorageClient
                $bucket = $storage->bucket($bucketName);

                // Lógica para consultar GCP Storage y obtener la ruta de descarga
                try {
                    // Referencia al objeto en GCP Storage
                    $object = $bucket->object("Adjuntos_ticket/$nombreArchivo");

                    // Especifica opciones para la URL firmada, incluyendo el encabezado 'Content-Disposition'
                    $options = [
                        'responseDisposition' => 'attachment; filename="' . basename($nombreArchivo) . '"',
                        'expiry' => new \DateTime('2030-01-01T00:00:00Z')
                    ];

                    // Generar la URL firmada con las opciones adicionales
                    $url = $object->signedUrl($options['expiry'], $options);

                    // Crear una respuesta JSON con la ruta de descarga
                    $response = array(
                        'success' => true,
                        'rutaDescarga' => $url
                    );

                    // Devolver la respuesta JSON
                    return json_encode($response);
                } catch (\Exception $e) {
                    // Manejar errores según tus necesidades
                    $response = array(
                        'success' => false,
                        'message' => 'Error al obtener la ruta desde GCP: ' . $e->getMessage()
                    );

                    // Devolver la respuesta JSON de error
                    return json_encode($response);
                }
            }


            ?>



            <div id="notasTrabajoContainer_2">
                <!-- Aquí se mostrarán las notas de trabajo y registros de historial en cards -->

                <?php
                if ($row) {
                    if (!empty($combined_results)) {
                        echo '<div id="notasTrabajoContainer">';
                        foreach ($combined_results as $fila) {
                            // Verificar el tipo de registro (historial o notas de trabajo)
                            if (isset($fila['id_historico'])) {
                                // Es un registro de historial
                                $idHistorico = $fila['id_historico'];
                                $fechaHoraHistorico = $fila['fecha_hora'];
                                $estadoAnterior = $fila['nombre_estado_anterior']; // Este campo podría no existir si el registro es una creación
                                $estadoActual = $fila['estado_actual'];
                                $motivo = $fila['motivo']; // Motivo del cambio de estado, si está disponible
                                $rutUsuarioAsignado = $fila['nombre_usuario_asignado']; // RUT del usuario asignado al ticket
                                $adjuntos = $fila['adjuntos']; // Ruta o nombre de los adjuntos
                                $rutEmpresa = $fila['rut_empresa']; // RUT de la empresa relacionada al ticket
                                $nombre_usuario_historico = $fila['nombre_usuario_historico']; // RUT del usuario que hizo el cambio
                                $idTicket = $fila['id_ticket']; // ID del ticket asociado al historial
                                $tipoHistorico = $fila['tipo_historico']; // Tipo de registro histórico (creación, actualización, etc.)
                                $nombre_estado_actual = $fila['nombre_estado_actual'];
                                $nombre_archivo = $fila['archivos_adjuntos'];
                                $nombre_GCP = $fila['nombre_GCP'];
                                $correo_asunto = $fila['correo_asunto'];
                                $correo_asignado = $fila['correo_asignado'];
                                $correo_solicitante = $fila['correo_solicitante'];
                                $body = $fila['body'];





                                // Dependiendo del tipo de cambio, puedes personalizar la visualización
                                switch ($tipoHistorico) {
                                    case 'creacion':


                                        echo '<div style="font-family: Arial, sans-serif; border: 1px solid #e6e6e6; margin-bottom: 20px; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-left: 5px solid #bec1c6; font-weight: ">';
                                        echo '<div style="padding: 16px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e6e6;">';
                                        echo '<span class="card-title" style="font-family: Arial, sans-serif; font-weight: 600;">' . $nombre_usuario_historico . '</span>'; // Nombre del usuario
                                        echo '<span style="font-family: Arial, sans-serif; font-size: 0.8em; color: #000;">Cambios de campo · ' . $fechaHoraHistorico . '</span>'; // Fecha y hora
                                        echo '</div>';
                                        echo '<div class="card-body" style="font-family: Arial, sans-serif; padding: 10px 15px;">';
                                        echo '<p><Abierto por: ' . $nombre_usuario_historico . '</p>'; // Usuario que abrió el ticket
                                        echo '<p>Empresa: ' . $nombreEmpresa . '</p>'; // Empresa
                                        echo '<p>Estado: ' . $nombre_estado_actual . '</p>'; // Estado
                                        echo '</div>';
                                        echo '</div>';





                                        break;
                                    case 'Asignación':

                                        echo '<div style="font-family: Arial, sans-serif; border: 1px solid #e6e6e6; margin-bottom: 20px; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-left: 5px solid #bec1c6;">';
                                        echo '<div style="padding: 16px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e6e6;">';
                                        echo '<span class="card-title" style="font-family: Arial, sans-serif; ">' . $nombre_usuario_historico . '</span>'; // Nombre del usuario
                                        echo '<span style="font-family: Arial, sans-serif; font-size: 0.8em;  color: #000;">Cambios de campo · ' . $fechaHoraHistorico . '</span>'; // Fecha y hora
                                        echo '</div>';
                                        echo '<div class="card-body" style="font-family: Arial, sans-serif; padding: 10px 15px;">';
                                        echo '<p style="margin: 5px 0; color: #333;">Usuario Asignado : ' . $rutUsuarioAsignado . '</p>'; // Empresa
                                        echo '</div>';
                                        echo '</div>';



                                        break;
                                    case 'cambio':


                                        echo '<div style="font-family: Arial, sans-serif; border: 1px solid #e6e6e6; margin-bottom: 20px; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-left: 5px solid #bec1c6;">';
                                        echo '<div style="padding: 16px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e6e6;">';
                                        echo '<span style="font-family: Arial, sans-serif; font-size: 1.1em;">Integración API Automatización</span>';
                                        echo '<span style="font-family: Arial, sans-serif; font-size: 0.8em; color: #000;">Cambios de campo · ' . $fechaHoraHistorico . '</span>'; // Fecha y hora
                                        echo '</div>';
                                        echo '<div style="padding: 16px; background-color: #fafafa; font-family: Arial, sans-serif;">';
                                        echo '<p>Estado: ' . $nombre_estado_actual . ' era ' . $estadoAnterior . '</p>';
                                        // echo '<p>Motivo de Estado: ' . $motivo . '</p>';
                                        echo '</div>';
                                        echo '</div>';





                                        break;

                                    case 'adjunto':

                                        echo '<div style="font-family: Arial, sans-serif; border: 1px solid #e6e6e6; margin-bottom: 20px; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-left: 5px solid #bec1c6; ">';
                                        echo '<div style="padding: 16px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e6e6e6;">';
                                        echo '<span class="card-title" style="font-family: Arial, sans-serif; ">' . $nombre_usuario_historico . '</span>'; // Nombre del usuario
                                        echo '<span style="font-family: Arial, sans-serif; font-size: 0.8em; color: #000;">Cambios de campo · ' . $fechaHoraHistorico . '</span>'; // Fecha y hora
                                        echo '</div>';
                                        echo '<div class="card-body" style="font-family: Arial, sans-serif; padding: 10px 15px;">';




                                        $rutaDescarga = obtenerRutaDescargaDesdeGCPStorage($nombre_GCP, $idHistorico);

                                        if ($rutaDescarga !== false) {
                                            $response = json_decode($rutaDescarga, true);
                                            if ($response['success']) {
                                                $rutaDescarga = $response['rutaDescarga'];

                                                if (esImagen($nombre_archivo)) {
                                                    // Si es una imagen, muestra la imagen con la clase "imagen-en-pantalla-completa" y atributos data
                                                    echo '<img src="' . $rutaDescarga . '" alt="Imagen adjunta" style="max-width: 40%; max-height: 350px; cursor: pointer;" class="imagen-en-pantalla-completa" data-imagen="' . $rutaDescarga . '">';
                                                } else {
                                                    // Si no es una imagen, muestra el nombre del archivo y un enlace de descarga
                                                    //echo '<p><strong></strong> ' . $nombre_archivo . ' <a href="' . $rutaDescarga . '" target="_blank">Descargar</a></p>';
                                                    echo '<p> <a href="' . $rutaDescarga . '" target="_blank" download>' . $nombre_archivo . '</a></p>';
                                                }
                                            } else {
                                                // Maneja el caso de error según tus necesidades
                                                echo '<p>Error al obtener la ruta desde GCP: ' . $response['message'] . '</p>';
                                            }
                                        } else {
                                            // Maneja el caso de error según tus necesidades
                                            echo '<p>Error al obtener la ruta desde GCP.</p>';
                                        }

                                        echo '</div>';
                                        echo '</div>';
                                        break;
                                    case 'correo_enviado':
                                        echo '<div class="correo-container">';
                                        echo '<div class="correo-header">';
                                        echo '<span class="correo-info">Sistema</span>';
                                        echo '<span class="correo-timestamp">Correo electrónico enviado · ' . $fechaHoraHistorico . '</span>';
                                        echo '</div>';
                                        echo '<div class="correo-body">';
                                        echo '<div class="correo-flex-container">';
                                        echo '<div class="correo-titles">';
                                        echo '<p class="correo-paragraph"><strong><i class="far fa-envelope"></i>:</strong></p>';
                                        echo '<p class="correo-paragraph"><strong>Asunto:</strong></p>';
                                        echo '<p class="correo-paragraph"><strong>Del:</strong></p>';
                                        echo '<p class="correo-paragraph"><strong>Destino:</strong></p>';
                                        echo '</div>';
                                        echo '<div class="correo-texts">';
                                        echo '<p class="correo-paragraph">Correo electrónico enviado</p>';
                                        echo '<p class="correo-paragraph">' . $correo_asunto . '</p>';
                                        echo '<p class="correo-paragraph">Soporte ManageMate</p>';
                                        echo '<p class="correo-paragraph">' . $correo_asignado . '</p>';
                                        echo '<p class="correo-paragraph">';
                                        echo '<p class="correo-details" onclick="toggleBodyVisibility(\'bodyContentCorreoEnviado\')">Mostrar detalles de correo electrónico</p>';
                                        echo '<div id="bodyContentCorreoEnviado" class="correo-body-content">' . $body . '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        break;

                                    case 'correo_enviado_solicitante':
                                        echo '<div class="correo-container">';
                                        echo '<div class="correo-header">';
                                        echo '<span class="correo-info">Sistema</span>';
                                        echo '<span class="correo-timestamp">Correo electrónico enviado · ' . $fechaHoraHistorico . '</span>';
                                        echo '</div>';
                                        echo '<div class="correo-body">';
                                        echo '<div class="correo-flex-container">';
                                        echo '<div class="correo-titles">';
                                        echo '<p class="correo-paragraph"><strong><i class="far fa-envelope"></i>:</strong></p>';
                                        echo '<p class="correo-paragraph"><strong>Asunto:</strong></p>';
                                        echo '<p class="correo-paragraph"><strong>Del:</strong></p>';
                                        echo '<p class="correo-paragraph"><strong>Destino:</strong></p>';
                                        echo '</div>';
                                        echo '<div class="correo-texts">';
                                        echo '<p class="correo-paragraph">Correo electrónico enviado</p>';
                                        echo '<p class="correo-paragraph">' . $correo_asunto . '</p>';
                                        echo '<p class="correo-paragraph">Soporte ManageMate</p>';
                                        echo '<p class="correo-paragraph">' . $correo_solicitante . '</p>';
                                        echo '<p class="correo-paragraph">';
                                        echo '<p class="correo-details" onclick="toggleBodyVisibility(\'bodyContent_correo_solicitante\')">Mostrar detalles de correo electrónico</p>';
                                        echo '</p>';
                                        echo '<div id="bodyContent_correo_solicitante" class="correo-body-content">' . $body . '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        break;





                                        // Añadir más casos según sea necesario
                                }
                            } elseif (isset($fila['id_notas'])) {
                                // Es una nota de trabajo
                                $idNotas = $fila["id_notas"];
                                $fechaHora = $fila["fecha_hora"];
                                $descripcionNota = $fila["descripcion"];
                                $nombre_usuario_notas = $fila["nombre_usuario_notas"];
                                $idTicket = $fila["id_ticket"];

                                // Crea una tarjeta para la nota de trabajo






                                // ... código PHP anterior ...

                                // Crea una tarjeta para la nota de trabajo
                                echo '<div class="card notas-trabajo">';
                                echo '<div class="card-header">';
                                echo '<span class="user-name">' . $nombre_usuario_notas . '</span>'; // Nombre del usuario
                                echo '<span class="date-time"> <span> Notas de trabajo º</span> ' . $fechaHora . '</span>'; // Fecha y hora
                                echo '</div>';
                                echo '<div class="card-body">';


                                echo '<p class="card-text">' . $descripcionNota .  '</p>';


                                // ... Resto del contenido de la tarjeta ...
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        echo '</div>';
                    } else {
                        echo "No se encontraron datos para mostrar." . $id_ticket;
                    }

                    // ... Resto de tu código ...
                } else {
                    echo "No se encontró el ticket con ID: $id_ticket";
                }
                ?>
            </div>

        </div>


        <script>
            // Ocultar el cuerpo al cargar la página
            document.addEventListener("DOMContentLoaded", function() {
                var bodyContent = document.getElementById("bodyContent");
                bodyContent.style.display = "none";
            });


            function toggleBodyVisibility(id) {
                var element = document.getElementById(id);
                if (element.style.display === "none") {
                    element.style.display = "block";
                } else {
                    element.style.display = "none";
                }
            }
        </script>






        <style>
            .card {
                border: 1px solid #cccccc;
                margin-bottom: 10px;
                border-radius: 5px;
                background: #f9f9f9;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                background: #ffffff;
                padding: 10px 15px;
                border-bottom: 1px solid #eeeeee;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-title {
                font-weight: 600;
            }

            .text-muted {
                font-size: 0.8em;
            }

            .card-body {
                padding: 10px 15px;
            }
        </style>
        <script>
            // Función para abrir la imagen en el visor
            function abrirImagenEnVisor(urlImagen) {
                var visor = document.querySelector('.visor-imagen');
                var imagen = document.querySelector('.imagen-en-visor');

                // Establece la URL de la imagen
                imagen.src = urlImagen;

                // Muestra el visor
                visor.style.display = 'flex';

                // Agrega el botón de cierre (X) al visor de imágenes
                var botonCerrar = document.createElement('div');
                botonCerrar.className = 'cerrar-visor';
                botonCerrar.innerHTML = '&times;'; // Carácter X
                botonCerrar.addEventListener('click', cerrarVisor);
                visor.appendChild(botonCerrar);

                // Agrega un controlador de eventos para la tecla "ESC" para cerrar el visor
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        cerrarVisor();
                    }
                });
            }

            // Función para cerrar el visor de imágenes
            function cerrarVisor() {
                var visor = document.querySelector('.visor-imagen');
                visor.style.display = 'none';
            }

            // Captura los clics en elementos con la clase "imagen-en-pantalla-completa" y abre la imagen en el visor
            var imagenesEnPantallaCompleta = document.querySelectorAll('.imagen-en-pantalla-completa');
            imagenesEnPantallaCompleta.forEach(function(imagen) {
                imagen.addEventListener('click', function() {
                    abrirImagenEnVisor(this.getAttribute('data-imagen'));
                });
            });
        </script>




        <style>
            /* Estilo para el visor de imágenes */
            .visor-imagen {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                /* Fondo oscurecido */
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            /* Estilo para la imagen en el visor */
            /* Estilo para la imagen en el visor */
            .imagen-en-visor {
                max-width: auto;
                max-height: auto;
                width: auto;
                height: auto;
            }


            /* Estilo para el botón de cierre */
            .cerrar-visor {
                position: absolute;
                top: 20px;
                right: 20px;
                cursor: pointer;
                color: white;
                font-size: 45px;
            }

            .cerrar-visor:hover {
                color: orange;
            }
        </style>
        <div class="visor-imagen">
            <img class="imagen-en-visor" src="" alt="Imagen en visor">
        </div>





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



        <div id="Series" class="tabcontent_series">
            <div class="columna">
                <!-- Primera columna -->
                <label for="Nombre_user_completo_afectado">Nombre completo del usuario::</label>
                <input type="text" id="Nombre_user_completo_afectado" name="Nombre_user_completo_afectado" required value="<?php echo htmlspecialchars($Nombre_user_completo_afectado); ?>">



                <!-- Campo de búsqueda de Marca -->
                <div class="busqueda-container">
                    <label for="Rut_usuario_afectado">Rut del usuario:</label>
                    <input type="search" id="Rut_usuario_afectado" name="Rut_usuario_afectado" autocomplete="off" value="<?php echo htmlspecialchars($Rut_usuario_afectado); ?>">
                    <div id="rut_usuario_afectado-error"></div>
                </div>





                <!-- Campo de búsqueda de Modelo, inicialmente desactivado -->
                <div class="busqueda-container">
                    <label for="Modelo">Usuario Modelo:</label>
                    <input type="search" id="Modelo" name="Modelo" value="<?php echo htmlspecialchars($modelo); ?>">
                </div>


            </div>


            <div class="columna">
                <!-- Segunda columna -->
                <label for="Cargo_afectado">Cargo:</label>
                <input type="text" id="Cargo_afectado" name="Cargo_afectado" required value="<?php echo htmlspecialchars($Cargo_afectado); ?>">

                <label for="Mandante_afectado">Mandante:</label>
                <input type="text" id="Mandante_afectado" name="Mandante_afectado" required value="<?php echo htmlspecialchars($Mandante_afectado); ?>">
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
                        <input type="checkbox" id="interno" name="actividades[recepcion][interno]" value="Recepcion en Taller" <?php echo $interno ? 'checked' : ''; ?>>
                        <label for="interno">Colaborador interno</label><br>


                        <input type="checkbox" id="lavadoEquipo" name="actividades[recepcion][lavadoEquipo]" value="Lavado Equipo" <?php echo $lavado_equipo ? 'checked' : ''; ?>>
                        <label for="lavadoEquipo">COY</label> <!-- lavado equipo-->
                    </td>
                    <td>
                        <input type="checkbox" id="UPW" name="actividades[ambiente][UPW]" value="UPW" <?php echo $UPW ? 'checked' : ''; ?>>
                        <label for="UPW">UPW</label><br>
                        <input type="checkbox" id="PRB" name="actividades[ambiente][PRB]" value="Lavado de Partes" <?php echo $PRB ? 'checked' : ''; ?>>
                        <label for="PRB">PRB</label><br>
                        <input type="checkbox" id="PRD" name="actividades[ambiente][PRD]" value="Tintas Penetrantes" <?php echo $PRD ? 'checked' : ''; ?>>
                        <label for="PRD">PRD</label><br>
                        <input type="checkbox" id="QAS" name="actividades[ambiente][QAS]" value="Control Dimensional" <?php echo $QAS ? 'checked' : ''; ?>>
                        <label for="QAS">QAS</label>
                    </td>
                    <td>
                        <input type="checkbox" id="Creacion" name="actividades[accion][Creacion]" value="Creacion" <?php echo $Creacion ? 'checked' : ''; ?>>
                        <label for="Creacion">Creacion</label><br>
                        <input type="checkbox" id="desvinculacion" name="actividades[accion][desvinculacion]" value="desvinculacion" <?php echo $desvinculacion ? 'checked' : ''; ?>>
                        <label for="desvinculacion">Desvinculacion</label><br>
                        <input type="checkbox" id="homologacion" name="actividades[accion][homologacion]" value="homologacion" <?php echo $homologacion ? 'checked' : ''; ?>>
                        <label for="homologacion">Homologacion</label><br>
                        <input type="checkbox" id="reseteo" name="actividades[accion][reseteo]" value="reseteo" <?php echo $reseteo ? 'checked' : ''; ?>>
                        <label for="reseteo">Reset / Modificacion</label>
                    </td>
                </tr>
                <!-- Puedes continuar agregando más filas y columnas si es necesario -->
            </table>

        </div>


    </form>


    <script src="../controllers/Js_functions/ajax_js_crearOT.js"></script> <!-- Scripts genéricos de la aplicación -->


    <?php include("side_menu_bot.php"); ?>



</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <!-- Configuración de caracteres y vista en dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título de la página -->
    <title>Listar Usuarios</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">

    <!-- Hojas de estilo CSS -->
    <!-- Hoja de estilo de Select2 para elementos de selección -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">

    <!-- Hoja de estilo de Select2 personalizada para Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

    <!-- Hoja de estilo personalizada para la página -->
    <link rel="stylesheet" href="../css/users_list.css">

    <!-- Script de la biblioteca SweetAlert2 para alertas personalizadas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <?php

    include("menu.php");
    include("../config/conexion_bd.php");
    ?>

    <nav class="menu-horizontal">
        <ul class="menu-items">
            <li>
            <h3 style="color: #007BFF; padding: 10px;">Modificar usuarios</h3>
            </li>
        </ul>
        <ul class="menu-items" id="archivoListNav"> <!-- Elemento para mostrar archivos adjuntos -->
            <!-- Aquí se mostrarán los archivos adjuntos -->

          

            <button id="guardar-permisos-externo" value="Crear Ticket" class="submit_mininav">
                    <i class="fas fa-save"></i> Grabar Cambios

                </button>

        </ul>
    </nav>


    <div id="confirmation-alert" class="confirmation-alert">
        Cambios guardados correctamente.
    </div>

    <div class="content_principal">

        <div class="content_top">
            <div class="input_group">
                <label for="usuario_modf">Modificado por</label>
                <input type="text" id="usuario_modf" name="usuario_modf" readonly value="Gado Briones Aravena">

                <label for="fecha_modf" style="margin-left: 10px;">Fecha</label>
                <input type="text" id="fecha_modf" name="fecha_modf" readonly value="10-12-2023">
                <input type="text" id="hora_modf" name="hora_modf" readonly value="20:20">
            </div>
        </div>


        <style>
            /* Estilos para filas seleccionadas */
            .selected {
                background-color: #007bff !important;
                color: white;
            }

            .permisos-container {
                display: flex;
                align-items: center;
            }

            .tabla-izquierda,
            .tabla-derecha {
                flex: 1;
                border: 1px solid #ccc;
                padding: 10px;
                margin: 10px;
                max-height: 500px;
                min-height: 500px;
                overflow-y: auto;
                background-color: #f7f7f7;
            }

            .permisos-tabla {
                width: 100%;
                border-collapse: collapse;
            }

            .permisos-tabla th,
            .permisos-tabla td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ccc;
            }

            .permisos-tabla th {
                background-color: #f7f7f7;
                color: black;
                position: relative;
            }

            .permisos-tabla th input[type="text"] {
                width: 100%;
                padding: 5px;
                box-sizing: border-box;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            .permisos-lista {
                list-style: none;
                padding: 0;
            }

            .permisos-lista li {
                cursor: pointer;
            }

            .selected {
                background-color: #007BFF;
                color: white;
            }



            button {
                margin: 5px;
            }

            .confirmation-alert {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background-color: #4CAF50;
                color: white;
                text-align: center;
                padding: 10px;
                font-size: 18px;
                display: none;
                animation: slideIn 1s ease-out forwards, fadeOut 1s 2s ease-out forwards;
            }

            @keyframes slideIn {
                0% {
                    transform: translateY(-100%);
                }

                100% {
                    transform: translateY(0);
                }
            }

            @keyframes fadeOut {
                0% {
                    opacity: 1;
                }

                100% {
                    opacity: 0;
                }
            }
        </style>




        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Tab1')">Perfil</button>
            <button class="tablinks" onclick="openTab(event, 'Tab2')">Roles</button>
            <button class="tablinks" onclick="openTab(event, 'Tab3')">Permisos</button>
        </div>

        <form id="userEditForm" action="users.php" method="POST">
            <!-- Pestaña "Perfil" -->
            <div id="Tab1" class="tabcontent">
                <h3>Editar Perfil</h3>
                <div class="contenedor_columnas">
                    <div class="column_1">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre"><br>

                        <label for="s_nombre">Segundo Nombre:</label>
                        <input type="text" id="s_nombre" name="s_nombre"><br>

                        <label for="a_paterno">A. Paterno:</label>
                        <input type="text" id="a_paterno" name="a_paterno"><br>

                        <label for="a_materno">A. Materno:</label>
                        <input type="text" id="a_materno" name="a_materno"><br>
                    </div>

                    <div class="column_2">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email"><br>

                        <label for="fecha_expiracion" style="font-weight: bold; color: #333;">Fecha de Expiración:</label>
                        <input type="datetime-local" id="fecha_expiracion" name="fecha_expiracion">


                        <label for="telefono">Teléfono:</label>
                        <div class="telefono">

                            <input type="tel" id="codigo_area" name="codigo_area" placeholder="(+56)" pattern="[0-9]{3}">

                            <input type="tel" id="telefono" name="telefono" placeholder="Ingrese su número de teléfono" pattern="[0-9]{10}">


                        </div>

                        <label for="estado_cuenta">Estado de Cuenta:</label>
                        <select id="estado_cuenta" name="estado_cuenta">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="bloqueado">Bloqueado</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- Pestaña "Roles" -->
            <div id="Tab2" class="tabcontent">
                <h3>Editar Roles</h3>
                <label for="rol">Rol:</label>
                <select id="rol" name="rol">
                    <option value="admin">Administrador</option>
                    <option value="usuario">Usuario</option>
                </select><br>
            </div>





            <div id="Tab3" class="tabcontent">


                <h3>Editar Permisos</h3>
                <div class="permisos-container">
                    <div class="tabla-izquierda">
                        <div class="filtro_tablas"> 
                        <!-- Campo de búsqueda para Permisos Disponibles -->
                        <input type="text" class="input_wrapper" id="busqueda-permisos-disponibles" placeholder="Buscar permisos disponibles">
                        <!-- Campo de selección para el tipo de búsqueda en Permisos Disponibles -->
                        <select  class="select_wrapper" id="tipo-busqueda-disponibles">
                            <option value="id">ID</option>
                            <option value="nombre">Nombre</option>
                            <option value="tipo">Tipo</option>
                            <option value="detalle">Detalle</option>
                        </select>
                        </div>

                        <h4 class="sticky-title">
                            Permisos Disponibles
                        </h4>
                        <!-- Tabla de Permisos Disponibles -->
                        <table id="permisos-disponibles" class="permisos-tabla">
                            <thead>
                                <tr>
                                    <th class="sticky-header">ID</th>
                                    <th class="sticky-header">Nombre</th>
                                    <th class="sticky-header">Tipo</th>
                                    <th class="sticky-header">Detalle</th>
                                    <th class="sticky-header">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se agregarán los permisos disponibles desde la base de datos -->
                            </tbody>
                        </table>
                    </div>




                    <div class="tabla-derecha">

                    <div class="filtro_tablas"> 
                        <!-- Campo de búsqueda para Permisos Seleccionados -->
                        <input type="text"  class="input_wrapper" id="busqueda-permisos-seleccionados" placeholder="Buscar permisos seleccionados">
                        <!-- Campo de selección para el tipo de búsqueda en Permisos Seleccionados -->
                        <select class="select_wrapper" id="tipo-busqueda-seleccionados">
                            <option value="id">ID</option>
                            <option value="nombre">Nombre</option>
                            <option value="tipo">Tipo</option>
                            <option value="detalle">Detalle</option>
                        </select>
                    </div>
                        <h4 class="sticky-title">Permisos Seleccionados</h4>
                        <!-- Tabla de Permisos Seleccionados -->
                        <table id="permisos-seleccionados" class="permisos-tabla">
                            <thead>
                                <tr>
                                    <th class="sticky-header">ID</th>
                                    <th class="sticky-header">Nombre</th>
                                    <th class="sticky-header">Tipo</th>
                                    <th class="sticky-header">Detalle</th>
                                    <th class="sticky-header">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se agregarán los permisos seleccionados -->

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
           


    </div>




    </form>

    <script>
    // Obtener elementos del DOM para Permisos Disponibles
    const tipoBusquedaDisponibles = document.getElementById("tipo-busqueda-disponibles");
    const busquedaDisponibles = document.getElementById("busqueda-permisos-disponibles");
    const tablaPermisosDisponibles = document.getElementById("permisos-disponibles").getElementsByTagName("tbody")[0];

    // Obtener elementos del DOM para Permisos Seleccionados
    const tipoBusquedaSeleccionados = document.getElementById("tipo-busqueda-seleccionados");
    const busquedaSeleccionados = document.getElementById("busqueda-permisos-seleccionados");
    const tablaPermisosSeleccionados = document.getElementById("permisos-seleccionados").getElementsByTagName("tbody")[0];

    // Función para resaltar las coincidencias en una celda
    function resaltarCoincidencias(celda, filtro) {
        const contenido = celda.textContent.toLowerCase();
        const contenidoOriginal = contenido.replace(/<span class="resaltado">(.*?)<\/span>/gi, '$1');
        const coincidencias = contenidoOriginal.split(new RegExp(`(${filtro})`, 'gi'));
        const contenidoResaltado = coincidencias.map(part => {
            return filtro.toLowerCase() === part.toLowerCase() ? `<span class="resaltado">${part}</span>` : part;
        }).join('');
        celda.innerHTML = contenidoResaltado;
    }

    // Función para quitar el resaltado en una celda
    function quitarResaltado(celda) {
        const contenidoOriginal = celda.textContent.replace(/<span class="resaltado">(.*?)<\/span>/gi, '$1');
        celda.innerHTML = contenidoOriginal;
    }

    // Función para limpiar el resaltado en todas las celdas de la tabla
    function limpiarResaltadoTabla(tabla) {
        const celdasResaltadas = tabla.querySelectorAll("span.resaltado");
        celdasResaltadas.forEach((span) => {
            const parent = span.parentNode;
            parent.replaceChild(document.createTextNode(span.textContent), span);
        });
    }

    // Función para filtrar y resaltar la tabla de Permisos
    function filtrarYResaltarTabla(tabla, tipoBusqueda, busqueda) {
        const tipo = tipoBusqueda.value.toLowerCase();
        const filtro = busqueda.value.toLowerCase();

        limpiarResaltadoTabla(tabla);

        const filas = tabla.getElementsByTagName("tr");
        for (let i = 0; i < filas.length; i++) {
            const fila = filas[i];
            const celda = fila.getElementsByTagName("td")[tipo === "id" ? 0 : tipo === "nombre" ? 1 : tipo === "tipo" ? 2 : 3];

            if (celda) {
                const contenido = celda.textContent.toLowerCase();
                if (contenido.includes(filtro)) {
                    resaltarCoincidencias(celda, filtro);
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
            }
        }
    }

    // Agregar eventos de cambio de valor al campo de selección y entrada de texto para Permisos Disponibles
    tipoBusquedaDisponibles.addEventListener("change", () => {
        filtrarYResaltarTabla(tablaPermisosDisponibles, tipoBusquedaDisponibles, busquedaDisponibles);
    });
    busquedaDisponibles.addEventListener("input", () => {
        filtrarYResaltarTabla(tablaPermisosDisponibles, tipoBusquedaDisponibles, busquedaDisponibles);
    });

    // Agregar eventos de cambio de valor al campo de selección y entrada de texto para Permisos Seleccionados
    tipoBusquedaSeleccionados.addEventListener("change", () => {
        filtrarYResaltarTabla(tablaPermisosSeleccionados, tipoBusquedaSeleccionados, busquedaSeleccionados);
    });
    busquedaSeleccionados.addEventListener("input", () => {
        filtrarYResaltarTabla(tablaPermisosSeleccionados, tipoBusquedaSeleccionados, busquedaSeleccionados);
    });

    // Evitar que la tecla "Enter" afecte a ambas tablas al mismo tiempo en Permisos Disponibles
    busquedaDisponibles.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    // Evitar que la tecla "Enter" afecte a ambas tablas al mismo tiempo en Permisos Seleccionados
    busquedaSeleccionados.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    // Función para filtrar la tabla de Permisos
    function filtrarTabla(tabla, tipoBusqueda, busqueda) {
        const tipo = tipoBusqueda.value.toLowerCase();
        const filtro = busqueda.value.toLowerCase();

        const terminos = filtro.split(";").map(term => term.trim()); // Separa los términos por punto y coma

        const filas = tabla.getElementsByTagName("tr");
        for (let i = 0; i < filas.length; i++) {
            const fila = filas[i];
            const celda = fila.getElementsByTagName("td")[tipo === "id" ? 0 : tipo === "nombre" ? 1 : tipo === "tipo" ? 2 : 3];

            if (celda) {
                const contenido = celda.textContent.toLowerCase();
                let coincidenciaEnFila = false;

                for (const termino of terminos) {
                    if (contenido.includes(termino)) {
                        coincidenciaEnFila = true;
                        resaltarCoincidencias(celda, termino);
                        break; // Si encuentra al menos un término, no es necesario seguir buscando
                    }
                }

                fila.style.display = coincidenciaEnFila ? "" : "none";
            }
        }
    }

    // Agregar eventos de cambio de valor al campo de selección y entrada de texto para Permisos Disponibles
    tipoBusquedaDisponibles.addEventListener("change", () => {
        filtrarTabla(tablaPermisosDisponibles, tipoBusquedaDisponibles, busquedaDisponibles);
    });
    busquedaDisponibles.addEventListener("input", () => {
        filtrarTabla(tablaPermisosDisponibles, tipoBusquedaDisponibles, busquedaDisponibles);
    });

    // Agregar eventos de cambio de valor al campo de selección y entrada de texto para Permisos Seleccionados
    tipoBusquedaSeleccionados.addEventListener("change", () => {
        filtrarTabla(tablaPermisosSeleccionados, tipoBusquedaSeleccionados, busquedaSeleccionados);
    });
    busquedaSeleccionados.addEventListener("input", () => {
        filtrarTabla(tablaPermisosSeleccionados, tipoBusquedaSeleccionados, busquedaSeleccionados);
    });

    // Evitar que la tecla "Enter" afecte a ambas tablas al mismo tiempo en Permisos Disponibles
    busquedaDisponibles.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });

    // Evitar que la tecla "Enter" afecte a ambas tablas al mismo tiempo en Permisos Seleccionados
    busquedaSeleccionados.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
    });
</script>







    <style>
        /* Estilos iniciales para los títulos y encabezados de tabla */
        .sticky-title {
            background-color: #f7f7f7;
            position: -webkit-sticky;
            position: sticky;
            top: -10px;
            z-index: 1;
            height: 40px;
            /* Asegura que los títulos estén por encima de los encabezados de tabla */
        }

        /* Estilos para los encabezados de tabla pegajosos */
        .permisos-tabla th.sticky-header {
            background-color: #f7f7f7;
            color: black;
            position: -webkit-sticky;
            position: sticky;
            top: 30px;
            z-index: 2;
            height: 50px;

        }
        .resaltado {
    background-color: yellow; /* Puedes ajustar el color de resaltado aquí */
    font-weight: bold;
}

.filtro_tablas {
    display: flex;
    justify-content: space-between;
}

.input_wrapper {
    flex: 80%;
}

.select_wrapper {
    flex: 20%;
}


    </style>


    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;

            // Ocultar todos los contenidos de las pestañas
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Desactivar todos los botones de pestaña
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Mostrar el contenido de la pestaña seleccionada y activar su botón
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";

            // Ajustar la posición de desplazamiento de la página para centrarse en la pestaña
            var tabElement = document.getElementById(tabName);
            if (tabElement) {
                tabElement.scrollIntoView({
                    behavior: "smooth"
                });
            }
        }

        // Establecer la pestaña activa por defecto
        document.querySelector('.tab button').click();
    </script>


    <style>
        /* Estilos para los botones de pestaña */
        .tab {
            display: flex;
            background-color: #f3f3f3;
            border-bottom: 2px solid #ccc;

        }

        .tab button {
            flex: 1;
            background-color: black;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 15px 20px;
            transition: background-color 0.3s;
            font-size: 16px;

            border: 1px solid #ccc;


        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #fff;

            color: #007bff;
            margin-bottom: -2px;
            border-bottom: none;
            font-weight: bold;
            /* Esto hará que la etiqueta esté en negrita */
            color: #000;


        }

        /* Estilos para el contenido de las pestañas */
        .tabcontent {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 5px 5px;
            background-color: #fff;
        }

        /* Mostrar el contenido de la pestaña activa */
        .tabcontent.active {
            display: block;

        }

        /* Estilos para el contenedor de columnas */
        .contenedor_columnas {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;

        }

        /* Estilos para cada columna */
        .column_1,
        .column_2 {
            flex: 0 0 calc(50% - 10px);
            /* 50% de ancho menos 10px de espacio entre columnas */
            box-sizing: border-box;
            margin-bottom: 10px;
            padding: 10px;

            border-radius: 4px;

        }

        /* Estilos para etiquetas y campos de entrada */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="datetime-local"],

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Estilos para el botón */
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;

            cursor: pointer;
        }

        /* Cambiar el color del botón cuando se pasa el mouse por encima */
        button:hover {
            background-color: #45a049;
        }

        .telefono {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Espacio entre los elementos */
        }

        #codigo_area {
            width: 100px;

        }
    </style>

    </div>





    <!-- Agrega tus scripts adicionales aquí -->

    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>



    
    <script src="../controllers/Js_functions/main.js"></script>

</body>

</html>
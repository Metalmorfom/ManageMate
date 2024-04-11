
// Variable para rastrear si se ha realizado alguna acción en la página
let accionRealizada = false;
let formularioEnviado = false; // Bandera para el envío del formulario






const archivosAdjuntos = [];

// Función para cargar el archivo temporalmente
// Función para cargar el archivo temporalmente
function subirArchivoTemporalmente(archivo) {



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
        xhr.open('POST', '../controllers/controlador_storage_temp.php', true);

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
                    actualizarListaArchivos();
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












function actualizarListaArchivos() {
    const fileList = document.getElementById('fileList');
    const fileListNav = document.getElementById('archivoListNav');
    fileList.innerHTML = ''; // Vacía la lista para evitar duplicados
    fileListNav.innerHTML = ''; // Vacía la lista del menú de navegación

    archivosAdjuntos.forEach(nombreArchivo => {

        // Aquí aplicas la función de formateo
        const nombreFormateado = formatearNombreArchivo(nombreArchivo);

        // Actualiza la lista de archivos adjuntos en la tabla
        const fila = document.createElement('tr');
        const nombre = document.createElement('td');
        nombre.id = 'nombre-td';


        // Crear un enlace para descargar el archivo en la tabla
        const downloadLinkTabla = document.createElement('a');
        downloadLinkTabla.href = `../temp/${nombreArchivo}`; // Asegúrate de que la ruta sea correcta
        downloadLinkTabla.download = nombreArchivo; // Esto hará que el enlace descargue el archivo

        // Crear un icono para el archivo en la tabla
        const iconoTabla = document.createElement('i');
        iconoTabla.className = 'fas fa-file-alt';
        iconoTabla.style.marginRight = '5px';

        // Crea un span para el texto del nombre del archivo para mejor control de estilos en la tabla
        const textSpanTabla = document.createElement('span');
        textSpanTabla.textContent = nombreFormateado;

        // Agrega primero el icono y luego el span de texto al enlace de descarga en la tabla
        downloadLinkTabla.appendChild(iconoTabla);
        downloadLinkTabla.appendChild(textSpanTabla);
        nombre.appendChild(downloadLinkTabla);

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

        // Actualiza la lista de archivos adjuntos en el menú de navegación
        const listItemNav = document.createElement('li');

        // Contenedor para el icono
        const iconoContenedor = document.createElement('span');
        iconoContenedor.className = 'icono-contenedor';
        const iconoNav = document.createElement('i');
        iconoNav.className = 'fas fa-file-alt notita';
        iconoContenedor.appendChild(iconoNav);

        // Contenedor para el nombre del archivo (como un enlace)
        const nombreContenedor = document.createElement('a');
        nombreContenedor.className = 'nombre-contenedor';
        nombreContenedor.href = `../temp/${nombreArchivo}`; // Ruta del archivo
        nombreContenedor.download = nombreFormateado; // Esto hará que el enlace descargue el archivo
        nombreContenedor.textContent = nombreFormateado;


        // Agrega los contenedores al elemento de la lista
        listItemNav.appendChild(iconoContenedor);
        listItemNav.appendChild(nombreContenedor);

        // Añade el elemento de la lista al menú de navegación
        fileListNav.appendChild(listItemNav);
    });

    const archivoInput = document.getElementById('archivoInput');
    archivoInput.value = ""; // Establece el valor del input a una cadena vacía
}




function formatearNombreArchivo(nombreOriginal) {
    // Elimina los primeros 13 caracteres del nombre del archivo
    let nombreFormateado = nombreOriginal.substring(14);
    return nombreFormateado;
}





function eliminarArchivo(nombreArchivo) {
    console.log("se ejecuto EliminarArchivo_ temp");
    // Confirmar con el usuario antes de eliminar el archivo
    const confirmacion = confirm(`¿Seguro que deseas eliminar el archivo "${nombreArchivo}"?`);

    if (confirmacion) {
        // Eliminar el archivo del servidor PHP
        $.ajax({
            url: '../controllers/eliminar_archivo_temp.php', // Reemplaza con la URL correcta de tu script PHP
            type: 'POST',
            data: {
                nombreArchivo: nombreArchivo
            },
            success: function(response) {
                // Verificar la respuesta del servidor
                var respuestaTrimmed1 = response.trim();
                if (respuestaTrimmed1 === 'OK') {
                    // Eliminación exitosa del archivo en el servidor
                    alert('Archivo eliminado exitosamente: ' + nombreArchivo);

                    // Eliminar el archivo de la lista de archivos adjuntos en la página
                    const indice = archivosAdjuntos.indexOf(nombreArchivo);
                    if (indice !== -1) {
                        archivosAdjuntos.splice(indice, 1);
                        actualizarListaArchivos(); // Actualiza la lista en la página
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



function eliminarArchivoDOS(nombreArchivo) {
    console.log("se ejecuto EliminarArchivo_temp");

    // Eliminar el archivo del servidor PHP
    $.ajax({
        url: '../controllers/eliminar_archivo_temp.php', // Reemplaza con la URL correcta de tu script PHP
        type: 'POST',
        data: {
            nombreArchivo: nombreArchivo
        },
        success: function(response) {
            // Verificar la respuesta del servidor
            if (response === 'OK') {
                // Eliminación exitosa del archivo en el servidor
                console.log('Archivo eliminado DOS exitosamente: ' + nombreArchivo);

                // Eliminar el archivo de la lista de archivos adjuntos en la página
                const indice = archivosAdjuntos.indexOf(nombreArchivo);
                if (indice !== -1) {
                    archivosAdjuntos.splice(indice, 1);
                    actualizarListaArchivos(); // Actualiza la lista en la página
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


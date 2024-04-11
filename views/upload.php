<!DOCTYPE html>
<html>
<head>
    <title>Carga de archivos en tiempo real</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">
</head>
<body>
<div id="respuesta"></div>


    <input type="file" id="archivoInput" onchange="subirArchivo()">
    <progress value="0" max="100" id="progressBar"></progress>
    <div id="mensaje"></div>
    <script>
        function subirArchivo() {
    const archivoInput = document.getElementById('archivoInput');
    const progressBar = document.getElementById('progressBar');
    const respuestaDiv = document.getElementById('respuesta'); // El <div> para mostrar la respuesta
    const archivo = archivoInput.files[0];

    if (archivo) {
        respuestaDiv.innerHTML = 'Subiendo archivo...';

        const formData = new FormData();
        formData.append('archivo', archivo);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'controllers/controlador_storage.php', true);

        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                const porcentaje = (e.loaded / e.total) * 100;
                progressBar.value = porcentaje;
            }
        };

        xhr.onload = function () {
            if (xhr.status === 200) {
                progressBar.value = 100;
                respuestaDiv.innerHTML = xhr.responseText; // Muestra la respuesta del script
            } else {
                respuestaDiv.innerHTML = 'Error al subir el archivo.';
            }
        };

        xhr.send(formData);
    }
}

    </script>
</body>
</html>

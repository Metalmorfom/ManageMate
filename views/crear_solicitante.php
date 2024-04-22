<!DOCTYPE html>
<html>

<head>
    <title>Crear Usuarios</title>
    <link rel="icon" href="../images/icon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/crear_solicitante.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <?php
    include("menu.php");

    ?>
    <?php include("side_menu_top.php"); ?>

    <div class="header">
        <h1>Crear Usuarios</h1>
    </div>


    <div class="form_container">
        <form method="post" id="SolicitanteForm" action="">
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la Empresa:</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa">
                <input type="hidden" class="form-control" id="rut_empresa" name="rut_empresa">
                <div id="resultado_empresas" class="resultado-empresas">
                <div id="loader" class="loader">Cargando...</div>
            </div>
            </div>
           




            <div class="form-group">
                <label for="rut_cliente">RUT del Cliente:</label>
                <input type="text" class="form-control" id="rut_cliente" name="rut_cliente">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="form-group">
                <label for="s_nombre">Segundo Nombre:</label>
                <input type="text" class="form-control" id="s_nombre" name="s_nombre">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos">
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" class="form-control" id="telefono" name="telefono">
            </div>
            <!-- Agrega más campos según sea necesario -->

            <button type="submit" class="btn btn-primary">Crear Cliente</button>


        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#SolicitanteForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '../controllers/crear_solicitante/controlador_crear_solicitante.php', // Asegúrate de que la ruta sea correcta
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message,
                                onClose: () => {
                                    // Limpiar la variable 'message'
                                    response.message = '';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                onClose: () => {
                                    // Limpiar la variable 'message'
                                    response.message = '';
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // Ver qué respuesta está enviando el servidor
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema con la petición, por favor inténtalo de nuevo.',
                            onClose: () => {
                                // Limpiar la variable 'message'
                                response.message = '';
                            }
                        });
                    }
                });
            });
        });
    </script>


    <script>
$(document).ready(function() {
    $('#nombre_empresa').on('input', function() {
        var nombreEmpresa = $(this).val();
        buscarEmpresas(nombreEmpresa);
    });

    $('#nombre_empresa').on('focus', function() {
        var nombreEmpresa = $(this).val();
        if (nombreEmpresa === '') {
            buscarEmpresas('');
        }
    });

    function buscarEmpresas(nombreEmpresa) {
        $('#resultado_empresas').html('<div id="loader" class="loader">Cargando...</div>').show(); 
        $.ajax({
            url: '../controllers/crear_solicitante/buscar_empresa.php',
            type: 'post',
            dataType: 'json',
            data: {
                nombre_empresa: nombreEmpresa
            },
            success: function(response) {
                var html = '';
                if (response.length > 0) {
                    $.each(response, function(index, empresa) {
                        html += '<p class="empresa-item" data-rut="' + empresa.rut_empresa + '">' + empresa.nombre + '</p>';
                    });
                } else {
                    html = '<p>No hay resultados</p>';
                }
                $('#resultado_empresas').html(html).show();
            },
            complete: function() {
                $('#loader').hide(); // Ocultar el loader después de que se complete la solicitud AJAX
            }
        });
    }

    // Manejar el clic en los elementos de la lista
    $(document).on('click', '.empresa-item', function() {
        var nombreEmpresaSeleccionada = $(this).text();
        var rutEmpresaSeleccionada = $(this).data('rut'); // Obtener el rut de la empresa seleccionada
        $('#nombre_empresa').val(nombreEmpresaSeleccionada);
        $('#rut_empresa').val(rutEmpresaSeleccionada); // Asignar el rut al campo hidden
        $('#resultado_empresas').html('').hide();
    });

    // Manejar la tecla Enter en el campo de búsqueda
    $('#nombre_empresa').on('keypress', function(e) {
        if (e.which === 13) { // Verificar si se presionó la tecla Enter
            var nombreEmpresaSeleccionada = $('#resultado_empresas .empresa-item:first').text();
            var rutEmpresaSeleccionada = $('#resultado_empresas .empresa-item:first').data('rut'); // Obtener el rut de la empresa seleccionada
            $('#nombre_empresa').val(nombreEmpresaSeleccionada);
            $('#rut_empresa').val(rutEmpresaSeleccionada); // Asignar el rut al campo hidden
            $('#resultado_empresas').html('').hide();
        }
    });

    // Ocultar la lista si se hace clic fuera del campo de búsqueda y fuera de la lista
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#resultado_empresas').length && !$(e.target).is('#nombre_empresa')) {
            $('#resultado_empresas').html('').hide();
        }
    });
});


    </script>

    <!-- boostrap 5-->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- boostrap 5-->
    <!-- Incluye solo el archivo JavaScript de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


    <script src="../controllers/validations/valid_edit_perfil.js"></script>
    <?php include("side_menu_bot.php"); ?>
</body>

</html>
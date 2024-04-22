var formulario = document.querySelector('form');
var btnSubmit = document.querySelector('button[name="btnRegistrarse"]'); // Obtener el botón de envío

var vali_rut = '';
var vali_correo = '';
var vali_clave = '';
var vali_rol = '';
var vali_fecha_exp = '';
var vali_vacio = 'ok';
var vali_check = '';

var miCheckbox = document.getElementById('miCheckbox');

miCheckbox.addEventListener('click', function () {
    if (miCheckbox.checked) {
        vali_check = 'ok';
        // La casilla de verificación está marcada, puedes llamar a tu función aquí
        GlobalValidadores()// Reemplaza 'tuFuncionDeValidacion' con el nombre de tu función
    } else {
        vali_check = '';
        GlobalValidadores()
        // La casilla de verificación está desmarcada
    }
});


function GlobalValidadores() {
    var check_error = document.getElementById('miCheckbox-error');
    if (vali_rut && vali_correo && vali_clave && vali_rol && vali_fecha_exp && vali_vacio &&  vali_check === 'ok') {

        btnSubmit.disabled = false;
        check_error.textContent = '';
    } else {
        btnSubmit.disabled = true;
        check_error.textContent = 'Revisa campos(*)';
    }
}




// Función para validar el formulario completo
function validarFormulario() {
    
    var camposValidos = true;

    // Función de validación para campos no vacíos
    function validarCampoNoVacio(inputId, errorId) {
        var input = document.getElementById(inputId);
        var error = document.getElementById(errorId);
        var valor = input.value.trim();

        if (valor === '') {
            error.textContent = 'Este campo no puede estar vacío.';
            camposValidos = false;
        } else {
            error.textContent = '';
        }
    }

    // Validar cada campo
    validarCampoNoVacio('rut_user', 'rut_user-error');
    validarCampoNoVacio('nombre', 'nombre-error');
    validarCampoNoVacio('s_nombre', 's_nombre-error');
    validarCampoNoVacio('ap_paterno', 'ap_paterno-error');
    validarCampoNoVacio('ap_materno', 'ap_materno-error');
    validarCampoNoVacio('correo', 'correo-error');
    validarCampoNoVacio('contrasenia', 'contrasenia-error');
    validarCampoNoVacio('id_rol', 'id_rol-error');
    //validarCampoNoVacio('telefono', 'telefono-error');




    if (camposValidos) {
        vali_vacio = 'ok'; // Habilitar el botón
    } else {
        vali_vacio = ''; // Deshabilitar el botón
    }



    // ... (validaciones de otros campos)

    // Agrega la validación de la fecha de expiración
    if (!validarFechaExpiracion()) {
        // Manejar el error de la fecha de expiración si es necesario
        camposValidos = false;
    }

    // ... (resto del código)

    // Devolver true si todos los campos son válidos, de lo contrario, false
    return camposValidos;
}



// Llamar a la función validarFormulario al enviar el formulario
formulario.addEventListener('submit', function (event) {
    if (!validarFormulario()) {
        event.preventDefault(); // Evitar el envío del formulario si no son válidos
    }
});


//#############################################################################################################################################################


document.addEventListener('DOMContentLoaded', function () {
    var fechaExpiracionInput = document.getElementById('fecha_expiracion');
    var fechaActual = new Date();
    var dia = ('0' + fechaActual.getDate()).slice(-2); // Asegúrate de tener dos dígitos
    var mes = ('0' + (fechaActual.getMonth() + 1)).slice(-2); // Asegúrate de tener dos dígitos y suma 1 porque los meses empiezan en 0
    var ano = fechaActual.getFullYear();

    // Establece el atributo 'min' en el formato de fecha 'aaaa-mm-dd'
    fechaExpiracionInput.setAttribute('min', ano + '-' + mes + '-' + dia);
});

//#############################################################################################################################################################


// Función para validar un RUT 
function validarRUT(rut) {
    var rutRegex = /^\d{1,2}\.\d{3}\.\d{3}-[\dkK]$/;

    if (!rutRegex.test(rut)) return false;

    rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();
    var cuerpo = rut.slice(0, -1);
    var digitoVerificador = rut.slice(-1);

    var suma = 0;
    var multiplicador = 2;

    for (var i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo[i]) * multiplicador;
        multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
    }

    var resto = suma % 11;
    var digitoCalculado = 11 - resto;

    if (digitoCalculado === 11) digitoCalculado = 0;
    if (digitoCalculado === 10) digitoCalculado = 'K';

    return digitoCalculado == digitoVerificador;
}


// Función de validación en cada pulsación del RUT
function validarRutEnTiempoReal() {
    var rutInput = document.getElementById('rut_user');
    var rutError = document.getElementById('rut_user-error');
    var rut = rutInput.value.trim();
    var btnSubmit = document.getElementById('btnSubmit'); // Obtener el botón de envío

    // Limitar la cantidad máxima de caracteres a 12
    if (rut.length > 12) {
        rutInput.value = rut.slice(0, 12);
    }

    if (validarRUT(rut)) {
        rutError.textContent = '';
        vali_rut = 'ok'; // Habilitar el botón si el RUT es válido
        GlobalValidadores();
    } else {
        rutError.textContent = 'Por favor, ingrese un RUT válido en el formato "12.445.678-k".';
        vali_rut = '';// Deshabilitar el botón si el RUT es inválido
        GlobalValidadores();
    }
}

// Asignar la función de validación al evento keyup del campo de RUT
var rutInput = document.getElementById('rut_user');
rutInput.addEventListener('keyup', function () {
    validarRutEnTiempoReal();
});


//#############################################################################################################################################################
//#############################################################################################################################################################

// Función para formatear el RUT con puntos y guión
function formatearRUT(rut) {
    rut = rut.replace(/[^\dKk]/g, '').toUpperCase();
    var cuerpo = rut.slice(0, -1);
    var digitoVerificador = rut.slice(-1);

    if (cuerpo.length > 1) {
        var rutFormateado = cuerpo.slice(-6).replace(/(\d)(?=(\d{3})+$)/g, '$1.');
        rutFormateado = cuerpo.slice(0, -6) + '.' + rutFormateado + '-' + digitoVerificador;
        return rutFormateado;
    } else {
        return rut;
    }
}

// Función para aplicar el formato automáticamente
function aplicarFormatoRUT() {
    var rutInput = document.getElementById('rut_user');
    var rut = rutInput.value;
    rutInput.value = formatearRUT(rut);
}

// Asignar la función de formato al evento input del campo de RUT
var rutInput = document.getElementById('rut_user');
rutInput.addEventListener('input', function () {
    aplicarFormatoRUT();
});

//#############################################################################################################################################################
//#############################################################################################################################################################
// Función de validación en cada pulsación de los campos de texto
function bloquearCaracteresInvalidosYLimitar(event, maxLength) {
    var campo = event.target;
    var tecla = event.key;

    // Permitir teclas de control como retroceso (backspace) y borrar (delete)
    if (event.ctrlKey || event.altKey || event.metaKey || event.key === 'Backspace' || event.key === 'Delete') {
        return;
    }


if (!/^[a-zA-Z\s]$/.test(tecla)) {
    event.preventDefault(); 
}


    // Verificar si se ha alcanzado el límite de caracteres
    if (campo.value.length >= maxLength) {
        event.preventDefault(); // Evitar la entrada de más caracteres
    }

    // Aplicar el estilo al campo según su validez
    if (campo.value.length > 0 && campo.value.length <= maxLength && /^[a-zA-Z]*$/.test(campo.value)) {
        campo.style.borderColor = 'green'; // Campo válido
    } else {
        campo.style.borderColor = ''; // Campo no válido
    }
}

// Asignar la función de bloqueo al evento keydown de los campos de texto
var camposTexto = document.querySelectorAll('.limitar-caracteres');
camposTexto.forEach(function (campo) {
    var maxLength = parseInt(campo.getAttribute('maxlength')) || 30; // Obtener el límite de caracteres o usar 30 por defecto
    campo.addEventListener('keydown', function (event) {
        bloquearCaracteresInvalidosYLimitar(event, maxLength);
    });
});


//#############################################################################################################################################################
//#############################################################################################################################################################

var correoInput = document.getElementById("correo");
var errorCorreo = document.getElementById("correo-error");


// Agregar el evento keyup para llamar a la función validarCorreo en tiempo real
correoInput.addEventListener("keyup", validarCorreo);

function validarCorreo() {
    var correo = correoInput.value;
    var formatoCorreo = /^[^\s@]+@(duocuc|gmail|hotmail)\.(com|cl|otro)$/;

    if (!correo.match(formatoCorreo)) {
        correoInput.style.borderColor = "red";
        errorCorreo.textContent = "Por favor, introduce un correo electrónico válido (example@gmail.com).";
        vali_correo = '';// Deshabilitar el botón si el correo no es válido
        GlobalValidadores();
    } else {
        correoInput.style.borderColor = "green";
        errorCorreo.innerText = "";
        vali_correo = 'ok';// Habilitar el botón si el correo es válido
        GlobalValidadores();
    }
}



//#############################################################################################################################################################
//#############################################################################################################################################################


var contraseniaInput = document.getElementById('contrasenia');
var contraseniaError = document.getElementById('contrasenia-error');


// Agregar el evento keyup para llamar a la función validarContraseniaEnTiempoReal en tiempo real
contraseniaInput.addEventListener('keyup', function () {
    validarContraseniaEnTiempoReal();
});

function validarContrasenia(contrasenia) {
    // Al menos 2 números
    var numeroRegex = /\d/g;
    var numerosEncontrados = contrasenia.match(numeroRegex);
    if (numerosEncontrados && numerosEncontrados.length >= 2) {
        // Al menos 1 letra mayúscula
        var mayusculaRegex = /[A-Z]/g;
        if (mayusculaRegex.test(contrasenia)) {
            // Al menos 1 letra minúscula
            var minusculaRegex = /[a-z]/g;
            if (minusculaRegex.test(contrasenia)) {
                // Al menos 1 símbolo (cualquier caracter que no sea letra ni número)
                var simboloRegex = /[^a-zA-Z0-9]/g;
                if (simboloRegex.test(contrasenia)) {
                    return true; // La contraseña es válida
                }
            }
        }
    }
    return false; // La contraseña no cumple con los requisitos
}

// Función de validación en tiempo real
function validarContraseniaEnTiempoReal() {
    var contrasenia = contraseniaInput.value;

    if (validarContrasenia(contrasenia)) {
        contraseniaError.textContent = '';
        contraseniaInput.style.borderColor = 'green'; // Establece el borde en verde
        vali_clave = 'ok';// Habilitar el botón si la contraseña es válida
        GlobalValidadores();
    } else {
        contraseniaError.textContent = 'La contraseña debe tener al menos dos números, una letra mayúscula, una letra minúscula y un símbolo.';
        contraseniaInput.style.borderColor = 'red'; // Establece el borde en rojo
        vali_clave = '';// Deshabilitar el botón si la contraseña no es válida
        GlobalValidadores();
    }
}

$(document).ready(function () {

    function validarRol() {
        var selectedValue = $('#id_rol').val();
        var idRolError = $('#id_rol-error');

        if (selectedValue === "") {
            idRolError.text('Por favor, seleccione un rol válido.');
            $('#id_rol').css('borderColor', 'red');
            vali_rol = '';
        } else {
            idRolError.text('');
            $('#id_rol').css('borderColor', 'green');
            vali_rol = 'ok';
        }
        GlobalValidadores();
    }

    // Asignar la función de validación al evento change del select
    $('#id_rol').on('change', validarRol);
    
    // No llame a validarRol aquí para que no se muestre el mensaje de error al cargar
    // $('#id_rol').trigger('change');
});




function validarFechaExpiracion() {
    var inputFecha = document.getElementById('fecha_expiracion');
    var fecha = inputFecha.value;
    var errorFecha = document.getElementById('fecha_expiracion-error');
    var fechaActual = new Date();
    fechaActual.setHours(0, 0, 0, 0); // Omitir la hora actual y comparar solo la fecha

    // Convertir la fecha ingresada a un objeto Date para comparar
    var fechaExpiracion = new Date(fecha);

    // Verificar que la fecha ingresada no sea inválida y no sea anterior a la fecha actual
    if (fecha === '') {
        errorFecha.textContent = 'Por favor, ingrese la fecha de expiración.';
        vali_fecha_exp = ''; // Indicar que la validación de la fecha de expiración falló
        GlobalValidadores();
        return false;
    } else if (fechaExpiracion < fechaActual) {
        errorFecha.textContent = 'La fecha de expiración no puede ser anterior a la fecha actual.';
        vali_fecha_exp = ''; // Indicar que la validación de la fecha de expiración falló
        GlobalValidadores();
        return false;
    } else {
        errorFecha.textContent = ''; // Limpiar cualquier mensaje de error previo
        vali_fecha_exp = 'ok'; // Indicar que la validación de la fecha de expiración pasó
        GlobalValidadores();
        return true;
    }
    
}

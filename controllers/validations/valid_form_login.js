function validarCorreo() {
    var correo = document.getElementById("usuario");
    var errorCorreo = document.getElementById("errorCorreo");
    var formatoCorreo = /^[^\s@]+@(duocuc|gmail|hotmail)\.(com|cl|otro)$/;

    // solo dominios var formatoCorreo = /^[^\s@]+@[^\s@]+\.(com|cl|otro)$/;

    if (!correo.value.match(formatoCorreo)) {
        correo.style.borderColor = "red";
        errorCorreo.innerText = "Por favor, introduce un correo electrónico válido. example@gmail.com";
        return false;
    } else {
        correo.style.borderColor = "green";

        errorCorreo.innerText = "";
    }
    return true;
}

function validarContrasena() {
    var contrasena = document.getElementById("contrasena");
    var errorContrasena = document.getElementById("errorContrasena");

    if (contrasena.value.length < 6) {
        contrasena.style.borderColor = "red";
        errorContrasena.innerText = "La contraseña debe tener al menos 6 caracteres.";

        return false;
    } else {
        contrasena.style.borderColor = "green";

        errorContrasena.innerText = "";
    }
    return true;
}
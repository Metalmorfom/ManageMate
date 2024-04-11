document.addEventListener("DOMContentLoaded", function () {
    // Obtén los elementos de entrada por su ID
    var nuevoNombreInput = document.getElementById("nuevoNombre");
    var nuevoSNombreInput = document.getElementById("nuevoSNombre");
    var nuevoApPaternoInput = document.getElementById("nuevoApPaterno");
    var nuevoApMaternoInput = document.getElementById("nuevoApMaterno");

    // Función para bloquear números, símbolos y permitir una sola palabra de hasta 20 caracteres
    function bloquearNumerosYSimbolos(event) {
        var inputValue = event.target.value;
        var newValue = inputValue.replace(/[^a-zA-Z]/g, ""); // Permitir solo letras
        newValue = newValue.slice(0, 20); // Limitar a 20 caracteres

        // Verificar si hay espacios en blanco y eliminarlos
        if (newValue.indexOf(" ") !== -1) {
            newValue = newValue.replace(/\s/g, "");
        }

        event.target.value = newValue; // Establecer el nuevo valor en el campo
    }

    // Agrega el evento input a cada campo de entrada
    nuevoNombreInput.addEventListener("input", bloquearNumerosYSimbolos);
    nuevoSNombreInput.addEventListener("input", bloquearNumerosYSimbolos);
    nuevoApPaternoInput.addEventListener("input", bloquearNumerosYSimbolos);
    nuevoApMaternoInput.addEventListener("input", bloquearNumerosYSimbolos);

    // Obtén el elemento de entrada de correo electrónico por su ID
    var nuevoCorreoInput = document.getElementById("nuevoCorreo");

    // Establecer el campo de correo electrónico como no editable
    nuevoCorreoInput.setAttribute("readonly", "readonly");
    nuevoCorreoInput.classList.add("disabled-field");
});



document.addEventListener("DOMContentLoaded", function () {
    // Obtén el elemento de entrada de teléfono por su ID
    var nuevoTelefonoInput = document.getElementById("telefono");

    // Función para bloquear caracteres no numéricos
    function bloquearCaracteresNoNumericos(event) {
        var inputValue = event.target.value;
        var newValue = inputValue.replace(/[^\d]/g, ""); // Permitir solo dígitos numéricos
        event.target.value = newValue; // Establecer el nuevo valor en el campo

        // Validar que no haya más de 9 dígitos
        if (newValue.length > 9) {
            event.target.value = newValue.slice(0, 9); // Limitar a 9 dígitos
        }
    }

    // Agregar el evento input al campo de teléfono
    nuevoTelefonoInput.addEventListener("input", bloquearCaracteresNoNumericos);
});


document.addEventListener("DOMContentLoaded", function () {
    // Obtén el elemento de entrada de teléfono por su ID
    var telefonoInput = document.getElementById("telefono");

    // Función para formatear el número de teléfono visualmente
    function formatearNumeroTelefono() {
        var inputValue = telefonoInput.value;
        var formattedNumber = '';

        if (inputValue.length > 0) {
            formattedNumber = inputValue.substring(0, 1); // Primer dígito sin formato
        }
        if (inputValue.length > 1) {
            formattedNumber += ' ' + inputValue.substring(1).replace(/(\d{2})(?=\d)/g, '$1 '); // Grupos de dos dígitos sin paréntesis
        }

        telefonoInput.value = formattedNumber; // Establecer el nuevo valor formateado en el campo
    }

    // Agregar el evento input al campo de teléfono para formatearlo en tiempo real
    telefonoInput.addEventListener("input", formatearNumeroTelefono);

    // También puedes llamar a la función formatearNumeroTelefono cuando se cargue la página
    formatearNumeroTelefono();
});



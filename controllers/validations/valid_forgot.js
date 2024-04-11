document.addEventListener("DOMContentLoaded", function() {
    var emailInput = document.getElementById("email");
    var form = document.querySelector("form");

    // Función para cambiar el color del borde a rojo cuando hay un error
    function setEmailErrorStyle() {
        emailInput.style.borderColor = 'red';
    }

    // Función para cambiar el color del borde a verde cuando la entrada es válida
    function setEmailValidStyle() {
        emailInput.style.borderColor = 'green';
    }

    // Función para restablecer el estilo del borde
    function resetEmailStyle() {
        emailInput.style.borderColor = ''; // Puedes cambiar esto a cualquier color predeterminado que uses
    }

    // Función para validar el correo electrónico
    function validateEmail() {
        var value = emailInput.value;
        var maxLength = 40; // Longitud máxima para direcciones de correo electrónico
    
        // Restablece el estilo del borde cada vez que el usuario escribe
        resetEmailStyle();
    
        if (value.length > maxLength) {
            // Si necesitas manejar este caso, aunque el atributo maxlength debería prevenirlo
            setEmailErrorStyle();
            return false;
        } else if (!emailRegex.test(value) && value.length > 0) {
            // Verifica que haya un error y que el campo no esté vacío
            setEmailErrorStyle();
            return false;
        } else if (value.length > 0) {
            // Solo establece el borde verde si hay contenido en el input
            setEmailValidStyle();
        }
        return true;
    }

    // Agrega un controlador de eventos para el evento keyup
    emailInput.addEventListener("keyup", validateEmail);

    // Agrega un controlador de eventos para la validación al enviar el formulario
    form.onsubmit = function(e) {
        var isValidEmail = validateEmail();
        if (!isValidEmail) {
            e.preventDefault(); // Prevenir el envío del formulario
        }
    };
});

function validateForm() {
    var password = document.getElementById("contrasenia").value;
    var confirmPassword = document.getElementById("contraseniarepetir").value;

    // Validar que las contraseñas sean iguales
    if (password != confirmPassword) {
        alert("Las contraseñas no coinciden.");
        return false;
    }

    // Validar la fortaleza de la contraseña
    if (!isValidPassword(password)) {
        alert("La contraseña debe contener al menos 8 caracteres, incluyendo al menos una letra mayúscula, una letra minúscula y un número y un simbolo.");
        return false;
    }

    return true;
}

function isValidPassword(password) {
    // Utiliza una expresión regular para validar la contraseña
    var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;

    return passwordPattern.test(password);
}

function checkPassword() {
    var password = document.getElementById("contrasenia").value;
    var passwordExample = document.getElementById("passwordExample");
    var passwordMatchMessage = document.getElementById("passwordMatchMessage");
    var passwordInput = document.getElementById("contrasenia");
    var confirmPassword = document.getElementById("contraseniarepetir").value;

    if (isValidPassword(password)) {
        passwordInput.classList.remove("valid");
        passwordExample.textContent = "";
    } else {
        passwordInput.classList.add("valid");
        passwordExample.textContent = "Ejemplo de contraseña: Abc12345!";
        passwordExample.style.color = "red";
    }

    if (password === confirmPassword) {
        passwordMatchMessage.textContent = "Las contraseñas coinciden.";
        passwordMatchMessage.style.color = "green";
    } else {
        passwordMatchMessage.textContent = "Las contraseñas no coinciden.";
        passwordMatchMessage.style.color = "red";
    }
}
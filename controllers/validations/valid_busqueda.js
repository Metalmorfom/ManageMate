document.addEventListener("DOMContentLoaded", function () {
    // Obtener el elemento select por su ID
    var selectElement = document.getElementById("estado_tk");

    var inputsToDisable = document.querySelectorAll("#resumen, #descripcion, #empresa, #cliente, #asignado ,#prioridad,#Nombre_user_completo_afectado,#Rut_usuario_afectado,#Modelo,#Cargo_afectado, #Mandante_afectado");

    // Agregar un event listener para el evento change
    selectElement.addEventListener("change", function () {
        // Obtener el valor seleccionado
        var selectedValue = parseInt(selectElement.value);

        // Manejar diferentes casos según el valor seleccionado
        switch (selectedValue) {
            case 1:
                console.log("Se seleccionó la opción 1");
                // Habilitar los campos seleccionados y eliminar estilos CSS
                inputsToDisable.forEach(function(input) {
                    input.disabled = false;
                    input.classList.remove("disabled");
                });
                break;
            case 2:
                console.log("Se seleccionó la opción 2");
                // Deshabilitar los campos seleccionados y aplicar estilos CSS
                inputsToDisable.forEach(function(input) {
                    input.disabled = true;
                    input.classList.add("disabled");
                });
                break;
            case 3:
                console.log("Se seleccionó la opción 3");
                // Realizar acciones para el valor 3 pendiente
                break;
            case 4:
                console.log("Se seleccionó la opción 4");
                // Realizar acciones para el valor 4 resuelto
                break;
            case 5:
                console.log("Se seleccionó la opción 5");
                // Realizar acciones para el valor 5 cancelado
                break;
            default:
                console.log("Opción no reconocida");
                // Manejar caso cuando el valor no coincide con ningún caso
                break;
        }
    });
});

</div>
<script>
    function imprimirValorLocalStorage(nombreItem) {
        var valorItem = localStorage.getItem(nombreItem);
        console.log("Valor de '" + nombreItem + "' en localStorage: " + valorItem);
    }

    function guardarEstadoMenu(estado) {
        localStorage.setItem("estadoMenu", estado);
        imprimirValorLocalStorage("estadoMenu");
    }

    function leerEstadoMenu() {
        // Verificar si 'estadoMenu' existe en localStorage
        var estadoMenu = localStorage.getItem("estadoMenu");
        if (!estadoMenu) {
            // Si no existe, inicializar con un valor predeterminado (por ejemplo, "abierto")
            estadoMenu = "abierto";
            guardarEstadoMenu(estadoMenu);
        }
        imprimirValorLocalStorage("estadoMenu");
        return estadoMenu;
    }

    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("main-content");
        var showSidebarBtn = document.getElementById("showSidebarBtn");
        var estadoMenu = leerEstadoMenu(); // Leer el estado del menú actual

        if (estadoMenu === "abierto") {
            // Si el menú estaba abierto, dejarlo cerrado
            sidebar.style.left = "-230px";
            showSidebarBtn.innerText = "<<"; // Cambia el texto del botón a "<<"
            mainContent.style.marginLeft = "0"; // Restablecer el margen del contenido principal
            guardarEstadoMenu("cerrado"); // Actualizar el estado del menú en el localStorage
        } else {
            // Si el menú estaba cerrado, dejarlo abierto
            sidebar.style.left = "0";
            showSidebarBtn.innerText = ">>"; // Cambia el texto del botón a ">>"
            mainContent.style.marginLeft = "250px"; // Desplazar el contenido principal
            guardarEstadoMenu("abierto"); // Actualizar el estado del menú en el localStorage
        }
    }

    // Al cargar la página, verifica y aplica el estado del menú guardado
    window.onload = function() {
        toggleSidebar(); // Aplicar el estado del menú al cargar la página
    };
</script>



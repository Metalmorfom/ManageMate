
// Función para capturar el RUT cuando se hace clic en el botón -->

function editarUsuario(userId) {
    // Aquí puedes redirigir a la página de edición del usuario o realizar alguna otra acción
    alert('Editar usuario con ID: ' + userId);
}

// Implementa aquí tus funciones de búsqueda, ordenamiento, paginación, etc.
function searchUsers() {
    // Código para buscar usuarios por nombre
}

function sortUsers(sortBy) {
    // Código para ordenar la lista de usuarios
}


//Script para ordenar la tabla -->

let sortOrder = 1; // Variable para controlar el orden ascendente (1) o descendente (-1)
let sortColumnIndex = -1; // Variable para almacenar el índice de la columna ordenada

function sortTable(columnIndex) {
    const table = document.querySelector('table');
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const sortIcon = document.querySelectorAll('.sort-icon');

    // Oculta todos los íconos de orden
    sortIcon.forEach(icon => icon.innerHTML = '');

    if (sortColumnIndex === columnIndex) {
        // Si la columna ya está ordenada, cambia la dirección del orden
        sortOrder *= -1;
    } else {
        // Si se hace clic en una nueva columna, reinicia la dirección del orden
        sortOrder = 1;
        sortColumnIndex = columnIndex;
    }

    // Agrega el ícono de orden a la columna actual
    sortIcon[columnIndex].innerHTML = sortOrder === 1 ? '&darr;' : '&uarr;';

    rows.sort((a, b) => {
        const cellA = a.querySelectorAll('td')[columnIndex].textContent.trim();
        const cellB = b.querySelectorAll('td')[columnIndex].textContent.trim();

        return sortOrder * cellA.localeCompare(cellB, undefined, {
            numeric: true
        });
    });

    // Elimina las filas existentes de la tabla
    rows.forEach(row => table.querySelector('tbody').removeChild(row));

    // Agrega las filas ordenadas nuevamente
    rows.forEach(row => table.querySelector('tbody').appendChild(row));
}

function searchOnEnter(event) {
    if (event.key === "Enter") {
        searchUsers();
    }
}

function searchUsers() {
    const searchValue = document.getElementById("searchInput").value.toLowerCase();

    // Si el valor de búsqueda está vacío, llama a resetSearch()
    if (!searchValue) {
        resetSearch();
        return; // Detiene la ejecución adicional de la función
    }

    // Continúa con el proceso de búsqueda si hay un valor de búsqueda
    const rows = document.querySelectorAll("table tbody tr");
    rows.forEach(row => {
        const nombre = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
        row.style.display = nombre.includes(searchValue) ? "" : "none";
    });
}

function resetSearch() {
    // Limpia el campo de búsqueda
    document.getElementById("searchInput").value = "";

    // Restablecer el orden y eliminar los iconos de las flechas
    sortOrder = 1; // Restablece la dirección del orden a ascendente
    sortColumnIndex = -1; // Restablece la columna ordenada a ninguna
    document.querySelectorAll('.sort-icon').forEach(icon => icon.innerHTML = ''); // Elimina los iconos

    // Obtiene todas las filas de la tabla
    const rows = document.querySelectorAll("table tbody tr");

    // Restablece la vista de las filas según la paginación actual
    displayRows(current_page, rows);
    setupPagination();
}



document.getElementById('exportToExcel').addEventListener('click', function () {
    var table = document.getElementById('userTable');
    var workbook = XLSX.utils.book_new();
    var ws = XLSX.utils.table_to_sheet(table);

    XLSX.utils.book_append_sheet(workbook, ws, 'Sheet1');

    // Obtener la fecha y hora actual
    var now = new Date();
    var fechaFormato = [
        now.getFullYear(),
        (now.getMonth() + 1).toString().padStart(2, '0'),
        now.getDate().toString().padStart(2, '0')
    ].join('-');

    var horaFormato = [
        now.getHours().toString().padStart(2, '0'),
        now.getMinutes().toString().padStart(2, '0')
    ].join('.');

    // Concatenar fecha y hora sin segundos con el nombre del archivo
    var nombreArchivo = 'Usuarios_' + fechaFormato + '_' + horaFormato + '.xlsx';

    // Exportar con el nuevo nombre de archivo
    XLSX.writeFile(workbook, nombreArchivo);
});



// Variables para la paginación
let current_page = 1;
let rows_per_page = 6;

document.addEventListener('DOMContentLoaded', function () {
    // Configurar la paginación al cargar la página
    setupPagination();
});

function setupPagination() {
    const rows = document.querySelectorAll('#userTable tbody tr');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    // Limpiar el contenedor de paginación
    paginationWrapper.innerHTML = '';

    let pageCount = Math.ceil(rows.length / rows_per_page);
    for (let i = 1; i <= pageCount; i++) {
        let btn = paginationButton(i, rows);
        paginationWrapper.appendChild(btn);
    }

    displayRows(1, rows); // Muestra la primera página
}

function paginationButton(page, rows) {
    let button = document.createElement('button');
    button.innerText = page;
    button.classList.add('pagination-btn');

    // Agrega una clase al botón si es la página actual
    if (current_page === page) {
        button.classList.add('active-page');
    }

    button.addEventListener('click', function() {
        current_page = page;
        displayRows(page, rows);

        // Actualiza los estilos de los botones de paginación
        updatePaginationButtons();
    });

    return button;
}

function updatePaginationButtons() {
    document.querySelectorAll('#pagination-wrapper button').forEach(btn => {
        btn.classList.remove('active-page');
    });
    let activeButton = document.querySelector('#pagination-wrapper button:nth-child(' + current_page + ')');
    if (activeButton) {
        activeButton.classList.add('active-page');
    }
}


function displayRows(page, rows) {
    let start = (page - 1) * rows_per_page;
    let end = start + rows_per_page;

    rows.forEach((row, index) => {
        if (index >= start && index < end) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}




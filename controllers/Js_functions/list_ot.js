// list_ot.js

document.addEventListener('DOMContentLoaded', function () {
    setupTableSortListeners();
    setupPagination();
    setupSearch();
    setupExportToExcel();

    const tableRows = document.querySelectorAll('#ticketTable tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });
});



function setupTableSortListeners() {
    const headers = document.querySelectorAll('#ticketTable th.sortable');
    headers.forEach((header, index) => {
        header.addEventListener('click', () => sortTable(index));
    });
}

function sortTable(columnIndex) {
    const table = document.querySelector('#ticketTable');
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    let isAscending = toggleSortOrder(columnIndex);

    rows.sort((a, b) => {
        let cellA = a.querySelectorAll('td')[columnIndex].textContent.trim();
        let cellB = b.querySelectorAll('td')[columnIndex].textContent.trim();

        // Asumiendo que la columna de 'Fecha de Creación' es la tercera (índice 2)
        if (columnIndex === 3) {
            // Convertir las fechas a objetos Date para compararlas
            cellA = cellA ? new Date(cellA) : new Date(0); // Usar una fecha antigua para valores nulos
            cellB = cellB ? new Date(cellB) : new Date(0);
            return isAscending ? cellA - cellB : cellB - cellA;
        }

        if (!isNaN(parseFloat(cellA)) && !isNaN(parseFloat(cellB))) {
            return (isAscending ? 1 : -1) * (parseFloat(cellA) - parseFloat(cellB));
        }

        return (isAscending ? 1 : -1) * cellA.localeCompare(cellB, undefined, { numeric: true });
    });

    rows.forEach(row => table.querySelector('tbody').appendChild(row));
    updateSortIcons(columnIndex, isAscending);
}


function toggleSortOrder(columnIndex) {
    if (window.sortColumnIndex !== columnIndex) {
        window.sortColumnIndex = columnIndex;
        window.sortOrder = 1;
    } else {
        window.sortOrder *= -1;
    }
    return window.sortOrder === 1;
}

function updateSortIcons(columnIndex, isAscending) {
    const sortIcons = document.querySelectorAll('#ticketTable .sort-icon');
    sortIcons.forEach((icon, index) => {
        icon.innerHTML = index === columnIndex
            ? (isAscending ? '↓' : '↑')
            : '';
    });
}

function setupSearch() {
    const searchButton = document.querySelector('.search-container button');
    searchButton.addEventListener('click', searchTickets);
}

function searchTickets() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ticketTable tbody tr');

    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
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

function setupExportToExcel() {
    const exportButton = document.getElementById('exportToExcel');
    exportButton.addEventListener('click', exportTableToExcel);
}

document.getElementById('exportToExcel').addEventListener('click', function () {
    var table = document.getElementById('ticketTable');
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
    var nombreArchivo = 'Lista_Tickes_' + fechaFormato + '_' + horaFormato + '.xlsx';

    // Exportar con el nuevo nombre de archivo
    XLSX.writeFile(workbook, nombreArchivo);
});

// Variables de paginación
window.current_page = 1;
window.rows_per_page = 10;

function setupPagination() {
    const rows = document.querySelectorAll('#ticketTable tbody tr');
    const paginationWrapper = document.getElementById('pagination-wrapper');
    paginationWrapper.innerHTML = '';

    let pageCount = Math.ceil(rows.length / window.rows_per_page);
    for (let i = 1; i <= pageCount; i++) {
        let btn = paginationButton(i, rows);
        paginationWrapper.appendChild(btn);
    }

    displayRows(window.current_page, rows);
}

function paginationButton(page, rows) {
    let button = document.createElement('button');
    button.innerText = page;
    button.addEventListener('click', function () {
        window.current_page = page;
        displayRows(page, rows);
    });

    return button;
}

function displayRows(page, rows) {
    let start = (page - 1) * window.rows_per_page;
    let end = start + window.rows_per_page;

    rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
    });
}

window.sortOrder = 1;
window.sortColumnIndex = -1;

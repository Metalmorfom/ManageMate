<?php
// pie.php controlador

include("../../config/conexion_bd.php");

// Obtener las fechas enviadas por AJAX y sanitizarlas
$fechaInicial = isset($_GET['fechaInicial']) ? $_GET['fechaInicial'] : '';
$fechaTermino = isset($_GET['fechaTermino']) ? $_GET['fechaTermino'] : '';

// Obtener el estado del checkbox y convertir a booleano
$buscarPorFechas = filter_var($_GET['buscarPorFechas'], FILTER_VALIDATE_BOOLEAN);

// Convertir fechas al formato de la base de datos, si es necesario
$fechaInicial = date('Y-m-d', strtotime($fechaInicial));
$fechaTermino = date('Y-m-d', strtotime($fechaTermino));

// Inicialización de conteos para cada categoría
$conteos = [
    'Creacion' => 0,
    'Desvinculacion' => 0,
    'Homologacion' => 0,
    'Reseteo' => 0
];

// Construcción dinámica del filtro de fechas
$filtroFecha = '';
if ($buscarPorFechas) {
    $filtroFecha = " AND DATE_FORMAT(fecha_creacion, '%Y-%m-%d') BETWEEN '$fechaInicial' AND '$fechaTermino'";
}

foreach ($conteos as $campo => $valor) {
    // Asume que tu vista ya contiene una columna para cada tipo de conteo y una columna para fechas
    $queryConteo = "SELECT SUM($campo) AS conteo FROM Vista_Pie WHERE 1 = 1" . $filtroFecha;

    $resultadoConteo = $conexion->query($queryConteo);

    if ($resultadoConteo && $resultadoConteo->num_rows > 0) {
        $filaConteo = $resultadoConteo->fetch_assoc();
        $conteos[$campo] = $filaConteo['conteo'];
    } else {
        // Manejo de errores o consulta sin resultados
    }
}

$response = [
    'conteos' => array_values($conteos),
    'etiquetas' => array_keys($conteos)
];

header('Content-Type: application/json');
echo json_encode($response);
?>

<?php
// Incluir el archivo de conexión a la base de datos
include("../config/conexion_bd.php");

// Consulta SQL para obtener la lista de usuarios
$sql = "SELECT 
 * FROM vista_listar_usuarios;
";
$result = mysqli_query($conexion, $sql);



// Cerrar la conexión a la base de datos
mysqli_close($conexion);



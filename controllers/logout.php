<?php
session_start();
session_destroy(); // Destruye la sesión

header("Location: ../"); // Redirige al usuario a la página de inicio de sesión
exit();
?>

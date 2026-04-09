<?php
// php/conn.php
$host = "localhost";
$user = "tu_usuario"; // Por defecto en local es 'root'
$pass = "tu_contraseña"; 
$db   = "clinica_coniuntio";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configuración de caracteres para que se vean bien los acentos
mysqli_set_charset($conn, "utf8");
?>
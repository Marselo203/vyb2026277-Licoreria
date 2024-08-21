<?php
$servername = "localhost";
$username = "root"; // Cambia esto si tu usuario de MySQL es diferente
$password = ""; // Cambia esto si tu contraseña es diferente
$dbname = "licoreria";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

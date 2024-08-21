<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Clientes (nombre_usuario, contraseña)
            VALUES ('$nombre_usuario', '$contraseña')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<form method="POST" action="">
    Nombre de Usuario: <input type="text" name="nombre_usuario" required><br>
    Contraseña: <input type="password" name="contraseña" required><br>
    <input type="submit" value="Registrar">
</form>

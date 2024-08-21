<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Administradores (nombre, primer_apellido, segundo_apellido, correo, contraseña)
            VALUES ('$nombre', '$primer_apellido', '$segundo_apellido', '$correo', '$contraseña')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<form method="POST" action="">
    Nombre: <input type="text" name="nombre" required><br>
    Primer Apellido: <input type="text" name="primer_apellido" required><br>
    Segundo Apellido: <input type="text" name="segundo_apellido"><br>
    Correo: <input type="email" name="correo" required><br>
    Contraseña: <input type="password" name="contraseña" required><br>
    <input type="submit" value="Registrar">
</form>

<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $precio = trim($_POST['precio']);

    $sql = "INSERT INTO Productos (nombre, precio) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sd", $nombre, $precio);
        if ($stmt->execute()) {
            echo "Producto creado exitosamente.";
        } else {
            echo "Error en la creación del producto: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $conn->close();
}
?>

<form method="POST" action="">
    Nombre del Producto: <input type="text" name="nombre" required><br>
    Precio: <input type="number" step="0.01" name="precio" required><br>
    <input type="submit" value="Crear Producto">
</form>

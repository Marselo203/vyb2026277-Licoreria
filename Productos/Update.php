<?php
include '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $precio = trim($_POST['precio']);

    $sql = "UPDATE Productos SET nombre = ?, precio = ? WHERE id_producto = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sdi", $nombre, $precio, $id);
        if ($stmt->execute()) {
            echo "Producto actualizado exitosamente.";
        } else {
            echo "Error en la actualización del producto: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $conn->close();
} else {
    // Obtener datos del producto para prellenar el formulario
    $sql = "SELECT * FROM Productos WHERE id_producto = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Producto no encontrado.";
            exit;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}
?>

<form method="POST" action="">
    Nombre del Producto: <input type="text" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required><br>
    Precio: <input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($row['precio']); ?>" required><br>
    <input type="submit" value="Actualizar Producto">
</form>

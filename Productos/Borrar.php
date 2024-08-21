<?php
include '../conexion.php';

$sql = "SELECT * FROM Productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id_producto']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['precio']}</td>
                <td>
                    <a href='Update.php?id={$row['id_producto']}'>Actualizar</a> |
                    <a href='Borrar.php?id={$row['id_producto']}'>Eliminar</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay productos.";
}

$conn->close();
?>

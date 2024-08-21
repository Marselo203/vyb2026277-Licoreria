<?php
include 'conexion.php';

// Obtener lista de usuarios y productos
$usuarios = $conn->query("SELECT id_usuario, nombre_usuario FROM clientes");
$productos = $conn->query("SELECT id_producto, nombre FROM productos");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $productos_seleccionados = $_POST['productos']; // Array de productos seleccionados
    $cantidad = $_POST['cantidad']; // Array de cantidades

    // Calcular el total
    $total = 0;
    $detalles_compra = [];

    foreach ($productos_seleccionados as $index => $id_producto) {
        $cantidad_producto = $cantidad[$index];
        $result = $conn->query("SELECT nombre, precio FROM productos WHERE id_producto = $id_producto");
        $producto = $result->fetch_assoc();
        $subtotal = $producto['precio'] * $cantidad_producto;
        $total += $subtotal;

        $detalles_compra[] = [
            'nombre' => $producto['nombre'],
            'cantidad' => $cantidad_producto,
            'precio' => $producto['precio'],
            'subtotal' => $subtotal
        ];
    }

    // Obtener el administrador que gestiona la compra (puede ser un valor predeterminado o dinámico)
    $id_admin = 1; // Aquí puedes poner el ID del administrador actual

    // Insertar compra en la base de datos
    $fecha_compra = date('Y-m-d H:i:s');
    $sql_compra = "INSERT INTO ordenes (id_usuario, id_admin, fecha_orden, total) VALUES (?, ?, ?, ?)";
    if ($stmt_compra = $conn->prepare($sql_compra)) {
        $stmt_compra->bind_param("iisd", $id_usuario, $id_admin, $fecha_compra, $total);
        if ($stmt_compra->execute()) {
            $id_compra = $stmt_compra->insert_id;

            // Insertar detalles de compra
            foreach ($detalles_compra as $detalle) {
                $sql_detalle = "INSERT INTO detallesordenes (id_orden, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
                if ($stmt_detalle = $conn->prepare($sql_detalle)) {
                    $stmt_detalle->bind_param("iiid", $id_compra, $id_producto, $detalle['cantidad'], $detalle['precio']);
                    $stmt_detalle->execute();
                }
            }

            // Mostrar la nota de venta en pantalla
            echo "<h1>Nota de Venta</h1>";
            echo "<p>Fecha: $fecha_compra</p>";
            echo "<p>Usuario: $id_usuario</p>";
            echo "<p>Administrador: $id_admin</p>";

            echo "<table border='1'>";
            echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>";

            foreach ($detalles_compra as $detalle) {
                echo "<tr>";
                echo "<td>{$detalle['nombre']}</td>";
                echo "<td>{$detalle['cantidad']}</td>";
                echo "<td>" . number_format($detalle['precio'], 2) . "</td>";
                echo "<td>" . number_format($detalle['subtotal'], 2) . "</td>";
                echo "</tr>";
            }

            echo "<tr><td colspan='3' style='text-align:right'>Total</td><td>" . number_format($total, 2) . "</td></tr>";
            echo "</table>";
        } else {
            echo "Error en la inserción de la compra: " . $stmt_compra->error;
        }
        $stmt_compra->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    $conn->close();
}
?>

<form method="POST" action="">
    Usuario:
    <select name="id_usuario" required>
        <?php while ($usuario = $usuarios->fetch_assoc()) { ?>
            <option value="<?php echo $usuario['id_usuario']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
        <?php } ?>
    </select><br>

    Productos:
    <div id="productos">
        <div>
            Producto:
            <select name="productos[]" required>
                <?php while ($producto = $productos->fetch_assoc()) { ?>
                    <option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre']; ?></option>
                <?php } ?>
            </select>
            Cantidad:
            <input type="number" name="cantidad[]" min="1" required>
        </div>
    </div>
    <button type="button" onclick="agregarProducto()">Agregar Otro Producto</button><br>
    <input type="submit" value="Registrar Compra">
</form>

<script>
function agregarProducto() {
    var contenedor = document.getElementById('productos');
    var div = document.createElement('div');
    div.innerHTML = 'Producto: <select name="productos[]" required>' +
        '<?php while ($producto = $productos->fetch_assoc()) { ?>' +
        '<option value="<?php echo $producto['id_producto']; ?>"><?php echo $producto['nombre']; ?></option>' +
        '<?php } ?>' +
        '</select> Cantidad: <input type="number" name="cantidad[]" min="1" required>';
    contenedor.appendChild(div);
}
</script>

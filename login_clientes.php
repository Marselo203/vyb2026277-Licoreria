<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asegurarse de que las variables están definidas antes de usarlas
    $correo_usuario = isset($_POST['correo_usuario']) ? trim($_POST['correo_usuario']) : '';
    $contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';
    $tipo_usuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : '';

    if (!empty($correo_usuario) && !empty($contraseña) && !empty($tipo_usuario)) {
        if ($tipo_usuario == 'admin') {
            $sql = "SELECT * FROM Administradores WHERE correo = ? AND contraseña = ?";
        } else {
            $sql = "SELECT * FROM Clientes WHERE nombre_usuario = ? AND contraseña = ?";
        }

        if ($stmt = $conn->prepare($sql)) {
            // Enlaza los parámetros con la consulta SQL
            $stmt->bind_param("ss", $correo_usuario, $contraseña);
            $stmt->execute();

            // Obtén el resultado de la consulta
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($tipo_usuario == 'admin') {
                    echo "Login exitoso. Bienvenido, " . $row['nombre'] . "!";
                } else {
                    echo "Login exitoso. Bienvenido, " . $row['nombre_usuario'] . "!";
                }
            } else {
                echo "Usuario o contraseña incorrectos.";
            }

            // Cierra el statement
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }

    // Cierra la conexión
    $conn->close();
}
?>

<form method="POST" action="">
    Tipo de Usuario:
    <select name="tipo_usuario" required>
        <option value="admin">Administrador</option>
        <option value="cliente">Cliente</option>
    </select><br>
    Correo/Nombre de Usuario: <input type="text" name="correo_usuario" required><br>
    Contraseña: <input type="password" name="contraseña" required><br>
    <input type="submit" value="Login">
</form>

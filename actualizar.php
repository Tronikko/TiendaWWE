<?php
session_start();
include 'conexion.php';

// Conectar a la base de datos
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// Verificar si se recibió un ID válido para mostrar el formulario
if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']); // Convertir el ID a número entero

    // Consulta para obtener los datos del usuario
    $sql = "SELECT nombre, usuario FROM usuarios WHERE id = ?";
    $consulta = $conexion->prepare($sql);

    if ($consulta) {
        $consulta->bind_param("i", $id_usuario);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
        } else {
            echo "⚠️ Usuario no encontrado.";
            exit();
        }
    } else {
        echo "❌ Error al preparar la consulta: " . $conexion->error;
        exit();
    }
}

// Si se envían los datos del formulario, actualizar el registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['usuario']) && isset($_POST['contrasena'])) {
        $id_usuario = intval($_POST['id']);
        $nombre = $_POST['nombre'];
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena']; // Almacenar contraseña como texto plano

        // Consulta para actualizar el usuario
        $sql = "UPDATE usuarios SET nombre = ?, usuario = ?, contrasena = ? WHERE id = ?";
        $consulta = $conexion->prepare($sql);

        if ($consulta) {
            $consulta->bind_param("sssi", $nombre, $usuario, $contrasena, $id_usuario);
            if ($consulta->execute()) {
                echo "✅ Usuario actualizado correctamente.<br>";
            } else {
                echo "❌ Error al ejecutar la consulta: " . $conexion->error;
            }
            $consulta->close();
        } else {
            echo "❌ Error al preparar la consulta: " . $conexion->error;
        }
    } else {
        echo "❌ Faltan datos en el formulario.";
    }
    exit(); // Terminar ejecución después de actualizar
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Actualizar Usuario</h2>
<form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id_usuario; ?>">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
    <label for="usuario">Usuario:</label>
    <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" required>
    <label for="contrasena">Nueva Contraseña:</label>
    <input type="text" id="contrasena" name="contrasena" required>
    <button type="submit">Actualizar</button>
</form>

</body>
</html>


<?php
session_start();
include 'conexion.php';

// Conectar a la base de datos
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

$mensaje = ""; // Variable para mostrar errores

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // 🔍 Verificar si el usuario existe en la base de datos
    $sql = "SELECT rol FROM usuarios WHERE usuario = ? AND contrasena = ?";
    $consulta = $conexion->prepare($sql);
    $consulta->bind_param("ss", $usuario, $contrasena);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila) {
        $_SESSION['usuario'] = $usuario;

        // Registrar acceso exitoso en la bitácora
        $sql_bitacora = "INSERT INTO bitacora (usuario, fecha, hora, operacion) VALUES (?, CURDATE(), CURTIME(), 'exitoso')";
        $bitacora = $conexion->prepare($sql_bitacora);
        $bitacora->bind_param("s", $usuario);
        $bitacora->execute();
        $bitacora->close();

        // Redirigir según el rol
        if ($fila['rol'] == 'admin') {
            header("Location: menu.php"); // 🚀 Página de administrador
            exit();
        } else {
            header("Location: inicio.php"); // 🚀 Página de usuario normal
            exit();
        }
    } else {
        // Registrar intento fallido en la bitácora
        $sql_bitacora = "INSERT INTO bitacora (usuario, fecha, hora, operacion) VALUES (?, CURDATE(), CURTIME(), 'fallido')";
        $bitacora = $conexion->prepare($sql_bitacora);
        $bitacora->bind_param("s", $usuario);
        $bitacora->execute();
        $bitacora->close();

        $mensaje = "❌ Usuario o contraseña incorrectos.";
    }

    $consulta->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
            background-color: #B22222; /* Rojo fuego puro */
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #fff;
            padding: 1rem;
            background-color: #8B0000; /* Rojo oscuro */
            margin: 0;
        }

        form {
            background-color: #FFFAFA; /* Blanco nieve */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #B22222; /* Rojo fuego */
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #8B0000; /* Rojo oscuro */
            border-radius: 5px;
            background-color: #fff;
            color: #000;
        }

        .btn-primary {
            display: block;
            width: 100%;
            background-color: #8B0000; /* Rojo oscuro */
            color: #fff;
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #B22222; /* Rojo fuego */
        }

        .form-text {
            font-size: 0.9rem;
            color: #555;
        }

        p {
            text-align: center;
            margin: 0.5rem 0;
            color: #DC143C; /* Rojo vibrante */
        }
    </style>
</head>
<body>

<h2>Iniciar Sesión</h2>

<?php if (!empty($mensaje)) { echo "<p>$mensaje</p>"; } ?>

<form action="entrar.php" method="post">
  <div class="mb-3">
    <label for="usuario" class="form-label">Usuario: </label>
    <input name="usuario" type="text" class="form-control" id="usuario" required>
    <div class="form-text">Ingresa tu usuario.</div>
  </div>

  <div class="mb-3">
    <label for="contrasena" class="form-label">Contraseña: </label>
    <input name="contrasena" type="password" class="form-control" id="contrasena" required>
    <div class="form-text">Ingresa tu contraseña.</div>
  </div>

  <button type="submit" class="btn-primary">Ingresar</button>
</form>

</body>
</html>

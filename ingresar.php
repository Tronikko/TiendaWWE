<?php
session_start();
include 'conexion.php';

// Conectar a la base de datos
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena']; // 🔓 Contraseña sin encriptar
$rol = $_POST['rol']; // Usuario o Administrador

// Verificar si el usuario ya existe
$checkQuery = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$checkQuery->bind_param("s", $usuario);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    header("Location: registrar.php?mensaje=2"); // Usuario ya registrado
    exit();
}

// Insertar nuevo usuario con rol (sin encriptar la contraseña)
$peticion = $conexion->prepare("INSERT INTO usuarios (nombre, apellido, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
$peticion->bind_param("sssss", $nombre, $apellido, $usuario, $contrasena, $rol);

if ($peticion->execute()) {
    header("Location: registrar.php?mensaje=1"); // Registro exitoso
} else {
    echo "Error al registrar usuario: " . $peticion->error;
}

$checkQuery->close();
$peticion->close();
$conexion->close();
?>


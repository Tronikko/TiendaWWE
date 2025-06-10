<?php
session_start();
include 'conexion.php';

// Conectar a la base de datos
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// Obtener usuario desde el formulario de entrada
$usuario = $_POST['usuario'] ?? null;

if (!$usuario) {
    header("Location: entrar.php?mensaje=1");
    exit();
}

// Verificar si el usuario existe en `usuarios`
$checkQuery = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$checkQuery->bind_param("s", $usuario);
$checkQuery->execute();
$checkQuery->store_result();

// Determinar si el usuario es "exitoso" o "fallido"
$operacion = ($checkQuery->num_rows > 0) ? "exitoso" : "fallido";

// **Registrar el intento de inicio de sesión en `bitacora`**
$fecha = date("Y-m-d");
$hora = date("H:i:s");

$insertQuery = $conexion->prepare("INSERT INTO bitacora (usuario, fecha, hora, operacion) VALUES (?, ?, ?, ?)");
$insertQuery->bind_param("ssss", $usuario, $fecha, $hora, $operacion);
$insertQuery->execute();

// Cerrar conexiones
$checkQuery->close();
$insertQuery->close();
$conexion->close();

// Si el usuario es exitoso, redirigir a la página principal; si fallido, redirigir con mensaje de error
if ($operacion === "exitoso") {
    header("Location: inicio.php");
} else {
    header("Location: entrar.php?mensaje=1");
}
exit();
?>

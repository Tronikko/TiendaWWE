<?php
session_start();
include 'conexion.php';

// Conectar a la base de datos
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// Usar consulta preparada para evitar inyección SQL
$peticion = $conexion->prepare("SELECT contrasena FROM usuarios WHERE usuario = ?");
$peticion->bind_param("s", $usuario);
$peticion->execute();
$resultado = $peticion->get_result();

if ($fila = $resultado->fetch_assoc()) {
    if ($contrasena === $fila['contrasena']) { // Comparación directa ⚠
        header("Location: inicio.php");
        exit();
    } else {
        header("Location: entrar.php?mensaje=1");
        exit();
    }
} else {
    header("Location: entrar.php?mensaje=1");
    exit();
}

?>

<?php
session_start();
require('C:/xampp/htdocs/tiendawwe/fpdf186/fpdf.php');
include 'conexion.php';

// Conectar a la base de datos
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// 🔥 Obtener el usuario más reciente con operación "exitoso"
$consulta_bitacora = "SELECT usuario, fecha, hora FROM bitacora WHERE operacion = 'exitoso' ORDER BY id DESC LIMIT 1";
$stmt = $conexion->prepare($consulta_bitacora);
$stmt->execute();
$bitacora_resultado = $stmt->get_result()->fetch_assoc();

$usuario_actual = $bitacora_resultado['usuario'] ?? 'Usuario no identificado';
$fecha_compra = $bitacora_resultado['fecha'] ?? date("Y-m-d");
$hora_compra = $bitacora_resultado['hora'] ?? date("H:i:s");

// 🔥 Obtener información del usuario correcto desde la tabla `usuarios`
$consulta_usuario = "SELECT nombre, apellido FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($consulta_usuario);
$stmt->bind_param("s", $usuario_actual);
$stmt->execute();
$usuario_resultado = $stmt->get_result()->fetch_assoc();

// 🔥 Manejo de valores nulos para el ticket
$nombre_completo = ($usuario_resultado['nombre'] ?? 'Usuario no identificado') . " " . ($usuario_resultado['apellido'] ?? '');

// 🔥 Obtener número de tarjeta y monto desde la sesión (de `metodopago.php`)
$numero_tarjeta = $_SESSION['tarjeta'] ?? 'Tarjeta no registrada';
$monto_pagado = $_SESSION['monto'] ?? 'Monto no registrado';

// ✅ Crear PDF con diseño mejorado
$pdf = new FPDF();
$pdf->AddPage();

// 🔥 Fondo rojo para el encabezado
$pdf->SetFillColor(231, 76, 60); // Rojo
$pdf->SetTextColor(255, 255, 255); // Blanco
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 15, "TICKET DE COMPRA", 0, 1, 'C', true);
$pdf->Ln(10);

// 🔥 Restablecer colores y agregar datos de compra
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(190, 10, "Comprador: " . $nombre_completo, 0, 1, 'C');
$pdf->Cell(190, 10, "Fecha: " . $fecha_compra . " | Hora: " . $hora_compra, 0, 1, 'C');
$pdf->Ln(5);

// 🔥 Mostrar número de tarjeta usada y monto pagado en azul
$pdf->SetTextColor(52, 152, 219); // Azul
$pdf->Cell(190, 10, "Tarjeta utilizada: " . $numero_tarjeta, 0, 1, 'C');
$pdf->Cell(190, 10, "Monto pagado: $" . number_format($monto_pagado, 2), 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Volver a negro
$pdf->Ln(5);

// ✅ Mostrar mensaje de "Pago Exitoso" en verde
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 128, 0); // Verde
$pdf->Cell(190, 10, " PAGO EXITOSO", 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->Ln(10);

// ✅ Agregar productos al ticket con imágenes alineadas
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(52, 152, 219); // Azul
$pdf->SetTextColor(255, 255, 255); // Blanco
$pdf->Cell(40, 10, "Imagen", 1, 0, 'C', true);
$pdf->Cell(110, 10, "Producto", 1, 0, 'C', true);
$pdf->Cell(40, 10, "Precio", 1, 1, 'C', true);
$pdf->SetTextColor(0, 0, 0); // Negro

$total = 0;

if (!empty($_SESSION['ticket'])) {
    foreach ($_SESSION['ticket'] as $producto) {
        // ✅ Imagen alineada correctamente
        $pdf->Cell(40, 20, $pdf->Image($producto['imagen'], $pdf->GetX() + 5, $pdf->GetY() + 5, 15), 1, 0, 'C');
        // ✅ Nombre del producto alineado
        $pdf->Cell(110, 20, $producto['nombre'], 1, 0, 'C');
        // ✅ Precio alineado a la derecha
        $pdf->Cell(40, 20, "$" . number_format($producto['precio'], 2), 1, 1, 'C');
        $total += $producto['precio'];
    }
} else {
    $pdf->Cell(190, 10, "No hay productos en el carrito.", 1, 1, 'C');
}

// 🔥 Total de compra resaltado en rojo
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetFillColor(231, 76, 60); // Rojo
$pdf->SetTextColor(255, 255, 255); // Blanco
$pdf->Cell(190, 10, "Total: $" . number_format($total, 2), 1, 1, 'C', true);

// ✅ Generar PDF
ob_clean();
$pdf->Output('D', 'ticket.pdf');

// 🔥 Vaciar el carrito después de generar el ticket
$_SESSION['ticket'] = [];
?>

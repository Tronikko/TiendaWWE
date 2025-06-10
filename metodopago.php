<?php
session_start();
include 'conexion.php';
require('C:/xampp/htdocs/tiendawwe/fpdf186/fpdf.php');
require 'C:/xampp/htdocs/tiendawwe/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/tiendawwe/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/tiendawwe/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['tarjeta'] = $_POST['tarjeta'];
    $_SESSION['monto'] = $_POST['monto'];
    $correo_destino = $_POST['correo'];

    $conexionObj = new Conexion();
    $conexion = $conexionObj->getConexion();
    $usuario_actual = $_SESSION['usuario_id'] ?? 'Usuario no identificado';

    $query = "INSERT INTO pagos (usuario, tarjeta, monto, fecha) VALUES (?, ?, ?, NOW())";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssd", $usuario_actual, $_SESSION['tarjeta'], $_SESSION['monto']);
    $stmt->execute();

    // --------- Generar el ticket (basado en tu ticket.php) ---------
    $consulta_bitacora = "SELECT usuario, fecha, hora FROM bitacora WHERE operacion = 'exitoso' ORDER BY id DESC LIMIT 1";
    $stmt = $conexion->prepare($consulta_bitacora);
    $stmt->execute();
    $bitacora_resultado = $stmt->get_result()->fetch_assoc();

    $usuario_actual = $bitacora_resultado['usuario'] ?? 'Usuario no identificado';
    $fecha_compra = $bitacora_resultado['fecha'] ?? date("Y-m-d");
    $hora_compra = $bitacora_resultado['hora'] ?? date("H:i:s");

    $consulta_usuario = "SELECT nombre, apellido FROM usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($consulta_usuario);
    $stmt->bind_param("s", $usuario_actual);
    $stmt->execute();
    $usuario_resultado = $stmt->get_result()->fetch_assoc();
    $nombre_completo = ($usuario_resultado['nombre'] ?? 'Usuario no identificado') . " " . ($usuario_resultado['apellido'] ?? '');

    $numero_tarjeta = $_SESSION['tarjeta'] ?? 'Tarjeta no registrada';
    $monto_pagado = $_SESSION['monto'] ?? 'Monto no registrado';

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFillColor(231, 76, 60);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 15, "TICKET DE COMPRA", 0, 1, 'C', true);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(190, 10, "Comprador: " . $nombre_completo, 0, 1, 'C');
    $pdf->Cell(190, 10, "Fecha: " . $fecha_compra . " | Hora: " . $hora_compra, 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetTextColor(52, 152, 219);
    $pdf->Cell(190, 10, "Tarjeta utilizada: " . $numero_tarjeta, 0, 1, 'C');
    $pdf->Cell(190, 10, "Monto pagado: $" . number_format($monto_pagado, 2), 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(190, 10, "✅ PAGO EXITOSO", 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(52, 152, 219);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(40, 10, "Imagen", 1, 0, 'C', true);
    $pdf->Cell(110, 10, "Producto", 1, 0, 'C', true);
    $pdf->Cell(40, 10, "Precio", 1, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0);

    $total = 0;
    if (!empty($_SESSION['ticket'])) {
        foreach ($_SESSION['ticket'] as $producto) {
            $pdf->Cell(40, 20, $pdf->Image($producto['imagen'], $pdf->GetX() + 5, $pdf->GetY() + 5, 15), 1, 0, 'C');
            $pdf->Cell(110, 20, $producto['nombre'], 1, 0, 'C');
            $pdf->Cell(40, 20, "$" . number_format($producto['precio'], 2), 1, 1, 'C');
            $total += $producto['precio'];
        }
    } else {
        $pdf->Cell(190, 10, "No hay productos en el carrito.", 1, 1, 'C');
    }

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetFillColor(231, 76, 60);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(190, 10, "Total: $" . number_format($total, 2), 1, 1, 'C', true);

    $archivo_pdf = 'ticket_pago.pdf';
    $pdf->Output('F', $archivo_pdf);
    $_SESSION['ticket'] = [];

    // --------- Enviar correo ---------
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lucerogali.12.3f@gmail.com';
        $mail->Password = 'kbzt zkdh zbde jchk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('lucerogali.12.3f@gmail.com', 'Tienda WWE');
        $mail->addAddress($correo_destino);
        $mail->Subject = '🎫 Ticket de Compra - WWE';
        $mail->Body = 'Gracias por tu compra. Adjunto encontrarás tu ticket de pago.';
        $mail->addAttachment($archivo_pdf);

        $mail->send();
        unlink($archivo_pdf);
        echo '<script>alert("✅ Ticket enviado correctamente a ' . $correo_destino . '");</script>';
    } catch (Exception $e) {
        echo '<script>alert("❌ Error al enviar el ticket: ' . $mail->ErrorInfo . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Método de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #e8f5e9; }
        .pago-form { width: 320px; margin: auto; padding: 20px; border: 2px solid #28a745; background: white; border-radius: 5px; }
        .pago-form h2 { margin-bottom: 10px; color: #28a745; }
        .input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #28a745; border-radius: 5px; }
        .boton-pagar { padding: 12px 20px; background: #28a745; color: white; font-size: 16px; border: none; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>

<div class="pago-form">
    <h2>🟢 Método de Pago</h2>
    <form action="metodopago.php" method="post">
        <input type="text" name="tarjeta" placeholder="Número de Tarjeta" class="input" required>
        <input type="text" name="monto" placeholder="Monto a Pagar" class="input" required>
        <input type="email" name="correo" placeholder="Correo para enviar ticket" class="input" required>
        <button type="submit" class="boton-pagar">Pagar y Enviar Ticket</button>
    </form>
</div>

</body>
</html>

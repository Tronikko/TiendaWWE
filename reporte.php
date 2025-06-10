<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/tiendawwe/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/tiendawwe/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/tiendawwe/PHPMailer-master/src/SMTP.php';
require 'C:/xampp/htdocs/tiendawwe/fpdf186/fpdf.php';

class Reporte {
    private $conexion;

    public function __construct() {
        include 'conexion.php';
        $conexionObj = new Conexion();
        $this->conexion = $conexionObj->getConexion();
    }

    public function mostrarFormulario() {
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_final = $_POST['fecha_final'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $chart_data = [];

        if (isset($_POST['btn_grafica'])) {
            $resultado = $this->procesarDatos();
            if (!isset($resultado['error'])) {
                $chart_data = $resultado;
            } else {
                echo '<script>alert("' . $resultado['error'] . '");</script>';
            }
        }

        echo '
        <html>
        <head>
            <title>Reporte de Ventas</title>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f0f4ff;
                    text-align: center;
                    margin: 0;
                    padding: 40px 0;
                }
                h2 {
                    color: #003366;
                    margin-bottom: 20px;
                }
                form {
                    background: white;
                    padding: 25px 30px;
                    display: inline-block;
                    border-radius: 12px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                    max-width: 600px;
                    width: 100%;
                }
                input[type="date"], input[type="email"] {
                    padding: 12px 15px;
                    margin: 8px 10px 20px 10px;
                    border: 1.8px solid #3399ff;
                    border-radius: 8px;
                    font-size: 15px;
                    width: calc(50% - 30px);
                    box-sizing: border-box;
                    transition: border-color 0.3s ease;
                }
                input[type="email"] {
                    width: calc(100% - 20px);
                }
                input[type="date"]:focus, input[type="email"]:focus {
                    border-color: #0066cc;
                    outline: none;
                }
                button {
                    background-color: #0073e6;
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    margin: 10px 10px 0 10px;
                    border-radius: 10px;
                    font-size: 16px;
                    cursor: pointer;
                    box-shadow: 0 3px 8px rgba(0,115,230,0.5);
                    transition: background-color 0.3s ease;
                }
                button:disabled {
                    background-color: #a0c4ff;
                    cursor: not-allowed;
                    box-shadow: none;
                }
                button:hover:not(:disabled) {
                    background-color: #005bb5;
                }
                canvas {
                    margin-top: 30px;
                    max-width: 600px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
                }
            </style>
        </head>
        <body>
            <h2>Generar Reporte de Ventas</h2>
            <form id="formReporte" method="POST">
                <input type="date" name="fecha_inicio" required value="' . htmlspecialchars($fecha_inicio) . '" placeholder="Fecha Inicio">
                <input type="date" name="fecha_final" required value="' . htmlspecialchars($fecha_final) . '" placeholder="Fecha Final">
                <br>
                <input type="email" name="correo" placeholder="Correo para enviar PDF" value="' . htmlspecialchars($correo) . '" required>
                <br>
                <button type="submit" name="btn_grafica" value="1">Mostrar Gráfica</button>
                <button type="button" id="btnPdf" disabled>Generar y Enviar PDF</button>
                <input type="hidden" name="chart_image" id="chart_image">
                <input type="hidden" name="btn_pdf" id="btn_pdf" value="0">
            </form>
            <canvas id="ventasChart"></canvas>

            <script>
                let chartInstance = null;
                const btnPdf = document.getElementById("btnPdf");
                const inputPdf = document.getElementById("btn_pdf");

                function crearGrafica(labels, data) {
                    const ctx = document.getElementById("ventasChart").getContext("2d");

                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    chartInstance = new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Ventas por Producto",
                                data: data,
                                backgroundColor: "#0073e6",
                                borderColor: "#004a99",
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: true } }
                        }
                    });

                    setTimeout(() => {
                        const imgBase64 = document.getElementById("ventasChart").toDataURL("image/png");
                        document.getElementById("chart_image").value = imgBase64;
                        btnPdf.disabled = false;
                    }, 500);
                }

                btnPdf.addEventListener("click", function() {
                    if (!chartInstance) {
                        alert("Primero genera la gráfica.");
                        return;
                    }
                    inputPdf.value = "1";
                    document.getElementById("formReporte").submit();
                });

                const chartData = ' . json_encode($chart_data) . ';
                if (chartData.length > 0) {
                    const labels = chartData.map(item => item.nombre);
                    const data = chartData.map(item => parseInt(item.total_vendido));
                    crearGrafica(labels, data);
                }
            </script>
        </body>
        </html>
        ';
    }

    public function procesarDatos() {
        if (empty($_POST['fecha_inicio']) || empty($_POST['fecha_final'])) {
            return ['error' => 'Debes ingresar una fecha de inicio y una fecha final.'];
        }

        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];

        $query = "SELECT p.nombre, SUM(v.cantidad) as total_vendido 
                  FROM ventas v
                  INNER JOIN producto p ON v.producto_id = p.id
                  WHERE v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_final'
                  GROUP BY p.nombre";

        $result = mysqli_query($this->conexion, $query);

        if (!$result) {
            return ['error' => 'Error en la consulta SQL: ' . mysqli_error($this->conexion)];
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return empty($data) ? ['error' => 'No hay datos disponibles para estas fechas.'] : $data;
    }

    public function procesarEnvio() {
        if (!isset($_POST['btn_pdf']) || $_POST['btn_pdf'] !== "1") return;

        $correo = $_POST['correo'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_final = $_POST['fecha_final'] ?? '';

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo '<script>alert("Correo electrónico inválido.");</script>';
            return;
        }

        if (empty($_POST['chart_image'])) {
            echo '<script>alert("No se recibió la imagen del gráfico.");</script>';
            return;
        }

        $data = $this->procesarDatos();
        if (isset($data['error'])) {
            echo '<script>alert("' . $data['error'] . '");</script>';
            return;
        }

        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $_POST['chart_image']));
        $filePath = 'reporte_ventas.png';
        file_put_contents($filePath, $imageData);

        $pdf = new FPDF();
        $pdf->AddPage();

        // Fondo azul claro
        $pdf->SetFillColor(224, 235, 255);
        $pdf->Rect(0, 0, 210, 297, 'F'); // A4

        // Título azul oscuro
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(0, 51, 102);
        $pdf->Cell(0, 15, 'Reporte de Ventas', 0, 1, 'C');

        // Fechas seleccionadas
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(0, 10, "Fecha Inicio: " . $fecha_inicio, 0, 1, 'C');
        $pdf->Cell(0, 10, "Fecha Final: " . $fecha_final, 0, 1, 'C');
        $pdf->Ln(5);

        // Después de imprimir la imagen de la gráfica
        $pdf->Image($filePath, 25, $pdf->GetY(), 160);
        $pdf->Ln(140);  // <--- Salto de línea mayor para separar tabla y gráfica

        // Tabla con datos más pequeña
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetFillColor(30, 58, 138); // azul oscuro
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(90, 10, 'Producto', 1, 0, 'C', true);
$pdf->Cell(45, 10, 'Total Vendido', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10); // tamaño de fuente más pequeño
$pdf->SetTextColor(0, 0, 0);
$alto_fila = 10;
$fill = false;

foreach ($data as $fila) {
    $pdf->Cell(90, $alto_fila, $fila['nombre'], 1, 0, 'L', $fill);
    $pdf->Cell(45, $alto_fila, $fila['total_vendido'], 1, 1, 'R', $fill);
    $fill = !$fill;
}


        $pdfFile = 'reporte_ventas.pdf';
        $pdf->Output('F', $pdfFile);
        unlink($filePath);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'lucerogali.12.3f@gmail.com';        // Cambia aquí tu correo
            $mail->Password = 'kbzt zkdh zbde jchk';          // Cambia aquí tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('lucerogali.12.3f@gmail.com', 'Sistema de Ventas');
            $mail->addAddress($correo);
            $mail->Subject = 'Reporte de Ventas';
            $mail->Body = 'Adjunto se encuentra el reporte de ventas en PDF.';

            $mail->addAttachment($pdfFile);
            $mail->send();

            echo '<script>alert("Correo enviado correctamente a ' . $correo . '");</script>';
        } catch (Exception $e) {
            echo '<script>alert("Error al enviar el correo: ' . $mail->ErrorInfo . '");</script>';
        } finally {
            if (file_exists($pdfFile)) unlink($pdfFile);
        }
    }
}

$reporte = new Reporte();
$reporte->procesarEnvio();
$reporte->mostrarFormulario();
?>

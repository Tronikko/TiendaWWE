<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ✅ Solo vacía el carrito si el usuario lo borra manualmente
if (!isset($_SESSION['usuario_id'])) {
    if (!isset($_SESSION['ticket'])) {
        $_SESSION['ticket'] = [];
    }
}

// Conectar a la base de datos y gestionar productos
include 'conexion.php';
include 'Carro.php';

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();
$carro = new Carro($conexion);

// ✅ Verificar si el carrito ya existe antes de inicializarlo
if (!isset($_SESSION['ticket'])) {
    $_SESSION['ticket'] = [];
}

// ✅ Agregar productos al carrito
if (isset($_POST['id_producto'])) {
    $carro->agregarProducto($_POST['id_producto']);
}

// ✅ Vaciar el carrito solo si el usuario presiona el botón
if (isset($_POST['eliminar_carrito'])) {
    $_SESSION['ticket'] = []; // Vaciar el carrito manualmente
}

// ✅ Función corregida para obtener total
$total = 0;
foreach ($_SESSION['ticket'] as $producto) {
    $total += $producto['precio'] ?? 0; // Evita error si el índice no existe
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Compra</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f8f9fa; } /* ✅ Corregido */
        .ticket { width: 350px; margin: auto; padding: 20px; border: 2px dashed black; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .ticket h2 { margin-bottom: 10px; }
        .producto { display: flex; align-items: center; border-bottom: 1px solid #ccc; padding: 10px; }
        img { width: 70px; height: auto; margin-right: 10px; }
        .total { font-weight: bold; margin-top: 10px; font-size: 18px; color: #333; }
        .boton-rojo, .boton-verde {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            color: white;
            font-size: 16px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .boton-rojo { background: red; }
        .boton-rojo:hover { background: darkred; }
        .boton-verde { background: #28a745; }
        .boton-verde:hover { background: #218838; }
    </style>
</head>
<body>

<div class="ticket">
    <h2>Tu Ticket de Compra</h2>
    <?php $carro->mostrarTicket(); ?>
    <p class="total">Total: <?php echo number_format($total, 2); ?> MXN</p>

    <!-- 🔥 Botón rojo para regresar a inicio -->
    <a href="inicio.php" class="boton-rojo">Seguir Comprando</a>

    <!-- 🔥 Botón para eliminar carrito -->
    <form action="cart.php" method="post">
        <button type="submit" name="eliminar_carrito" class="boton-rojo">Eliminar Carrito</button>
    </form>

    <!-- ✅ Nuevo botón verde para Método de Pago -->
    <a href="metodopago.php" class="boton-verde">Método de Pago</a>
</div>

</body>
</html>

<?php
class Carro {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['ticket'])) {
            $_SESSION['ticket'] = [];
        }
    }

    public function agregarProducto($id_producto) {
        $query = "SELECT nombre, precio, imagen FROM producto WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();

        if ($producto) {
            $_SESSION['ticket'][] = [
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'imagen' => $producto['imagen']
            ];

            // Actualizar stock
            $sql = "UPDATE producto SET stock = stock - 1 WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("i", $id_producto);
            $stmt->execute();
        }
    }

    public function mostrarTicket() {
        if (empty($_SESSION['ticket'])) {
            echo "<p>No hay productos en el ticket.</p>";
            return;
        }

        echo "<div class='ticket'><h2>🛒 Ticket de Compra</h2>";
        $total = 0;
        foreach ($_SESSION['ticket'] as $producto) {
            echo "<div class='producto'>
                    <img src='{$producto['imagen']}' alt='Imagen del producto'>
                    <div>
                        <p>{$producto['nombre']}</p>
                        <p>$".number_format($producto['precio'], 2)."</p>
                    </div>
                  </div>";
            $total += $producto['precio'];
        }
        echo "<p class='total'>Total: $".number_format($total, 2)."</p></div>";
    }
}
?>

<?php
require_once 'conexion.php'; // Incluye la clase de conexión

class EditarProducto {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function obtenerProducto($id) {
        try {
            $sql = "SELECT * FROM producto WHERE id = ?";
            $stmt = $this->conexion->getConexion()->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Retorna un producto como arreglo asociativo
        } catch (Exception $e) {
            echo "Error al obtener el producto: " . $e->getMessage();
            return null;
        }
    }

    public function mostrarFormularioEdicion($producto) {
        echo '
        <form action="" method="post" style="font-family:Arial, sans-serif;">
            <input type="hidden" name="id" value="' . $producto['id'] . '">
            
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="' . $producto['nombre'] . '" required style="width:100%; padding:10px; margin-bottom:10px;">

            <label for="precio">Precio:</label>
            <input type="number" name="precio" id="precio" step="0.01" value="' . $producto['precio'] . '" required style="width:100%; padding:10px; margin-bottom:10px;">
            
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="' . $producto['stock'] . '" required style="width:100%; padding:10px; margin-bottom:10px;">
            
            <label for="material">Material:</label>
            <input type="text" name="material" id="material" value="' . $producto['material'] . '" style="width:100%; padding:10px; margin-bottom:10px;">
            
            <label for="color">Color:</label>
            <input type="text" name="color" id="color" value="' . $producto['color'] . '" style="width:100%; padding:10px; margin-bottom:10px;">
            
            <label for="talla">Talla:</label>
            <input type="text" name="talla" id="talla" value="' . $producto['talla'] . '" style="width:100%; padding:10px; margin-bottom:10px;">
            
            <label for="tipo">Tipo:</label>
            <input type="text" name="tipo" id="tipo" value="' . $producto['tipo'] . '" style="width:100%; padding:10px; margin-bottom:10px;">
            
            <label for="imagen">Ruta de la Imagen:</label>
            <input type="text" name="imagen" id="imagen" value="' . $producto['imagen'] . '" required style="width:100%; padding:10px; margin-bottom:10px;">
            
            <button type="submit" name="editar" style="background-color:#66c2ff; color:white; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;">Guardar Cambios</button>
        </form>';
    }

    public function editarProducto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
            try {
                $id = $_POST['id'];
                $nombre = $_POST['nombre'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                $material = $_POST['material'];
                $color = $_POST['color'];
                $talla = $_POST['talla'];
                $tipo = $_POST['tipo'];
                $imagen = $_POST['imagen'];

                $sql = "UPDATE producto SET nombre = ?, precio = ?, stock = ?, material = ?, color = ?, talla = ?, tipo = ?, imagen = ? WHERE id = ?";
                $stmt = $this->conexion->getConexion()->prepare($sql);
                $stmt->bind_param("sdisssssi", $nombre, $precio, $stock, $material, $color, $talla, $tipo, $imagen, $id);

                if ($stmt->execute()) {
                    echo "<div style='color:#4caf50;'>Producto actualizado exitosamente.<br></div>";
                    echo "<a href='productos.php' style='color:#66c2ff;'>Volver a la lista de productos</a>";
                } else {
                    echo "<div style='color:red;'>Error al actualizar el producto.<br></div>";
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}

// Lógica principal
if (isset($_GET['id'])) {
    $editarProducto = new EditarProducto();
    $producto = $editarProducto->obtenerProducto($_GET['id']);
    if ($producto) {
        $editarProducto->mostrarFormularioEdicion($producto);
        $editarProducto->editarProducto();
    } else {
        echo "Producto no encontrado.";
    }
}
?>

<?php
require_once 'conexion.php'; // Asegúrate de incluir tu clase conexión

class Productos {
    private $conexion;

    public function __construct() {
        // Crear instancia de conexión
        $this->conexion = new Conexion();
    }

    // Mostrar el formulario para agregar productos
    public function mostrarFormulario() {
        echo '
        <form action="" method="post" style="margin-bottom:20px; font-family:Arial, sans-serif;">
            <label for="nombre" style="display:block; margin-bottom:5px;">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="precio" style="display:block; margin-bottom:5px;">Precio:</label>
            <input type="number" name="precio" id="precio" step="0.01" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="stock" style="display:block; margin-bottom:5px;">Stock:</label>
            <input type="number" name="stock" id="stock" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="material" style="display:block; margin-bottom:5px;">Material:</label>
            <input type="text" name="material" id="material" style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="color" style="display:block; margin-bottom:5px;">Color:</label>
            <input type="text" name="color" id="color" style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="talla" style="display:block; margin-bottom:5px;">Talla:</label>
            <input type="text" name="talla" id="talla" style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="tipo" style="display:block; margin-bottom:5px;">Tipo:</label>
            <input type="text" name="tipo" id="tipo" style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <label for="imagen" style="display:block; margin-bottom:5px;">Ruta de la Imagen:</label>
            <input type="text" name="imagen" id="imagen" placeholder="./img/ropa1.jpeg" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #cce4f6; border-radius:5px;">
            
            <button type="submit" style="background-color:#66c2ff; color:white; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;">Agregar Producto</button>
        </form>';
    }

    // Agregar producto
    public function agregarProducto() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'])) {
            try {
                $nombre = $_POST['nombre'];
                $precio = $_POST['precio'];
                $stock = $_POST['stock'];
                $material = $_POST['material'];
                $color = $_POST['color'];
                $talla = $_POST['talla'];
                $tipo = $_POST['tipo'];
                $imagen = $_POST['imagen'];

                $sql = "INSERT INTO producto (nombre, precio, stock, material, color, talla, tipo, imagen) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->conexion->getConexion()->prepare($sql);
                $stmt->bind_param("sdisssss", $nombre, $precio, $stock, $material, $color, $talla, $tipo, $imagen);

                if ($stmt->execute()) {
                    echo "<div style='color:#4caf50;'>Producto agregado exitosamente.<br></div>";
                } else {
                    echo "<div style='color:red;'>Error al agregar el producto.<br></div>";
                }
            } catch (Exception $e) {
                echo "<div style='color:red;'>Error: " . $e->getMessage() . "<br></div>";
            }
        }
    }

    // Mostrar productos con botones de Editar y Eliminar
    public function mostrarProductos() {
        try {
            $sql = "SELECT * FROM producto";
            $result = $this->conexion->getConexion()->query($sql);
    
            if ($result->num_rows > 0) {
                echo '
                <table border="1" style="width:100%; text-align:center; border-collapse:collapse; font-family:Arial, sans-serif;">
                    <thead>
                        <tr style="background-color:#cce4f6;">
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Material</th>
                            <th>Color</th>
                            <th>Talla</th>
                            <th>Tipo</th>
                            <th>Imagen</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>';
    
                while ($row = $result->fetch_assoc()) {
                    echo '<tr style="background-color:#f2f9fc;">
                        <td>' . $row['id'] . '</td>
                        <td>' . $row['nombre'] . '</td>
                        <td>' . $row['precio'] . '</td>
                        <td>' . $row['stock'] . '</td>
                        <td>' . $row['material'] . '</td>
                        <td>' . $row['color'] . '</td>
                        <td>' . $row['talla'] . '</td>
                        <td>' . $row['tipo'] . '</td>
                        <td><img src="' . $row['imagen'] . '" alt="' . $row['nombre'] . '" style="width:100px; height:auto; border-radius:5px;"></td>
                        <td>
                            <a href="editarproducto.php?id=' . $row['id'] . '" style="color:#66c2ff;">Editar</a>
                            <a href="?eliminar=' . $row['id'] . '" 
                                onclick="return confirm(\'¿Estás seguro de que deseas eliminar este producto?\')" 
                                style="text-decoration:none;">
                                <button style="background-color: #f28b82; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">
                                    Eliminar
                                </button>
                            </a>
                        </td>
                    </tr>';
                }
    
                echo '
                    </tbody>
                </table>';
            } else {
                echo "<div style='color:#66c2ff;'>No hay productos para mostrar.<br></div>";
            }
        } catch (Exception $e) {
            echo "<div style='color:red;'>Error: " . $e->getMessage() . "<br></div>";
        }
    }

    // Eliminar un producto
    public function eliminarProducto($id) {
        try {
            $sql = "DELETE FROM producto WHERE id = ?";
            $stmt = $this->conexion->getConexion()->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<div style='color:#4caf50;'>Producto eliminado exitosamente.<br></div>";
            } else {
                echo "<div style='color:red;'>Error al eliminar el producto.<br></div>";
            }
        } catch (Exception $e) {
            echo "<div style='color:red;'>Error: " . $e->getMessage() . "<br></div>";
        }
    }

    // Editar producto
    public function editarProducto($id) {
        // Lógica para editar el producto
    }
}

// Instancia y uso de la clase
$productos = new Productos();

if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $productos->editarProducto($id);
} elseif (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $productos->eliminarProducto($id);
} else {
    $productos->mostrarFormulario();
    $productos->agregarProducto();
    $productos->mostrarProductos();
}
?>

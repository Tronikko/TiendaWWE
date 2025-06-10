<?php
session_start();

class AdminUsuarios {
    private $conexion;

    public function __construct() {
        include 'conexion.php';
        $conexionObj = new Conexion();
        $this->conexion = $conexionObj->getConexion();
        $this->conexion->set_charset("utf8");
    }

    // Obtener lista de usuarios
    public function obtenerUsuarios() {
        $sql = "SELECT id, nombre, usuario, rol FROM usuarios";
        $resultado = $this->conexion->query($sql);
        return $resultado->num_rows > 0 ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Eliminar usuario
    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0 ? "✅ Usuario eliminado correctamente" : "❌ Error al eliminar usuario";
    }
}

// Instancia de la clase
$adminUsuarios = new AdminUsuarios();

// Manejar solicitud de eliminación
if (isset($_GET['id'])) {
    echo $adminUsuarios->eliminarUsuario(intval($_GET['id']));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        button {
            padding: 5px 10px;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .eliminar-btn {
            background-color: #ff4d4d;
        }
        .eliminar-btn:hover {
            background-color: #ff1a1a;
        }
        .regresar-btn {
            background-color: #ff0000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }
        .regresar-btn:hover {
            background-color: #cc0000;
        }
    </style>
    <script>
        function eliminarUsuario(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
                fetch("admin1.php?id=" + id, { method: "GET" })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    if (data.includes("✅ Usuario eliminado correctamente")) {
                        document.getElementById("fila-" + id).remove();
                    } else {
                        alert(data);
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        }
    </script>
</head>
<body>
    <h2>Lista de Usuarios</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
            <th>Actualizar</th>
        </tr>
        <?php
        $usuarios = $adminUsuarios->obtenerUsuarios();

        if (!empty($usuarios)) {
            foreach ($usuarios as $fila) {
                echo "<tr id='fila-{$fila['id']}'>";
                echo "<td>{$fila['id']}</td>";
                echo "<td>{$fila['nombre']}</td>";
                echo "<td>{$fila['usuario']}</td>";
                echo "<td>{$fila['rol']}</td>";
                echo "<td><button class='eliminar-btn' onclick='eliminarUsuario({$fila['id']})'>Eliminar</button></td>";
                echo "<td><a href='actualizar.php?id={$fila['id']}'><button>Actualizar</button></a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay usuarios registrados.</td></tr>";
        }
        ?>
    </table>

    <a href="http://localhost/tiendawwe" target="_top">
        <button class="regresar-btn">Regresar a Entrar</button>
    </a>
</body>
</html>

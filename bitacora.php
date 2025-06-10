<?php
require_once 'conexion.php'; // Incluye tu archivo de conexión

// Crear una instancia de la clase Conexion
$conn = new Conexion();
$conexion = $conn->getConexion(); // Obtenemos la conexión activa

// Consulta para obtener los datos de la tabla bitacora
$sql = "SELECT * FROM bitacora";
$result = $conexion->query($sql);

echo "<style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #e74c3c;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #e74c3c;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e6b0aa;
        }
        .regresar-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .regresar-btn:hover {
            background-color: #c0392b;
        }
        a {
            text-decoration: none;
        }
    </style>";

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Usuario</th><th>Fecha</th><th>Hora</th><th>Operación</th></tr>";
    
    // Iterar sobre los resultados y mostrarlos en la tabla
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['usuario'] . "</td>
                <td>" . $row['fecha'] . "</td>
                <td>" . $row['hora'] . "</td>
                <td>" . $row['operacion'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align: center; color: #e74c3c;'>No hay registros en la tabla bitacora.</p>";
}

// Botón Regresar
echo "<div style='text-align: center; margin: 20px;'>
        <a href='http://localhost/tiendawwe' target='_top'>
            <button class='regresar-btn'>Regresar a Entrar</button>
        </a>
      </div>";

// Cerrar la conexión
$conexion->close();
?>

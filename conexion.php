<?php
class Conexion {
    private $conexion;

    public function __construct() {
        $server = "localhost";
        $userdb = "root";
        $passworddb = "temp";
        $namedb = "tienda";

        $this->conexion = new mysqli($server, $userdb, $passworddb, $namedb);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function getConexion() {
        return $this->conexion;
    }
}
?>

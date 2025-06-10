<?php
class Conexion {
    private $conexion;

    public function __construct() {
        $server = "loggin.mysql.database.azure.com";
        $userdb = "paco";
        $passworddb = "199627Fggv27";
        $namedb = "tiendawwe";

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

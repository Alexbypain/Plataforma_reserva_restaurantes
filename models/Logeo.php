<?php

class Logeo {

    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function store($usuario, $email, $contrasenia) {
        // Encriptar contraseña
        $password = password_hash($contrasenia, PASSWORD_BCRYPT);

        $sql = "INSERT INTO logeo(usuario, email, contrasenia)
                VALUES ('$usuario', '$email', '$password')";

        $this->db->query($sql);
    }

    public function consultarUsuario($email) {
        $sql = "SELECT * FROM logeo
                WHERE email = '$email'";
        $consulta = $this->db->query($sql);
        $objetoUsuario = $consulta->fetch_assoc();
        return $objetoUsuario;
    }


}



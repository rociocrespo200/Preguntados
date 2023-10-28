<?php

class ProfileModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerUsuario($id){
        $result = $this->database->query("SELECT * FROM `usuario` WHERE id = $id");
        if(!empty($result)){
        return $result[0];
        }
    }

    public function modificarUsuario($id, $nombre, $apellido, $anioNacimiento, $genero, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil) {
        $sql = "UPDATE `usuario` SET `nombre`='$nombre', `apellido`='$apellido', `anio_nacimiento`='$anioNacimiento', `genero`='$genero',`pais`='$pais', `ciudad`='$ciudad', `mail`='$mail', `usuario`='$usuario', `clave`='$clave', `foto_perfil`='$fotoPerfil' WHERE `id`='$id'";
        $this->database->query($sql);
    }


}
<?php

class UserModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function alta($nombre, $apellido, $anioNacimiento, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil) {
        $sql = "INSERT INTO `usuario` (`nombre`, `apellido`, `anio_nacimiento`, `pais`, `ciudad`, `mail`, `usuario`, `clave` ,`foto_perfil`) 
        VALUES ('$nombre', '$apellido', '$anioNacimiento', '$pais', '$ciudad', '$mail', '$usuario', '$clave', '$fotoPerfil')";


        Logger::info('Usuario alta: ' . $sql);

        $this->database->query($sql);
    }

}
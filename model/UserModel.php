<?php

class UserModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function alta($usuario, $clave) {
        $sql = "INSERT INTO `usuario` ( `usuario`, `clave`) VALUES ( '$usuario', '$clave');";
        Logger::info('Usuario alta: ' . $sql);

        $this->database->query($sql);
    }

}
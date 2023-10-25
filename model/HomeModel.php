<?php

class HomeModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerUsuario($id){ //agregado
        $result = $this->database->query("SELECT * FROM `usuario` WHERE id = $id");
        if(!empty($result)){
            return $result[0];
        }
    }


}
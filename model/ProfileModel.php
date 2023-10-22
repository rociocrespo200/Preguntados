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


}
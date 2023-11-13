<?php

class HistorialModel
{
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


    public function traerHistorial($id){ //agregado

        return $this->database->query("SELECT * FROM `partida` WHERE id_usuario = $id");

    }
}
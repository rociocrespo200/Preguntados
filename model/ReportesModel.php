<?php

class ReportesModel
{

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerUsuario($id){
        return $this->database->query("SELECT * FROM `usuario` WHERE id = $id")[0];
    }

    public function traerReportadas(){
        return $this->database->query("SELECT * FROM pregunta WHERE fueReportada = 1");
    }

}
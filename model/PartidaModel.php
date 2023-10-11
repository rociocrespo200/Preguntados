<?php

class PartidaModel
{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerPreguntas() {
        return $this->database->query("SELECT * FROM pregunta");
    }
    public function traerRespuestas($idpregunta) {
        return $this->database->query("SELECT * FROM respuesta WHERE respuesta.id_pregunta = " . $idpregunta);
    }


}
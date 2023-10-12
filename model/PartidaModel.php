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
   /* public function traerRespuestas($idpregunta) {
        return $this->database->query("SELECT * FROM respuesta WHERE respuesta.id_pregunta = " . $idpregunta);
    }*/


    public function traerRespuestas($idpregunta)
    {
        return $this->database->query("SELECT Respuesta.respuesta
                                      FROM Respuesta
                                      WHERE Respuesta.id_pregunta =" . $idpregunta);
    }



        public function traerPreguntaConRespuestas(){
            // Obtén la pregunta aleatoria
            $preguntas = $this->database->query("SELECT * FROM Pregunta");
            $preguntaAleatoria = $preguntas[rand(0, count($preguntas) - 1)];

            // Obtén las respuestas asociadas a la pregunta aleatoria
            $respuestas = $this->database->query("SELECT respuesta  FROM Respuesta WHERE id_pregunta = " . $preguntaAleatoria['id']);

            // Construye un array que contiene la pregunta y sus respuestas
            $result = [
                'pregunta' => $preguntaAleatoria['pregunta'],
                'respuestas' => []
            ];

            foreach ($respuestas as $respuesta) {
                $result['respuestas'][] = $respuesta['respuesta'];
            }

            return $result;

    }


    public function contarPreguntas (){
        return $this->database->query("SELECT COUNT(*) FROM pregunta");

    }


}
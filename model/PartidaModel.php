<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function crearPartida($idUsuario)
    {
        $this->database->query("INSERT INTO partida (id_usuario) VALUES (" . $idUsuario . ")");
    }

    public function obtenerPartidaActual($idUsuario)
    {
        return $this->database->query("SELECT * FROM partida WHERE id_usuario = " . $idUsuario . " ORDER BY fecha DESC LIMIT 1");
    }


    public function buscarRespuestaPorId($id)
    {
        return $this->database->query("SELECT *  FROM Respuesta WHERE Respuesta.id = " . $id)[0];
    }

    public function buscarPreguntaPorId($id)
    {
        return $this->database->query("SELECT *  FROM Pregunta WHERE Pregunta.id = " . $id);
    }

    public function validarRecargoDePagina($idpartida, $idRespuesta){
        $respuestas = $this->database->query("SELECT * FROM partida_respuestas WHERE id_partida = " . $idpartida);
//
//        print_r($respuestas);
//        echo "tamaño " . sizeof($respuestas);
        if (sizeof($respuestas) != 0 && $respuestas[sizeof($respuestas)-1]['id_respuesta'] == $idRespuesta) return true;
        return false;
    }

    public function agregarRespuestaALaPartida($partida, $idRespuesta)
    {
        $this->database->query("UPDATE `preguntados`.`partida` SET `preguntasContestadas` = '" . ($partida['preguntasContestadas'] + 1) . "' WHERE `id` =" . $partida['id']);
        $this->database->query("INSERT INTO partida_respuestas (id_partida, id_respuesta) VALUES (" . $partida['id'] . "," . $idRespuesta . ")");
    }

    public function obtenerDificultad($id)
    {
        return $this->database->query("SELECT * FROM Dificultad  WHERE Dificultad.id =" . $id);
    }

    public function obtenerCategoria($id)
    {
        return $this->database->query("SELECT * FROM Categoria  WHERE Categoria.id =" . $id);
    }

    public function sumarPuntos($partida, $puntosPartida, $puntosUsuario)
    {
        $this->database->query("UPDATE `preguntados`.`partida` SET `puntos` = '" . $puntosPartida . "' WHERE `id` =" . $partida['id']);
        $this->database->query("UPDATE `preguntados`.`usuario` SET `puntos` = '" . $puntosUsuario . "' WHERE `id` =" . $partida['id_usuario']);
    }


    public function traerPreguntaConRespuestas($partida)
    {
        // Obtén la pregunta aleatoria
        $preguntas = $this->database->query("SELECT * FROM Pregunta");
        $preguntaAleatoria = $preguntas[rand(0, count($preguntas) - 1)];

        // Obtén las respuestas asociadas a la pregunta aleatoria
        $respuestas = $this->database->query("SELECT *  FROM Respuesta WHERE id_pregunta = " . $preguntaAleatoria['id']);

        // Construye un array que contiene la pregunta y sus respuestas
        $result = [
            'pregunta' => $preguntaAleatoria,
            'respuestas' => $respuestas
        ];

        //SACAR ESTO DE ACA Y PONERLO EN EL CONTROLADOR
       return $result;

    }









}
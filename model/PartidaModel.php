<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function traerUsuario($id){ //agregado
        $result = $this->database->query("SELECT * FROM `usuario` WHERE id = $id");
        if(!empty($result)){
            return $result[0];
        }
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
        //$respuesta = $this->buscarRespuestaPorId($idRespuesta);
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
        $dificultad = $this ->validarDificultadQueCorresponde($partida);
        $preguntas = $this->database->query("SELECT * FROM Pregunta WHERE Pregunta.id_dificultad = ". $dificultad );
        $preguntaAleatoria = $preguntas[rand(0, count($preguntas) - 1)];
        $this->database->query("INSERT INTO partida_preguntas (id_partida, id_pregunta) VALUES (". $partida['id'] ."," . $preguntaAleatoria['id'] . ")");

        $respuestas = $this->database->query("SELECT *  FROM Respuesta WHERE id_pregunta = " . $preguntaAleatoria['id']);

        $result = [
            'pregunta' => $preguntaAleatoria,
            'respuestas' => $respuestas
        ];

       return $result;

    }

    public function reportarPregunta($idPregunta){
        $this->database->query("UPDATE pregunta SET fueReportada = 1 WHERE id = " . $idPregunta);
    }


    public function validarDificultadQueCorresponde($partida){

        $query1 = "SELECT COUNT(pr.id_dificultad) AS dificultad FROM partida_preguntas AS pp JOIN partida AS p ON p.id = pp.id_partida JOIN pregunta AS pr ON pr.id = pp.id_pregunta WHERE pr.id_dificultad = 1 AND pp.id_partida = " . $partida['id'];
        $query2 = "SELECT COUNT(pr.id_dificultad) AS dificultad FROM partida_preguntas AS pp JOIN partida AS p ON p.id = pp.id_partida JOIN pregunta AS pr ON pr.id = pp.id_pregunta WHERE pr.id_dificultad = 2 AND pp.id_partida = " . $partida['id'];
        $query3 = "SELECT COUNT(pr.id_dificultad) AS dificultad FROM partida_preguntas AS pp JOIN partida AS p ON p.id = pp.id_partida JOIN pregunta AS pr ON pr.id = pp.id_pregunta WHERE pr.id_dificultad = 3 AND pp.id_partida = " . $partida['id'];


        $result1 = (int)$this->database->query($query1)[0][0];
        $result2 = (int)$this->database->query($query2)[0][0];
        $result3 = (int)$this->database->query($query3)[0][0];


        if ($result1 < 5) {
            return 1;
        }
        else if ($result2 < 5) {
            return 2;
        }
        else {
            return 3;
        }
    }






}
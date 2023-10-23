<?php

class RanckingModel
{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }
    public function getRanking ()
    {
        $ranking = $this->database->query("SELECT ROW_NUMBER() OVER (ORDER BY U.puntos DESC) AS 'Posicion', U.usuario, U.nivel, U.puntos, 
                                            COUNT(*) AS 'CantidaddePartidas', SUM(P.preguntasContestadas) AS 'CantidaddePreguntas' 
                                            FROM usuario U JOIN partida P ON U.id = P.id_usuario 
                                            GROUP BY U.usuario, U.nivel, U.puntos 
                                            ORDER BY U.puntos DESC;");
        $result = array();

        foreach ($ranking as $rank){
            $result[] = [
                'posicion' => $rank["Posicion"],
                'usuarios' => $rank["usuario"],
                'nivel' => $rank ["nivel"],
                'puntos' => $rank["puntos"],
                'cantidadDePartidas' => $rank ["CantidaddePartidas"],
                'preguntas' => $rank["CantidaddePreguntas"]
            ];
        }
        return $result;
    }

}
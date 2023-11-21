<?php

class RanckingModel
{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }
    public function getRanking ($inicio, $limite)
    {
        $ranking = $this->database->query("SELECT ROW_NUMBER() OVER (ORDER BY U.puntos DESC) AS 'Posicion', 
                                           U.id as 'id', 
                                           U.usuario, 
                                           U.nivel, 
                                           U.puntos, 
                                           U.foto_perfil, 
                                           U.foto_qr,
                                           COUNT(*) AS 'CantidaddePartidas', 
                                           SUM(P.preguntasContestadas) AS 'CantidaddePreguntas' 
                                            FROM usuario U 
                                            JOIN partida P ON U.id = P.id_usuario 
                                            GROUP BY U.id, U.usuario, U.nivel, U.puntos 
                                            ORDER BY U.puntos DESC
                                            LIMIT $limite OFFSET $inicio;");
        //  U.foto_perfil,  agregado para que traiga foto perfil
        $result = array();

        foreach ($ranking as $rank) {
            $foto_qr = empty($rank["foto_qr"]) ? "qr.png" : $rank["foto_qr"];

            $result[] = [
                'id' => $rank["id"],
                'posicion' => $rank["Posicion"],
                'usuario' => $rank["usuario"],
                'nivel' => $rank ["nivel"],
                'puntos' => $rank["puntos"],
                'foto_perfil'=>$rank["foto_perfil"],
                'foto_qr'=>$foto_qr,
                'cantidadDePartidas' => $rank ["CantidaddePartidas"],
                'preguntas' => $rank["CantidaddePreguntas"]
            ];
            //if(!isset($result['foto_qr'])){
             //   $result['foto_qr'] = "qr.png";
            //
        }
        return $result;

    }

    public function traerUsuario($id){ //agregado
        $result = $this->database->query("SELECT * FROM `usuario` WHERE id = $id");
        if(!empty($result)){
            return $result[0];
        }
    }

}
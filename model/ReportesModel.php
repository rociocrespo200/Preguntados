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

    public function traerListaDePreguntas(){
        return $this->database->query("SELECT * FROM `pregunta`");
    }

    public function buscarPreguntaPorId($id){
        return $this->database->query("SELECT *  FROM Pregunta WHERE Pregunta.id = " . $id)[0];
    }

    public function traerReportes(){
        return $this->database->query("select r.id as id, r.fecha as fecha, r.motivo as motivo, p.id as id_pregunta, p.pregunta as pregunta, u.id as id_usuario, u.usuario as usuario 
                                        from reporte as r join usuario as u on r.id_usuario = u.id join pregunta as p on r.id_pregunta = p.id
                                        order by fecha");
    }

    public function eliminarReporte($id){
        $this->database->query("DELETE FROM `preguntados`.`reporte` WHERE `id` = " . (int)$id);

    }
    public function deshabilitarPregunta($idPreg, $idReporte){
        $this->database->query("UPDATE `preguntados`.`pregunta` SET `habilitada` = 0 WHERE `id` = " . $idPreg);

        $this->eliminarReporte($idReporte);

    }

    public function agregarReporte($idUsuario, $idPregunta, $motivo){
        $this->database->query("INSERT into reporte (id_pregunta, motivo, id_usuario) VALUES (" . $idPregunta . ", '" . $motivo . "', " . $idUsuario . ")");
    }

    public function terminarPartida($idUsuario){
       $partidaAct= $this->obtenerPartidaActual($idUsuario);
       $this->agregarRespuestaNulaALaPartida($partidaAct);
    }

    public function obtenerPartidaActual($idUsuario)
    {
        return $this->database->query("SELECT * FROM partida WHERE id_usuario = " . $idUsuario . " ORDER BY fecha DESC LIMIT 1")[0];
    }

    public function agregarRespuestaNulaALaPartida($partida){
        //print_r($partida[0]);
        $this->database->query("UPDATE `preguntados`.`partida` SET `preguntasContestadas` = '" . ($partida['preguntasContestadas'] + 1) . "' WHERE `id` =" . $partida['id']);
        $this->database->query("INSERT INTO partida_respuestas (id_partida) VALUES (" .$partida['id']. ")");

    }



}
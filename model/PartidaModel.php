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

        if (sizeof($respuestas) != 0 && $respuestas[sizeof($respuestas)-1]['id_respuesta'] == $idRespuesta) return true;
        return false;
    }

    public function agregarRespuestaALaPartida($partida, $idRespuesta)
    {
        if($this->validarTiempo($partida['id'])){
        //$respuesta = $this->buscarRespuestaPorId($idRespuesta);
        $this->database->query("UPDATE `preguntados`.`partida` SET `preguntasContestadas` = '" . ($partida['preguntasContestadas'] + 1) . "' WHERE `id` =" . $partida['id']);
        $this->database->query("INSERT INTO partida_respuestas (id_partida, id_respuesta) VALUES (" . $partida['id'] . "," . $idRespuesta . ")");
        $this->actualizarDificultad($this->buscarRespuestaPorId($idRespuesta));
        $this->actualizarNivelUsuario($_SESSION['usuario']['id']);
            return true;
        }else{
            $this->agregarRespuestaNulaALaPartida($partida, $idRespuesta);
            $this->actualizarNivelUsuario($_SESSION['usuario']['id']);
            return false;
        }
    }

    public function agregarRespuestaNulaALaPartida($partida)
    {
        //$respuesta = $this->buscarRespuestaPorId($idRespuesta);
        $this->database->query("UPDATE `preguntados`.`partida` SET `preguntasContestadas` = '" . ($partida['preguntasContestadas'] + 1) . "' WHERE `id` =" . $partida['id']);
        $this->database->query("INSERT INTO partida_respuestas (id_partida) VALUES (" .$partida['id']. ")");

        //$this->actualizarDificultad($this->buscarRespuestaPorId($idRespuesta));

    }

    public function obtenerDificultad($id)
    {
        return $this->database->query("SELECT * FROM Dificultad  WHERE Dificultad.id =" . $id);
    }

    public function obtenerCategoria($id)
    {
        return $this->database->query("SELECT * FROM Categoria  WHERE Categoria.id =" . $id);
    }

    public function sumarPuntos($partida, $puntosPartida){

        $this->database->query("UPDATE `preguntados`.`partida` SET `puntos` = `puntos` + '" . $puntosPartida . "' WHERE `id` =" . $partida['id']);
        $this->database->query("UPDATE `preguntados`.`usuario` SET `puntos` = `puntos` + '" . $puntosPartida . "' WHERE `id` =" . $partida['id_usuario']);
        $devolver = $this->database->query("select PUNTOS from usuario WHERE `id` =" . $partida['id_usuario']);
        return $devolver;
    }


    public function traerPreguntaConRespuestas($partida)
    {
        $dificultad = $this ->validarDificultadQueCorresponde($partida);


        $preguntas = $this->database->query("SELECT * FROM Pregunta WHERE Pregunta.id_dificultad = ". $dificultad . " AND Pregunta.habilitada=1 " );


        for ($i = 0; $i < sizeof($preguntas); $i++) {
            if (!isset($_SESSION['preguntas']) || !in_array($preguntas[$i], $_SESSION['preguntas'], true)) {
                $_SESSION['preguntas'][] = $preguntas[$i];
                $preguntaAleatoria = $preguntas[$i];
                break;
            }
        }

        if(!isset($preguntaAleatoria)){
            $_SESSION['preguntas'] = array();
            $preguntaAleatoria = $preguntas[rand(0, count($preguntas) - 1)];
            $_SESSION['preguntas'][] = $preguntaAleatoria;
        }
        $this->database->query("INSERT INTO partida_preguntas (id_partida, id_pregunta) VALUES (". $partida['id'] ."," . $preguntaAleatoria['id'] . ")");

        $respuestas = $this->database->query("SELECT *  FROM Respuesta WHERE id_pregunta = " . $preguntaAleatoria['id']);
        shuffle($respuestas);

        $result = [
            'pregunta' => $preguntaAleatoria,
            'respuestas' => $respuestas
        ];

       return $result;

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

    private function actualizarDificultad($respuesta)
    {
        $vecesContestada = (int) $this->database->query("SELECT count(*) FROM partida_respuestas pr JOIN respuesta r ON r.id = pr.id_respuesta WHERE r.id_pregunta =" . $respuesta['id_pregunta'])[0][0];
        $correctas = (int) $this->database->query("SELECT count(*) FROM partida_respuestas pr JOIN respuesta r ON r.id = pr.id_respuesta WHERE r.esCorrecta = 1 AND r.id_pregunta =" . $respuesta['id_pregunta'])[0][0];

        $promedioCorrectas = (100 * $correctas)/ $vecesContestada;

        if($promedioCorrectas > 80){
            //FACIL
            $this->database->query("UPDATE `preguntados`.`pregunta` SET `id_dificultad` = '1' WHERE `id` =" . $respuesta['id_pregunta']);
        }else if($promedioCorrectas > 60){
            //MODERADO
            $this->database->query("UPDATE `preguntados`.`pregunta` SET `id_dificultad` = '2' WHERE `id` =" . $respuesta['id_pregunta']);
        }else{
            $this->database->query("UPDATE `preguntados`.`pregunta` SET `id_dificultad` = '3' WHERE `id` =" . $respuesta['id_pregunta']);
            //DIFICIL
        }
    }

    private function validarTiempo($partida)
    {
        $fecha = $this->database->query(" SELECT fecha FROM partida_preguntas WHERE id_partida = '$partida' ORDER BY fecha DESC LIMIT 1")[0][0];
        $fecha_actual = new DateTime();
        $fecha_ultima_pregunta = new DateTime($fecha);
        $intervalo = $fecha_actual->getTimestamp() - $fecha_ultima_pregunta->getTimestamp();

        if ($intervalo > 500000) {
            return false;
        }

        return true;
    }
    private function actualizarNivelUsuario($idActual){
        $totalDePreguntas = $this->database->query("select Sum(preguntasContestadas) as 'cantidad total de preguntas' from partida where id_usuario = $idActual;");
        $totalPreguntasContestadas = $this->database->query("select Count(preguntasContestadas) from partida p left join partida_respuestas pr on pr.id_partida = p.id where id_usuario=$idActual;");
        $promedio = ($totalPreguntasContestadas[0][0]*100)/$totalDePreguntas[0][0];
        $nivel = number_format($promedio, 2, ',', '.');
        $jugador = "";
        if($nivel > 70){
            $jugador = "Veterano";
        }
        elseif ($nivel > 40 && $nivel< 70){
            $jugador = "Experimentado";
        }else{
            $jugador = "Novato";
        }
        $this->database->query ("update usuario set nivel = '$jugador' where id = $idActual");

    }

}
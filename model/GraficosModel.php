<?php

class GraficosModel
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

    public function genero($fecha){ //agregado

        if ($fecha == null) {
            $result = $this->database->query("SELECT SUM(CASE WHEN genero = 'Masculino' THEN 1 ELSE 0 END) AS masculinos, SUM(CASE WHEN genero = 'Femenino' THEN 1 ELSE 0 END) AS femeninos FROM usuario WHERE id_rol = 1;")[0];
        } else if (sizeof($fecha) == 1) {
            $fecha = $fecha[0];
            $result = $this->database->query("SELECT SUM(CASE WHEN genero = 'Masculino' THEN 1 ELSE 0 END) AS masculinos, SUM(CASE WHEN genero = 'Femenino' THEN 1 ELSE 0 END) AS femeninos FROM usuario WHERE id_rol = 1 AND DATE(fecha) = '$fecha';")[0];
        } else {
            $fechaInicio = $fecha[0];
            $fechaFin = $fecha[1];
            $result = $this->database->query("SELECT SUM(CASE WHEN genero = 'Masculino' THEN 1 ELSE 0 END) AS masculinos, SUM(CASE WHEN genero = 'Femenino' THEN 1 ELSE 0 END) AS femeninos  FROM usuario WHERE id_rol = 1 AND fecha BETWEEN '$fechaInicio' AND '$fechaFin';")[0];
        }

        return $result;

    }

    public function edad($fecha){ //agregado
        $calcularEdad = "SUM(CASE WHEN YEAR(CURDATE()) - anio_nacimiento < 18 THEN 1 ELSE 0 END) AS menores, 
                        SUM(CASE WHEN YEAR(CURDATE()) - anio_nacimiento BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS adultos,
                        SUM(CASE WHEN YEAR(CURDATE()) - anio_nacimiento >= 65 THEN 1 ELSE 0 END) AS jubilados";

        if ($fecha == null) {
            $result = $this->database->query("SELECT $calcularEdad FROM usuario WHERE id_rol = 1;")[0];
        } else if (sizeof($fecha) == 1) {
            $fecha = $fecha[0];
            $result = $this->database->query("SELECT $calcularEdad FROM usuario WHERE id_rol = 1 AND DATE(fecha) = '$fecha';")[0];
        } else {
            $fechaInicio = $fecha[0];
            $fechaFin = $fecha[1];
            $result = $this->database->query("SELECT $calcularEdad FROM usuario WHERE id_rol = 1 AND fecha BETWEEN '$fechaInicio' AND '$fechaFin';")[0];
        }
        return $result;
    }

    public function porcentajeCorrectas($fecha)
    {


        if ($fecha == null) {
            $vecesContestada = $this->database->query("SELECT  COUNT(*) AS totales, u.usuario FROM partida p JOIN partida_respuestas pr ON p.id = pr.id_partida JOIN usuario u  ON u.id = p.id_usuario WHERE u.id_rol = 1  GROUP BY u.id ORDER BY u.usuario ASC;");
            $correctas = $this->database->query("SELECT COUNT(*) AS correctas, u.usuario FROM partida p JOIN partida_respuestas pr ON p.id = pr.id_partida JOIN usuario u ON u.id = p.id_usuario JOIN respuesta r ON r.id = pr.id_respuesta WHERE u.id_rol = 1 AND r.esCorrecta = 1 GROUP BY u.id ORDER BY u.usuario ASC");

        } else if (sizeof($fecha) == 1) {
            $fecha = $fecha[0];
            $vecesContestada = $this->database->query("SELECT  COUNT(*) AS totales, u.usuario FROM partida p JOIN partida_respuestas pr ON p.id = pr.id_partida JOIN usuario u  ON u.id = p.id_usuario WHERE u.id_rol = 1 AND DATE(u.fecha) = '$fecha'  GROUP BY u.id ORDER BY u.usuario ASC;");
            $correctas = $this->database->query("SELECT COUNT(*) AS correctas, u.usuario FROM partida p JOIN partida_respuestas pr ON p.id = pr.id_partida JOIN usuario u ON u.id = p.id_usuario JOIN respuesta r ON r.id = pr.id_respuesta WHERE u.id_rol = 1 AND r.esCorrecta = 1 AND DATE(u.fecha) = '$fecha' GROUP BY u.id ORDER BY u.usuario ASC");
        } else {
            $fechaInicio = $fecha[0];
            $fechaFin = $fecha[1];
            $vecesContestada = $this->database->query("SELECT  COUNT(*) AS totales, u.usuario FROM partida p JOIN partida_respuestas pr ON p.id = pr.id_partida JOIN usuario u  ON u.id = p.id_usuario WHERE u.id_rol = 1 AND u.fecha BETWEEN '$fechaInicio' AND '$fechaFin'  GROUP BY u.id ORDER BY u.usuario ASC;");
            $correctas = $this->database->query("SELECT COUNT(*) AS correctas, u.usuario FROM partida p JOIN partida_respuestas pr ON p.id = pr.id_partida JOIN usuario u ON u.id = p.id_usuario JOIN respuesta r ON r.id = pr.id_respuesta WHERE u.id_rol = 1 AND r.esCorrecta = 1 AND u.fecha BETWEEN '$fechaInicio' AND '$fechaFin'  GROUP BY u.id ORDER BY u.usuario ASC");
        }

        $result = [];

;
        for ($i = 0; $i < sizeof($vecesContestada); $i++) {
            $promedioCorrectas = (100 * $correctas[$i]['correctas']) / $vecesContestada[$i]['totales'];
            $obj = [
                'usuarios' => $vecesContestada[$i]['usuario'],
                'porcentajes' => $promedioCorrectas
                ];
            array_push($result,$obj);
        }

        return $result;
    }

    public function preguntasCreadas($fecha){ //agregado

        if ($fecha == null) {
            $result = $this->database->query("SELECT COUNT(*) FROM sugerencia WHERE aprobada = true")[0][0];
        } else if (sizeof($fecha) == 1) {
            $fecha = $fecha[0];
            $result = $this->database->query("SELECT COUNT(*) FROM sugerencia WHERE aprobada = true && DATE(fecha) = '$fecha';")[0][0];
        } else {
            $fechaInicio = $fecha[0];
            $fechaFin = $fecha[1];
            $result = $this->database->query("SELECT COUNT(*) FROM sugerencia WHERE aprobada = true && fecha BETWEEN '$fechaInicio' AND '$fechaFin';")[0][0];
        }

        return $result;
   }
    public function usuariosNuevos($fecha){ //agregado

        if($fecha == null) $result = $this->database->query("SELECT COUNT(*) FROM usuario WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH);")[0][0];
        else{
            $fechaInicio = $fecha[0];
            $result = $this->database->query("SELECT COUNT(*) FROM usuario WHERE fecha >= DATE_SUB('$fechaInicio', INTERVAL 1 MONTH);")[0][0];
        }
        return $result;
    }

    public function cantJugadores($fecha) {
        if ($fecha == null) {
            $result = $this->database->query("SELECT COUNT(*) FROM usuario WHERE id_rol = 1;")[0][0];
        } else if (sizeof($fecha) == 1) {
            $fecha = $fecha[0];
            $result = $this->database->query("SELECT COUNT(*) FROM usuario WHERE id_rol = 1 AND DATE(fecha) = '$fecha';")[0][0];
        } else {
            $fechaInicio = $fecha[0];
            $fechaFin = $fecha[1];
            $result = $this->database->query("SELECT COUNT(*) FROM usuario WHERE id_rol = 1 AND fecha BETWEEN '$fechaInicio' AND '$fechaFin';")[0][0];
        }

        return $result;
    }


    public function cantPartidas($fecha){ //agregado
        if ($fecha == null) {
            $result = $this->database->query("SELECT COUNT(*) FROM partida")[0][0];
        } else if (sizeof($fecha) == 1) {
            $fecha = $fecha[0];
            $result = $this->database->query("SELECT COUNT(*) FROM partida WHERE DATE(fecha) = '$fecha';")[0][0];
        } else {
            $fechaInicio = $fecha[0];
            $fechaFin = $fecha[1];
            $result = $this->database->query("SELECT COUNT(*) FROM partida WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin';")[0][0];
        }

        return $result;
    }

    public function cantPreguntas($fecha){ //agregado

        return $this->database->query("SELECT COUNT(*) FROM pregunta")[0][0];
    }

}
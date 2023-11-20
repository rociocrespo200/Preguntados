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

            return $this->database->query("SELECT SUM(CASE WHEN genero = 'Masculino' THEN 1 ELSE 0 END) AS masculinos, SUM(CASE WHEN genero = 'Femenino' THEN 1 ELSE 0 END) AS femeninos FROM usuario;")[0];
        }

    public function edad($fecha){ //agregado

        return $this->database->query("SELECT 
            SUM(CASE WHEN YEAR(CURDATE()) - anio_nacimiento < 18 THEN 1 ELSE 0 END) AS menores,
            SUM(CASE WHEN YEAR(CURDATE()) - anio_nacimiento BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS adultos,
            SUM(CASE WHEN YEAR(CURDATE()) - anio_nacimiento >= 65 THEN 1 ELSE 0 END) AS jubilados
             FROM usuario;")[0];
    }

}
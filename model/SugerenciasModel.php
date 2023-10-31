<?php

class SugerenciasModel
{

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerUsuario($id){
        return $this->database->query("SELECT * FROM `usuario` WHERE id = $id")[0];
    }

    public function traerSugerencias(){
        return $this->database->query("SELECT * FROM `sugerencia` s JOIN categoria c ON s.id_categoria = c.id JOIN usuario u ON s.id_usuario = u.id");
    }
    public function agregarSugerencia($respuestasCant,$pregunta,$correcta,$incorrecta1,$incorrecta2,$incorrecta3, $categoria,$dificultad){

        $id_usuario = $_SESSION['usuario']['id'];
        if($respuestasCant == 4){
            $this->database->query("INSERT INTO sugerencia (id_usuario, pregunta, id_categoria, id_dificultad, respuestaCorrecta, respuestaIncorrecta1, respuestaIncorrecta2, respuestaIncorrecta3)VALUES 
                                    ('$id_usuario','$pregunta', '$categoria', '$dificultad', '$correcta', '$incorrecta1', '$incorrecta2', '$incorrecta3')");
        }else if($respuestasCant == 3){
            $this->database->query("INSERT INTO sugerencia (id_usuario, pregunta, id_categoria, id_dificultad, respuestaCorrecta, respuestaIncorrecta1, respuestaIncorrecta2)VALUES 
                                    ('$id_usuario','$pregunta', '$categoria', '$dificultad', '$correcta', '$incorrecta1', '$incorrecta2')");
        }else{
        $this->database->query("INSERT INTO sugerencia (id_usuario, pregunta, id_categoria, id_dificultad, respuestaCorrecta, respuestaIncorrecta1)VALUES 
                                    ('$id_usuario','$pregunta', '$categoria', '$dificultad', '$correcta', '$incorrecta1')");
    }
    }

    public function aprobarSugerencia($idSugerencia){
        $sugerencia = $this->database->query("SELECT * FROM `sugerencia`  WHERE id = '$idSugerencia'")[0];

        $pregunta = $sugerencia["pregunta"];
        $categoria = $sugerencia["id_categoria"];
        $dificultad = $sugerencia["id_dificultad"];
        $correcta = $sugerencia["respuestaCorrecta"];
        $incorrecta1 = $sugerencia["respuestaIncorrecta1"];



        $this->database->query("INSERT INTO pregunta (pregunta, id_categoria, id_dificultad) VALUES ('$pregunta','$categoria','$dificultad')");
        $ultimaPregunta = $this->database->query("SELECT * FROM pregunta ORDER BY id DESC LIMIT 1")[0];
        $idPreg = $ultimaPregunta['id'];

        if(!isset($sugerencia["respuestaIncorrecta2"]) && !isset($sugerencia["respuestaIncorrecta3"])){
            $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),('$incorrecta1', '0', '$idPreg')");
        }
        else if(isset($sugerencia["respuestaIncorrecta2"]) && !isset($sugerencia["respuestaIncorrecta3"])){
            $incorrecta2 = $sugerencia["respuestaIncorrecta2"];
            $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),('$incorrecta1', '0', '$idPreg'),('$incorrecta2', '0', '$idPreg')");
        }else{
            $incorrecta2 = $sugerencia["respuestaIncorrecta2"];
            $incorrecta3 = $sugerencia["respuestaIncorrecta3"];
            $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),('$incorrecta1', '0', '$idPreg'),('$incorrecta2', '0', '$idPreg'),('$incorrecta3', '0', '$idPreg')");
        }

        $this->eliminarSugerencia($idSugerencia);
    }

    public function eliminarSugerencia($idSugerencia){
        return $this->database->query("DELETE FROM `sugerencia` WHERE id = '$idSugerencia'");
    }
}
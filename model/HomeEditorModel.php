<?php

class HomeEditorModel
{
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerUsuario($id){
        return $this->database->query("SELECT * FROM `usuario` WHERE id = $id")[0];
    }

    public function traerListaDePreguntas(){
        return $this->database->query("SELECT * FROM `pregunta` WHERE habilitada = 1");
    }

    public function traerListaDeCategorias(){
        return $this->database->query("SELECT * FROM `categoria` WHERE habilitada = 1");
    }

    public function traerPregunta($id){
        return $this->database->query("SELECT * FROM `pregunta` WHERE id = $id")[0];
    }

    public function traerRespuestaCorrecta($idPreg){
        return $this->database->query("SELECT * FROM respuesta WHERE id_pregunta = $idPreg");
    }

    public function traerCategoria($idPreg){
        $pregunta = $this->traerPregunta($idPreg);
        return $this->database->query("SELECT * FROM `categoria` WHERE id = ". $pregunta['id_categoria'])[0];
    }

    public function traerDificultad($idPreg){
        $pregunta = $this->traerPregunta($idPreg);
        return $this->database->query("SELECT * FROM `dificultad` WHERE id = ". $pregunta['id_dificultad'])[0];
    }

    public function actualizarPregunta4($idPreg,$pregunta,$correcta,$incorrecta1,$incorrecta2,$incorrecta3, $categoria,$dificultad){
        $this->database->query("UPDATE pregunta SET pregunta = '$pregunta', id_categoria = $categoria, id_dificultad = '$dificultad' WHERE id = $idPreg");
        $this->database->query("DELETE from respuesta WHERE id_pregunta=$idPreg");
        $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),
                                                                                                  ('$incorrecta1', '0', '$idPreg'),
                                                                                                  ('$incorrecta2', '0', '$idPreg'),
                                                                                                  ('$incorrecta3', '0', '$idPreg')");
    }

    public function actualizarPregunta2($idPreg,$pregunta,$correcta,$incorrecta1,$categoria,$dificultad){
        $this->database->query("UPDATE pregunta SET pregunta = '$pregunta', id_categoria = $categoria, id_dificultad = '$dificultad' WHERE id = $idPreg");
        $this->database->query("DELETE from respuesta WHERE id_pregunta=$idPreg");
        $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),
                                                                                                  ('$incorrecta1', '0', '$idPreg')");
    }

    public function agregarPregunta($respuestasCant,$pregunta,$correcta,$incorrecta1,$incorrecta2,$incorrecta3, $categoria,$dificultad){

        $this->database->query("INSERT INTO pregunta (pregunta, id_categoria, id_dificultad) VALUES ('$pregunta','$categoria','$dificultad')");
        $ultimaPregunta = $this->database->query("SELECT * FROM pregunta ORDER BY id DESC LIMIT 1")[0];

        $idPreg = $ultimaPregunta['id'];

        if($respuestasCant == 2){
            $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),('$incorrecta1', '0', '$idPreg')");
        }else if($respuestasCant == 3){
            $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),('$incorrecta1', '0', '$idPreg'),('$incorrecta2', '0', '$idPreg')");
        }else{
            $this->database->query("INSERT INTO respuesta (respuesta, esCorrecta, id_pregunta) VALUES ('$correcta', '1', '$idPreg'),('$incorrecta1', '0', '$idPreg'),('$incorrecta2', '0', '$idPreg'),('$incorrecta3', '0', '$idPreg')");
        }
    }

    public function eliminarPregunta($idPregunta){
        $this->database->query("UPDATE pregunta SET habilitada = 0 WHERE id = $idPregunta");

    }



    public function actualizarCategoria($idCategoria,$categoria,$fotoCategoria){
        $this->database->query("UPDATE categoria SET categoria = '$categoria', imagen = '$fotoCategoria'  WHERE id = $idCategoria");
    }

    public function agregarCategoria($categoria,$fotoCategoria){

        $this->database->query("INSERT INTO categoria (categoria, imagen) VALUES ('$categoria','$fotoCategoria')");

    }

    public function eliminarCategoria($idCategoria){
        $this->database->query("UPDATE pregunta SET habilitada = 0 WHERE id_categoria = $idCategoria");
        $this->database->query("UPDATE categoria SET habilitada = 0 WHERE id = $idCategoria");

    }
}
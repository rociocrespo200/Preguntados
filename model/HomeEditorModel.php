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
        return $this->database->query("SELECT * FROM `pregunta`");
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




}
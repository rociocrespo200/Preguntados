<?php

class HomeEditorController
{
    private $render;
    private $model;

    public function __construct($render, $model)
    {
        $this->render = $render;
        $this->model = $model;
    }

    public function show()
    {
        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
            'preguntas' => $this->model->traerListaDePreguntas()
        ];
        $this->render->printViewEditor('homeEditor', $datos);
    }

    public function verHome()
    {
        if($_GET['tipo'] == "pregunta"){
            $datos = [
                'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
                'preguntas' => $this->model->traerListaDePreguntas()
            ];
            $this->render->printViewEditor('homeEditor', $datos);
        }
        if($_GET['tipo'] == "categoria"){
            $datos = [
                'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
                'categorias' => $this->model->traerListaDeCategorias()
            ];
            $this->render->printViewEditor('homeEditor', $datos);
        }

    }


    public function administrarPregunta(){

        if(isset($_GET['id'])){
            $idPreg=$_GET['id'];
            $respuestas=$this->model->traerRespuestaCorrecta($idPreg);



            $datos = [
                //'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
                'pregunta' => $this->model->traerPregunta($idPreg),
                'respuesta1' => $respuestas[0],
                'respuesta2' => $respuestas[1],
                'categoria' => $this->model->traerCategoria($idPreg),
                'dificultad'=> $this->model->traerDificultad($idPreg),
                'titulo'=>'Modificar',
                'action'=> '/HomeEditor/modificarPregunta?id='.$idPreg

            ];

            if (isset($respuestas[2])) {
                $datos['respuesta3'] = $respuestas[2];
            }

            if (isset($respuestas[3])) {
                $datos['respuesta4'] = $respuestas[3];
            }

        }else if(isset($_GET['editor']) && $_GET['editor'] == "true"){
            $datos = [
                'titulo'=>'Agregar',
                'action'=> '/HomeEditor/agregarPregunta'

            ];
        }else if(isset($_GET['editor']) && $_GET['editor'] == "false"){
            $datos = [
                'titulo'=>'Sugerir',
                'action'=> '/Sugerencias/agregarSugerencia'
            ];
        }






        $datos['user'] =  $this->model->traerUsuario($_SESSION['usuario']['id']);

        $this->render->printViewEditor('administrarPregunta', $datos);
    }

    public function agregarPregunta(){
        $pregunta = $_POST['pregunta'];
        $correcta = $_POST['correcta'];
        $incorrecta1 = $_POST['incorrecta1'];
        $incorrecta2 = $_POST['incorrecta2'];
        $incorrecta3 = $_POST['incorrecta3'];
        $categoria = $_POST['categoria'];
        $dificultad = $_POST['dificultad'];
        $respuestasCant = 2;

        if($_POST['incorrecta3'] != ""){
            $respuestasCant++;
        }
        if($_POST['incorrecta2'] != ""){
            $respuestasCant++;
        }
//        echo $respuestasCant . "<br>";
//        echo $_POST['incorrecta2'];
//

        $this->model->agregarPregunta($respuestasCant,$pregunta,$correcta,$incorrecta1,$incorrecta2,$incorrecta3, $categoria,$dificultad);

        $this->show();
    }

    public function eliminarPregunta(){
        $this->model->eliminarPregunta($_GET['id']);

        $this->show();
    }


    public function modificarPregunta(){
        $idPreg=$_GET['id'];
        $pregunta = $_POST['pregunta'];
        $correcta = $_POST['correcta'];
        $incorrecta1 = $_POST['incorrecta1'];
        $categoria = $_POST['categoria'];
        $dificultad = $_POST['dificultad'];

        if(isset($_POST['incorrecta2'])){
            $incorrecta2 = $_POST['incorrecta2'];
            $incorrecta3 = $_POST['incorrecta3'];
            $this->model->actualizarPregunta4($idPreg,$pregunta,$correcta,$incorrecta1,$incorrecta2,$incorrecta3, $categoria,$dificultad);
        } else {
            $this->model->actualizarPregunta2($idPreg,$pregunta,$correcta,$incorrecta1,$categoria,$dificultad);
        }
        $this->show();
    }



    public function eliminarCategoria(){
        $this->model->eliminarCategoria($_GET['id']);

        $this->show();
    }


    public function modificarCategoria(){
        $categoria = $_POST['categoria'];

        if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) {
            move_uploaded_file($_FILES["fileInput"]["tmp_name"], "./public/" . $_FILES['fileInput']['name']);
            $fotoCategoria = $_FILES['fileInput']['name'];
        } else {
            $fotoCategoria = "categoria.png";
        }

        if(isset($_GET['id'])){
            $idPreg=$_GET['id'];
            $this->model->actualizarCategoria($idPreg,$categoria,$fotoCategoria);
        }else{
            $this->model->agregarCategoria($categoria,$fotoCategoria);
        }



        $this->show();
    }



}
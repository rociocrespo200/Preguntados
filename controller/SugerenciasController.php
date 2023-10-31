<?php

class SugerenciasController
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
            'sugerencias' => $this->model->traerSugerencias()
        ];
        $this->render->printViewEditor('sugerencias', $datos);
    }


    public function agregarSugerencia()
    {
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


        $this->model->agregarSugerencia($respuestasCant,$pregunta,$correcta,$incorrecta1,$incorrecta2,$incorrecta3, $categoria,$dificultad);

        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];
        $this->render->printViewEditor('home', $datos);
    }

    public function aprobarSugerencia(){
        $this->model->aprobarSugerencia($_GET['id']);
        $this->show();
    }

    public function eliminarSugerencia(){

        $this->model->eliminarSugerencia($_GET['id']);
        $this->show();

    }
}
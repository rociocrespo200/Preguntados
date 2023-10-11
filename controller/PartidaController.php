<?php

class PartidaController
{
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $this->generarPregunta();
        $this->render->printView('partida', $_SESSION['usuario']);
    }

    private function generarPregunta()
    {
        $preguntas = [];
        $respuestas = [];

        $preguntas = $this->model->traerPreguntas();

        $preguntaAleatoria = $preguntas[rand(0,count($preguntas) - 1)];
    //print_r($preguntaAleatoria) ;
        $respuestas = $this->model->traerRespuestas($preguntaAleatoria['id']);

        $this->render->printView('partida', $preguntaAleatoria, $respuestas);
    }

    public function contestar($idRespuesta){
        $respuesta = null; //buscar respuesta en la base de datos

        if($respuesta['esCorrecta'] == 1){
            //sumar puntos a tabla partida
            $this->generarPregunta();
        }


        $this->render->printView('home');
    }



}
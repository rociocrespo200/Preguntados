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
        //crear partida asociada al usuario actual
        $this->generarPregunta();
    }

    private function generarPregunta()
    {
        //generara pregunta aleatoria (que no halla salido en la partida actual) con las cuatro respuestas
        // guardar pregunta en la BD para que no halla repetidas
        $this->render->printView('partida', $_SESSION['usuario']);
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
<?php

class PartidaController
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
        $datos = $this->generarPregunta(); // Llama a generarPregunta y obtén los datos
        $this->render->printViewSesion('partida', $datos); // Pasa los datos a la vista
    }


    private function generarPregunta()
    {
        $datos = $this->model->traerPreguntaConRespuestas();
        return $datos; // Devuelve los datos para usarlos en el método show
    }

    public function contestar($idRespuesta){
        $respuesta = null; //buscar respuesta en la base de datos

        if($respuesta['esCorrecta'] == 1){
            //sumar puntos a tabla partida
            $this->generarPregunta();
        }


        $this->render->printViewSesion('home');
    }



}
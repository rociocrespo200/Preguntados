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

    public function administrarPregunta(){
        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];
        $this->render->printViewEditor('administrarPregunta', $datos);
    }

}
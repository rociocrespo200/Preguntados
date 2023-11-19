<?php

class GraficosController
{

    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $datos = [
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),//agregado

        ];
        $this->render->printViewAdmin('graficos', $datos);//crea una vista, con el constructor de esta clase, llamada home
    }

}
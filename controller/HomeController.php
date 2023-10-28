<?php

class HomeController {
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
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])//agregado

        ];
        $this->render->printViewSesion('home', $datos);//crea una vista, con el constructor de esta clase, llamada home
    }



}
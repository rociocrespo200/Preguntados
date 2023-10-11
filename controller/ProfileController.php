<?php

class ProfileController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $this->render->printViewSesion('perfil', $_SESSION['usuario']);
    }

    public function cerrarSesion(){
        session_destroy();
        Redirect::root();
    }


}
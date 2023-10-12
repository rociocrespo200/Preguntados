<?php

class HomeController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $this->render->printViewSesion('home');//crea una vista, con el constructor de esta clase, llamada home
    }



}
<?php

class HomeController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $this->render->printView('home');//crea una vista, con el constructor de esta clase, llamada home
    }



}
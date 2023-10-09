<?php

class ProfileController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $this->render->printView('perfil');
    }

    public function perfil($usuario) {

        $this->render->printView('perfil');
    }



}
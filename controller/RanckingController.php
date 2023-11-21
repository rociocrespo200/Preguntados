<?php

class RanckingController
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
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];


        $this->render->printViewSesion('rancking', $datos); //crea una vista, con el constructor de esta clase, llamada home
    }

    public function traerRanking(){
        $inicio = $_GET['inicio'];
        $limite = $_GET['limite'];

        $ranking = $this->model->getRanking($inicio, $limite);

        header('Content-Type: application/json');

        // Devuelve el array codificado como JSON
        echo json_encode($ranking);
    }





}

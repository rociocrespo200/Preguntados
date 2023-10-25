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
        $ranking = $this->model->getRanking();
        
        $datos = [
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'topTres' => [$ranking[0],$ranking[1],$ranking[2]],
            'ranking' => $this->traerRestoDelRanking($ranking),
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];
        

        
        $this->render->printViewSesion('rancking', $datos); //crea una vista, con el constructor de esta clase, llamada home
    }

    public function traerRestoDelRanking($ranking){
        $rankingSinTopTres = [];
        for ($i=3; $i < sizeof($ranking); $i++) { 
            array_push($rankingSinTopTres, $ranking[$i]);
        }
        return $rankingSinTopTres;
    }


}

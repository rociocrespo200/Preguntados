<?php

class ReportesController
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
            'reportadas' => $this->model->traerReportadas()
        ];
        $this->render->printViewEditor('reportes', $datos);
    }


}
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
            'reportes'=> $this->model->traerReportes()
        ];
        $this->render->printViewEditor('reportes', $datos);
    }

    public function traerPreguntas()
    {
        $preguntaActual = $this->model->buscarPreguntaPorId($_GET['idPreguntaActual']);
        $preguntas = $this->model->traerListaDePreguntas($_SESSION['usuario']['id']);

        $datos = [
            'preguntaActual' => $preguntaActual,
            'preguntas' => $preguntas
        ];

        header('Content-Type: application/json');
        echo json_encode($datos);
    }

    public function rechazarReporte(){
        $idReporte=$_GET['id_reporte'];
        $this->model->eliminarReporte($idReporte);
        $this->show();
    }

    public function aprobarReporte(){
        $idPreg=$_GET['id_pregunta'];
        $idReporte=$_GET['id_reporte'];
        $this->model->deshabilitarPregunta($idPreg, $idReporte);
        $this->show();
    }

    public function agregarReporte(){
        $idUsuario= $_SESSION['usuario']['id'];
        $idPregunta= $_POST['id_pregunta'];
        $motivo=$_POST['motivo'];
        $this->model->agregarReporte($idUsuario,$idPregunta,$motivo);
        $this->model->terminarPartida($idUsuario);
        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];
        $this->render->printViewSesion('home', $datos);
    }

}
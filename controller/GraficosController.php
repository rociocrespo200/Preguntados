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
            'graficos' => [
                'cantGenero' => $this->model->genero(null),
                'cantEdad' => $this->model->edad(null),
                'porcentajeCorrectas' => $this->model->porcentajeCorrectas(null),
                'usuariosNuevos' => $this->model->usuariosNuevos(null),
                'preguntasCreadas' => $this->model->preguntasCreadas(null),
            ]
        ];



        //print_r($datos);

        $this->render->printViewAdmin('graficos', $datos);//crea una vista, con el constructor de esta clase, llamada home
    }


    public function traerDatos() {
        $datos = [
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),//agregado
            'dia' => $_POST['dia']

        ];
        $this->render->printViewAdmin('graficos', $datos);
    }

    public function descargarPDF() {

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Informe de Graficos');
        $pdf->Ln(10);
        $datos = [
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];

        // Agrega el contenido dinámico de la vista al PDF
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, 'Usuario: ' . $datos['usuario']);
        $pdf->MultiCell(0, 10, 'Puntos: ' . $datos['usuarioPuntos']);
        $pdf->MultiCell(0, 10, 'Nivel: ' . $datos['nivel']);
        $pdf->MultiCell(0, 10, 'Nombre de Usuario: ' . $datos['user']['nombre']);


        $pdf->Output('graficos.pdf', 'D');
    }


}
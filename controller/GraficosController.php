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
        //$this->obtenerDatos(null);
        $this->render->printViewAdmin('graficos', $this->obtenerDatos(null));//crea una vista, con el constructor de esta clase, llamada home
    }


    public function traerDatos() {
        $fechas = null;
        if (isset($_POST['dia']) && $_POST['dia'] != "0"){
            if (!isset($_POST['mes']) || $_POST['mes'] == "0" || !isset($_POST['anio']) || $_POST['anio'] == "0"){
                $datos = $this->obtenerDatos(null);
                $datos['error'] = "Si ingresa el dia debe indicar mes y año";
                $this->render->printViewAdmin('graficos', $datos);
            }
            //DIA MES Y ANIO
            echo $_POST['dia'] . "<br>";
            echo $_POST['mes'] . "<br>";
            echo $_POST['anio'] . "<br>";

            $fechas[] = $this->formatDate($_POST['dia'], $_POST['mes'], $_POST['anio']);
        }

        else if (isset($_POST['mes']) && $_POST['mes'] != "0"){
            if (!isset($_POST['anio']) || $_POST['anio'] == "0"){
                $datos = $this->obtenerDatos(null);
                $datos['error'] = "Si ingresa el mes debe indicar el año";
                $this->render->printViewAdmin('graficos', $datos);
            }
            $mes = $_POST['mes'];
            $ultimoDiaDelMes = ($mes == 2) ? 28 : (($mes % 2 == 0) ? 31 : 30);

            $fechas[] = $this->formatDate(1, $mes, $_POST['anio']);
            $fechas[] = $this->formatDate($ultimoDiaDelMes, $mes, $_POST['anio']);

        }

        else if (isset($_POST['anio']) && $_POST['anio'] != "0"){
            $fechas[] = $this->formatDate(1, 1, $_POST['anio']);
            $fechas[] = $this->formatDate(31, 12, $_POST['anio']);
        }

        $this->render->printViewAdmin('graficos', $this->obtenerDatos($fechas));

    }

    public function formatDate($dia, $mes, $anio){
        // Formatear los valores en una cadena de fecha en formato "YYYY-MM-DD"
        return $anio . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dia, 2, '0', STR_PAD_LEFT);
    }

    public function  obtenerDatos($fechas){
        $datos = [
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),//agregado
            'graficos' => [
                'cantGenero' => $this->model->genero($fechas),
                'cantEdad' => $this->model->edad($fechas),
                'porcentajeCorrectas' => $this->model->porcentajeCorrectas($fechas),
                'usuariosNuevos' => $this->model->usuariosNuevos($fechas),
                'preguntasCreadas' => $this->model->preguntasCreadas($fechas),
                'cantJugadores' => $this->model->cantJugadores($fechas),
                'cantPartidas' => $this->model->cantPartidas($fechas),
                'cantPreguntas' => $this->model->cantPreguntas($fechas),
            ]
        ];
        return $datos;
    }

    public function descargarPDF() {

        // Crea una instancia de FPDF
        $pdf = new FPDF();

        // Agrega una página al PDF
        $pdf->AddPage();

        // Establece la fuente y el tamaño del texto
        $pdf->SetFont('Arial', 'B', 16);

        // Agrega el título al PDF
        $pdf->Cell(40, 10, 'Informe de Gráficos');

        // Agrega una línea en blanco
        $pdf->Ln(10);

        // Obtén los datos dinámicos de la vista
        $datos = [
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
            'graficos' => [
                'cantGenero' => $this->model->genero(null),
                'cantEdad' => $this->model->edad(null),
                'porcentajeCorrectas' => $this->model->porcentajeCorrectas(null),
                'usuariosNuevos' => $this->model->usuariosNuevos(null),
                'preguntasCreadas' => $this->model->preguntasCreadas(null),
            ]
        ];

        // Agrega el contenido dinámico de la vista al PDF
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, 'Usuario: ' . $datos['usuario']);
        $pdf->MultiCell(0, 10, 'Puntos: ' . $datos['usuarioPuntos']);
        $pdf->MultiCell(0, 10, 'Nivel: ' . $datos['nivel']);
        $pdf->MultiCell(0, 10, 'Nombre de Usuario: ' . $datos['user']['nombre']);

        // Puedes agregar más líneas de MultiCell para incluir otros datos dinámicos

        // Agrega los gráficos al PDF
        // ...

        // Guarda el PDF en un archivo o lo envía al navegador
        $pdf->Output('graficos.pdf', 'D');
    }


}
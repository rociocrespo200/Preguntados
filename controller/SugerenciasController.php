<?php

    class SugerenciasController
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
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];
        $this->render->printViewEditor('sugerencias', $datos);
    }

    public function mandarSolicitud(){
        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']), 
            "action" => "sugerencias/enviarSolicitud",
            "titulo" => "Sugerir Pregunta"
        ];
        $this->render->printViewEditor('administrarPregunta', $datos);
    }

    public function enviarSolicitud(){
        $pregunta = $_POST['pregunta'];
        $categoria = $_POST['categoria'];
        $dificultad = $_POST['dificultad'];
        $respuestaCorrecta = $_POST['respuestaCorrecta'];
        $respuestaIncorrecta1 = $_POST['respuestaIncorrecta1'];
        $respuestaIncorrecta2 = $_POST['respuestaIncorrecta2'];
        $respuestaIncorrecta3 = $_POST['respuestaIncorrecta3'];

        // Realizar validaciones y procesamiento de datos si es necesario

        // Insertar pregunta en la tabla 'pregunta'
        $idPregunta = $this->model->agregarPregunta($pregunta, $categoria, $dificultad);

        // Insertar respuestas en la tabla 'respuesta'
        $this->model->agregarRespuesta($idPregunta, $respuestaCorrecta, 1); // La respuesta correcta tiene el atributo 'correcta' = 1
        $this->model->agregarRespuesta($idPregunta, $respuestaIncorrecta1, 0);
        $this->model->agregarRespuesta($idPregunta, $respuestaIncorrecta2, 0);
        $this->model->agregarRespuesta($idPregunta, $respuestaIncorrecta3, 0);

        // Redirigir a alguna página de éxito o mostrar un mensaje de éxito al usuario
    }
}
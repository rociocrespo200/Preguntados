<?php

class PartidaController
{
    private $render;
    private $model;


    public function __construct($render, $model)
    {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() { //aca no muestra el cartelito que sí muestra profilecontroller
        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])
        ];
        $this->render->printViewSesion('partida', $datos);
    }

    public function crearPartida(){
        $this->model->crearPartida($_SESSION['usuario']['id']);

        $datos = $this->traerDatos();
        $this->render->printViewSesion('partida', $datos );

    }


    private function traerDatos()
    {
        $partida = $this->model->obtenerPartidaActual($_SESSION['usuario']['id']);
        $preguntaRandom = $this->model->traerPreguntaConRespuestas($partida[0]);
        $datos = [
            'data' => $preguntaRandom,
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id'])//agregado
        ];
        $datos['dificultad'] = $this->model->obtenerDificultad($datos['data']['pregunta']['id_dificultad'])[0];
        $datos['categoria'] = $this->model->obtenerCategoria($datos['data']['pregunta']['id_categoria'])[0];
        return $datos;
    }

    public function contestar(){
        if (isset($_GET['tiempo_agotado']) && $_GET['tiempo_agotado']){ //SI SE AGOTA EL TIEMPO MANDAR AL HOME
            $datos['error'] = true;
            $datos['tiempo'] = "El tiempo se ha agotado tu puntaje es: ";
            $this->render->printViewSesion('partida', $datos);
        }
        $partida = $this->model->obtenerPartidaActual($_SESSION['usuario']['id']);
        $respuesta = $this->model->buscarRespuestaPorId($_GET['id']);


        $datos = $this->traerDatos();

            if(!$this->model->validarRecargoDePagina($partida[0]['id'], $respuesta['id'])){
                $this->model->agregarRespuestaALaPartida($partida[0],$_GET['id']);
                $this->actualizarPuntaje($partida[0], $respuesta);

                if(!$respuesta['esCorrecta'] == 1) {
                    $datos['error'] = true;
                    $datos['incorrecta'] = "La respuesta es incorrecta";
                    $this->render->printViewSesion('partida', $datos);
                }
            }else{
                $datos['error'] = true;
                $datos['recarga'] = "Si recargas la pagina perderas el progreso";
            }
        $this->render->printViewSesion('partida', $datos);
    }

    public function actualizarPuntaje($partida, $respuesta){
        $pregunta = $this->model->buscarPreguntaPorId($respuesta['id_pregunta']);
        $valorDePregunta = $this->model->obtenerDificultad($pregunta[0]['id_dificultad']);
        $valor = $valorDePregunta[0]['valor'];

        if($respuesta['esCorrecta'] == 1){
            $puntosPartida = $partida['puntos'] + $valor;
            $puntosUsuario = $_SESSION['usuario']['id'] + $valor;
            $this->model->sumarPuntos($partida, $puntosPartida, $puntosUsuario);
        }else if($respuesta['esCorrecta'] == 0){
            $puntosPartida = $partida['puntos'] - $valor;
            $puntosUsuario = $_SESSION['usuario']['id'] - $valor;
            $this->model->sumarPuntos($partida, $puntosPartida, $puntosUsuario);

        }
    }






}
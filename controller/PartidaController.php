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
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),//agregado
            'partida' => $this->model->obtenerPartidaActual($_SESSION['usuario']['id'])[0]

        ];
        $datos['dificultad'] = $this->model->obtenerDificultad($datos['data']['pregunta']['id_dificultad'])[0];
        $datos['categoria'] = $this->model->obtenerCategoria($datos['data']['pregunta']['id_categoria'])[0];
        return $datos;
    }

    public function contestar(){
        $partida = $this->model->obtenerPartidaActual($_SESSION['usuario']['id']);
        if (isset($_GET['tiempo_agotado']) && $_GET['tiempo_agotado']){ //SI SE AGOTA EL TIEMPO MANDAR AL HOME
            $datos['error'] = true;
            $datos['tiempo'] = "El tiempo se ha agotado tu puntaje es: " . $partida[0]['puntos'];
            $this->model->agregarRespuestaNulaALaPartida($partida[0]);
            $this->render->printViewSesion('partida', $datos);
        }

        $respuesta = $this->model->buscarRespuestaPorId($_GET['id']);



        $datos = $this->traerDatos();

        if(!$this->model->validarRecargoDePagina($partida[0]['id'], $respuesta['id'])){
            if($this->model->agregarRespuestaALaPartida($partida[0],$_GET['id'])) {
                $puntaje = $this->actualizarPuntaje($partida[0], $respuesta);


                if ($respuesta['esCorrecta'] == 0) {
                    $datos['error'] = true;
                    $datos['incorrecta'] = "La respuesta es incorrecta";
                    $datos['puntos'] = $puntaje;
                    $this->render->printViewSesion('partida', $datos);
                }
            }else{
                    $datos['error'] = true;
                    $datos['tiempo'] = "El tiempo se ha agotado tu puntaje es: ";
                    $this->model->agregarRespuestaNulaALaPartida($partida[0]);
                    $this->render->printViewSesion('partida', $datos);
            }
        }else{
            $this->model->agregarRespuestaNulaALaPartida($partida[0]);
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
            return  $this->model->sumarPuntos($partida, $valor);
        }else if($respuesta['esCorrecta'] == 0){
            return   $this->model->sumarPuntos($partida, 0);

        }
    }






}
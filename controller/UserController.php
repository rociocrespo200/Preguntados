<?php

class UserController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function login() {
        $this->render->printView('login');
    }

    public function signin() {
        $data = [];

        if (!empty($_SESSION['error'])) {
            $data["error"] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $data['action'] = '/user/procesarAlta';
        $data['submitText'] = 'Registrarme';

        $this->render->printView('registro', $data);

    }


    public function procesarAlta(){
        if( empty($_POST['usuario'] ) || empty($_POST['clave'] || empty($_POST['mail']))){
            $_SESSION["error"] = "Alguno de los campos era erroneo o vacio";
            Redirect::to('/user/signin');
        }
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $anioNacimiento = $_POST["anio_nacimiento"];
        $pais = $_POST["pais"];
        $ciudad = $_POST["ciudad"];
        $mail = $_POST["mail"];
        $usuario = $_POST["usuario"];
        $clave = $_POST['clave'];
        $clave2 = $_POST['clave2'];
        $fotoPerfil = $_POST['fotoPerfil'] ?? "error.jpg";

        if(!$this->model->validarUsuario($usuario) ||
            !$this->model->validarCorreo($mail) ||
            !$this->model->validarClave($clave)){
            $_SESSION["error"] = "Alguno de los campos era erroneo o vacio";
            Redirect::to('/user/signin');
        }

        if(!$this->model->compararClaves($clave,$clave2)){
            $_SESSION["error"] = "Las claves no coinciden";
            Redirect::to('/user/signin');
        }

        if(!$this->model->buscarUsuario($usuario)){
            $_SESSION["error"] = "El usuario ya existe";
            Redirect::to('/user/signin');
        }

        $this->model->alta($nombre, $apellido, $anioNacimiento, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil);

        Redirect::root();
    }



}
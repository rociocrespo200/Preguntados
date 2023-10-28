<?php

class UserController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function login() {
        $data = [];

        if (!empty($_SESSION['error'])) {
            $data["error"] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $data['action'] = '/user/procesarLogin';
        $data['submitText'] = 'Ingresar';

        $this->render->printView('login', $data);
    }

    public function procesarLogin() {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $_SESSION["error"] = "Debe completar todos los campos";
            Redirect::to('/user/login');
        }

        $usuario = $_POST["usuario"];
        $clave = $_POST['clave'];

        $usuarioBuscado = $this->model->get($usuario, $clave);

        if ($usuarioBuscado == null) {
            $_SESSION["error"] = "Usuario o contraseña incorrectos";
            Redirect::to('/user/login');
        }

        $_SESSION["usuario"] = $usuarioBuscado;

        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
            'preguntas' => $this->model->traerListaDePreguntas()
        ];

        if ($_SESSION['usuario']['id_rol'] == 2) $this->render->printViewEditor('homeEditor',$datos);
        else $this->render->printViewSesion('home',$datos);
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
        echo $_POST['nombre'];
        if( empty($_POST['usuario'] ) || empty($_POST['clave'] || empty($_POST['mail']))){
            $_SESSION["error"] = "Alguno de los campos era erroneo o vacio";
            Redirect::to('/user/signin');
        }

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $anioNacimiento = $_POST["anio_nacimiento"];
        $genero = $_POST["genero"];
        $pais = $_POST["pais"];
        $ciudad = $_POST["ciudad"];
        $mail = $_POST["mail"];
        $usuario = $_POST["usuario"];
        $clave = $_POST['clave'];
        $clave2 = $_POST['clave2'];

        if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) {
            move_uploaded_file($_FILES["fileInput"]["tmp_name"] , "./public/usuarios/" . $_FILES['fileInput']['name']);
            $fotoPerfil = $_FILES['fileInput']['name'];
        } else {
            $fotoPerfil =  "profile.png";
        }

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

        $this->model->alta($nombre, $apellido, $anioNacimiento, $genero, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil);

        Redirect::root();
    }


}
<?php

class ProfileController {
    private $render;
    private $model;

    public function __construct($render, $model) {
        $this->render = $render;
        $this->model = $model;
    }

    public function show() {
        $datos = [
            //'usuario' == $this->model->traerUsuario($_SESSION['usuario']['id']),
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel'],
            'user' => $_SESSION['usuario']
        ];
        $this->render->printViewSesion('perfil', $datos);
    }

    public function cerrarSesion(){
        session_destroy();
        Redirect::root();
    }

    public function verPerfil() {
        if (isset($_GET['id'])) {
            if($_GET['id']==$_SESSION['usuario']['id']){
                header('location:/profile/show');
            //$this->render->printViewSesion('perfil/show');
            }
            $idUsuario = $_GET['id'];

            if (ctype_digit($idUsuario) && $idUsuario > 0) {
                $usuario = $this->model->traerUsuario($idUsuario);

                if ($usuario) {
                    $data = [
                        'otroUser' => $usuario,
                        'usuario' => $_SESSION['usuario']['usuario'],
                        'usuarioPuntos' => $_SESSION['usuario']['puntos'],
                        'nivel' => $_SESSION['usuario']['nivel']
                    ];
                } else {
                    $data = ['error' => "El usuario no existe."];
                }
                $this->render->printViewSesion('perfil', $data);
            } else {
                $data = ['error' => "ID de usuario no proporcionado."];
                $this->render->printViewSesion('perfil', $data);
            }
        }
}

    public function modificarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['usuario']['id'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $anioNacimiento = $_POST['anio_nacimiento'];
            $pais = $_POST['pais'];
            $ciudad = $_POST['ciudad'];
            $mail = $_POST['mail'];
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];

           $fotoPerfil = $_SESSION['usuario']['foto_perfil']; // Mantén la foto de perfil actual si no se envía una nueva

            if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) {
                move_uploaded_file($_FILES["fileInput"]["tmp_name"], "./public/usuarios/" . $_FILES['fileInput']['name']);
                $fotoPerfil = $_FILES['fileInput']['name'];
            }

            // se necesita validaciones para modificar el usuario?
            //$_SESSION['usuario'] = $this->model->traerUsuario($id); // Actualizar datos de usuario en sesión
            $this->model->modificarUsuario($id,$nombre, $apellido, $anioNacimiento, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil);

            header('location:/profile/show');
        } else {
            // Manejar solicitud GET si es necesario
        }
    }
}
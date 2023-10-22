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
            'usuario' => $_SESSION['usuario']['usuario'],
            'usuarioPuntos' => $_SESSION['usuario']['puntos'],
            'nivel' => $_SESSION['usuario']['nivel']
        ];
        $this->render->printViewSesion('perfil', $datos);
    }

    public function cerrarSesion(){
        session_destroy();
        Redirect::root();
    }

    public function verPerfil() {
        if (isset($_GET['id'])) {
            $idUsuario = $_GET['id'];

            if (ctype_digit($idUsuario) && $idUsuario > 0) {
                $usuario = $this->model->traerUsuario($idUsuario);

                if ($usuario) {
                    $data = [
                        'user' => $usuario["usuario"],
                        'name' => $usuario["nombre"],
                        'surname' => $usuario["apellido"],
                        'points' => $usuario['puntos'],
                        'usuario' => $_SESSION['usuario']['usuario'],
                        'usuarioPuntos' => $_SESSION['usuario']['puntos'],
                        'nivel' => $_SESSION['usuario']['nivel']
                    ];
                } else {
                    $data = ['error' => "El usuario no existe."];
                }
                $this->render->printViewSesion('perfilUsuario', $data);
            } else {
                $data = ['error' => "ID de usuario no proporcionado."];
                $this->render->printViewSesion('perfilUsuario', $data);
            }
        }
}
}
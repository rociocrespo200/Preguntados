<?php
include('lib/full/qrlib.php');


use PHPMailer\PHPMailer\PHPMailer;

class UserController
{
    private $render;
    private $model;

    public function __construct($render, $model)
    {
        $this->render = $render;
        $this->model = $model;
    }

    public function login()
    {
        $data = [];

        if (!empty($_SESSION['error'])) {
            $data["error"] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $data['action'] = '/user/procesarLogin';
        $data['submitText'] = 'Ingresar';

        $this->render->printView('login', $data);
    }

    public function procesarLogin()
    {
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
        $_SESSION['preguntas'] = array();

        $datos = [
            'user' => $this->model->traerUsuario($_SESSION['usuario']['id']),
            'preguntas' => $this->model->traerListaDePreguntas()
        ];

        if ($_SESSION['usuario']['id_rol'] == 2) $this->render->printViewEditor('homeEditor', $datos);
        if ($_SESSION['usuario']['id_rol'] == 3) Redirect::to('/graficos/show');
        else $this->render->printViewSesion('home', $datos);
    }


    public function signin()
    {
        $data = [];

        if (!empty($_SESSION['error'])) {
            $data["error"] = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        $data['action'] = '/user/procesarAlta';
        $data['submitText'] = 'Registrarme';

        $this->render->printView('registro', $data);
    }


    public function procesarAlta()
    {
        if (empty($_POST['nombre']) || empty($_POST['usuario']) || empty($_POST['clave']) || empty($_POST['mail']) || empty($_POST['latitud']) || empty($_POST['longitud'])) {
            $_SESSION["error"] = "Todos los campos son obligatorios, incluyendo la ubicación en el mapa.";
            Redirect::to('/user/signin');
            return;
        }

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $anioNacimiento = $_POST["anio_nacimiento"];
        $genero = $_POST["genero"];
        $mail = $_POST["mail"];
        $usuario = $_POST["usuario"];
        $clave = $_POST['clave'];
        $clave2 = $_POST['clave2'];
        $token = $this->generateRandomToken();
        $latitud = $_POST["latitud"];
        $longitud = $_POST["longitud"];

        // Verificar si se han proporcionado latitud y longitud
        if (empty($latitud) || empty($longitud)) {
            $_SESSION["error"] = "Por favor, selecciona una ubicación en el mapa.";
            Redirect::to('/user/signin');
            return;
        }

        if (isset($_FILES["fileInput"]) && $_FILES["fileInput"]["error"] === UPLOAD_ERR_OK) {
            move_uploaded_file($_FILES["fileInput"]["tmp_name"], "./public/usuarios/" . $_FILES['fileInput']['name']);
            $fotoPerfil = $_FILES['fileInput']['name'];
        } else {
            $fotoPerfil = "profile.png";
        }


        if (!$this->model->validarUsuario($usuario) ||
            !$this->model->validarCorreo($mail) ||
            !$this->model->validarClave($clave)) {
            $_SESSION["error"] = "Alguno de los campos era erroneo o vacio";
            Redirect::to('/user/signin');
        }

        if (!$this->model->compararClaves($clave, $clave2)) {
            $_SESSION["error"] = "Las claves no coinciden";
            Redirect::to('/user/signin');
        }

        if (!$this->model->buscarUsuario($usuario)) {
            $_SESSION["error"] = "El usuario ya existe";
            Redirect::to('/user/signin');
        }
        $this->model->alta($nombre, $apellido, $anioNacimiento, $genero, $latitud, $mail, $longitud, $usuario, $clave, $fotoPerfil, $token);


        $idPerfil = $this->model->obtenerUltimoRegistrado();
        $fotoQR = $this->generarQR($idPerfil); // Llama a generarQR() para obtener el código QR
        $this->model->actualizarDatos($idPerfil, $fotoQR);
        //var_dump($fotoQR);
        //var_dump($idPerfil);
        Redirect::root();


    }

    public function enviarMailDeValidacion() {

        $mail = new PHPMailer(true);
        try {
            $token = $_SESSION['usuario']['token'];
            $from = "roccrespo@alumno.unlam.edu.ar";
            $to = $_SESSION['usuario']['mail'];
            $subject = "Checking PHP mail";
            $message = "Estimado usuario, haga clic en el siguiente enlace para validar su cuenta: http://localhost/user/validarTocken?token=" . $token;

            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'roccrespo@alumno.unlam.edu.ar'; // Reemplaza con tu dirección de correo electrónico de Outlook
            $mail->Password = ''; // Reemplaza con tu contraseña
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($from);
            $mail->addAddress($to);

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();


            $this->render->printViewSesion('perfil');
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }



    }

    public function generateRandomToken()
    {
        $length = 32;
        return bin2hex(random_bytes($length));

    }

    public function validarTocken()
    {
        if ($_SESSION['usuario']['token'] == $_GET['token']) {
            $this->model->validarMailUsuario($_SESSION['usuario']['id']);
        }

        Redirect::root();
    }

    public function generarQR($idPerfil) {
        $url = "/profile/verperfil?id=" . $idPerfil;
        $tamano = 8;
        $nivel_correccion = 'H';

        ob_start();
        QRcode::png($url, null, $nivel_correccion, $tamano);
        $foto_qr = ob_get_clean();

        $rutaQR = "codigo_qr_" . $idPerfil . ".png";
        file_put_contents("./public/qrs/".$rutaQR, $foto_qr);

        return $rutaQR;
    }

}

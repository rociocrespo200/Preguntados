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
        $token = $this->generateRandomToken();


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

        $this->model->alta($nombre, $apellido, $anioNacimiento, $genero, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil, $token);

        Redirect::root();


    }

    public function enviarMailDeValidacion() {

        $token = $_SESSION['usuario']['token'];
        $to_email = $_SESSION['usuario']['mail'];
        $subject = "Pregundatos: Validación de cuenta";
        $body = "Estimado usuario, haga clic en el siguiente enlace para validar su cuenta: http://localhost/user/validarTocken?token=' . $token";
        $headers = "From: sender\'s email";

        if (mail($to_email, $subject, $body, $headers)) {
            echo "Email successfully sent to $to_email...";
        } else {
            echo "Email sending failed...";
        }

    }

    public function generateRandomToken()
    {
        $length = 32;
        return bin2hex(random_bytes($length));

    }

    public function validarTocken(){
        if($_SESSION['usuario']['token'] == $_GET['token']){
            $this->model->validarMailUsuario($_GET['usuario']);
        }

        Redirect::root();
    }

//    public function enviarMailDeValidacion() {
//
////        $mail = new PHPMailer\PHPMailer\PHPMailer();
////
////        // Configura el servidor SMTP y las credenciales
////        $mail->isSMTP();
////        $mail->Host = 'smtp.gmail.com';
////        $mail->Port = 587; // Puerto estándar para TLS
////        $mail->SMTPSecure = 'tls'; // Habilitar encriptación TLS
////        $mail->SMTPAuth = true;
////        $mail->Username = 'matiasandreas200@gmail.com'; // Cambia a tu dirección de correo electrónico
////        $mail->Password = '46521541'; // Cambia a tu contraseña de correo electrónico
////
////        $token = $_SESSION['usuario']['token'];
////
////        // Configura el correo electrónico
////        $mail->setFrom('matiasandreas200@gmail.com', 'Remitente');
////        $mail->addAddress($_SESSION['usuario']['mail'], 'Destinatario');
////        $mail->Subject = 'Pregundatos: Validación de cuenta';
////        $mail->Body = 'Estimado usuario, haga clic en el siguiente enlace para validar su cuenta: http://localhost/user/validarTocken?token=' . $token ;
////        $mail->isHTML(true);
////
////        // Envía el correo electrónico
////        if ($mail->send()) {
////            echo 'El correo se ha enviado correctamente.';
////        } else {
////            echo 'Hubo un error al enviar el correo: ' . $mail->ErrorInfo;
////        }
//
//        $token = $_SESSION['usuario']['token'];
//        $to_email = $_SESSION['usuario']['mail'];
//        $subject = "Pregundatos: Validación de cuenta";
//        $body = "Estimado usuario, haga clic en el siguiente enlace para validar su cuenta: http://localhost/user/validarTocken?token=' . $token";
//        $headers = "From: sender\'s email";
//
//        if (mail($to_email, $subject, $body, $headers)) {
//            echo "Email successfully sent to $to_email...";
//        } else {
//            echo "Email sending failed...";
//        }
//
////        // Datos del formulario
////        $to = $_SESSION['usuario']['mail'];
////        $token = $_SESSION['usuario']['token'];
////        $subject = "Validación de cuenta";
////        $message = "Estimado usuario, haga clic en el siguiente enlace para validar su cuenta: http://localhost/user/validarTocken?token=" . $token ;
////
////        // Cabeceras
////        $headers = "From: remitente@example.com\r\n";
////        $headers .= "Reply-To: remitente@example.com\r\n";
////        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
////
////        // Envío del correo
////        $mail_sent = mail($to, $subject, $message, $headers);
////
////        // Verificación del estado del envío
////        if ($mail_sent) {
////            echo "El correo de validación se ha enviado correctamente a " . $to;
////        } else {
////            echo "Hubo un error al enviar el correo de validación.";
////        }
//    }
}
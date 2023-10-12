<?php
session_start();
include_once ("config/Configuracion.php");

$configuracion = new Configuracion();
$router = $configuracion->getRouter();

$controller = $_GET['controller'] ?? "home";
$method = $_GET['method'] ?? 'listar';

// Validar si la sesión NO es válida
if (!isset($_SESSION['usuario'])) {

    // Si la sesión no es válida, verifica si se solicita una vista de inicio de sesión o registro
    if ($controller === 'user' && in_array($method, ['login', 'signin' ,'procesarLogin'])) {
        // Si la vista es "user/login" o "user/signin," permite el acceso
        $router->route($controller, $method);
        exit;
    }

    // Si la sesión no es válida y no se solicita "user/login" o "user/signin," redirige a la página de inicio de sesión por defecto



   //descomentar esto
    $controller = "user";
    $method = "login";
}

// Si la sesión es válida, permite el acceso a la ruta deseada
$router->route($controller, $method);




//$router->route($controller, $method); //aca le mando el controlador y el metodo "ruta" al objeto router
                                      //ruta se encarga de hacer el get___Controler y accionar el método del controller
                                      //ambas cosas se arman por URL. Sino será por defecto

// ese get_____Controller crea una instancia de tal controllador, un model y se lo manda por parámetro para poder hacer todas las acciones
// y en simultaneo, el proximo metodo de la clase Router se encarga de accionar el método que ya está dentro del controlador previamente instanciado





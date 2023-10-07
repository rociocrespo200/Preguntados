<?php
session_start();
include_once ("config/Configuracion.php");

$configuracion = new Configuracion();
$router = $configuracion->getRouter(); //se crea una ruta default con getHomeController y el metodo show
                                       //return new Router($this,"getPokedexController","list");

$controller = $_GET['controller'] ?? "home"; //aca se ataja el primer parametro de la solicitud http, o sea al controller que iria
$method = $_GET['method'] ?? 'show'; //aca se ataja el metodo que usaría la solicitud

$router->route($controller, $method); //aca le mando el controlador y el metodo "ruta" al objeto router
                                      //ruta se encarga de hacer el get___Controler y accionar el método del controller
                                      //ambas cosas se arman por URL. Sino será por defecto

// ese get_____Controller crea una instancia de tal controllador, un model y se lo manda por parámetro para poder hacer todas las acciones
// y en simultaneo, el proximo metodo de la clase Router se encarga de accionar el método que ya está dentro del controlador previamente instanciado

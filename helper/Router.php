<?php

class Router {

    private $configuration;
    private $defaultController;
    private $defaultMethod;

    public function __construct($configuration, $defaultController, $defaultMethod) {
        $this->configuration = $configuration;
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    public function route($module, $method) { //recibe al controlador (module) y al metodo (puede ser listar u otro)
        $controller = $this->getControllerFrom($module); //aca este obtiene el controlador correspondiente al primer parametro
        $this->executeMethodFromController($controller,$method);  // se manda el controller y el metodo, pueden ser los de defecto
    }

    private function getControllerFrom($module) {
        $controllerName = 'get' . ucfirst($module) . 'Controller'; //se arma el nombre del controller
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController; //si existe lo carga, sino pone el de defecto

        return call_user_func(array($this->configuration, $validController));
        // Se llama al controlador correspondiente (por ejemplo getPokedexController qe es el default)
        //  en la configuración y se devuelve su resultado

    }

    private function executeMethodFromController($controller, $method) {
        // Se verifica si existe el método en tal controlador
        // Si existe, se utiliza el nombre del método; de lo contrario, se utiliza el método predeterminado
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        // Se llama al método en el controlador y acá se usa el pokedexController, se acciona en la clase configuracion

        call_user_func(array($controller, $validMethod)); //llama al controlador que le llega, le aplica el método que le llega previamente validado que exista aca arriba
    }

}
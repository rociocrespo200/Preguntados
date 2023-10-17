<?php
include_once('helper/Database.php');
include_once('helper/Render.php');
include_once('helper/MustacheRender.php');
include_once("helper/Router.php");
include_once("helper/Logger.php");
include_once('helper/Redirect.php');

include_once('controller/HomeController.php');
include_once('controller/UserController.php');
include_once ('controller/ProfileController.php');
include_once ('controller/PartidaController.php');
include_once ('controller/HistorialController.php');
include_once ('controller/RanckingController.php');
include_once("model/HomeModel.php");
include_once("model/RanckingModel.php");
include_once("model/HistorialModel.php");
include_once("model/UserModel.php");
include_once("model/ProfileModel.php");
include_once("model/PartidaModel.php");


include_once('third-party/mustache/src/Mustache/Autoloader.php');

class Configuracion {
    public function __construct() {
    }

    public function getDatabase() {
        $config = parse_ini_file('configuration.ini');
        $database = new Database(
            $config['servername'],
            $config['username'],
            $config['password'],
            $config['dbname']
        );
        return $database;
    }

    public function getRender() {
        //return new Render("view/header.php", "view/footer.php");
        return new MustacheRender();
    }


    public function getHomeController() {
        $model = new HomeModel($this->getDatabase());
        return new HomeController($this->getRender(), $model);
    }

    public function getUserController() {
        $model = new UserModel($this->getDatabase());
        return new UserController($this->getRender(), $model);
    }

    public function getProfileController() {
        $model = new ProfileModel($this->getDatabase());
        return new ProfileController($this->getRender(), $model);
    }

    public function getPartidaController() {
        $model = new PartidaModel($this->getDatabase());
        return new PartidaController($this->getRender(), $model);
    }

    public function getRanckingController() {
        $model = new RanckingModel($this->getDatabase());
        return new RanckingController($this->getRender(), $model);
    }

    public function getHistorialController() {
        $model = new HistorialModel($this->getDatabase());
        return new HistorialController($this->getRender(), $model);
    }
    public function getRouter() {
      //crea un objeto de tipo Ruta con un Controller y un metodo por defecto
        return new Router($this,"getHomeController","show");//show no sera mejor?
    }
}

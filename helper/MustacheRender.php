<?php

class MustacheRender {
    private $mustache;


    public function __construct() {
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
                'partials_loader' => new Mustache_Loader_FilesystemLoader(  "/")
            ));
    }

    public function printView($contenido, $datos = null) {
        echo  $this->generateHtml($contenido, $datos);
    }

    public function printViewSesion($contenido, $datos = null) {
        echo  $this->generateHtmlSesion($contenido, $datos);
    }

    public function printViewEditor($contenido, $datos = null) {
        echo  $this->generateHtmlEditor($contenido, $datos);
    }

    public function printViewAdmin($contenido, $datos = null) {
        echo  $this->generateHtmlAdmin($contenido, $datos);
    }
    public function generateHtml($contentFile, $data = array()) {//recien aca data es un array
        $contentAsString = file_get_contents('view/header.mustache');
        $contentAsString .= file_get_contents('view/' . $contentFile . "View.mustache");
        $contentAsString .= file_get_contents('view/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }

    public function generateHtmlSesion($contentFile, $data = array()) {

        $contentAsString = file_get_contents('view/header2.mustache');
        $contentAsString .= file_get_contents('view/' . $contentFile . "View.mustache");
        $contentAsString .= file_get_contents('view/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }

    public function generateHtmlEditor($contentFile, $data = array()) {

        $contentAsString = file_get_contents('view/headerEditor.mustache');
        $contentAsString .= file_get_contents('view/' . $contentFile . "View.mustache");
        $contentAsString .= file_get_contents('view/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }

    public function generateHtmlAdmin($contentFile, $data = array()) {

        $contentAsString = file_get_contents('view/headerAdmin.mustache');
        $contentAsString .= file_get_contents('view/' . $contentFile . "View.mustache");
        $contentAsString .= file_get_contents('view/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }
}

<?php

class UserModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function alta($nombre, $apellido, $anioNacimiento, $genero, $pais, $ciudad, $mail, $usuario, $clave, $fotoPerfil) {
        $sql = "INSERT INTO `usuario` (`nombre`, `apellido`, `anio_nacimiento`, `genero`, `pais`, `ciudad`, `mail`, `usuario`, `clave` ,`foto_perfil`) 
        VALUES ('$nombre', '$apellido', '$anioNacimiento', '$genero','$pais', '$ciudad', '$mail', '$usuario', '$clave', '$fotoPerfil')";
        Logger::info('Usuario alta: ' . $sql);
        $this->database->query($sql);
    }

    public function compararClaves($clave1, $clave2){
        return $clave1==$clave2;
    }

    function validarClave($clave1){
        $regex = '/^(?=.*[A-Z])(?=.*\d).{6,}$/';
        return (preg_match($regex, $clave1));
    }

    function validarUsuario($usuario){
        $regex = '/^\S{6,}$/';
        return (preg_match($regex, $usuario));
    }

    function validarCorreo($correo) {
        $patron = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        return (preg_match($patron, $correo));
    }

    function buscarUsuario($usuario){
        $query= "SELECT * FROM usuario WHERE usuario = '$usuario'";
        $resultado =  $this->database->query($query);
        if (empty($resultado)){
            return true;
        } else {
            return false;
        }
    }

    function validarIngreso($usuario, $clave){
        $query = "SELECT * FROM usuario WHERE usuario = '$usuario' AND clave = '$clave'";
        $resultado =  $this->database->query($query);

        if ($resultado !== false && $resultado->num_rows > 0) {
            print_r($resultado);
            return $resultado->fetch_object();
        } else {
            return null;
        }
    }

    public function get($usuario, $clave) {
        $result = $this->database->query("SELECT * FROM usuario WHERE usuario = '$usuario' AND clave = '$clave'");
        return $result[0];
    }


    public function traerUsuario($id){
        return $this->database->query("SELECT * FROM `usuario` WHERE id = $id")[0];
    }

    public function traerListaDePreguntas(){
        return $this->database->query("SELECT * FROM `pregunta`");
    }




}
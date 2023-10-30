

<?php
class SugerenciasModel
{

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function traerUsuario($id){
        return $this->database->query("SELECT * FROM `usuario` WHERE id = $id")[0];
    }

    public function agregarPreguntaSugerida($idUsuario, $pregunta, $categoria, $dificultad, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3)
    {
        $sql = "INSERT INTO sugerencia (id_usuario, pregunta, id_categoria, id_dificultad, respuestaCorrecta, respuestaIncorrecta1, respuestaIncorrecta2, respuestaIncorrecta3) VALUES ($idUsuario, '$pregunta', $categoria, $dificultad, '$respuestaCorrecta', '$respuestaIncorrecta1', '$respuestaIncorrecta2', '$respuestaIncorrecta3')";
        $this->database->query($sql);
    }

    public function agregarRespuestasSugerida($idSugerencia, $respuestaCorrecta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3)
    {
        $sql = "UPDATE sugerencia SET respuestaCorrecta = '$respuestaCorrecta', respuestaIncorrecta1 = '$respuestaIncorrecta1', respuestaIncorrecta2 = '$respuestaIncorrecta2', respuestaIncorrecta3 = '$respuestaIncorrecta3' WHERE id = $idSugerencia";
        $this->database->query($sql);
    }
//consultas, cada q se envie el formulario, enviar preguntas y respuestas 
//dividir los campos en dos 1 - los que son para crear la tabla pregunta y 2- los que son para crear la respuesta
// CONSULTA 1 (INSERT Pregunta) - $_POST['DIFICULTAD'] -$_POST['CATGEORIA'] - $_POST['DIFICULTAD']
// obtener el id de la ultima pregunta agregada
// CONSULTA 2 (INSERT Respuestas) - $_POST['respuestaCorrecta1'] -$idDeLaPreguntaObtenido- Atributo es corrercta si la pregunta es correcta es =1 si es incorrecta es = 0
}
<?php

    class Alumno Extends Controller {

        function __construct() {

            parent ::__construct(); 
            
            
        }

        function render() {

            $this->view->render('alumno/index');
        }

        /*
            Método:  delelte
            Descripción: Elimina un alumno
            Parámetros:  $id: Identificador del alumno a eliminar
        */
        function delete($param = []) {

            $id = $param[0];

            echo "Eliminar alumno con id: " . $id;

            exit();
        }

         /*
            Método:  show
            Descripción: Muestra los detalles de un alumno
            Parámetros:  $id: Identificador del alumno a eliminar
        */
        function show($param = []) {

            $id = $param[0];

            echo "Muestra alumno con id: " . $id;

            exit();
        }
    }

?>
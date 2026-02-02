<?php

    class Errores extends Controller {

        function __construct($mensaje_error = '') {

            parent ::__construct();
            $this->view->mensaje = $mensaje_error;
            $this->view->render('error/index');
        }

      

    }

?>
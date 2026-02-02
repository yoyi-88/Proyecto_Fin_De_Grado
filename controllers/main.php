<?php

    class Main Extends Controller {

        function __construct() {

            parent ::__construct(); 
            sec_session_start();
            
            
        }

        function render() {

            $this->view->render('main/index');
        }
    }

?>
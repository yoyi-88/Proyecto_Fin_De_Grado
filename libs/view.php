<?php

    class View {

        function __construct() {
   

        }

        function render($nombre) {

            //require 'views/' . $nombre . '.php';
            $this->view = $nombre;

            require 'template/layouts/main.layout.php';
        }

    } 

?>
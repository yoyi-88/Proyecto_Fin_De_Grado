<?php

    /*
        Definimos los privilegios de la aplicación

        Recordamos los perfiles:
        - 1: Administrador
        - 2: Editor
        - 3: Registrado

        Recordamos los controladores o recursos:
        - 1: Libro

        Los privilegios son:
        - 1: render
        - 2: new
        - 3: edit
        - 4: delete
        - 5: show
        - 6: order
        - 7: search

        Los perfiles se asignarán mediante un array asociativo, 
        donde la clave principal se corresponde con el controlador 
        la clave secundaria con el  método.

        $GLOBALS['libro']['main] = [1, 2, 3];

        Se asignan los perfiles que tienen acceso a un determinado método del controlador libro.

    */ 
    $GLOBALS['libro']['render'] = [1, 2, 3];
    $GLOBALS['libro']['new'] = [1, 2];
    $GLOBALS['libro']['edit'] = [1, 2];
    $GLOBALS['libro']['delete'] = [1];
    $GLOBALS['libro']['show'] = [1, 2, 3];
    $GLOBALS['libro']['search'] = [1, 2, 3];
    $GLOBALS['libro']['order'] = [1, 2, 3];
?>
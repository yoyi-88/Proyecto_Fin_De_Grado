<?php

    /*
        Privilegios para el controlador MENU (La Carta)
        -----------------------------------------------
        - Render: Todos pueden ver los menús (Chef y Clientes)
        - Gestión (New/Edit/Delete): Solo el Administrador (Chef)
    */
    $GLOBALS['menu']['render'] = [1, 2, 3];
    $GLOBALS['menu']['new']    = [1];
    $GLOBALS['menu']['create'] = [1];
    $GLOBALS['menu']['edit']   = [1];
    $GLOBALS['menu']['update'] = [1];
    $GLOBALS['menu']['delete'] = [1];
    $GLOBALS['menu']['show']   = [1, 2, 3]; 

    /*
        Privilegios para el controlador CITAS (Reservas)
        ------------------------------------------------    
        Render: 
            - Chef (1): Ve todas.
            - Cliente (3): Ve las suyas.
        New/Create: 
            - Cliente (3): Para reservar.
            - Chef (1): Por si quiere agendar manualmente por teléfono.
        Edit/Update (Cambiar estado): 
            - Solo Chef (1).
    */
    $GLOBALS['citas']['render'] = [1, 2, 3];
    $GLOBALS['citas']['new']    = [1, 3];
    $GLOBALS['citas']['create'] = [1, 3];
    $GLOBALS['citas']['edit']   = [1]; 
    $GLOBALS['citas']['update'] = [1];
    $GLOBALS['citas']['show']   = [1, 3]; // Ver detalle de una cita
?>
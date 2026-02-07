<?php
/*
    Clase: class_cita
    Descripción: Define la entidad Cita (Reserva)
*/
class class_cita {
    
    public $id;
    public $fecha;
    public $hora;
    public $estado;
    public $user_id;
    public $menu_id;

    // Propiedades extendidas (para mostrar datos de las tablas relacionadas)
    public $cliente_nombre;
    public $menu_nombre;
    public $menu_precio;

    public function __construct(
        $id = null,
        $fecha = null,
        $hora = null,
        $estado = 'Pendiente',
        $user_id = null,
        $menu_id = null
    ) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->estado = $estado;
        $this->user_id = $user_id;
        $this->menu_id = $menu_id;
    }
}
?>
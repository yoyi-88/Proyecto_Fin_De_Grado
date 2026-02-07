<?php
/*
    Clase: class_menu
    Descripción: Define la entidad Menú para mapear datos y formularios
*/
class class_menu {
    
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;

    public function __construct(
        $id = null,
        $nombre = null,
        $descripcion = null,
        $precio = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
    }
}
?>
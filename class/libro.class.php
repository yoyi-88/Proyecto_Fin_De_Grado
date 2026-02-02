<?php

class class_libro {
    public $id;
    public $titulo;
    public $autor_id;
    public $editorial_id;
    public $precio_venta;
    public $stock;
    public $fecha_edicion;
    public $isbn;
    public $temas; 


    public function __construct(
        $id = null,
        $titulo = null,
        $autor_id = null,
        $editorial_id = null,
        $precio_venta = null,
        $stock = null,
        $fecha_edicion = null,
        $isbn = null,
        $temas = null 
    )
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor_id = $autor_id;
        $this->editorial_id = $editorial_id;
        $this->precio_venta = $precio_venta;
        $this->stock = $stock;
        $this->fecha_edicion = $fecha_edicion;
        $this->isbn = $isbn;
        $this->temas = $temas; 
    }

}

?>
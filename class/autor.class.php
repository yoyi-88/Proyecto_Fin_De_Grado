<?php

class class_autor {
    public $id;
    public $nombre;
    public $nacionalidad;
    public $fecha_nac;
    public $email;
    public $premios;

    public function __construct(
        $id = null,
        $nombre = null,
        $nacionalidad = null,
        $fecha_nac = null,
        $email = null,
        $premios = null,
    )
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->nacionalidad = $nacionalidad;
        $this->fecha_nac = $fecha_nac;
        $this->email = $email;
        $this->premios = $premios;
    }

}

?>
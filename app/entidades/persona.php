<?php 

abstract class Persona{
    public $nombre;
    public $email;

protected function __construct(){
    $this->nombre = "";
    $this->email = "";
}

public abstract function funcAbst();

}

?>
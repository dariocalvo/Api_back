<?php

class Publicacion {
  
    public $id_publicación;
    public $id_usuario;
    public $id_rubro;
    public $fecha;
    public $titulo;
    public $contenido;
    public $pie;
    public $estado;
    public $control;
      
    public function GetId_publicación(){
        return $this->id_publicación;
    }

    public function GetId_usuario(){
        return $this->id_usuario;
    }

    public function GetId_rubro(){
        return $this->id_rubro;
    }

    public function GetFecha(){
        return $this->fecha;
    }

    public function GetTitulo(){
        return $this->titulo;
    }

    public function GetContenido(){
        return $this->contenido;
    }

    public function GetPie(){
        return $this->pie;
    }

    public function GetEstado(){
        return $this->estado;
    }

    public function GetControl(){
        return $this->control;
    }

    public function __construct(){
        $this->$id_publicación = "";
        $this->$id_usuario = "";
        $this->$id_rubro = "";
        $this->$fecha = "";
        $this->$titulo = "";
        $this->$contenido = "";
        $this->$pie = "";
        $this->$estado = "";
        $this->$control = "";
    }

    public function TraerPublicaciones($request, $response, $args){
        $AccesoDatos = ConeccionBD::conectar();
        $consulta = $AccesoDatos->sql("SELECT * FROM publicaciones");
        $consulta->execute();
        $response->getBody()->Write(json_encode($consulta->fetchAll(PDO::FETCH_CLASS)));
    return $response; 
    }
}
?>
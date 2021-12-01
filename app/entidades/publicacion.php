<?php

class Publicacion {
  
    public $id_publicación;
    public $id_usuario;
    public $id_rubro;
    public $fecha;
    public $fecha_edicion;
    public $titulo;
    public $imagen;
    public $contenido;
    public $pie;
    public $estado;
    public $control;
    public $ruta_img;
      
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

    public function GetFechaEdicion(){
        return $this->fecha_edicion;
    }

    public function GetTitulo(){
        return $this->titulo;
    }

    public function GetImagen(){
        return $this->imagen;
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

    public function GetRuta(){
        return $this->ruta_img;
    }

    public function __construct(){
        $this->id_publicación = "";
        $this->id_usuario = "";
        $this->id_rubro = "";
        $this->fecha = "";
        $this->fecha_edicion = "";
        $this->titulo = "";
        $this->imagen = "";
        $this->contenido = "";
        $this->pie = "";
        $this->estado = "";
        $this->control = "";
        $this->ruta_img = "img/uploads/publicaciones/";
    }


    public static function TraerPublicaciones($request, $response, $args){
        $AccesoDatos = ConeccionBD::conectar();
        $consulta = $AccesoDatos->sql("SELECT * FROM publicaciones");
        $consulta->execute();
        $response->getBody()->Write(json_encode($consulta->fetchAll(PDO::FETCH_CLASS)));
    return $response; 
    }

    public static function bloquearPub($id_publicacion, $autorizacion, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $bloqueo = $coneccion->sql("UPDATE publicaciones SET habilitada = 0, bloqueada_por =? WHERE id_publicacion =?");
            $bloqueo->execute([$autorizacion, $id_publicacion]);
            //$response->getBody()->Write("Publicacion bloqueada...");
        }catch(PDOException $e) {
            $response->getBody()->Write(json_encode("Error: " . $e->getMessage()));
        }
        return $response;
    }

    public static function editarBDEDI($Publicacion, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $ediciom = $coneccion->sql("UPDATE publicaciones SET fecha = current_timestamp, titulo =?, contenido= ?, pie =? WHERE id_publicacion =?");
            $ediciom->execute([$Publicacion->titulo, $Publicacion->contenido, $Publicacion->pie, $Publicacion->id_publicacion]);
            $response->getBody()->Write(json_encode('Publicacion editada con exito...'));
        }catch(PDOException $e) {
            $response->getBody()->Write(json_encode("Error: " . $e->getMessage()));
        }
        return $response;
    }

    public static function guardarBD($Publicacion, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $ingreso = $coneccion->sql("INSERT INTO publicaciones (id_usuario, id_rubro, titulo, contenido, pie) VALUES (?, ?, ?, ?, ?)");
            $ingreso->execute([$Publicacion->id_usuario, $Publicacion->id_rubro, $Publicacion->titulo, $Publicacion->contenido, $Publicacion->pie]);
            $response->getBody()->Write(json_encode('Has publicado con exito...'));
        }catch(PDOException $e) {
            $response->getBody()->Write(json_encode("Error: " . $e->getMessage()));
        }
        return $response;
    }


}
?>
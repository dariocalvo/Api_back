<?php

class Rubro {
  
    public $id_rubro;
    public $rubro;
      
    public function GetRubro(){
        return $this->rubro;
    }
    
    public function GetIdRubro(){
        return $this->id_rubro;
    }

    public function __construct(){
        $this->id_rubro = "";
        $this->rubro = "";
    }

    public function TraerRubros($request, $response, $args){
        $AccesoDatos = ConeccionBD::conectar();
        $consulta = $AccesoDatos->sql("SELECT * FROM rubros");
        $consulta->execute();
        $response->getBody()->Write(json_encode($consulta->fetchAll(PDO::FETCH_CLASS)));
    return $response; 
    }
}
?>

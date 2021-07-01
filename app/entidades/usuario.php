<?php

class Usuario extends Persona {
  
    public $usuario;
    public $pass;
    public $avatar;
      
    public function GetNombre(){
        return $this->nombre;
    }

    public function GetEmail(){
        return $this->email;
    }

    public function GetUsuario(){
        return $this->usuario;
    }

    public function GetPass(){
        return $this->pass;
    }

    public function GetAvatar(){
        return $this->avatar;
    }
    

    public function __construct(){
        parent::__construct();
        $this->usuario = "";
        $this->pass = "";
        $this->avatar = ""; 
    }

    public function funcAbst(){

    }

    
    public function guardar(){
        
    }


}
?>

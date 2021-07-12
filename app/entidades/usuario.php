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

    
    public function guardarBD($Usuario, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $ingreso = $coneccion->sql("INSERT INTO usuarios (nombre, email, username, password, imagen) VALUES (?, ?, ?, ?, ?)");
            $ingreso->execute([$Usuario->nombre, $Usuario->email, $Usuario->usuario, $Usuario->pass, $Usuario->avatar]);
            $response->getBody()->Write('Te has registrado con exito '.$Usuario->nombre.'.');
        }catch(PDOException $e) {
            $response->getBody()->Write("Error: " . $e->getMessage());
        }
        return $response;
    }

    public static function eliminarBD($Usuario, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $consulta = $coneccion->sql("DELETE FROM `usuarios` WHERE username =?");
            $consulta->execute([$Usuario]);
            $response->getBody()->Write("Tu cuenta ha sido dada de baja con exito!");
        }catch(PDOException $e) {
            $response->getBody()->Write("Error: " . $e->getMessage());
        }
        return $response;
    }

    public static function BuscarBD($request, $response, $args){
        
        $Recibido = $request->getParsedBody();
        $UsuarioAbuscar = filter_var(strtolower($Recibido['usuario']), FILTER_SANITIZE_STRING);
                
        try{
            $coneccion = ConeccionBD::conectar();
            //preparo consulta por usuario solo o usuario y contraseña acorde al request
            if (isset($Recibido['pass'])){
                $PassAbuscar = utilidades::encriptar($Recibido['pass']);
                $consulta = $coneccion->sql("SELECT * FROM `usuarios` WHERE username =? AND password =?");
                $consulta->execute([$UsuarioAbuscar, $PassAbuscar]);
                
            }else{
                $consulta = $coneccion->sql("SELECT * FROM `usuarios` WHERE username =?");
                $consulta->execute([$UsuarioAbuscar]);
                
            }
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            
            if($consulta -> rowCount() > 0){
                $response->getBody()->Write(json_encode($resultado));
            }
        }catch(PDOException $e) {
            $response->getBody()->Write("Error: " . $e->getMessage());
        }    
        return $response;
    }
}
?>

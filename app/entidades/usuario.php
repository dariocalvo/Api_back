<?php

class Usuario extends Persona {
  
    public $usuario;
    public $pass;
    public $passBy;
    public $avatar;
    public $privilegio;
    public $rutaImg;
      
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

    public function GetPassBy(){
        return $this->passBy;
    }

    public function GetAvatar(){
        return $this->avatar;
    }

    public function GetPrivilegio(){
        return $this->privilegio;
    }
    
    public function GetRuta(){
        return $this->rutaImg;
    }

    public function __construct(){
        parent::__construct();
        $this->usuario = "";
        $this->pass = "";
        $this->passBy = 1;
        $this->avatar = ""; 
        $this->privilegio = 3; 
        $this->rutaImg = 'img/uploads/usuarios/'; 
    }

    public function funcAbst(){

    }

    public function guardarBD($Usuario, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $ingreso = $coneccion->sql("INSERT INTO usuarios (nombre, email, username, password, generador_pass, imagen, permiso) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ingreso->execute([$Usuario->nombre, $Usuario->email, $Usuario->usuario, $Usuario->pass, $Usuario->passBy, $Usuario->avatar, $Usuario->privilegio]);
            $response->getBody()->Write("Registro exitoso de ".$Usuario->nombre.".\n");
        }catch(PDOException $e) {
            $response->getBody()->Write("Error: " . $e->getMessage());
        }
        return $response;
    }

    public function editarBD($Usuario, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            if (!empty($Usuario->pass)){
                $ingreso = $coneccion->sql("UPDATE usuarios SET nombre =?, email =?, password=?, imagen =? WHERE username =?");
                $ingreso->execute([$Usuario->nombre, $Usuario->email, $Usuario->pass, $Usuario->avatar, $Usuario->usuario]);
            }else{
                $ingreso = $coneccion->sql("UPDATE usuarios SET nombre =?, email =?, imagen =? WHERE username =?");
                $ingreso->execute([$Usuario->nombre, $Usuario->email, $Usuario->avatar, $Usuario->usuario]);
            }
                $response->getBody()->Write(json_encode('Tus datos han sido guardados '.$Usuario->nombre.'.'));
        }catch(PDOException $e) {
            $response->getBody()->Write(json_encode("Error: " . $e->getMessage()));
        }
        return $response;
    }

    public static function bajaBD($Usuario, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $consulta = $coneccion->sql("UPDATE `usuarios` SET activo = 0 WHERE username =?");
            $consulta->execute([$Usuario]);
            $response->getBody()->Write(json_encode("Tu cuenta ha sido dada de baja con exito!"));
        }catch(PDOException $e) {
            $response->getBody()->Write(json_encode("Error: " . $e->getMessage()));
        }
        return $response;
    }

    public static function eliminarBD($Usuario, $response){
        try{
            $coneccion = ConeccionBD::conectar();
            $consulta = $coneccion->sql("DELETE FROM `usuarios` WHERE id_usuario =?");
            $consulta->execute([$Usuario]);
            $response->getBody()->Write(json_encode("El usuario ha sido eliminado con exito!\n"));
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
                $consulta = $coneccion->sql("SELECT * FROM `usuarios` WHERE username =? AND password =? AND activo =1");
                $consulta->execute([$UsuarioAbuscar, $PassAbuscar]);
            }else{
                if (isset($Recibido['email'])){
                    $EmailAbuscar = $Recibido['email'];
                    $consulta = $coneccion->sql("SELECT * FROM `usuarios` WHERE username =? AND email =? AND activo =1");
                    $consulta->execute([$UsuarioAbuscar, $EmailAbuscar]);
                }else{
                    $consulta = $coneccion->sql("SELECT * FROM `usuarios` WHERE username =? AND activo =1");
                    $consulta->execute([$UsuarioAbuscar]);
                }
            }
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            if($consulta -> rowCount() > 0){
                $response->getBody()->Write(json_encode($resultado));
            }
        }catch(PDOException $e) {
            $response->getBody()->Write(json_encode("Error: " . $e->getMessage()));
        }    
        return $response;
    }

}
?>

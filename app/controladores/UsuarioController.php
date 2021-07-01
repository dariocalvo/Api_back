<?php

class UsuarioController{
/*Contenido
    Buscar usuario - recibe (usuario y pass o solo usuario) en FormData, devuelve datos del usuario
    Guardar usuario - recibe (datos) un FormData, crea registro, guarda imagen (utilidades:guardar imagen), devuelve estado de la accion
    Borrar usuario - recibe (usuario e imagen) en un FormData, borra registro e imagen (utilidades:borrar imagen)
*/

    public function BuscarUsuario($request, $response, $args){
        
        $Recibido = $request->getParsedBody();
        $UsuarioAbuscar = filter_var(strtolower($Recibido['usuario']), FILTER_SANITIZE_STRING);
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
        return $response;
    }


//Crear un registro con nuevo usuario 
    public function GuardarUsuario($request, $response, $args){

        $recibido = $request->getParsedBody();
        //opcion para guardar creando objeto y usando un metodo de la clase Usuario
        /*
        $NuevoUsuario = new Usuario();
        $NuevoUsuario->nombre = $recibido['nombre'];
        $NuevoUsuario->usuario = $recibido['usuario'];
        $NuevoUsuario->email = $recibido['email'];
        $NuevoUsuario->pass = utilidades::encriptar($recibido['pass']);
        if (!isset($recibido['avatar'])){
            $archivo =  $request->getUploadedFiles();
            $NuevoUsuario->imagen = utilidades::GuardarImagen($archivo);
        }else{
            $NuevoUsuario->avatar = "";
        }
        $NuevoUsuario->guardar(); //crear metodo en Usuario... todavia no esta creado para usar esta opcion
        */
        
        //opcion para guardar aca mismo
        $nombre = $recibido['nombre'];
        $usuario = $recibido['usuario'];
        $email = $recibido['email'];
        $pass = utilidades::encriptar($recibido['pass']);
            
        if (!isset($recibido['avatar'])){
            $archivo =  $request->getUploadedFiles()['avatar'];
            $imagen = utilidades::GuardarImagen($archivo, $usuario);
        }else{
            $imagen = "";
        }
        
        try{
            $coneccion = ConeccionBD::conectar();
            $ingreso = $coneccion->sql("INSERT INTO usuarios (nombre, email, username, password, imagen) VALUES (?, ?, ?, ?, ?)");
            $ingreso->execute([$nombre, $email, $usuario, $pass, $imagen]);
            $response->getBody()->Write('Te has registrado con exito '.$nombre.'.');
        }catch(PDOException $e) {
            $response->getBody()->Write("Error: " . $e->getMessage());
        }
        return $response;
    }

    public function BorrarUsuario($request, $response, $args){
        
        $Recibido = $request->getParsedBody();
        $UsuarioAbuscar = filter_var(strtolower($Recibido['usuario']), FILTER_SANITIZE_STRING);
        $imagen = $Recibido['imagen'];
        utilidades::BorrarImagen($imagen);
        $coneccion = ConeccionBD::conectar();
        $consulta = $coneccion->sql("DELETE FROM `usuarios` WHERE username =?");
        $consulta->execute([$UsuarioAbuscar]);
        $response->getBody()->Write("Tu cuenta ha sido dada de baja con exito!");
        return $response;
    }

}

?>
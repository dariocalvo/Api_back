<?php

class UsuarioController{
/*Contenido
    Guardar usuario - recibe (datos) FormData, crea objeto, llama a metodo guardarBD de clase Usuario, guarda imagen (utilidades:guardar imagen), devuelve estado de la accion
    Borrar usuario - recibe (usuario e imagen) en un FormData, llama a metodo borrarBD de clase Usuario y utilidades:borrar imagen
*/

    public function GuardarUsuario($request, $response, $args){

        $recibido = $request->getParsedBody();
        
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
        $NuevoUsuario->guardarBD($NuevoUsuario, $response); 
        return $response;
    
    }

    public function BorrarUsuario($request, $response, $args){
        
        $Recibido = $request->getParsedBody();
        $UsuarioAborrar = filter_var(strtolower($Recibido['usuario']), FILTER_SANITIZE_STRING);
        $imagen = $Recibido['imagen'];
        Usuario::eliminarBD($UsuarioAborrar, $response);
        utilidades::BorrarImagen($imagen);
        return $response;        
    }

    public function BuscarUsuario($request, $response, $args){
        Usuario::BuscarBD($request, $response, $args);
        return $response;        
    }

    public function BajaUsuario($request, $response, $args){
        $Recibido = $request->getParsedBody();
        $UsuarioBaja = filter_var(strtolower($Recibido['usuario']), FILTER_SANITIZE_STRING);
        Usuario::eliminarBD($UsuarioBaja, $response);
        return $response;        
    }
    

}

?>
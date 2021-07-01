<?php
    class Utilidades {
    /*Contenido
        LeerPost = Lee y devuelve con formato json los envios post del front
        Encriptar = devuelve un hash de lo que recibe
        GuardarImagen = recibe archivo, valida, renombra como el usuario, guarda y devuelve nombre
        Borrar imagen = recibe nombre de archivo, si existe lo borra     
    */    

        public static function LeerPost($request, $response, $args){
            
            $recibido = $request->getParsedBody();
            $response->getBody()->Write(json_encode($recibido));
            
            return $response;
        }

        public static function encriptar($texto){
            return hash('sha512', $texto);
        }


        public static function GuardarImagen($archivo, $usuario){
            $ruta = "uploads/img/usuarios/";
            $titulo = $archivo->getClientFilename();
            $ext = pathinfo($archivo->getClientFilename(), PATHINFO_EXTENSION);
            $tamano = $archivo->getSize();
            $tipo = $archivo->getClientMediaType();
            $imagen = $usuario.'.'.$ext;
            $destino = $ruta.$imagen;

            if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
                echo "Tipo o tamaño del archivo seleccionado como imagen no admitido.
                Se permiten archivos .gif, .jpg, .png. y de 200 kb como máximo.
                NO se guardo imagen de usuario. ";
            }else{
                $archivo->MoveTo($destino);
                return $imagen;
            }
        }

        public static function BorrarImagen($archivo){
            if (strlen($archivo) > 0){
                $imagen = "uploads/img/usuarios/".$archivo;
                unlink($imagen);
            }
        }    
    }
?>
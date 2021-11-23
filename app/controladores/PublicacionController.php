<?php

class PublicacionController{
/*Contenido
    Filtrado de publicaciones por rubro recibido
*/

    public function FiltrarTodasPorRubro($request, $response, $args){
        $Recibido = $request->getParsedBody();
        $Rubro = intval($Recibido['id_rubro']);
        $coneccion = ConeccionBD::conectar();
        $consulta = $coneccion->sql("SELECT * FROM publicaciones where Id_rubro =? AND habilitada = 1");
        $consulta->execute([$Rubro]);
        $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->Write(json_encode($resultado));
        return $response;
    }
    
    public function FiltrarRubros($request, $response, $args){
        
        $Recibido = $request->getParsedBody();
        $Rubro = intval($Recibido['id_rubro']);
        $coneccion = ConeccionBD::conectar();
        if ($Rubro <= 0){
            $consulta = $coneccion->sql
            ("SELECT `p`.`id_publicacion` AS `id_publicacion`,`p`.`titulo` AS `titulo`,`p`.`imagen` AS `imagen`,`p`.`tipo_imagen` AS `tipo_imagen`,`p`.`contenido` AS `contenido`,`p`.`pie` AS `pie`,`p`.`fecha` AS `fecha`,`p`.`habilitada` AS `habilitada`,`p`.`bloqueada_por` AS `bloqueada_por`,`u`.`id_usuario` AS `id_usuario`,`u`.`username` AS `username`,`u`.`permiso` AS `permiso`,`u`.`activo` AS `activo`,`r`.`id_rubro` AS `id_rubro`,`r`.`categoria` AS `categoria` from ((`publicaciones` `p` join `usuarios` `u`) join `rubros` `r`) where `p`.`id_usuario` = `u`.`id_usuario` and `p`.`id_rubro` = `r`.`id_rubro` and `u`.`activo` = 1 AND `p`.`habilitada` = 1 order by `p`.`fecha` desc" );
            $consulta->execute();
        }else{
            $consulta = $coneccion->sql
            ("SELECT `p`.`id_publicacion` AS `id_publicacion`,`p`.`titulo` AS `titulo`,`p`.`imagen` AS `imagen`,`p`.`tipo_imagen` AS `tipo_imagen`,`p`.`contenido` AS `contenido`,`p`.`pie` AS `pie`,`p`.`fecha` AS `fecha`,`p`.`habilitada` AS `habilitada`,`p`.`bloqueada_por` AS `bloqueada_por`,`u`.`id_usuario` AS `id_usuario`,`u`.`username` AS `username`,`u`.`permiso` AS `permiso`,`u`.`activo` AS `activo`,`r`.`id_rubro` AS `id_rubro`,`r`.`categoria` AS `categoria` from ((`publicaciones` `p` join `usuarios` `u`) join `rubros` `r`) where `p`.`id_usuario` = `u`.`id_usuario` and `p`.`id_rubro` = `r`.`id_rubro` and `u`.`activo` = 1 AND `p`.id_rubro =? AND `p`.`habilitada` = 1 order by `p`.`fecha` desc");
            $consulta->execute([$Rubro]);
        }
        $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->Write(json_encode($resultado));
  
        return $response;

    }

    public function GuardarPublicacion($request, $response, $args){
        $Recibido = $request->getParsedBody();
        $NuevaPublicacion = New Publicacion();
        $NuevaPublicacion->id_usuario = $Recibido['id_usuario'];
        $NuevaPublicacion->id_rubro = $Recibido['id_rubro'];
        $NuevaPublicacion->titulo = $Recibido['titulo'];
        $NuevaPublicacion->contenido = $Recibido['contenido'];
        $NuevaPublicacion->pie = $Recibido['pie'];
        
        if (!isset($Recibido['imagen'])){
            $archivo = $request->getUploadedFiles();
            $imagen = $archivo['imagen'];
            $ruta = $NuevaPublicacion->GetRuta();
            $nombre = $Recibido['id_usuario'].$Recibido['id_rubro'].implode(getdate());
            $NuevaPublicacion->imagen = utilidades::GuardarImagen($imagen, $nombre, $ruta);
        }else{
            $NuevaPublicacion->imagen = "";
        }
        $NuevaPublicacion->guardarBD($NuevaPublicacion, $response);
        return $response;
    }




    public function BloquearPublicacion($request, $response, $args){
        $Recibido = $request->getParsedBody();
        $Publicacion = intval($Recibido['id_publicacion']);
        $Autorizacion = intval($Recibido['autorizacion']);
        Publicacion::bloquearPub($Publicacion, $Autorizacion, $response);
        //$response = $this->FiltrarID($request, $response, $args);
        return $response;
    }   

    public function EditarPublicacionEDI($request, $response, $args){
        $Recibido = $request->getParsedBody();
        $PublicacionEditada = New Publicacion();
        $PublicacionEditada->id_publicacion = $Recibido['id_publicacion'];
        $PublicacionEditada->titulo = $Recibido['titulo'];
        $PublicacionEditada->contenido = $Recibido['contenido'];
        $PublicacionEditada->pie = $Recibido['pie'];
        $PublicacionEditada->EditarBDEDI($PublicacionEditada, $response);
        return $response; 
    }
}
?>
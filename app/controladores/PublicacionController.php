<?php

class PublicacionController{
/*Contenido
    Filtrado de publicaciones por rubro recibido
*/

    public function FiltrarRubros($request, $response, $args){
        
        $Recibido = $request->getParsedBody();
        $Rubro = intval($Recibido['id_rubro']);
        $coneccion = ConeccionBD::conectar();
        if ($Rubro <= 0){
            $consulta = $coneccion->sql
            ("SELECT `p`.`id_publicacion` AS `id_publicacion`,`p`.`titulo` AS `titulo`,`p`.`imagen` AS `imagen`,`p`.`tipo_imagen` AS `tipo_imagen`,`p`.`contenido` AS `contenido`,`p`.`pie` AS `pie`,`p`.`fecha` AS `fecha`,`p`.`habilitada` AS `habilitada`,`p`.`bloqueada_por` AS `bloqueada_por`,`u`.`id_usuario` AS `id_usuario`,`u`.`username` AS `username`,`u`.`permiso` AS `permiso`,`u`.`activo` AS `activo`,`r`.`id_rubro` AS `id_rubro`,`r`.`categoria` AS `categoria` from ((`publicaciones` `p` join `usuarios` `u`) join `rubros` `r`) where `p`.`id_usuario` = `u`.`id_usuario` and `p`.`id_rubro` = `r`.`id_rubro` and `u`.`activo` = 1 order by `p`.`fecha` desc");
            $consulta->execute();
        }else{
            $consulta = $coneccion->sql
            ("SELECT `p`.`id_publicacion` AS `id_publicacion`,`p`.`titulo` AS `titulo`,`p`.`imagen` AS `imagen`,`p`.`tipo_imagen` AS `tipo_imagen`,`p`.`contenido` AS `contenido`,`p`.`pie` AS `pie`,`p`.`fecha` AS `fecha`,`p`.`habilitada` AS `habilitada`,`p`.`bloqueada_por` AS `bloqueada_por`,`u`.`id_usuario` AS `id_usuario`,`u`.`username` AS `username`,`u`.`permiso` AS `permiso`,`u`.`activo` AS `activo`,`r`.`id_rubro` AS `id_rubro`,`r`.`categoria` AS `categoria` from ((`publicaciones` `p` join `usuarios` `u`) join `rubros` `r`) where `p`.`id_usuario` = `u`.`id_usuario` and `p`.`id_rubro` = `r`.`id_rubro` and `u`.`activo` = 1 AND `p`.id_rubro =? order by `p`.`fecha` desc");
            $consulta->execute([$Rubro]);
        }
        $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->Write(json_encode($resultado));
  
        return $response;

    }
}
?>
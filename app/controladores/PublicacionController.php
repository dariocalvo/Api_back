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
            $consulta = $coneccion->sql("SELECT * FROM `VW_publicaciones`");
            $consulta->execute();
        }else{
            $consulta = $coneccion->sql("SELECT * FROM `VW_publicaciones` WHERE id_rubro =?");
            $consulta->execute([$Rubro]);
        }
        $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
        $response->getBody()->Write(json_encode($resultado));
  
        return $response;

    }
}
?>
<?php

class RubroController{
/*Contenido
    Traer rubros para listado
*/

    public function ListarRubros($request, $response, $args){
        Rubro::TraerRubros($request, $response, $args);
        return $response;
    }
}
?>
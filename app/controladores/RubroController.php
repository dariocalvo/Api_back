<?php

class RubroController{
/*Contenido
    Traer rubros para listado
*/

    public function ListarRubros($request, $response, $args){
        $recibido = $request->getParsedBody();
        Rubro::TraerRubros;
        return $response;
    }
}
?>
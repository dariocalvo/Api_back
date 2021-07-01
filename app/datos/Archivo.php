<?php
    class Archivos{

        public static function leerArchivo($ruta){

            $mostrar = '';
            try{
                $archivo = fopen ($archivo, 'r');
                while (!feof($archivo)){
                    $mostrar = fgets($archivo);
                }
                fclose($archivo);
            }
            catch (Exception $e) {
                $mostrar = "Error: " . $e->getMessage();
            }
            return $mostrar;
        }

        public static function EscribirArchivo($ruta){

            
            return $mostrar;
        }
    }

?>
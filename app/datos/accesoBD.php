<?php

class ConeccionBD
{

    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
    
        try {
            $this->objetoPDO = new PDO('mysql:host='.getenv('ServidorMySQL').';dbname='.getenv('Database').';charset=utf8', getenv("Usuario"), getenv('Pass'), array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
            die();
        }

    }  

    public static function conectar(){
        if (!isset(self::$objAccesoDatos)) {
            self::$objAccesoDatos = new ConeccionBD();
        }
        return self::$objAccesoDatos;
    }

    public function sql($sql){
        return $this->objetoPDO->prepare($sql);
    }

    public function obtenerUltimoId(){
        return $this->objetoPDO->lastInsertId();
    }

    public function __clone(){
        trigger_error('ERROR: La clonación de este objeto no está permitida', E_USER_ERROR);
    }
    
} 

?>
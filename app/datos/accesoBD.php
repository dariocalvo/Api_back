<?php

class ConeccionBD
{

    private $servidor;
    private $nombre_BD;
    private $user;
    private $password;
    private static $objAccesoDatos;
    private $objetoPDO;

    private function __construct()
    {
    /*
        $this->servidor = "localhost";
        $this->nombre_BD = "sistema_calvo";
        $this->user = "root";
        $this->password = "";
    */  
    
        $this->servidor = "remotemysql.com";
        $this->nombre_BD = "qdg8wnFsb4";
        $this->user = "qdg8wnFsb4";
        $this->password = "NZN0iuhEJJ";
    
        try {
            $this->objetoPDO = new PDO('mysql:host='.$this->servidor.';dbname='.$this->nombre_BD.';charset=utf8',$this->user,$this->password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        //    $this->objetoPDO->exec("SET CHARACTER SET utf8");
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
<?php 

require_once 'conexion.php';

class Producto {
    private $conexion; //variable privada que se usa para la conexión a la base de datos
    private $tabla = 'productos'; //variable privada que se usa para almacenar el nombre de la tabla

    //Método constructor para inicializar la conexión a la base de datos
    public function __construct(){ 

        $this->conexion = Conexion::conexionBBDD();
    }

   
    public function obtenerTodos($tipo=null){
        
    }
}
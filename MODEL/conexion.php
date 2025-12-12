<?php

/** con MSQLi
 * class conexion {
 *
 *  //Método para conectar a la base de datos
 * public static function conexionBBDD() {
 *    //Datos de conexión a la base de datos
 *  $datos = [
 *      "host" => "localhost",
 *      "bbdd" => "u393433014_circulosatenea",
 *      "user" => "u393433014_adminatenea",
 *      "pswd" => "tP2?P5M4Xo"
 *  ];

 *  Crear la conexión mediante mysqli
 * $conexion = new mysqli(
 *    $datos['host'],
 *    $datos['user'],
 *    $datos['pswd'],
 *    $datos['bbdd']
 *  );

 *  Comprobar si hay errores de conexión en caso de error, mostrar mensaje y finalizar
 *  if ($conexion->connect_error){
 *   die("Error de conexión en a la base de datos:" . $conexion->connect_error);
 *  }
 *  return $conexion;
 * }
 */

// Clase para manejar la conexión a la base de datos usando PDO
class Conexion {
    
    // Método para conectar a la base de datos usando PDO
    public static function conexionBBDD() {
        // Datos de conexión a la base de datos
        $datos = [
            "host" => "localhost",
            "bbdd" => "u393433014_circulosatenea",
            "user" => "u393433014_adminatenea",
            "pswd" => "tP2?P5M4Xo",
            "charset" => "utf8mb4"// Agregado para definir el conjunto de caracteres en español
        ];

        try {
            // Crear la conexión mediante PDO
            $conexion = new PDO(
                "mysql:host=" . $datos['host'] . ";dbname=" . $datos['bbdd'],
                $datos['user'],
                $datos['pswd'],
                [ //VISTO EN APUNTES 
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Configura el modo de error de PDO a excepción
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Configura el modo de obtención de resultados a arreglo asociativo
                    PDO::ATTR_EMULATE_PREPARES => false // Desactiva la emulación de sentencias preparadas
                    
                ]
            );
            return $conexion;

        } catch (PDOException $e) {
            // En caso de error, mostrar mensaje y finalizar
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
<?php 
require_once '../conexion.php';

class User {
    private $conexion; //variable privada que se usa para la conexión a la base de datos
    private $tabla = 'users'; //variable privada que se usa para almacenar el nombre de la tabla

    //Método constructor para inicializar la conexión a la base de datos
    public function __construct(){ 

        $this->conexion = Conexion::conexionBBDD();
    }

    //Método para el login de usuario
    public function login($email,$password){

        //Sentencia sql para que se busque al usuario introducido 
        $sql = "SELECT user_id, nombre, apellidos, email, password_hash, rol FROM {$this->tabla} WHERE email :email";

        //
        $stmt = $this->conexion->prepare($sql);
    }

    //Método para el registro de usuario
    public function register ($data){

        if($this->emailExists($data['email'])){
            return ['success' => false, 'message' => 'El correo electrónico ya está registrado.'];

        }

        $sql = "INSERT INTO {$this->tabla} (nombre, apellidos, email, password_hash, fecha_nacimiento, telefono, genero, rol) VALUES (:nombre, :apellidos, :email, :password_hash, :fecha_nacimiento, :telefono, :genero, 'cliente')";

        try{
            // Preparar la sentencia SQL 
            $stmt = $this->conexion->prepare($sql);

            // Vincular los parámetros
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':apellidos', $data['apellidos']);
            $stmt->bindParam(':email', $data['email']);
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password_hash', $passwordHash);
            $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
            $stmt->bindParam(':telefono', $data['telefono']);
            $stmt->bindParam(':genero', $data['genero']);

            $stmt->execute();

            return ['success' => true, 'message' => 'Registro exitoso.'];

        } catch (PDOException $e){
            return ['success' => false, 'message' => 'Error en el registro: ' . $e->getMessage()];
        }
    }


    //Método para comprobar si el email ya existe en la base de datos
    public function emailExists($email){
        $sql = "SELECT COUNT(*) FROM {$this->tabla} WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    //Método para obtener los datos del usuario por su email
    public function getUserByEmail($email){
        $sql = "SELECT user_id, nombre, apellidos, email, password_hash, rol FROM {$this->tabla} WHERE email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
}
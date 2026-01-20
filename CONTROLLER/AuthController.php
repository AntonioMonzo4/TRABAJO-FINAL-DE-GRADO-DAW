<?php
// CONTROLLER/authController.php
// Controlador para manejo de autenticación (login, registro, logout)
session_start(); // Iniciamos sesión para manejar datos de usuario

require_once dirname(__DIR__) . '/MODEL/conexion.php'; // Incluimos la conexión a la base de datos usamos PDO y dirname(__DIR__) para ir a la carpeta padre por fallos de ruta


// Controlador de autenticación
class AuthController
{


    //Función para manejar el login de usuarios
    public static function login()
    {
        // Solo procesamos si es una petición POST para mayor seguridad
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Redirigimos si no es POST
            header('Location: /login');
            exit;
        }

        // Recogemos y saneamos(trim que elimina espacios en blanco al inicio y al final) datos del formulario 
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas
        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = 'Debes introducir email y contraseña.';
            header('Location: /login');
            exit;
        }

        // Intentamos autenticar al usuario consultando la base de datos
        try {

            // Conexión a la base de datos usando PDO de la clase conexion
            $pdo = conexion::conexionBBDD();

            // Preparar y ejecutar la consulta para obtener el usuario por email
            $stmt = $pdo->prepare(
                "SELECT user_id, nombre, apellidos, email, password_hash, rol 
                 FROM users 
                 WHERE email = :email"
            );

            // Ejecutamos la consulta
            $stmt->execute([':email' => $email]);
            // Obtenemos el usuario
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificamos si el usuario existe y la contraseña es correcta mediante password_verify y hash que se guarda en la base de datos
            if (!$user || !password_verify($password, $user['password_hash'])) {
                $_SESSION['login_error'] = 'Email o contraseña incorrectos.';
                header('Location: /login');
                exit;
            }

            // Login correcto: guardar datos mínimos en sesión
            $_SESSION['usuario'] = [
                'id'       => $user['user_id'],
                'nombre'   => $user['nombre'],
                'apellidos' => $user['apellidos'],
                'email'    => $user['email'],
                'rol'      => $user['rol'],
            ];

            // Limpio posible mensaje de error previo
            unset($_SESSION['login_error']);

            header('Location: /home');
            exit;
        } catch (PDOException $e) {

            // En entorno real: log y mensaje genérico
            error_log('Error login: ' . $e->getMessage());
            $_SESSION['login_error'] = 'Error interno. Inténtalo más tarde.';
            header('Location: /login');
            exit;
        }
    }

    //Función para manejar el logout de usuarios
    public static function logout()
    {

        // Destruir la sesión y redirigir a la página de inicio
        session_start();
        session_unset();
        session_destroy();
        header('Location: /home');
        exit;
    }

    //Función para manejar el registro de nuevos usuarios
    public static function register()
    {
        // Solo procesamos si es una petición POST para mayor seguridad
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }


        // Recogemos y saneamos(trim que elimina espacios en blanco al inicio y al final) datos del formulario
        $nombre           = trim($_POST['nombre'] ?? '');
        $apellidos        = trim($_POST['apellidos'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
        $telefono         = trim($_POST['telefono'] ?? '');
        $genero           = $_POST['genero'] ?? null;
        $password         = $_POST['password'] ?? '';
        $password2        = $_POST['password2'] ?? '';

        $errores = [];

        // Validaciones básicas
        if ($nombre === '')  $errores[] = 'El nombre es obligatorio.';
        if ($email === '')   $errores[] = 'El email es obligatorio.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no tiene un formato válido.';
        }
        if ($password === '' || $password2 === '') {
            $errores[] = 'Debes introducir y confirmar la contraseña.';
        } elseif ($password !== $password2) {
            $errores[] = 'Las contraseñas no coinciden.';
        } elseif (strlen($password) < 6) {
            $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        // Si hay errores, redirigimos de vuelta al formulario con mensajes
        if (!empty($errores)) {
            $_SESSION['register_errors'] = $errores;
            // Guardamos también datos para repoblar el formulario
            $_SESSION['register_old'] = [
                'nombre'           => $nombre,
                'apellidos'        => $apellidos,
                'email'            => $email,
                'fecha_nacimiento' => $fecha_nacimiento,
                'telefono'         => $telefono,
                'genero'           => $genero,
            ];
            header('Location: /register');
            exit;
        }

        // Intentamos registrar al usuario en la base de datos
        try {
            $pdo = conexion::conexionBBDD();

            // Comprobar si ya existe un usuario con ese email
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);


            if ($stmt->fetch()) {
                $_SESSION['register_errors'] = ['Ya existe un usuario con ese email.'];
                $_SESSION['register_old'] = [
                    'nombre'           => $nombre,
                    'apellidos'        => $apellidos,
                    'email'            => $email,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'telefono'         => $telefono,
                    'genero'           => $genero,
                ];
                header('Location: /register');
                exit;
            }

            // Insertar usuario nuevo en la base de datos con contraseña hasheada usando password_hash para mayor seguridad 
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Preparar la inserción usando sentencias preparadas para evitar inyección SQL
            $stmt = $pdo->prepare(
                "INSERT INTO users 
                (nombre, apellidos, email, fecha_nacimiento, telefono, genero, password_hash, rol) 
                VALUES 
                (:nombre, :apellidos, :email, :fecha_nacimiento, :telefono, :genero, :password_hash, :rol)"
            );

            // Ejecutar la inserción con los datos proporcionados por el usuario
            $stmt->execute([
                ':nombre'           => $nombre,
                ':apellidos'        => $apellidos,
                ':email'            => $email,
                ':fecha_nacimiento' => $fecha_nacimiento ?: null,
                ':telefono'         => $telefono ?: null,
                ':genero'           => $genero ?: null,
                ':password_hash'    => $password_hash,
                ':rol'              => 'cliente',
            ]);

            // Opcional: loguear directamente tras registro
            $user_id = $pdo->lastInsertId();
            $_SESSION['usuario'] = [
                'id'       => $user_id,
                'nombre'   => $nombre,
                'apellidos' => $apellidos,
                'email'    => $email,
                'rol'      => 'cliente',
            ];

            unset($_SESSION['register_errors'], $_SESSION['register_old']);

            header('Location: /home');
            exit;
        } catch (PDOException $e) {
            error_log('Error registro: ' . $e->getMessage());
            $_SESSION['register_errors'] = ['Error interno. Inténtalo más tarde.'];
            header('Location: /register');
            exit;
        }
    }
    public static function actualizarPerfil()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario'])) {
            header("Location: /login");
            exit;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header("Location: /perfil");
            exit;
        }

        require_once __DIR__ . '/../MODEL/conexion.php';
        $pdo = conexion::conexionBBDD();

        $userId = (int)($_SESSION['usuario']['id'] ?? 0);
        if ($userId <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Sesión inválida.'];
            header("Location: /perfil");
            exit;
        }

        $nombre = trim((string)($_POST['nombre'] ?? ''));
        $apellidos = trim((string)($_POST['apellidos'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $telefono = trim((string)($_POST['telefono'] ?? ''));
        $genero = trim((string)($_POST['genero'] ?? ''));
        $fecha = trim((string)($_POST['fecha_nacimiento'] ?? ''));

        if ($nombre === '' || $email === '') {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Nombre y email son obligatorios.'];
            header("Location: /perfil");
            exit;
        }

        // Validación mínima email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Email no válido.'];
            header("Location: /perfil");
            exit;
        }

        // Evitar colisión de email con otro usuario
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email AND user_id <> :id LIMIT 1");
        $stmt->execute([':email' => $email, ':id' => $userId]);
        if ($stmt->fetch()) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Ese email ya está en uso.'];
            header("Location: /perfil");
            exit;
        }

        // Actualiza datos (NO tocamos rol)
        $stmt = $pdo->prepare("
        UPDATE users
        SET nombre = :nombre,
            apellidos = :apellidos,
            email = :email,
            telefono = :telefono,
            genero = :genero,
            fecha_nacimiento = :fecha
        WHERE user_id = :id
    ");
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':telefono' => $telefono,
            ':genero' => $genero,
            ':fecha' => ($fecha === '' ? null : $fecha),
            ':id' => $userId
        ]);

        // Cambio de contraseña (opcional)
        $p1 = (string)($_POST['password_nueva'] ?? '');
        $p2 = (string)($_POST['password_nueva_2'] ?? '');
        if ($p1 !== '' || $p2 !== '') {
            if ($p1 !== $p2) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Las contraseñas no coinciden.'];
                header("Location: /perfil");
                exit;
            }
            if (strlen($p1) < 6) {
                $_SESSION['flash'] = ['type' => 'error', 'msg' => 'La contraseña debe tener al menos 6 caracteres.'];
                header("Location: /perfil");
                exit;
            }

            $hash = password_hash($p1, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = :h WHERE user_id = :id");
            $stmt->execute([':h' => $hash, ':id' => $userId]);
        }

        // Refresca sesión (sin rol visible/editable, pero lo conservamos)
        $_SESSION['usuario']['nombre'] = $nombre;
        $_SESSION['usuario']['apellidos'] = $apellidos;
        $_SESSION['usuario']['email'] = $email;
        $_SESSION['usuario']['telefono'] = $telefono;
        $_SESSION['usuario']['genero'] = $genero;
        $_SESSION['usuario']['fecha_nacimiento'] = ($fecha === '' ? null : $fecha);

        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Perfil actualizado correctamente.'];
        header("Location: /perfil");
        exit;
    }
}

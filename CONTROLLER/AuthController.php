<?php
require_once __DIR__ . '/../MODEL/conexion.php';

class AuthController
{
    public static function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $nombre   = trim((string)($_POST['nombre'] ?? ''));
        $apellidos = trim((string)($_POST['apellidos'] ?? ''));
        $email    = strtolower(trim((string)($_POST['email'] ?? '')));
        $telefono = trim((string)($_POST['telefono'] ?? ''));
        $fecha    = trim((string)($_POST['fecha_nacimiento'] ?? ''));
        $genero   = trim((string)($_POST['genero'] ?? ''));
        $pass1    = (string)($_POST['password'] ?? '');
        $pass2    = (string)($_POST['password2'] ?? '');

        $errores = [];

        // Nombre: solo letras y espacios
        if ($nombre === '' || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,100}$/u', $nombre)) {
            $errores[] = "El nombre solo puede contener letras y espacios (2-100).";
        }

        // Apellidos opcional
        if ($apellidos !== '' && !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]{2,150}$/u', $apellidos)) {
            $errores[] = "Los apellidos solo pueden contener letras y espacios (2-150).";
        }

        // Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
            $errores[] = "El email no es válido.";
        }

        // Teléfono opcional
        if ($telefono !== '' && !preg_match('/^[0-9+\-\s]{6,20}$/', $telefono)) {
            $errores[] = "El teléfono no es válido.";
        }

        // Fecha opcional (formato)
        if ($fecha !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            $errores[] = "La fecha debe tener formato YYYY-MM-DD.";
        }

        // Género opcional (enum)
        $validos = ['Hombre', 'Mujer', 'No especifica', 'Otro', ''];
        if (!in_array($genero, $validos, true)) {
            $errores[] = "Género inválido.";
        }

        // Password fuerte
        $regexFuerte = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{10,72}$/';
        if (!preg_match($regexFuerte, $pass1)) {
            $errores[] = "La contraseña debe tener mínimo 10 caracteres e incluir mayúscula, minúscula, número y símbolo.";
        }

        if ($pass1 !== $pass2) {
            $errores[] = "Las contraseñas no coinciden.";
        }

        if ($errores) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => implode(' ', $errores)];
            header("Location: /register");
            exit;
        }

        $pdo = conexion::conexionBBDD();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Email único
        $st = $pdo->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
        $st->execute([':email' => $email]);
        if ($st->fetch()) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Ese email ya está registrado.'];
            header("Location: /register");
            exit;
        }

        $hash = password_hash($pass1, PASSWORD_DEFAULT);

        $ins = $pdo->prepare("
            INSERT INTO users (nombre, apellidos, email, fecha_nacimiento, telefono, genero, password_hash, rol)
            VALUES (:nombre, :apellidos, :email, :fecha, :telefono, :genero, :hash, 'cliente')
        ");

        $ins->execute([
            ':nombre'   => $nombre,
            ':apellidos' => ($apellidos !== '' ? $apellidos : null),
            ':email'    => $email,
            ':fecha'    => ($fecha !== '' ? $fecha : null),
            ':telefono' => ($telefono !== '' ? $telefono : null),
            ':genero'   => ($genero !== '' ? $genero : null),
            ':hash'     => $hash,
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Cuenta creada. Ya puedes iniciar sesión.'];
        header("Location: /login");
        exit;
    }

    public static function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $pass  = (string)($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '') {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Email o contraseña inválidos'];
            header("Location: /login");
            exit;
        }

        $pdo = conexion::conexionBBDD();
        $st = $pdo->prepare("SELECT user_id, nombre, email, password_hash, rol FROM users WHERE email = :email LIMIT 1");
        $st->execute([':email' => $email]);
        $u = $st->fetch(PDO::FETCH_ASSOC);

        if (!$u || !password_verify($pass, $u['password_hash'])) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Credenciales incorrectas'];
            header("Location: /login");
            exit;
        }

        // Sesión
        $_SESSION['user'] = [
            'id'    => (int)$u['user_id'],
            'nombre' => $u['nombre'],
            'email' => $u['email'],
            'rol'   => $u['rol'], // <-- CLAVE: aquí entra 'admin'
        ];

        // Redirección según rol
        if ($u['rol'] === 'admin') {
            header("Location: /admin");
        } else {
            header("Location: /home");
        }
        exit;
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: /home");
        exit;
    }
}

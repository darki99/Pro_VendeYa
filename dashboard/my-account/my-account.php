<?php
session_start();
include '../../authdb/conexion.php';
if (!isset($_SESSION['nombre'])) {
    header('Location: /VendeYa/authdb/login.php');
    exit;
}

$nombre = $_SESSION['nombre'];
$email = $_SESSION['email'];
$password = $_SESSION['password'] ?? '********';
$perfil = 'Usuario'; // valor por defecto si no se encuentra

if (!empty($email)) {
    $stmt = $conn->prepare("SELECT p.nombre FROM usuarios u JOIN perfil p ON u.id_perfil = p.id WHERE u.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($perfil);
    $stmt->fetch();
    $stmt->close();
}

// Cargar plantilla HTML
$ruta = __DIR__ . '/../resources/my-account.html';

if (file_exists($ruta)) {
    $html = file_get_contents($ruta);
    $html = str_replace(
        ['{{nombre}}', '{{email}}', '{{password}}', '{{perfil}}'],
        [$nombre, $email, $password, strtoupper($perfil)],
        $html
    );
    echo $html;
} else {
    echo "❌ No se encontró la plantilla de diseño en: <br>$ruta";
}

<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header('Location: /VendeYa/authdb/login.php');
    exit;
}

$nombre = $_SESSION['nombre'];
$email = $_SESSION['email'];
$password = $_SESSION['password'] ?? '********'; // Asegúrate de asignar la clave si la tienes

$ruta = __DIR__ . '/../resources/my-account.html';

if (file_exists($ruta)) {
    $html = file_get_contents($ruta);
    $html = str_replace(
        ['{{nombre}}', '{{email}}', '{{password}}'],
        [$nombre, $email, $password],
        $html
    );
    echo $html;
} else {
    echo "❌ No se encontró la plantilla de diseño en: <br>$ruta";
}

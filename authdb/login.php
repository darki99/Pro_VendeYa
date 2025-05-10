<?php
include 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, nombre, email, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $nombre, $email_bd, $hash);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            $_SESSION['nombre'] = $nombre;
            $_SESSION['email'] = $email_bd;
            header("Location: /VendeYa/dashboard/home/index.php");
            exit;
        } else {
            echo "❌ Contraseña incorrecta.";
        }
    } else {
        echo "❌ Usuario no encontrado.";
    }
} else {
    include '../authFront/html/login.html';
}

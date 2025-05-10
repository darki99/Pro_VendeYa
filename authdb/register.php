<?php
include 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function contieneCaracterEspecial($str) {
    return preg_match('/[^a-zA-Z0-9]/', $str);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ El correo no es válido.";
        exit;
    }

    if (strlen($password) < 8 || strlen($password) > 25) {
        echo "❌ La contraseña debe tener entre 8 y 25 caracteres.";
        exit;
    }

    if (!contieneCaracterEspecial($password)) {
        echo "❌ La contraseña debe contener al menos un carácter especial.";
        exit;
    }

    if (!preg_match('/[A-Z]/', $password)) {
        echo "❌ La contraseña debe contener al menos una letra mayúscula.";
        exit;
    }

    if (!preg_match('/[0-9]/', $password)) {
        echo "❌ La contraseña debe contener al menos un número.";
        exit;
    }

    $check_sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "❌ El correo ya está registrado. Usa otro.";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    $sql = "INSERT INTO usuarios (nombre, email, password, token) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $email, $password_hash, $token);

    if ($stmt->execute()) {
        // Aquí usas PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ecmaror@gmail.com';
            $mail->Password = 'rutbosbcmjntzxdq';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tucorreo@gmail.com', 'VendeYa');
            $mail->addAddress($email, $nombre);
            $mail->Subject = 'Verifica tu cuenta';
            $mail->Body = "Hola $nombre,\n\nHaz clic aquí para verificar tu cuenta:\n";
            $mail->Body .= "http://localhost/VendeYa/authdb/verificar.php?email=$email&token=$token";

            $mail->send();
            echo "✅ Registro exitoso. Revisa tu correo para verificar tu cuenta.";
        } catch (Exception $e) {
            echo "❌ Registro creado, pero error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "❌ Error al registrar: " . $stmt->error;
    }
}

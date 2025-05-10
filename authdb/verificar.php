<?php
include 'conexion.php';

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $sql = "SELECT id, nombre, password FROM usuarios WHERE email = ? AND token = ? AND verificado = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nombre, $hashed_password);
        $stmt->fetch();

        $update_sql = "UPDATE usuarios SET verificado = 1 WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $email);
        $update_stmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ecmaror@gmail.com';
            $mail->Password = 'rutbosbcmjntzxdq';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('ecmaror@gmail.com', 'VendeYa');
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a VendeYa';
            $mail->Body = "
                <html>
                  <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                    <div style='max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px;'>
                      <h2 style='color: #2e86de;'>BIENVENIDO(A), $nombre!</h2>
                      <p>Tu cuenta ha sido activada correctamente. Aquí están tus credenciales:</p>
                      <ul>
                        <li><strong>Correo:</strong> $email</li>
                        <li><strong>Contraseña (cifrada):</strong> $hashed_password</li>
                      </ul>
                      <p>Puedes iniciar sesión haciendo clic en el siguiente botón:</p>
                      <a href='http://localhost/VendeYa/authFront/html/login.html' style='background-color: #2e86de; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Iniciar sesión</a>
                      <p style='margin-top: 30px;'>Si tiene alguna pregunta sobre su cuenta, no dude en comunicarse con nosotros en support@vendeya.com. Estamos aquí para ayudarle en cada paso del proceso.</p>
                      <p style='margin-top: 30px;'>Gracias por registrarte,<br><strong>El equipo de VendeYa</strong></p>
                    </div>
                  </body>
                </html>
            ";

            $mail->send();
        } catch (Exception $e) {

        }

        echo "✅ Cuenta verificada correctamente. <a href='/VendeYa/authdb/login.php'>Iniciar sesión</a>";
    } else {
        echo "❌ Token inválido o cuenta ya verificada.";
    }
} else {
    echo "❌ Parámetros inválidos.";
}
?>

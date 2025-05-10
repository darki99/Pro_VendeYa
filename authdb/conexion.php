<?php
$conn = new mysqli("localhost", "root", "", "vendoya_pro25");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

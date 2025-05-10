<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: /VendeYa/authdb/login.php");
    exit;
}
?>
<h2>Bienvenido, <?php echo $_SESSION['nombre']; ?> 🧃</h2>
<a href="logout.php">Cerrar sesión</a>

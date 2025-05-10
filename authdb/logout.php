<?php
session_start();
session_destroy();
header("Location: /VendeYa/authFront/html/login.html");
exit;
?>

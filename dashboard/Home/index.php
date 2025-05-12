<?php
session_start();

$nombre = $_SESSION['nombre'] ?? 'Usuario';
$html = file_get_contents('../resources/home_dashboard.html');
$html = str_replace('{{nombre}}', $nombre, $html);

echo $html;
?>

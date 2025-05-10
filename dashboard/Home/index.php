<?php
session_start();

// lógica de autenticación, consulta DB, etc.

$nombre = $_SESSION['nombre'] ?? 'Usuario';

// leer archivo html
$html = file_get_contents('../resources/home_dashboard.html');

// reemplazar etiquetas con contenido dinámico
$html = str_replace('{{nombre}}', $nombre, $html);

// mostrarlo
echo $html;
?>

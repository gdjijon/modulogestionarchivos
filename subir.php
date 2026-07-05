<?php
session_start();
require_once 'GestorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $gestor = new GestorArchivos();
    $resultado = $gestor->subir($_FILES['archivo']);
    
    $_SESSION['mensaje'] = $resultado['mensaje'];
    $_SESSION['exito'] = $resultado['exito'];
}

header('Location: index.php');
exit;
?>
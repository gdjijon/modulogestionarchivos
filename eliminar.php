<?php
session_start();
require_once 'GestorArchivos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_archivo'])) {
    $gestor = new GestorArchivos();
    $resultado = $gestor->eliminar($_POST['nombre_archivo']);
    
    $_SESSION['mensaje'] = $resultado['mensaje'];
    $_SESSION['exito'] = $resultado['exito'];
}

header('Location: listado.php');
exit;
?>
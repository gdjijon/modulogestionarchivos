<?php 
session_start();
require_once 'GestorArchivos.php';
$gestor = new GestorArchivos();
$archivos = $gestor->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Archivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1>Sistema Gestor de Archivos</h1>
        </div>
    </header>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="nav-link" href="index.php">Subir Archivo</a>
            <a class="nav-link active" href="listado.php">Listado de Archivos</a>
        </div>
    </nav>

    <section class="container">
        <?php
        if (isset($_SESSION['mensaje'])) {
            $clase = $_SESSION['exito'] ? 'alert-success' : 'alert-danger';
            echo "<div class='alert {$clase}'>{$_SESSION['mensaje']}</div>";
            unset($_SESSION['mensaje']);
            unset($_SESSION['exito']);
        }
        ?>
        <h3>Archivos Almacenados</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Tamaño</th>
                        <th>Fecha de Subida</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($archivos)): ?>
                        <tr><td colspan="4" class="text-center">No hay archivos subidos.</td></tr>
                    <?php else: ?>
                        <?php foreach ($archivos as $archivo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($archivo['nombre']); ?></td>
                                <td><?php echo $archivo['tamano']; ?></td>
                                <td><?php echo $archivo['fecha']; ?></td>
                                <td>
                                    <a href="<?php echo $archivo['ruta']; ?>" class="btn btn-sm btn-success" download>Descargar</a>
                                    <form action="eliminar.php" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este archivo?');">
                                        <input type="hidden" name="nombre_archivo" value="<?php echo htmlspecialchars($archivo['nombre']); ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <footer class="bg-light text-center p-3 mt-5">
        <p class="mb-0">Proyecto de Diseño Web</p>
    </footer>
</body>
</html>
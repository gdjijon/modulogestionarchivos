<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subida de Archivos</title>
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
            <a class="nav-link active" href="index.php">Subir Archivo</a>
            <a class="nav-link" href="listado.php">Listado de Archivos</a>
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
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Selecciona un archivo</h5>
                <form action="subir.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Formatos permitidos: PDF, JPG, PNG (Max. 2MB)</label>
                        <input class="form-control" type="file" id="archivo" name="archivo" required accept=".pdf, .jpg, .jpeg, .png">
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Archivo</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-light text-center p-3 mt-5">
        <p class="mb-0">Proyecto de Diseño Web</p>
    </footer>
</body>
</html>
<?php
class GestorArchivos {
    private $directorio;
    private $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'application/pdf' => 'pdf'
    ];
    private $tamanoMaximo = 2097152; // 2 MB en bytes

    public function __construct($directorio = 'uploads/') {
        $this->directorio = $directorio;
        if (!is_dir($this->directorio)) {
            mkdir($this->directorio, 0755, true);
        }
    }

    public function subir($archivo) {
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            return ["exito" => false, "mensaje" => "Error interno en la subida del archivo."];
        }

        if ($archivo['size'] > $this->tamanoMaximo) {
            return ["exito" => false, "mensaje" => "El archivo excede el límite de 2MB."];
        }

        // Validación estricta del tipo MIME mediante fileinfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mime, $this->tiposPermitidos)) {
            return ["exito" => false, "mensaje" => "Tipo de archivo no permitido. Solo se acepta PDF, JPG y PNG."];
        }

        // Renombrado criptográfico para evitar colisiones y sobrescrituras
        $extensionSegura = $this->tiposPermitidos[$mime];
        $nombreSeguro = bin2hex(random_bytes(8)) . '_' . time() . '.' . $extensionSegura;
        $rutaDestino = $this->directorio . $nombreSeguro;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return ["exito" => true, "mensaje" => "Archivo subido correctamente."];
        }

        return ["exito" => false, "mensaje" => "Error al mover el archivo al directorio final."];
    }

    public function listar() {
        $archivos = [];
        if ($gestor = opendir($this->directorio)) {
            while (false !== ($entrada = readdir($gestor))) {
                if ($entrada != "." && $entrada != ".." && $entrada != ".htaccess") {
                    $ruta = $this->directorio . $entrada;
                    $archivos[] = [
                        'nombre' => $entrada,
                        'tamano' => round(filesize($ruta) / 1024, 2) . ' KB',
                        'fecha'  => date("Y-m-d H:i:s", filemtime($ruta)),
                        'ruta'   => $ruta
                    ];
                }
            }
            closedir($gestor);
        }
        return $archivos;
    }

    public function eliminar($nombre) {
        // basename() previene Path Traversal
        $nombreSeguro = basename($nombre);
        $ruta = $this->directorio . $nombreSeguro;

        if (file_exists($ruta) && is_file($ruta) && $nombreSeguro !== '.htaccess') {
            if (unlink($ruta)) {
                return ["exito" => true, "mensaje" => "Archivo '$nombreSeguro' eliminado de forma segura."];
            }
        }
        return ["exito" => false, "mensaje" => "No se encontró el archivo o no se pudo eliminar."];
    }
}
?>
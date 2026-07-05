# modulogestionarchivos
tarea desarrollo web guillermo jijon utpl
# Módulo de Gestión de Archivos

--

## 1. Estructura del Proyecto

El sistema se ejecuta sobre un entorno de servidor web (Apache/PHP) y se organiza en la siguiente jerarquía de directorios y scripts:

* `modulogestionarchivos/` (Directorio raíz del proyecto)
* `GestorArchivos.php`: Capa lógica. Contiene la clase principal y las reglas de negocio.
* `index.php`: Vista frontal del usuario para la subida de archivos (Formulario).
* `listado.php`: Vista del catálogo de archivos disponibles en el servidor.
* `subir.php`: Controlador que procesa las peticiones de carga.
* `eliminar.php`: Controlador que procesa las peticiones de borrado seguro.
* `README.md`: Documentación técnica y manual de usuario.
* `uploads/`: Directorio autogenerado donde se persisten físicamente los archivos.
* `.htaccess`: Archivo de configuración de Apache para bloqueo de ejecución de scripts.



---

## 2. Componentes y Métodos Técnicos (Arquitectura)

El sistema está programado bajo el paradigma de Programación Orientada a Objetos (POO).

* **Clase Principal:** `GestorArchivos`
* **Métodos del Objeto:**
* `__construct()`: Inicializa la clase y crea dinámicamente el directorio `/uploads/` si no existe.
* `subir($archivo)`: Valida metadatos, filtra por peso/MIME y reubica el archivo temporal en el disco.
* `listar()`: Escanea el directorio físico y retorna un arreglo con los nombres, tamaños y fechas.
* `eliminar($nombre)`: Destruye físicamente un archivo del almacenamiento previa sanitización del parámetro.


---

## 3. Especificación de los Procesos 

### Subida de Archivo

La interfaz cliente envía el archivo en un formato multipart. El motor PHP lo captura en memoria temporal, y el método `subir()` ejecuta `move_uploaded_file()` para trasladar los bytes hacia el directorio `/uploads/`.

### Listado de Archivos

Se prescinde de motores de base de datos. El método `listar()` utiliza `opendir()` y `readdir()` para recorrer en tiempo real el directorio de destino. Extrae el tamaño con `filesize()` y la fecha de creación con `filemtime()`, devolviendo la matriz de datos .

### Eliminación

El proceso es irreversible. El controlador envía el identificador del archivo al método `eliminar()`. Tras verificar que la ruta existe, se ejecuta la función nativa de sistema `unlink()` para liberar los sectores en el disco duro.

### Medidas de Seguridad

El sistema implementa varios mecanismos de seguridad:

* **Validación MIME:** No se confía en la extensión del archivo. Utiliza `finfo_open` para analizar la firma binaria real (*Magic Bytes*).
* **Límite de Tamaño:** Condiciona la ejecución a archivos que no superen los 2MB para evitar ataques de denegación de servicio (DoS) por saturación.
* **Renombrado Criptográfico:** Previene ataques de inyección LFI mediante el reemplazo del nombre original por un hash aleatorio generado con `bin2hex(random_bytes(8))`.
* **Bloqueo de Compilación:** El directorio destino contiene un `.htaccess` con `php_flag engine off`, impidiendo que Apache ejecute cualquier script malicioso que haya vulnerado las capas anteriores.

### Uso Básico de la Clase en PHP

Los controladores (`subir.php`, `eliminar.php`) operan como simples intermediarios. El flujo consiste en: requerir el archivo mediante `require_once`, instanciar el objeto con `$gestor = new GestorArchivos();`, y pasar las variables superglobales (como `$_FILES` o `$_POST`) directamente a los métodos del objeto.

---

## 4. Manual de Usuario Final

Como utilizar el proyecto desde cualquier navegador web.

1.Ingresar a la dirección proporcionada por el administrador (ej. `http://localhost/modulogestionarchivos/`).

### ¿Cómo subir un archivo?

1. En la pantalla principal, localiza la sección **"Selecciona un archivo"**.
2. Haz clic en el botón de selección de archivos. Se abrirá el explorador de tu computadora.
3. Elige un documento válido. El sistema solo acepta imágenes (**JPG, PNG**) o documentos (**PDF**) que pesen como máximo **2MB**.
4. Haz clic en el botón azul **"Subir Archivo"**.
5. Aparecerá un cuadro verde confirmando el éxito de la operación. Si el archivo es inválido, un cuadro rojo indicará el motivo del rechazo.

### ¿Cómo consultar el listado?

1. En la barra de navegación superior, haz clic en la pestaña **"Listado de Archivos"**.
2. Accederás a una tabla que muestra el inventario en tiempo real.
3. Podrás visualizar columnas con el nombre asignado al archivo por el servidor, su peso exacto en KB y la fecha y hora en la que fue cargado.

### Tareas Básicas: Descarga y Eliminación

Desde la misma vista del **Listado de Archivos**, ubica la columna derecha llamada "Acciones" correspondiente al documento que deseas manejar:

* **Para descargar:** Haz clic en el botón verde **"Descargar"**. El archivo se guardará automáticamente en la carpeta de descargas de tu computadora.
* **Para eliminar:** Haz clic en el botón rojo **"Eliminar"**. El sistema lanzará una ventana emergente de confirmación en la parte superior del navegador preguntando si estás seguro. Al aceptar, el archivo desaparecerá del listado y será borrado definitivamente del servidor.

LINK DE YOUTUBE DE VIDEO REALIZADO:
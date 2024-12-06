 <?php
$host = "localhost";     // Host del servidor de la base de datos
$dbname = "inventario_db"; // Nombre de la base de datos
$username = "root";      // Usuario para conectarse a la base de datos
$password = "";          // Contraseña para el usuario (en este caso es vacío para XAMPP)

// Crear la conexión
$conexion = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>

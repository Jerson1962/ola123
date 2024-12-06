 <?php
// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Verificar si se ha pasado un ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el producto de la base de datos
    $sql = "DELETE FROM inventarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirigir a la página principal después de eliminar
    header("Location: index.php");
    exit();
} else {
    // Si no se pasa un ID, redirigir al inicio
    header("Location: index.php");
    exit();
}
?>

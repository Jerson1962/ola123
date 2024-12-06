 <?php
// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Verificar si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $unidades = $_POST['unidades'];
    $marca = $_POST['marca'];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO inventarios (codigo, descripcion, unidades, marca) 
            VALUES ('$codigo', '$descripcion', '$unidades', '$marca')";

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Producto agregado correctamente'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error al agregar producto'); window.location='index.php';</script>";
    }
}
?>

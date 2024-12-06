 <?php
// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Definir la variable de búsqueda
$search = '';
if (isset($_POST['buscar'])) {
    $search = $_POST['buscar'];
}

// Procesar el formulario de agregar producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigo'])) {
    // Recuperar datos del formulario
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $unidades = $_POST['unidades'];
    $marca = $_POST['marca'];

    // Insertar el producto en la base de datos
    $sql = "INSERT INTO inventarios (codigo, descripcion, unidades, marca) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssis", $codigo, $descripcion, $unidades, $marca);
    $stmt->execute();
}

// Consultar los productos almacenados en la base de datos con filtro de búsqueda
$sql = "SELECT * FROM inventarios WHERE 
        codigo LIKE ? OR 
        descripcion LIKE ? OR 
        unidades LIKE ? OR 
        marca LIKE ?";
$search_term = "%$search%"; // Búsqueda flexible (coincidencia parcial)
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Productos</title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">" 𝒍𝒖𝒎𝒊𝒍 𝒃𝒂𝒓𝒃𝒆𝒓"</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Ver Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="agregar.php">Agregar Producto</a>
                    </li>      
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2> 𝑰𝒏𝒗𝒆𝒏𝒕𝒂𝒓𝒊𝒐            
                  "𝑨𝒍𝒎𝒂𝒄𝒆𝒏 01"</h2>
        
        <!-- Formulario para agregar productos -->
        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" value="<?php echo isset($_POST['codigo']) ? $_POST['codigo'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo isset($_POST['descripcion']) ? $_POST['descripcion'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="unidades" class="form-label">Unidades</label>
                <input type="number" class="form-control" id="unidades" name="unidades" value="<?php echo isset($_POST['unidades']) ? $_POST['unidades'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control" id="marca" name="marca" value="<?php echo isset($_POST['marca']) ? $_POST['marca'] : ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
            <button type="submit" name="agregar_mas" class="btn btn-secondary ms-2">Agregar Más</button>
        </form>

        <hr>

        <!-- Formulario de Búsqueda -->
        <h3>Buscar Productos</h3>
        <form method="POST" action="index.php" class="mb-4">
            <input type="text" class="form-control" name="buscar" value="<?php echo $search; ?>" placeholder="Buscar por código, descripción, unidades o marca">
            <button type="submit" class="btn btn-primary mt-2">Buscar</button>
        </form>

        <!-- Panel de Información Guardada -->
        <h3>Información Guardada</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Unidades</th>
                        <th>Marca</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Verificar si hay productos en la base de datos
                    if ($resultado->num_rows > 0) {
                        // Mostrar los productos en la tabla
                        while ($row = $resultado->fetch_assoc()) {
                            $codigo = $row['codigo'];
                            $descripcion = $row['descripcion'];
                            $unidades = $row['unidades'];
                            $marca = $row['marca'];

                            // Resaltar el término de búsqueda en los resultados
                            if ($search) {
                                $codigo = str_ireplace($search, "<span class='highlight'>$search</span>", $codigo);
                                $descripcion = str_ireplace($search, "<span class='highlight'>$search</span>", $descripcion);
                                $marca = str_ireplace($search, "<span class='highlight'>$search</span>", $marca);
                            }

                            echo "<tr>";
                            echo "<td>" . $codigo . "</td>";
                            echo "<td>" . $descripcion . "</td>";
                            echo "<td>" . $unidades . "</td>";
                            echo "<td>" . $marca . "</td>";
                            echo "<td>
                                    <a href='eliminar.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Eliminar</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No hay productos registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enlace a Bootstrap JS (incluye dependencias de Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybJr6lJDNXWDRdR2lH0o1TXJ24mvL4jI93K5jtgf65r6p1Bz9" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-cuXjpe5u0rpNNlJ3kItJ5zHlAp7o3l34+z4jz6GMxVVnCwXlRYwD2b2x5gU8m9lg" crossorigin="anonymous"></script>
</body>
</html>

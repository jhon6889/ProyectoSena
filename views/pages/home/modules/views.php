<?php
// Conexión a la base de datos
$host = "localhost";
$dbname = "inventario";
$user = "root";
$password = "";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener la lista de productos
$sql = "SELECT * FROM productos";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="<?php echo $path ?>views/assets/css/view/view.css">
</head>
<body>
    <div class="container">
        <h1>Nuestros Productos</h1>
        
        <div class="product-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="card">
                    <div class="card-content">
                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="product-image" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <h3 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="product-price">$<?php echo number_format($producto['precio'], 2); ?></p>
                        
                        <!-- Nuevos campos añadidos -->
                        <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        
                        <p class="product-stock">
                            Stock disponible: <?php echo htmlspecialchars($producto['stock']); ?>
                        </p>
                        
                        <?php


                        
                        $statusClass = '';
                        switch ($producto['estado']) {
                            case 'activo':
                                $statusClass = 'status-active';
                                break;
                            case 'inactivo':
                                $statusClass = 'status-inactive';
                                break;
                            case 'agotado':
                                $statusClass = 'status-soldout';
                                break;
                        }
                        ?>
                        <span class="product-status <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars(ucfirst($producto['estado'])); ?>
                        </span>
                        
                        <a href="/productos/detalle?id=<?php echo $producto['id']; ?>" class="btn">Ver más</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
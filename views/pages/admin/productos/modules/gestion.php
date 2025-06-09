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

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"];
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $descripcion = $_POST["descripcion"];
    $stock = $_POST["stock"];
    $estado = $_POST["estado"];

    // Procesar imagen
    $imagen = $_POST["imagen_actual"] ?? '';
    if (!empty($_FILES["imagen"]["name"])) {
        $nombreImagen = basename($_FILES["imagen"]["name"]);
        $rutaImagen = "uploads/" . $nombreImagen;
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagen)) {
            $imagen = $rutaImagen;
        }
    }

    if ($accion == "crear") {
        $sql = "INSERT INTO productos (nombre, precio, imagen, descripcion, stock, estado) 
                VALUES (:nombre, :precio, :imagen, :descripcion, :stock, :estado)";
    } else {
        $id = $_POST["id"];
        $sql = "UPDATE productos SET 
                nombre = :nombre, 
                precio = :precio, 
                imagen = :imagen,
                descripcion = :descripcion,
                stock = :stock,
                estado = :estado 
                WHERE id = :id";
    }

    $stmt = $conexion->prepare($sql);
    $params = [
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":imagen" => $imagen,
        ":descripcion" => $descripcion,
        ":stock" => $stock,
        ":estado" => $estado
    ];
    if ($accion == "editar") $params[":id"] = $_POST["id"];

    $stmt->execute($params);
    header("Location: /admin/productos");
    exit();
}

// Obtener datos del producto si está en modo edición
$producto = null;
if (isset($_GET["id"])) {
    $sql = "SELECT * FROM productos WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([":id" => $_GET["id"]]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3><?= isset($producto) ? "Editar Producto" : "Agregar Producto" ?></h3>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="<?= isset($producto) ? 'editar' : 'crear' ?>">
                    <input type="hidden" name="id" value="<?= $producto['id'] ?? '' ?>">
                    <input type="hidden" name="imagen_actual" value="<?= $producto['imagen'] ?? '' ?>">

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control" value="<?= htmlspecialchars($producto['precio'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($producto['stock'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control" required>
                            <option value="activo" <?= isset($producto) && $producto['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="inactivo" <?= isset($producto) && $producto['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            <option value="agotado" <?= isset($producto) && $producto['estado'] == 'agotado' ? 'selected' : '' ?>>Agotado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control">
                        <?php if (isset($producto) && !empty($producto['imagen'])): ?>
                            <img src="<?= htmlspecialchars($producto['imagen']) ?>" width="100" class="mt-2">
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="/admin/productos" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</div>
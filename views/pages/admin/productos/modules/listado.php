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

// eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["accion"] == "eliminar") {
    $id = $_POST["id"];

    // Eliminar imagen si existe
    $sql = "SELECT imagen FROM productos WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([":id" => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($producto && file_exists($producto["imagen"])) {
        unlink($producto["imagen"]);
    }
    // Eliminar de la BD
    $sql = "DELETE FROM productos WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([":id" => $id]);
    header("Location: /admin/productos");
    exit();
}

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <a href="/admin/productos/gestion" class="btn btn-primary">Agregar Producto</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Imagen</th>
                            <th>Descripción</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= htmlspecialchars($producto['id']) ?></td>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td><?= htmlspecialchars($producto['precio']) ?></td>
                                <td><img src="<?= htmlspecialchars($producto['imagen']) ?>" width="100"></td>
                                <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                                <td><?= htmlspecialchars($producto['stock']) ?></td>
                                <td><?= htmlspecialchars($producto['estado']) ?></td>
                                <td>
                                    <a href="/admin/productos/gestion?id=<?= $producto['id'] ?>" class="btn btn-warning">Editar</a>
                                    <button class="btn btn-danger btn-eliminar" data-id="<?= $producto['id'] ?>">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
        btn.addEventListener("click", () => {
            if (confirm("¿Estás seguro de eliminar este producto?")) {
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "/admin/productos";
                form.innerHTML = `
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="${btn.dataset.id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>
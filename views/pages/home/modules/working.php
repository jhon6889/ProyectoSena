<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Sesión</title>
    <style>
        .session-box {
            background-color: #ffcc00;
            color: #000;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            width: 300px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<?php
    echo '<div class="session-box">Trabajando en esta sesión</div>';
?>

</body>
</html>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Productos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section class="productos">
        <div class="producto">
            <img src="producto1.jpg" alt="Producto 1">
            <h3>Nombre del Producto</h3>
            <p class="precio">$99.99</p>
            <button>Agregar al carrito</button>
        </div>
        <div class="producto">
            <img src="producto2.jpg" alt="Producto 2">
            <h3>Otro Producto</h3>
            <p class="precio">$79.99</p>
            <button>Agregar al carrito</button>
        </div>
        <div class="producto">
            <img src="producto3.jpg" alt="Producto 3">
            <h3>Producto Especial</h3>
            <p class="precio">$119.99</p>
            <button>Agregar al carrito</button>
        </div>
    </section>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    .productos {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        max-width: 1000px;
        margin: auto;
    }
    .producto {
        background: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }
    .producto img {
        width: 100%;
        border-radius: 8px;
    }
    .precio {
        color: #27ae60;
        font-size: 18px;
        font-weight: bold;
    }
    button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
    }
    button:hover {
        background: #0056b3;
    }
</style>

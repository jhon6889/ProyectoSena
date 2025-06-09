<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game X Press</title>


    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}



.container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 80%;
}

.left img, .right img {
    width: 250px;
}

.center {
 
    background: #ff9900;
    padding: 30px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

.center h1 {
    font-size: 48px;
    font-weight: bold;
}

.btn {
    display: block;
    width: 200px;
    margin: 10px auto;
    padding: 10px;
    font-size: 18px;
    background: black;
    color: white;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background: #333;
}

.method {
    margin-top: 15px;
    font-size: 14px;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="left">
        </div>
        <div class="center">
            <h1>GameXpress</h1>
            <a href="">
    <button class="btn">TIENDA</button>
</a>

            <button class="btn">IMPORTACIONES</button>
            <button class="btn">CONTACTO</button>
            <p class="method">Siguenos en Redes</p>
        </div>

        <div>
            <div>
                <i></i>
            </div>
        </div>
        <div class="right">
            
        </div>
    </div>
</body>
</html>

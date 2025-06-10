<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programación Web</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .main-container {
            display: flex;
            flex: 1;
        }

        .menu {
            width: 20%;
            background-color:rgb(250, 250, 250);
            padding: 10px;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .menu h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
            font-size: 18px;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        .menu a:hover {
            background-color: #ddd;
        }

        .content {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <header>
        Programación Web
    </header>
    <div class="main-container">
        <div class="menu">
            <h2>Menú</h2>
        <a href="admin1.php" target="contentFrame">Usuarios</a>
        <a href="productos.php" target="contentFrame">Productos</a>
        <a href="bitacora.php" target="contentFrame">Bitacora</a>
        <a href="reporte.php" target="contentFrame">Reporte</a>
        
        
        
        </a>
    </div>
        <div class="content">
            <iframe name="contentFrame" src=""></iframe>
        </div>
    </div>
</body>
</html>
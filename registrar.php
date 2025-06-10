<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
</head>
<body>
    <div class="container">
    <?php include "./navbar.php";?>
    <?php
    error_reporting(0);
    if ($_GET['mensaje']==1) {
    ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>EXITO!</strong> El usuario se guardo con satisfactoriamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php        
    }
    ?>
    <?php include "./formulario_ingresar.php"; ?>
    </div>
    <script src="./bootstrap.bundle.min.js"></script>
</body>
</html>
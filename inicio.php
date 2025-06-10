<?php session_start();?>

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
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="10000">
      <img src="./img/prom.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <img src="./img/prom2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="./img/prom3.jpeg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


<h2>Colección "Leyendas del Ring"</h2>
<!--articulos-->

<div class="container mt-5">
  <div class="row" style="justify-content: center;">
    <div class="col">
      <div class="card m-4" style="width: 18rem;">
        <form id="formulario1" name="formulario1" method="post" action="cart.php">
          <input name="precio" type="hidden" value="350"/>
          <input name="titulo" type="hidden" value="articulo 1"/>
          <input name="cantidad" type="hidden" value="1" class="p1-2"/>
          <img src="./img/ropa1.jpeg" class="card-img-top" alt="Pantalón Verde Olivo Dama">
          <div class="card-body">
            <h5 class="card-title">Esencia del Ring</h5>
            <p class="card-text">Esta playera rinde homenaje al espíritu indomable del cuadrilátero.</p>
            <p class="card-text">$350</p>
            <form action="cart.php" method="post">
    <input type="hidden" name="id_producto" value="1"> <!-- ID del producto -->
    <button type="submit">Añadir al carrito</button>
</form>



          </div>
        </form>
      </div>
    </div>

    <div class="col">
      <div class="card m-4" style="width: 18rem;">
        <for id="formulario2" name="formulario2" method="post" action="cart.php">
          <input name="precio" type="hidden" value="270"/>
          <input name="titulo" type="hidden" value="articulo 2"/>
          <input name="cantidad" type="hidden" value="1" class="p1-2"/>
          <img src="./img/ropa2.jpg" class="card-img-top" alt="Pantalón Rosa Dama">
          <div class="card-body">
            <h5 class="card-title">Sudadera Leyenda Viva</h5>
            <p class="card-text">Evocando el legado de los grandes luchadores</p>
            <p class="card-text">$270</p>
            <form action="cart.php" method="post">
            <input type="hidden" name="id_producto" value="7"> <!-- ID del producto -->
            <button type="submit">Añadir al carrito</button>
            </form>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-start">
  <div class="col">
    <div class="card m-4" style="width: 18rem;">
      <form id="formulario2" name="formulario2" method="post" action="cart.php">
        <input name="precio" type="hidden" value="270"/>
        <input name="titulo" type="hidden" value="articulo 2"/>
        <input name="cantidad" type="hidden" value="1" class="p1-2"/>
        <img src="./img/ropa3.jpg" class="card-img-top" alt="Guante Jefe Tribal">
        <div class="card-body">
          <h5 class="card-title">Guante Jefe Tribal</h5>
          <p class="card-text">El poder en tus manos</p>
          <p class="card-text">$600</p>
          <form action="cart.php" method="post">
    <input type="hidden" name="id_producto" value="8"> <!-- ID del producto -->
    <button type="submit">Añadir al carrito</button>
</form>
        </div>
      </form>
    </div>
  </div>

  <div class="col" style="margin-left:20px;"> <!-- Ajuste para más espacio a la derecha -->
    <div class="card m-4" style="width: 18rem;">
      <form id="formulario2" name="formulario2" method="post" action="cart.php">
        <input name="precio" type="hidden" value="270"/>
        <input name="titulo" type="hidden" value="articulo 2"/>
        <input name="cantidad" type="hidden" value="1" class="p1-2"/>
        <img src="./img/ropa4.jpg" class="card-img-top" alt="Funko pop">
        <div class="card-body">
          <h5 class="card-title">Funko pop</h5>
          <p class="card-text">Figura Colecionable</p>
          <p class="card-text">$900</p>
          <form action="cart.php" method="post">
    <input type="hidden" name="id_producto" value="9"> <!-- ID del producto -->
    <button type="submit">Añadir al carrito</button>
</form>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="col" style="margin-top:20px;"> <!-- Ajuste para más espacio hacia abajo -->
  <div class="card m-4" style="width: 18rem;">
    <form id="formulario2" name="formulario2" method="post" action="cart.php">
      <input name="precio" type="hidden" value="270"/>
      <input name="titulo" type="hidden" value="articulo 2"/>
      <input name="cantidad" type="hidden" value="1" class="p1-2"/>
      <img src="./img/ropa5.jpg" class="card-img-top" alt="Funko pop">
      <div class="card-body">
        <h5 class="card-title">Playera Belair</h5>
        <p class="card-text">Para las campeonas</p>
        <p class="card-text">$200</p>
        <form action="cart.php" method="post">
    <input type="hidden" name="id_producto" value="10"> <!-- ID del producto -->
    <button type="submit">Añadir al carrito</button>
</form>
      </div>
    </form>
  </div>
</div>




<script src="./bootstrap.bundle.min.js"></script>

<form action="./comprobar.php" method="post"><!--get y post-->
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Usuario: </label>
    <input name="usuario" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text">Ingresa tu usuario.</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Contraseña: </label>
    <input name="contrasena" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text">Ingresa tu contraseña.</div>
  </div>
  <button type="submit" class="btn btn-primary">Ingresar</button>
</form>
<form action="./ingresar.php" method="post">
  <div class="mb-3">
    <label for="nombre" class="form-label">Nombre: </label>
    <input name="nombre" type="text" class="form-control" id="nombre" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text">Ingresa tu Nombre.</div>
  </div>

  <div class="mb-3">
    <label for="apellido" class="form-label">Apellido: </label>
    <input name="apellido" type="text" class="form-control" id="apellido" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text">Ingresa tu Apellido.</div>
  </div>

  <div class="mb-3">
    <label for="usuario" class="form-label">Usuario:  </label>
    <input name="usuario" type="text" class="form-control" id="usuario" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text">Ingresa un Nombre de Usuario.</div>
  </div>

  <div class="mb-3">
    <label for="contrasena" class="form-label">Contraseña:  </label>
    <input name="contrasena" type="password" class="form-control" id="contrasena" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text">Ingresa una Contraseña.</div>
  </div>

  <div class="mb-3">
    <label for="rol" class="form-label">Tipo de Usuario:</label>
    <select name="rol" class="form-control" id="rol" required>
        <option value="usuario">Usuario</option>
        <option value="admin">Administrador</option>
    </select>
    <div id="emailHelp" class="form-text">Selecciona si eres un usuario o administrador.</div>
  </div>

  <button type="submit" class="btn btn-primary">Registrar</button>
</form>

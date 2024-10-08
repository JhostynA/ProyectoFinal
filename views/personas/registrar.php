<?php require_once '../header.php'; ?>

<main>
  <div class="container-fluid px-4">
    <h1 class="mt-4">Usuarios</h1>

    <!-- Contenido -->
    <div class="row">
      <div class="col-md-12">
        <form action="" autocomplete="off" id="formulario-personas">
          <div class="card">
            <div class="card-header">Datos de la persona</div>
            <div class="card-body">
              <!-- Fila 1 -->
              <div class="row g-3 mb-3">
                <div class="col-md-2">
                  <div class="input-group">
                    <div class="form-floating">
                      <input 
                        type="text" 
                        class="form-control" 
                        id="dni" 
                        pattern="[0-9]+" 
                        title="Solo se permiten números" 
                        maxlength="8" 
                        required 
                        autofocus>
                      <label for="" class="form-label">DNI</label>
                    </div>
                    <button type="button" id="buscar-dni" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="apellidos" maxlength="40" placeholder="Apellidos" required>
                    <label for="apellidos" class="form-label">Apellidos</label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="nombres" maxlength="40" required>
                    <label for="nombres" class="form-label">Nombres</label>
                  </div>
                </div>
              </div>
              <!-- Fin Fila 1 -->
              <!-- Fila 2 -->
              <div class="row g-3 mb-3">
                <div class="col-md-2">
                  <div class="form-floating">
                    <input
                      type="text"
                      class="form-control"
                      pattern="[0-9]+"
                      title="Solo se permiten números"
                      minlength="9"
                      maxlength="9"
                      id="telefono"
                      required>
                    <label for="telefono" class="form-label">Teléfono</label>
                  </div>
                </div>
                <div class="col-md-10">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="direccion">
                    <label for="direccion" class="form-label">Dirección</label>
                  </div>
                </div>
              </div>
              <!-- Fin Fila 2 -->
              <!-- Fila 3 -->
              <div class="row g-3">
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="usuario" required>
                    <label for="usuario">Nombre de usuario</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="claveacceso" required>
                    <label for="usuario">Clave de acceso</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <select name="perfil" id="perfil" class="form-select" required>
                      <option value="">Seleccione</option>
                      <option value="ADM">Administrador</option>
                      <option value="COL">Colaborador</option>
                      <option value="AST">Supervidor</option>
                    </select>
                    <label for="usuario">Nombre de usuario</label>
                  </div>
                </div>
              </div>
              <!-- Fin Fila 3 -->
            </div>
            <div class="card-footer text-end">
              <div class="row">
                <div class="col-md-6">
                  <span id="status" class="d-none">Buscando por favor espere...</span>
                </div>
                <div class="col-md-6">
                  <button type="submit" class="btn btn-sm btn-primary">Registrar</button>
                  <button type="reset" class="btn btn-sm btn-outline-secondary">Cancelar</button>
                  <a href="index.php" class="btn btn-sm btn-outline-primary">Mostrar lista</a>
                </div>
              </div>
            </div>
          </div> <!-- .card -->
        </form>
      </div> <!-- .col-md-12 -->
    </div> <!-- .row -->
    <!-- Fin contenido -->

  </div> <!-- .container-fluid -->
</main>


<?php require_once '../footer.php'; ?>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    //Función de referencia GLOBAL
    function $(object = null) {
      return document.querySelector(object);
    }

    //Funciones
    //Lógica comunicación con el API
    async function buscarDNI() {
      const dni = $("#dni").value;

      if (dni.length == 8){
        $("#status").classList.remove("d-none");
        const response = await fetch(`../../app/api/api.dni.php?dni=${dni}`, { method: 'GET' });
        const data = await response.json();
        
        //Identifica la estructura del JSON
        $("#status").classList.add("d-none");

        if (data.hasOwnProperty("message")){
          $("#apellidos").value = '';
          $("#nombres").value = '';
          showToast("No encontrado", "INFO", 1500);
        }else{
          $("#apellidos").value = data['apellidoPaterno'] + " " + data['apellidoMaterno'];
          $("#nombres").value = data['nombres'];
        }
      }
    }

    async function registrarPersona(){
      const parametros = new FormData();

      parametros.append("operation", "add");
      parametros.append("apellidos", $("#apellidos").value);
      parametros.append("nombres", $("#nombres").value);
      parametros.append("telefono", $("#telefono").value);
      parametros.append("dni", $("#dni").value);
      parametros.append("direccion", $("#direccion").value);

      //Enviar al controlador...
      const response = await fetch(`../../app/controllers/Persona.controller.php`, {
        method: 'POST',
        body: parametros
      });

      const data = await response.json();
      console.log("idpersona: ", data);

      if (data['idpersona'] > 0){
        await registrarUsuario(parseInt(data['idpersona']));
      }
    }

    async function registrarUsuario(idpersona = null){
      const parametros = new FormData();

      parametros.append("operation", "add");
      parametros.append("idpersona", idpersona);
      parametros.append("nomusuario", $("#usuario").value);
      parametros.append("claveacceso", $("#claveacceso").value);
      parametros.append("perfil", $("#perfil").value);

      const response = await fetch(`../../app/controllers/Usuario.controller.php`, {
        method: 'POST',
        body: parametros
      });

      const data = await response.json();
      console.log("idusuario: ", data);

      if (data['idusuario'] > 0){
        showToast("Usuario registrado", "SUCCESS");
      }
    }

    //Evento registrar PERSONA
    $("#formulario-personas").addEventListener("submit", async (event) => {
      event.preventDefault();

      if (await ask("¿Está seguro de guardar?", "Módulo Usuarios")) {
        await registrarPersona();
        showToast("Guardado correctamente", "INFO");
      }
    });

    //Evento busca PERSONA API
    $("#dni").addEventListener("keypress", (event) => {
      if (event.keyCode == 13) {
        buscarDNI();
      }
    });

    $("#buscar-dni").addEventListener("click", buscarDNI);

  });
</script>

<!-- No olvidar -->
</body>

</html>
<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login']['permitido']) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Seguridad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style-index.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-dark"> 
    <div id="layoutAuthentication" class="flex-grow-1">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header bg-danger text-white"> 
                                    <h3 class="text-center font-weight-light my-4">Login</h3>
                                </div>
                                <div class="card-body">
                                    <form autocomplete="off" id="form-login">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputNomuser" type="text" autofocus placeholder="Nombre de usuario" required />
                                            <label class="txtLogin" for="inputNomuser">Nombre de usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassusuario" type="password" placeholder="Password" required />
                                            <label class="txtLogin" for="inputPassusuario">Contraseña</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small text-white" href="password.html"></a> 
                                            <button class="btn btn-danger" type="submit">Login</button> 
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3 bg-dark text-white"> 
                                    <div class="small"><a href="register.html" class="text-danger">Crear una nueva cuenta</a></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector("#form-login").addEventListener("submit", (event) => {
                event.preventDefault();

                const params = new URLSearchParams();
                params.append("operation", "login");
                params.append("nomusuario", document.querySelector("#inputNomuser").value);
                params.append("passusuario", document.querySelector("#inputPassusuario").value);

                fetch(`./controllers/login.ct.php?${params}`)
                    .then(response => response.json())
                    .then(acceso => {
                        console.log(acceso);
                        if (!acceso.permitido) {
                            alert(acceso.status);
                        } else {
                            window.location.href = './dashboard.php';
                        }
                    })
            });
        })
    </script>
</body>

</html>

<?php
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]->permisos != 0) {
    header("Location: loginUsuario.php");
    exit();
}
$usuario = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Bienvenido, <?= htmlspecialchars($usuario->nombre) ?>!</h1>
        </div>
        <div id="contenido">
            <h3 class="mb-3">Opciones de Usuario</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Acción</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="location.href='/Digimon/views/user/mis-digimon.php'">
                                Ver Mis Digimon
                            </button>
                        </td>
                        <td>Revisa todos los Digimon que tienes en tu colección.</td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="location.href='/Digimon/views/user/organizar-equipo.php'">
                                Organizar Equipo
                            </button>
                        </td>
                        <td>Crea y personaliza tu equipo para las batallas.</td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="location.href='/Digimon/views/user/jugar-partida.php'">
                                Jugar Partida
                            </button>
                        </td>
                        <td>Compite en partidas y sube de nivel.</td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="location.href='/Digimon/views/user/digievolucionar.php'">
                                Digievolucionar
                            </button>
                        </td>
                        <td>Haz que tus Digimon alcancen su máximo potencial.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

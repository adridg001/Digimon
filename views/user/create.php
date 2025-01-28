<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto
require_once '../../models/userModel.php'; // Asegúrate de que esta ruta es correcta

$mensaje = '';
$usuario = null;
$digimones = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    if (empty($nombre) || empty($password)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $userModel = new UserModel();
            $user = [
                "nombre" => $nombre,
                "password" => $password,
            ];
            $usuarioId = $userModel->insert($user);

            if ($usuarioId) {
                // Asignar 3 Digimones de nivel 1 al nuevo usuario
                $conexion = db::conexion();
                $sql = "SELECT id FROM digimones WHERE nivel = 1 ORDER BY RAND() LIMIT 3";
                $stmt = $conexion->query($sql);
                $digimones = $stmt->fetchAll(PDO::FETCH_COLUMN);

                foreach ($digimones as $digimonId) {
                    $sql = "INSERT INTO DIGIMONES_USUARIO (usuario_id, digimon_id) VALUES (:usuario_id, :digimon_id)";
                    $stmt = $conexion->prepare($sql);
                    $stmt->execute([
                        ":usuario_id" => $usuarioId,
                        ":digimon_id" => $digimonId
                    ]);
                }

                // Obtener la información del usuario y los Digimones asignados
                $usuario = $userModel->read($usuarioId);
                $sql = "SELECT digimones.*FROM digimones
                        JOIN DIGIMONES_USUARIO ON digimones.id = DIGIMONES_USUARIO.digimon_id
                        WHERE DIGIMONES_USUARIO.usuario_id = :usuario_id;";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
                $stmt->execute();
                $digimones = $stmt->fetchAll(PDO::FETCH_OBJ);

                $mensaje = 'Usuario registrado exitosamente.';
            } else {
                $mensaje = 'Error al registrar el usuario.';
            }

        } catch (Exception $error) {
            $mensaje = 'Error: ' . $error->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link href="/Digimon/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Registrar Usuario</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <?php if ($usuario): ?>
            <h2>Información del Usuario</h2>
            <p><strong>ID:</strong> <?= htmlspecialchars($usuario->id) ?></p>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario->nombre) ?></p>
            <h2>Digimones Asignados</h2>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ataque</th>
                        <th>Defensa</th>
                        <th>Nivel</th>
                        <th>Tipo</th>
                        <th>Imagen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($digimones as $digimon): ?>
                        <tr>
                            <td><?= htmlspecialchars($digimon->id) ?></td>
                            <td><?= htmlspecialchars($digimon->nombre) ?></td>
                            <td><?= htmlspecialchars($digimon->ataque) ?></td>
                            <td><?= htmlspecialchars($digimon->defensa) ?></td>
                            <td><?= htmlspecialchars($digimon->nivel) ?></td>
                            <td><?= htmlspecialchars($digimon->tipo) ?></td>
                            <td><img src="/Digimon/Administracion/digimones/<?= htmlspecialchars($digimon->nombre) ?>/<?= htmlspecialchars($digimon->imagen) ?>" alt="<?= htmlspecialchars($digimon->nombre) ?>" width="50"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <form method="post" action="create.php">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
        <?php endif; ?>
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>
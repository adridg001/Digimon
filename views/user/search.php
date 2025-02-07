<?php
require_once '../../config/db.php'; // Ajusta la ruta según la estructura de tu proyecto

$usuarios = [];
$mensaje = '';

try {
    $conexion = db::conexion();
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $sql = "SELECT id, nombre, partidas_ganadas, partidas_perdidas, (partidas_ganadas + partidas_perdidas) AS partidas_totales FROM usuarios WHERE nombre LIKE :nombre";
        $stmt = $conexion->prepare($sql);
        $nombre = "%$nombre%";
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($usuarios)) {
            $mensaje = 'No se encontraron usuarios con ese nombre.';
        }
    } else {
        $sql = "SELECT id, nombre, partidas_ganadas, partidas_perdidas, (partidas_ganadas + partidas_perdidas) AS partidas_totales FROM usuarios";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuarios</title>
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Lista de Usuarios</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>
        <form method="post" action="search.php" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Buscar por nombre" required>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Partidas Ganadas</th>
                    <th>Partidas Perdidas</th>
                    <th>Partidas Totales</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($usuarios && count($usuarios) > 0): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id']); ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?= htmlspecialchars($usuario['partidas_ganadas']); ?></td>
                            <td><?= htmlspecialchars($usuario['partidas_perdidas']); ?></td>
                            <td><?= htmlspecialchars($usuario['partidas_totales']); ?></td>
                            <td>
                                <a href="/Digimon/Administracion/views/user/show.php?id=<?= $usuario['id']; ?>" class="btn btn-primary btn-sm">Ver Usuario</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron usuarios.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="../../index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>
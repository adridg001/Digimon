<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

try {
    $conexion = db::conexion();
    $sql = "SELECT id, nombre, ataque, defensa, nivel, tipo, evo_id, imagen FROM digimones";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $digimones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Digimones</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Lista de Digimones</h1>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Ataque</th>
                    <th>Defensa</th>
                    <th>Nivel</th>
                    <th>Tipo</th>
                    <th>Evo ID</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($digimones && $stmt->rowCount() > 0): ?>
                    <?php foreach ($digimones as $digimon): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($digimon['id']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['ataque']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['defensa']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['nivel']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['evo_id']); ?></td>
                            <td><img src="digimones/<?php echo htmlspecialchars($digimon['nombre']); ?>/<?php echo htmlspecialchars($digimon['imagen']); ?>" alt="<?php echo htmlspecialchars($digimon['nombre']); ?>" width="50"></td>
                            <td>
                                <a href="modificarDigimon.php?id=<?php echo $digimon['id']; ?>" class="btn btn-warning btn-sm">Modificar</a>
                                <a href="eliminarDigimon.php?id=<?php echo $digimon['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este Digimon?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No se encontraron digimones.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
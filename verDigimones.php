<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

try {
    $conexion = db::conexion();
    $sql = "SELECT id, nombre, ataque, defensa, nivel, tipo, evo_id, imagen, imagen_victoria, imagen_derrota  FROM digimones";
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
    <style>
        body{
            background-image: url('fondo_VerDigimones.webp');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            height: 100vh;
            margin: 0;
        }
        .container{
            background-color: rgba(255, 255, 255, 0.8); 
            border-radius: 15px;
            padding: 20px;
            margin-top: 10px;
        }
    </style>
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
                            <a href="datosDigimon.php?id=<?php echo $digimon['id']; ?>" class="btn btn-primary btn-sm">Ver Digimon</a>
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
        <a href="index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>
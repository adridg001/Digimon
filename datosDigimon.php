<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

if (!isset($_GET['id'])) {
    echo "No se proporcionó el ID del Digimon.";
    exit();
}

$id = $_GET['id'];

try {
    $conexion = db::conexion();

    // Consulta para obtener el Digimon por ID
    $sql = "SELECT * FROM digimones WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $digimon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$digimon) {
        echo "No se encontró el Digimon.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Digimon</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Información Digimon</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Nombre: <?php echo htmlspecialchars($digimon['nombre']); ?></h5>
                <p class="card-text">
                    <strong>Ataque:</strong> <?php echo htmlspecialchars($digimon['ataque']); ?><br>
                    <strong>Defensa:</strong> <?php echo htmlspecialchars($digimon['defensa']); ?><br>
                    <strong>Tipo:</strong> <?php echo htmlspecialchars($digimon['tipo']); ?><br>
                    <strong>Nivel:</strong> <?php echo htmlspecialchars($digimon['nivel']); ?><br>
                </p>
                <img src="digimones/<?php echo htmlspecialchars($digimon['nombre']); ?>/<?php echo htmlspecialchars($digimon['imagen']); ?>" alt="Imagen del Digimon" style="max-width: 100%; height: auto;">
                <div class="mt-3">
                    <a href="modificarDigimon.php?id=<?php echo $digimon['id']; ?>" class="btn btn-warning">Modificar</a>
                    <a href="borrarDigimon.php?id=<?php echo $digimon['id']; ?>" class="btn btn-danger">Borrar</a>
                    <a href="definirEvoluciones.php?id=<?php echo $digimon['id']; ?>" class="btn btn-primary">Definir Evoluciones</a>
                    <a href="altaDigimon.php" class="btn btn-secondary">Registrar Otro Digimon</a>
                    <a href="index.php" class="btn btn-light">Inicio</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

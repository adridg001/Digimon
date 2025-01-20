<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$mensaje = '';
$digimon = null;
$evoluciones = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $conexion = db::conexion();
        // Obtener el Digimon seleccionado
        $sql = "SELECT * FROM digimones WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $digimon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($digimon) {
            // Obtener los posibles Digimones a los que puede evolucionar
            $sql = "SELECT id, nombre FROM digimones WHERE tipo = :tipo AND nivel = :nivel";
            $stmt = $conexion->prepare($sql);
            $nivelMayor = $digimon['nivel'] + 1;
            $stmt->bindParam(':tipo', $digimon['tipo']);
            $stmt->bindParam(':nivel', $nivelMayor, PDO::PARAM_INT);
            $stmt->execute();
            $evoluciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $mensaje = 'Digimon no encontrado.';
        }
    } catch (PDOException $error) {
        $mensaje = 'Error: ' . $error->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $evo_id = $_POST['evo_id'];

    try {
        $conexion = db::conexion();
        $sql = "UPDATE digimones SET evo_id = :evo_id WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':evo_id', $evo_id, PDO::PARAM_INT);
        $stmt->execute();

        $mensaje = 'Evolución definida exitosamente.';
    } catch (PDOException $error) {
        $mensaje = 'Error: ' . $error->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Evolución</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Definir Evolución</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
            <a href="verDigimones.php" class="btn btn-primary">Volver a la lista de Digimones</a>
        <?php endif; ?>
        <?php if ($digimon): ?>
            <div class="mb-3">
                <h2><?php echo htmlspecialchars($digimon['nombre']); ?></h2>
                <img src="digimones/<?php echo htmlspecialchars($digimon['nombre']); ?>/<?php echo htmlspecialchars($digimon['imagen']); ?>" alt="<?php echo htmlspecialchars($digimon['nombre']); ?>" width="100">
            </div>
            <form method="post" action="definirEvolucion.php?id=<?php echo htmlspecialchars($digimon['id']); ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($digimon['id']); ?>">
                <div class="mb-3">
                    <label for="evo_id" class="form-label">Evolucionar a</label>
                    <select class="form-control" id="evo_id" name="evo_id" required>
                        <?php foreach ($evoluciones as $evolucion): ?>
                            <option value="<?php echo htmlspecialchars($evolucion['id']); ?>"><?php echo htmlspecialchars($evolucion['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Definir Evolución</button>
            </form>
        <?php elseif (!$digimon): ?>
            <p>No se encontró el Digimon.</p>
        <?php endif; ?>
    </div>
</body>
</html>
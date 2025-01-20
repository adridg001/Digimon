<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id']; // ID del Digimon que se va a evolucionar

    try {
        $conexion = db::conexion();

        // Obtener nivel actual del Digimon
        $sql = "SELECT nivel FROM digimones WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $digimon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($digimon) {
            $nivelActual = $digimon['nivel'];

            // Comprobar si ya está en el nivel máximo
            if ($nivelActual < 4) {
                $nuevoNivel = $nivelActual + 1;

                // Actualizar el nivel del Digimon en la base de datos
                $sqlUpdate = "UPDATE digimones SET nivel = :nuevoNivel WHERE id = :id";
                $stmtUpdate = $conexion->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':nuevoNivel', $nuevoNivel);
                $stmtUpdate->bindParam(':id', $id);
                $stmtUpdate->execute();

                $mensaje = "El Digimon ha evolucionado al nivel $nuevoNivel.";
            } else {
                $mensaje = "Este Digimon ya está en el nivel máximo (Perfecto).";
            }
        } else {
            $mensaje = "No se encontró el Digimon.";
        }
    } catch (PDOException $error) {
        $mensaje = 'Error: ' . $error->getMessage();
    }
}

// Obtener todos los Digimones
try {
    $conexion = db::conexion();
    $sql = "SELECT id, nombre, nivel FROM digimones";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $digimones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    $mensaje = 'Error: ' . $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Evoluciones</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Definir Evoluciones de los Digimones</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Nivel Actual</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($digimones && $stmt->rowCount() > 0): ?>
                    <?php foreach ($digimones as $digimon): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($digimon['id']); ?></td>
                            <td><?php echo htmlspecialchars($digimon['nombre']); ?></td>
                            <td>
                                <?php
                                // Mostrar el nivel actual como texto
                                switch ($digimon['nivel']) {
                                    case 1:
                                        echo "1: Bebe";
                                        break;
                                    case 2:
                                        echo "2: Infantil";
                                        break;
                                    case 3:
                                        echo "3: Adulto";
                                        break;
                                    case 4:
                                        echo "4: Perfecto";
                                        break;
                                    default:
                                        echo "Desconocido";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($digimon['nivel'] < 4): ?>
                                    <form method="post" action="definirEvoluciones.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($digimon['id']); ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Evolucionar</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Nivel Máximo</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No se encontraron Digimones.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>

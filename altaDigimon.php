<?php
require_once 'config/db.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $ataque = $_POST['ataque'];
    $defensa = $_POST['defensa'];
    $tipo = $_POST['tipo'];
    $nivel = $_POST['nivel'];

    if (empty($nombre) || empty($ataque) || empty($defensa) || empty($tipo) || empty($nivel)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        try {
            $conexion = db::conexion();
            $sql = "INSERT INTO digimones (nombre, ataque, defensa, tipo, nivel) VALUES (:nombre, :ataque, :defensa, :tipo, :nivel)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ataque', $ataque);
            $stmt->bindParam(':defensa', $defensa);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nivel', $nivel);
            $stmt->execute();

            // Crear la carpeta para el Digimon
            $carpeta = 'digimones/' . $nombre;
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $mensaje = 'Digimon registrado exitosamente.';
        } catch (PDOException $error) {
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
    <title>Alta Digimon</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Registrar Nuevo Digimon</h1>
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <form method="post" action="altaDigimon.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="ataque" class="form-label">Ataque</label>
                <input type="number" class="form-control" id="ataque" name="ataque" required>
            </div>
            <div class="mb-3">
                <label for="defensa" class="form-label">Defensa</label>
                <input type="number" class="form-control" id="defensa" name="defensa" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="Vacuna">Vacuna</option>
                    <option value="Virus">Virus</option>
                    <option value="Animal">Animal</option>
                    <option value="Planta">Planta</option>
                    <option value="Elemental">Elemental</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nivel" class="form-label">Nivel</label>
                <select class="form-control" id="nivel" name="nivel" required>
                    <option value="1">1: Bebe</option>
                    <option value="2">2: Infantil</option>
                    <option value="3">3: Adulto</option>
                    <option value="4">4: Perfecto</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <a href="index.php" class="btn btn-primary mt-3">Volver a Inicio</a>
    </div>
</body>
</html>
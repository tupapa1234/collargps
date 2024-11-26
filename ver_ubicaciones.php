<?php
include 'db_config.php';

$id_collar = $_GET['id_collar'];

$sql = "SELECT latitud, longitud, fecha_hora FROM ubicaciones WHERE id_collar = :id_collar";
$stmt = $conn->prepare($sql);
$stmt->execute([':id_collar' => $id_collar]);
$ubicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubicaciones del Perro</title>
</head>
<body>
    <h1>Historial de Ubicaciones</h1>
    <ul>
        <?php foreach ($ubicaciones as $ubicacion): ?>
            <li>
                Latitud: <?= $ubicacion['latitud'] ?>, Longitud: <?= $ubicacion['longitud'] ?>, Fecha: <?= $ubicacion['fecha_hora'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
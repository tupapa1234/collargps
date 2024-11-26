<?php
include 'global/config.php';
include 'global/conexion.php';
include 'templates/cabecera.php';
session_start();

// Verificar si hay un usuario autenticado
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Debe iniciar sesión para acceder a esta página'); window.location.href = 'login.php';</script>";
    exit();
}

// ID del usuario autenticado
$user_id = $_SESSION['user_id'];

// Eliminar perro
if (isset($_GET['eliminar'])) {
    $id_perro = intval($_GET['eliminar']);
    $stmt = $pdo->prepare("DELETE FROM info_mascota WHERE id = :id AND propietario_id = :user_id");
    $stmt->bindParam(':id', $id_perro, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo "<script>alert('Perro eliminado correctamente'); window.location.href = 'mis_perros.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el perro');</script>";
    }
}

// Consulta para obtener los perros registrados solo por este usuario
$sentencia = $pdo->prepare("SELECT * FROM info_mascota WHERE propietario_id = :user_id");
$sentencia->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$sentencia->execute();
$perros = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mis Perros</title>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Mis Perros Registrados</h2>
    <div class="row">
        <?php if (count($perros) > 0): ?>
            <?php foreach ($perros as $perro): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow">
                        <?php if (!empty($perro['img_perro'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($perro['img_perro']); ?>" class="card-img-top" alt="Foto del Perro" style="width: 100%; height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/200" class="card-img-top" alt="Foto no disponible" style="width: 100%; height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($perro['nombre']); ?></h5>
                            <p class="card-text">
                                <strong>Edad:</strong> <?php echo htmlspecialchars($perro['edad']); ?> años<br>
                                <strong>Raza:</strong> <?php echo htmlspecialchars($perro['raza']); ?><br>
                                <strong>Peso:</strong> <?php echo htmlspecialchars($perro['peso']); ?> kg<br>
                                <strong>Dirección:</strong> <?php echo htmlspecialchars($perro['direccion']); ?><br>
                                <strong>Teléfono:</strong> <?php echo htmlspecialchars($perro['telefono']); ?>
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="editar_perro.php?id=<?php echo $perro['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="muestraperro.php?eliminar=<?php echo $perro['id']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este perro?');" class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay perros registrados para este usuario.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

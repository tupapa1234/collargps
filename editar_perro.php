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

// Verificar si se recibió el ID del perro
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID de perro no válido.'); window.location.href = 'mis_perros.php';</script>";
    exit();
}

$id_perro = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Obtener la información del perro
$stmt = $pdo->prepare("SELECT * FROM info_mascota WHERE id = :id AND propietario_id = :user_id");
$stmt->bindParam(':id', $id_perro, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$perro = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encontró el perro, redirigir
if (!$perro) {
    echo "<script>alert('No se encontró el perro o no pertenece a este usuario.'); window.location.href = 'mis_perros.php';</script>";
    exit();
}

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $edad = intval($_POST['edad']);
    $raza = filter_var($_POST['raza'], FILTER_SANITIZE_STRING);
    $peso = floatval($_POST['peso']);
    $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);

    // Verificar si hay una imagen cargada
    if (!empty($_FILES['img_perro']['name'])) {
        $img_perro = file_get_contents($_FILES['img_perro']['tmp_name']);
    } else {
        $img_perro = $perro['img_perro']; // Mantener la imagen actual si no se carga una nueva
    }

    // Actualizar los datos del perro en la base de datos
    $sql = "UPDATE info_mascota 
            SET nombre = :nombre, edad = :edad, raza = :raza, peso = :peso, direccion = :direccion, telefono = :telefono, img_perro = :img_perro 
            WHERE id = :id AND propietario_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':raza', $raza);
    $stmt->bindParam(':peso', $peso);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':img_perro', $img_perro, PDO::PARAM_LOB);
    $stmt->bindParam(':id', $id_perro, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Perro actualizado correctamente'); window.location.href = 'muestraperro.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar los datos del perro.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Perro</title>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Información de <?php echo htmlspecialchars($perro['nombre']); ?></h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($perro['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="edad" class="form-label">Edad (en años)</label>
            <input type="number" class="form-control" id="edad" name="edad" value="<?php echo htmlspecialchars($perro['edad']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="raza" class="form-label">Raza</label>
            <input type="text" class="form-control" id="raza" name="raza" value="<?php echo htmlspecialchars($perro['raza']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="peso" class="form-label">Peso (en kg)</label>
            <input type="number" step="0.1" class="form-control" id="peso" name="peso" value="<?php echo htmlspecialchars($perro['peso']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($perro['direccion']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($perro['telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="img_perro" class="form-label">Foto del Perro (opcional)</label>
            <input type="file" class="form-control" id="img_perro" name="img_perro">
            <?php if (!empty($perro['img_perro'])): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($perro['img_perro']); ?>" alt="Foto actual" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="muestraperro.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

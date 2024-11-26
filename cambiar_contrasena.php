<?php
session_start();
include 'global/config.php';
include 'global/conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Debe iniciar sesión para acceder a esta página'); window.location.href = 'login.php';</script>";
    exit();
}

// ID del usuario autenticado
$user_id = $_SESSION['user_id'];

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contrasena_actual = filter_var($_POST['contrasena_actual'], FILTER_SANITIZE_STRING);
    $nueva_contrasena = filter_var($_POST['nueva_contrasena'], FILTER_SANITIZE_STRING);
    $confirmar_contrasena = filter_var($_POST['confirmar_contrasena'], FILTER_SANITIZE_STRING);

    // Validar que todos los campos están completos
    if (empty($contrasena_actual) || empty($nueva_contrasena) || empty($confirmar_contrasena)) {
        echo "<script>alert('Por favor, complete todos los campos');</script>";
    } elseif ($nueva_contrasena !== $confirmar_contrasena) {
        echo "<script>alert('La nueva contraseña y la confirmación no coinciden');</script>";
    } else {
        try {
            // Obtener la contraseña actual del usuario
            $stmt = $pdo->prepare("SELECT contrasena FROM usuarios WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($contrasena_actual, $usuario['contrasena'])) {
                // Encriptar la nueva contraseña
                $hashed_password = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

                // Actualizar la contraseña en la base de datos
                $update_stmt = $pdo->prepare("UPDATE usuarios SET contrasena = :nueva_contrasena WHERE id = :user_id");
                $update_stmt->bindParam(':nueva_contrasena', $hashed_password);
                $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $update_stmt->execute();

                echo "<script>alert('Contraseña actualizada correctamente'); window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('La contraseña actual no es correcta');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error en la base de datos: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cambiar Contraseña</title>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Cambiar Contraseña</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="contrasena_actual" class="form-label">Contraseña Actual</label>
            <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" required>
        </div>
        <div class="mb-3">
            <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
        </div>
        <div class="mb-3">
            <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

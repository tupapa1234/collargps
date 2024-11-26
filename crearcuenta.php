<?php
session_start(); // Iniciar la sesión al principio del archivo
include 'global/config.php';
include 'global/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y recibir los datos del formulario
    $nombre_titular = $_POST['name'];
    $correo = $_POST['email'];
    $contrasena = $_POST['password'];
    $confirm_contrasena = $_POST['confirm-password'];
    
    // Verificar que la contraseña y la confirmación coinciden
    if ($contrasena === $confirm_contrasena) {
        // Hashear la contraseña para mayor seguridad
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        // Preparar y ejecutar la consulta de inserción
        $sql = "INSERT INTO usuarios (nombre_titular, correo, contrasena) VALUES (:nombre_titular, :correo, :contrasena)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nombre_titular', $nombre_titular);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $hashed_password);

        if ($stmt->execute()) {
            // Redirigir o mostrar mensaje de éxito
            echo "<script>alert('Cuenta creada exitosamente'); window.location.href = 'dashboard.php';</script>";
        } else {
            echo "<script>alert('Hubo un error al crear la cuenta');</script>";
        }
    } else {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Crear Cuenta Section -->
    <section class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                            <!-- Imagen de perfil -->
                            <div class="text-center mb-4">
                                <img src="img/logo.webp" alt="Imagen de perfil" class="rounded-circle img-fluid" width="120" height="120">
                            </div>
                            <!-- Título -->
                            <h3 class="text-center mb-4">Crear Cuenta</h3>
                            <form action="" method="POST">
                                <!-- Nombre Completo -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Ingresar nombre" required>
                                </div>
                                <!-- Correo Electrónico -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Ingresar correo" required>
                                </div>
                                <!-- Contraseña -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                </div>
                                <!-- Confirmar Contraseña -->
                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Confirmar Contraseña" required>
                                </div>
                                <!-- Botón de Crear Cuenta -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-block">Crear Cuenta</button>
                                </div>
                                <!-- Separador -->
                                <hr class="my-4">
                                <!-- Enlace para iniciar sesión -->
                                <p class="text-center">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

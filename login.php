<?php
session_start(); // Iniciar la sesión al principio del archivo
include 'global/config.php';
include 'global/conexion.php';

// Verificar la conexión a la base de datos
if (!$pdo) {
    die('Error en la conexión a la base de datos');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar y obtener el correo y la contraseña desde el formulario
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contrasena = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Verificar si los datos fueron enviados
    if (!$correo || !$contrasena) {
        echo "<script>alert('Por favor, completa todos los campos.');</script>";
    } else {
        try {
            // Preparar y ejecutar la consulta para buscar al usuario por su correo
            $sql = "SELECT * FROM usuarios WHERE correo = :correo";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            // Verificar si el usuario existe
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña
                if (password_verify($contrasena, $usuario['contrasena'])) {
                    // Guardar el ID del usuario y su nombre en la sesión
                    $_SESSION['user_id'] = $usuario['id'];  // Suponiendo que 'id' es el campo del ID del usuario
                    $_SESSION['nombre_titular'] = $usuario['nombre_titular'];

                    // Redirigir a la página principal
                    header('Location: dashboard.php');
                    exit();
                } else {
                    echo "<script>alert('Contraseña incorrecta');</script>";
                }
            } else {
                echo "<script>alert('El correo no está registrado');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error en la consulta: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <section class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                        <div class="text-center mb-4">
                                <img src="img/logo.webp" alt="Imagen de perfil" class="rounded-circle img-fluid" width="120" height="120">
                            </div>
                            <h3 class="text-center mb-4">Iniciar Sesión</h3>
                            <form method="POST" action="login.php">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                                </div>
                                <div class="text-center">
                                    <p class="small">¿No tienes una cuenta? <a href="crearcuenta.php">Regístrate aquí</a></p>
                                </div>
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

<?php
session_start();
include 'global/config.php';
include 'global/conexion.php';
include 'templates/cabecera.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Debe iniciar sesión para acceder a esta página'); window.location.href = 'login.php';</script>";
    exit();
}

// Procesar la carga del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $peso = $_POST['peso'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $libreta_sanitaria = $_POST['libreta_sanitaria'];
    $propietario_id = $_SESSION['user_id'];

    // Validar la imagen
    if (isset($_FILES['img_perro']) && $_FILES['img_perro']['error'] === UPLOAD_ERR_OK) {
        $img_perro = file_get_contents($_FILES['img_perro']['tmp_name']);
    } else {
        echo "<script>alert('Por favor, sube una imagen del perro');</script>";
        exit();
    }

    // Insertar los datos en la base de datos
    try {
        $sql = "INSERT INTO info_mascota (nombre, edad, raza, peso, img_perro, direccion, telefono, libreta_sanitaria, propietario_id) 
                VALUES (:nombre, :edad, :raza, :peso, :img_perro, :direccion, :telefono, :libreta_sanitaria, :propietario_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':raza', $raza);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':img_perro', $img_perro, PDO::PARAM_LOB);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':libreta_sanitaria', $libreta_sanitaria);
        $stmt->bindParam(':propietario_id', $propietario_id);
        $stmt->execute();

        echo "<script>alert('Datos del perro registrados exitosamente'); window.location.href = 'muestraperro.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al registrar los datos: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Datos del Perro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <section class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                            <h3 class="text-center mb-4">Cargar Datos del Perro</h3>
                            <form action="cargar_datos_perro.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Perro</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edad" class="form-label">Edad</label>
                                    <input type="number" class="form-control" id="edad" name="edad" required>
                                </div>
                                <div class="mb-3">
                                    <label for="raza" class="form-label">Raza</label>
                                    <input type="text" class="form-control" id="raza" name="raza" required>
                                </div>
                                <div class="mb-3">
                                    <label for="peso" class="form-label">Peso</label>
                                    <input type="number" step="0.01" class="form-control" id="peso" name="peso" required>
                                </div>
                                <div class="mb-3">
                                    <label for="img_perro" class="form-label">Imagen del Perro</label>
                                    <input type="file" class="form-control" id="img_perro" name="img_perro" accept="image/*" required>
                                </div>
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                                </div>
                                <div class="mb-3">
                                    <label for="libreta_sanitaria" class="form-label">Libreta Sanitaria</label>
                                    <input type="text" class="form-control" id="libreta_sanitaria" name="libreta_sanitaria" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Registrar Perro</button>
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

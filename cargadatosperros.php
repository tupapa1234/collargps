<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Perros</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Registrar Perro</h1>
        
        <!-- Formulario para registrar nuevos perros -->
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nombre_perro" class="form-label">Nombre del Perro</label>
                    <input type="text" class="form-control" id="nombre_perro" name="nombre_perro" required>
                </div>
                <div class="col-md-6">
                    <label for="raza" class="form-label">Raza</label>
                    <input type="text" class="form-control" id="raza" name="raza" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="col-md-6">
                    <label for="nombre_dueno" class="form-label">Nombre del Dueño</label>
                    <input type="text" class="form-control" id="nombre_dueno" name="nombre_dueno" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefono" class="form-label">Teléfono del Dueño</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="col-md-6">
                    <label for="direccion" class="form-label">Dirección del Dueño</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                </div>
            </div>
            <button type="submit" name="register" class="btn btn-primary w-100">Registrar</button>
        </form>

        <!-- Formulario para generar código QR -->
        <div class="mt-5">
            <h2 class="text-center">Generar Código QR</h2>
            <form action="generar_qr.php" method="post">
                <div class="mb-3">
                    <label for="id_mascota" class="form-label">ID de la Mascota</label>
                    <input type="number" class="form-control" name="id_mascota" id="id_mascota" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Generar QR</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <?php
        // Incluimos la conexión a la base de datos
        include("db_config.php");

        if (isset($_POST['register'])) {
            // Verificar conexión
            if ($conex) {
                // Obtener los datos del formulario
                $nombre_perro = $_POST['nombre_perro'];
                $raza = $_POST['raza'];
                $fecha_nacimiento = $_POST['fecha_nacimiento'];
                $nombre_dueno = $_POST['nombre_dueno'];
                $telefono = $_POST['telefono'];
                $direccion = $_POST['direccion'];

                // Insertar los datos en la tabla info_mascota
                $query = "INSERT INTO info_mascota (nombre, raza, fecha_nacimiento, nombre_dueno, telefono, direccion) 
                          VALUES ('$nombre_perro', '$raza', '$fecha_nacimiento', '$nombre_dueno', '$telefono', '$direccion')";
                $result = mysqli_query($conex, $query);

                if ($result) {
                    echo "<div class='alert alert-success mt-3'>¡Perro registrado con éxito!</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Error al registrar el perro.</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-3'>Error de conexión a la base de datos.</div>";
            }
        }
    ?>
</body>
</html>

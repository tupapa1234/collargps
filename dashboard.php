<?php 
include 'templates/cabecera.php';
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION['nombre_titular'])) {
    header('Location: login.php');
    exit();
} else {
    // Si la sesión está iniciada, pasar el mensaje al frontend
    echo '<script>alert("Sesión iniciada correctamente");</script>';
}

?>
        <div class="row">
            <!-- Columna Izquierda: Lista de mascotas -->
            <div class="col-md-4">
                <h4>Mis Perros</h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="https://via.placeholder.com/50" alt="Foto del perro" class="rounded-circle me-2">
                            <span>Max</span>
                        </div>
                        <span>Salud: Excelente</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="https://via.placeholder.com/50" alt="Foto del perro" class="rounded-circle me-2">
                            <span>Luna</span>
                        </div>
                        <span>Salud: En revisión</span>
                    </li>
                </ul>
                <a href="cargar_datos_perro.php" class="btn btn-primary w-100">Agregar nueva mascota</a>
            </div>

            <!-- Columna Derecha: Detalles de la mascota seleccionada -->
            <div class="col-md-8">
                <h4>Detalles de Max</h4>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <img src="https://via.placeholder.com/100" alt="Foto de Max" class="rounded-circle me-3">
                            <div>
                                <h5>Max</h5>
                                <p>Dirección: Calle Falsa 123</p>
                                <p>Propietario: Juan Pérez</p>
                            </div>
                        </div>
                        <hr>
                        <h5>Código QR</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="https://via.placeholder.com/100" alt="Código QR" class="img-fluid">
                            <button class="btn btn-outline-primary">Descargar QR</button>
                        </div>
                        <hr>
                        <h5>Libreta Sanitaria</h5>
                        <button class="btn btn-outline-secondary">Subir documentos médicos</button>
                    </div>
                </div>

                <!-- Mapa de Ubicación Actual -->
                <h4>Ubicación Actual</h4>
                <div class="card">
                    <div class="card-body">
                        <!-- Aquí puedes integrar un servicio como Google Maps -->
                        <div id="map" style="height: 300px; background-color: #ddd;"></div>
                        <p>Ubicación actual de Max</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS y Popper.js para la funcionalidad interactiva -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para el mapa -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mapElement = document.getElementById('map');
            mapElement.innerHTML = 'Aquí se mostrará el mapa con la ubicación actual';
        });
    </script>
</body>
</html>

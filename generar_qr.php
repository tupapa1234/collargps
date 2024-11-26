<?php
require_once 'phpqrcode/src/QRCode.php';
require_once 'phpqrcode/src/QROptions.php';
require_once 'phpqrcode/src/Output/QRImage.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

// Incluir configuración y conexión a la base de datos
include 'global/config.php';
include 'global/conexion.php';

try {
    // Consulta para obtener información de la mascota
    $idMascota = 1; // Cambia esto por el ID que quieres usar dinámicamente
    $query = $pdo->prepare("SELECT * FROM info_mascota WHERE id = :id");
    $query->bindParam(':id', $idMascota, PDO::PARAM_INT);
    $query->execute();
    $mascota = $query->fetch(PDO::FETCH_ASSOC);

    if ($mascota) {
        // Información para el QR
        $contenidoQR = json_encode([
            'Nombre' => $mascota['nombre'],
            'Edad' => $mascota['edad'],
            'Raza' => $mascota['raza'],
            'Peso' => $mascota['peso'],
            'Dirección' => $mascota['direccion'],
            'Teléfono' => $mascota['telefono'],
            'Libreta Sanitaria' => $mascota['libreta_sanitaria']
        ]);

        // Configuración del QR
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 5,
        ]);

        $qrcode = new QRCode($options);

        // Ruta donde se guardará el archivo QR
        $carpetaQR = 'qrcodes/';
        if (!file_exists($carpetaQR)) {
            mkdir($carpetaQR, 0777, true); // Crea la carpeta si no existe
        }

        $archivoQR = $carpetaQR . 'mascota_' . $mascota['id'] . '.png';

        // Generar el QR y guardarlo en el archivo
        $qrcode->render($contenidoQR, $archivoQR);

        // Actualizar la base de datos con la ruta del QR
        $updateQuery = $pdo->prepare("UPDATE info_mascota SET qr_code_path = :path WHERE id = :id");
        $updateQuery->bindParam(':path', $archivoQR, PDO::PARAM_STR);
        $updateQuery->bindParam(':id', $idMascota, PDO::PARAM_INT);
        $updateQuery->execute();

        echo "Código QR generado y guardado en: " . $archivoQR;
    } else {
        echo "Mascota no encontrada.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

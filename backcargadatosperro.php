<?php
include("db_config.php");
if (isset($_POST['register'])){
   (strlen($_POST['nombre_perro']) > 1 && strlen($_POST['raza']) > 1 &&  strlen($_POST['fecha_nacimiento']) > 1 && strlen($_POST['nombre_dueno']) > 1 &&  strlen($_POST['telefono']) > 1 &&  strlen($_POST['direccion']) > 1){
    $nombre_perro = $_POST['nombre_perro'];
    $raza = $_POST['raza'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nombre_dueno = $_POST['nombre_dueno'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $consulta= "INSERT INTO `perros`(``, `nombre_perro`, `raza`, `fecha_nacimiento`, `id_dueno`, `genero`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]')"
   }
}
?>

<?php
<<<<<<< HEAD
$servername = "localhost";
$username = "root"; // Ajusta según tu configuración
$password = ""; // Ajusta según tu configuración
$dbname = "desafios_fitness"; // Nombre de tu base de datos
=======
//Mis credenciales de la base de datos
$dbHost ='localhost';
$dbUsername ='root';
$dbPassword ='';
$dbDatabase ='desafios_fitness';
$conn=mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbDatabase);
>>>>>>> 2335f31b72a045773e1970664637f160f4cc35e6

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit();
}
?>
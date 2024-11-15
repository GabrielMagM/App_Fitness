<?php
include('../database/config.php'); // Incluir la configuración para la base de datos

// Consulta SQL para obtener todos los desafíos
$sql = "SELECT * FROM challenges"; // Consulta para obtener todos los desafíos
$result = $conn->query($sql);

$challenges = []; // Creamos un arreglo vacío para almacenar los desafíos

// Verificar si la consulta devuelve resultados
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Almacenar los datos del desafío
        $challenge = [
            'title' => $row['title'],
            'duration' => $row['duration'],
            'description' => $row['description'],
            'stages' => [] // Inicializamos el arreglo de etapas
        ];

        // Obtener las etapas del desafío (suponiendo que tienes una tabla 'stages' con un campo 'challenge_id')
        $stage_sql = "SELECT * FROM stages WHERE challenge_id = " . $row['id']; // Ajusta según tu estructura
        $stage_result = $conn->query($stage_sql);

        if ($stage_result->num_rows > 0) {
            while($stage_row = $stage_result->fetch_assoc()) {
                // Almacenar cada etapa en el arreglo
                $challenge['stages'][] = [
                    'stage_num' => $stage_row['stage_num'],
                    'stage_title' => $stage_row['stage_title'],
                    'stage_description' => $stage_row['stage_description']
                ];
            }
        }

        // Agregar el desafío con las etapas al arreglo general
        $challenges[] = $challenge;
    }
} else {
    $challenges = null; // Si no hay resultados
}

$conn->close(); // Cerramos la conexión
?>
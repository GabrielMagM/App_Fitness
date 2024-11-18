<?php
// Verificar si se pasó el ID del desafío y la etapa actual
if (isset($_POST['challenge_id']) && isset($_POST['current_stage'])) {
    $challenge_id = $_POST['challenge_id'];
    $current_stage = $_POST['current_stage'];

    // Conectar a la base de datos
    include('../database/config.php');

    // Obtener las etapas del desafío
    $stages_query = "SELECT * FROM stages WHERE challenge_id = $challenge_id ORDER BY stage_num";
    $stages_result = $conn->query($stages_query);
    $total_stages = $stages_result->num_rows;

    $stage_content = ''; // Para almacenar el contenido de la etapa

    if ($stages_result->num_rows > 0) {
        // Buscar la etapa actual y preparar el contenido
        while ($stage = $stages_result->fetch_assoc()) {
            if ($stage['stage_num'] == $current_stage) {
                // Agregar el contenido de la etapa
                $stage_content .= "<p class='text-black'>Etapa: {$stage['stage_num']} / {$total_stages} : " . htmlspecialchars($stage['stage_name']) . "</p>";
                $stage_content .= "<p class='text-black'>" . htmlspecialchars($stage['stage_goal']) . "</p>";

                // Si es la última etapa, mostrar el botón de "Completar Desafío"
                if ($current_stage == $total_stages) {
                    $stage_content .= "<form id='complete-form' action='../assets/completeChallenge.php' method='POST'>
                        <input type='hidden' name='challenge_id' value='$challenge_id'>
                        <button type='submit' class='bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4'>Completar Desafío</button>
                    </form>";
                } else {
                    // Si no es la última etapa, mostrar el botón de "Siguiente Etapa"
                    $next_stage = $current_stage + 1;
                    $stage_content .= "<button type='button' class='bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-4' onclick='updateStage($challenge_id, $next_stage)'>Siguiente Etapa</button>";
                }
            }
        }
    }

    // Devolver el contenido de la etapa
    echo $stage_content;
}
?>
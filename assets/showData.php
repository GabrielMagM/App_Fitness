<?php
include('getData.php'); // Incluir el archivo que obtiene los datos

// Verificar si tenemos desafíos
if ($challenges) {
    foreach ($challenges as $challenge) {
        // Mostrar los datos del desafío
        echo '<h2 class="text-lg font-bold mb-2 text-black">Desafío: ' . htmlspecialchars($challenge['title']) . '</h2>';
        echo '<p><strong>Duración:</strong> ' . htmlspecialchars($challenge['duration']) . '</p>';
        echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($challenge['description']) . '</p>';

        // Mostrar las etapas
        echo '<h3 class="font-bold mt-4">Etapas:</h3>';
        $total_etapas = count($challenge['stages']);
        foreach ($challenge['stages'] as $index => $stage) {
            // Calcular el índice (1-based) y el total de etapas
            echo '<p><strong>Etapa ' . ($index + 1) . ':</strong> ';
            echo 'Etapa ' . ($index + 1) . ' (stage_num: ' . $stage['stage_num'] . ') / ' . $total_etapas . ' ';
            echo ': ' . htmlspecialchars($stage['stage_title']) . ' - ' . htmlspecialchars($stage['stage_description']) . '</p>';
        }
    }
} else {
    echo "No se encontraron desafíos.";
}
?>
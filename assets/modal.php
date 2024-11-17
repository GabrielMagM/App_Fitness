<script>
function nextStage(challengeId, currentStageNum) {
    // Obtener las etapas del desafío
    const stageContainer = document.getElementById('stage-container-' + challengeId);
    const stages = stageContainer.getElementsByTagName('p');
    
    // Obtener la siguiente etapa
    let nextStage = null;
    for (let i = 0; i < stages.length; i++) {
        if (stages[i].textContent.includes(currentStageNum + ' /')) {
            nextStage = stages[i + 1];
            break;
        }
    }

    // Si hay una siguiente etapa, mostramos los datos de la siguiente etapa
    if (nextStage) {
        // Mostrar la siguiente etapa
        nextStage.style.display = 'block';
        
        // Si llegamos a la última etapa, mostramos el botón de completar desafío
        if (currentStageNum + 1 === <?php echo $challenge['total_stages']; ?>) {
            document.getElementById('complete-btn-' + challengeId).style.display = 'inline-block';
        }
        
        // Ocultamos el botón de "Siguiente"
        const nextButton = document.querySelector(`#stage-container-${challengeId} button`);
        nextButton.style.display = 'none';
    }
}
</script>
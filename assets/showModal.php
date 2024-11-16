<?php if (!empty($availableChallenges)): ?>
    <?php foreach ($availableChallenges as $challenge): ?>
        <!-- Desafío en la lista -->
        <li class="mb-2 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-200" onclick="openModal(<?php echo $challenge['id']; ?>)">
            <?php echo htmlspecialchars($challenge['description']); ?>
        </li>

        <!-- Modal para mostrar detalles del desafío -->
        <div class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" id="modal-<?php echo $challenge['id']; ?>">
            <div class="modal-content bg-white rounded-lg shadow-lg w-11/12 sm:w-1/3 p-6">
                <h2 class="text-xl font-bold text-black mb-4" id="modal-description-<?php echo $challenge['id']; ?>"></h2>
                <p class="text-black"><strong>Duración:</strong> <span id="modal-duration-<?php echo $challenge['id']; ?>"></span> días</p>
                <p class="text-black"><strong>Objetivo:</strong> <span id="modal-goal-<?php echo $challenge['id']; ?>"></span></p>
                
                <h3 class="text-lg font-semibold text-black mt-4">Etapas:</h3>
                <ul id="modal-stages-<?php echo $challenge['id']; ?>" class="list-disc ml-6"></ul>

                <form action="../assets/selectChallenge.php" method="POST" class="mt-4">
                    <input type="hidden" name="challenge_id" value="<?php echo $challenge['id']; ?>">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full">Unirse al Desafío</button>
                </form>

                <button type="button" class="close-modal mt-4 w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded" onclick="closeModal(<?php echo $challenge['id']; ?>)">Cerrar</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <li class="mb-2 p-2 bg-white rounded shadow">No hay desafíos disponibles en este momento.</li>
<?php endif; ?>


<script>
    // Función para abrir el modal
    function openModal(challengeId) {
        document.getElementById('modal-' + challengeId).style.display = 'flex';

        // Realizamos una solicitud AJAX para obtener los detalles del desafío y las etapas
        fetch(`api.php?challenge_id=${challengeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const challenge = data.data.challenge;
                    const stages = data.data.stages;

                    // Asignamos los datos del desafío en el modal
                    document.getElementById('modal-description-' + challengeId).innerText = challenge.description;
                    document.getElementById('modal-duration-' + challengeId).innerText = challenge.duration;
                    document.getElementById('modal-goal-' + challengeId).innerText = challenge.goal;

                    // Llenamos las etapas en el modal
                    const stagesContainer = document.getElementById('modal-stages-' + challengeId);
                    stagesContainer.innerHTML = ''; // Limpiar etapas previas
                    stages.forEach(stage => {
                        const stageItem = document.createElement('li');
                        stageItem.innerHTML = `<strong>Etapa ${stage.stage_num}:</strong> ${stage.stage_name} - ${stage.stage_goal}`;
                        stagesContainer.appendChild(stageItem);
                    });
                } else {
                    alert('Error al obtener los detalles del desafío.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al cargar los detalles del desafío.');
            });
    }

    // Función para cerrar el modal
    function closeModal(challengeId) {
        document.getElementById('modal-' + challengeId).style.display = 'none';
    }
</script>


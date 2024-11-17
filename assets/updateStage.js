function updateStage(challengeId, currentStage) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../assets/nextStage.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Enviamos los datos
    var data = 'challenge_id=' + challengeId + '&current_stage=' + currentStage;
    xhr.send(data);

    // Cuando la solicitud termine, actualizamos el contenido del modal
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Aquí obtenemos el contenido nuevo de la etapa
            var modalContent = document.getElementById('modal-' + challengeId).querySelector('.modal-content');
            modalContent.innerHTML = xhr.responseText; // Actualizamos el contenido

            // Opcional: podrías querer cerrar el modal o añadir algún otro comportamiento
        } else {
            console.error('Error al actualizar la etapa');
        }
    };
}

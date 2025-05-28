<?php require_once __DIR__ . '/chatbot_functions.php';
$user_input = isset($_POST['mensaje']) ? $_POST['mensaje'] : '';
$respuesta = buscarRespuesta($user_input);
if ($respuesta) {
    echo $respuesta;
} else {
    echo "Lo siento, no tengo una respuesta para esa consulta. ¿Podrías reformular tu pregunta?";
}

<?php require_once __DIR__ . '/chatbot_functions.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['masivo'])) {
    // Asegura compatibilidad con saltos de línea de Windows, Mac y Linux 
    $lineas = preg_split('/\r\n|\r|\n/', trim($_POST['masivo']));
    $agregadas = 0;
    foreach ($lineas as $linea) {
        $partes = explode('|', $linea, 2);
        // separador: | 
        if (count($partes) == 2) {
            $pregunta = trim($partes[0]);
            $respuesta = trim($partes[1]);
            if ($pregunta && $respuesta) {
                $stmt = conectarDB()->prepare("INSERT INTO chatbot_respuestas (pregunta, respuesta) VALUES (?, ?)");
                $stmt->execute([$pregunta, $respuesta]);
                $agregadas++;
            }
        }
    }
    echo "<p style='color:green;'>¡$agregadas preguntas y respuestas agregadas!</p>";
} ?> <form method="post"> <label>Pega aquí preguntas y respuestas (separadas por |, una por línea):<br> <textarea name="masivo" rows="10" cols="60" placeholder="consulta|Para agendar una consulta…"></textarea> </label><br> <button type="submit">Cargar masivamente</button> </form>
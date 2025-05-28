<?php
// Conexión a la base de datos 
$conn = new PDO("mysql:host=localhost;dbname=u330560936_laboratorio", "u330560936_inbioslab", "41950361Cesarp$");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pregunta = trim($_POST['pregunta']);
    $respuesta = trim($_POST['respuesta']);
    if ($pregunta && $respuesta) {
        $stmt = $conn->prepare("INSERT INTO chatbot_respuestas (pregunta, respuesta) VALUES (?, ?)");
        $stmt->execute([$pregunta, $respuesta]);
        echo "<p>¡Pregunta y respuesta agregadas!</p>";
    }
} ?>
<form method="post">
    <label>Pregunta:<br><input type="text" name="pregunta" required></label><br>
    <label>Respuesta:<br><textarea name="respuesta" required></textarea></label><br>
    <button type="submit">Agregar</button>
</form>
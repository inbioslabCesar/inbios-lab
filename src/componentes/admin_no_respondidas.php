<?php require_once __DIR__ . '/chatbot_functions.php';
$conn = conectarDB();
// Procesar respuesta desde el panel 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pregunta'], $_POST['respuesta'])) {
    $pregunta = trim($_POST['pregunta']);
    $respuesta = trim($_POST['respuesta']);
    $palabras_clave = isset($_POST['palabras_clave']) ? trim($_POST['palabras_clave']) : null;
    $stmt = $conn->prepare("INSERT INTO chatbot_respuestas (pregunta, respuesta, palabras_clave) VALUES (?, ?, ?)");
    $stmt->execute([$pregunta, $respuesta, $palabras_clave]);
    $stmt = $conn->prepare("DELETE FROM chatbot_no_respondidas WHERE pregunta = ?");
    $stmt->execute([$pregunta]);
    echo "<p style='color:green;'>¡Respuesta agregada y pregunta marcada como respondida!</p>";
}
$no_respondidas = obtenerNoRespondidas(); ?> <h2>Preguntas no respondidas</h2> <?php if (empty($no_respondidas)): ?> <p style="color:blue;">No hay preguntas pendientes.</p> <?php else: ?> <table border="1" cellpadding="8" style="border-collapse:collapse;">
        <tr>
            <th>Pregunta</th>
            <th>Fecha</th>
            <th>Responder</th>
        </tr> <?php foreach ($no_respondidas as $row): ?> <tr>
                <td><?php echo htmlspecialchars($row['pregunta']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                <td>
                    <form method="post" style="margin:0;"> <input type="hidden" name="pregunta" value="<?php echo htmlspecialchars($row['pregunta']); ?>"> <textarea name="respuesta" rows="2" cols="30" placeholder="Escribe la respuesta aquí..." required></textarea><br> <input type="text" name="palabras_clave" placeholder="Palabras clave (opcional)"> <button type="submit">Agregar respuesta</button> </form>
                </td>
            </tr> <?php endforeach; ?>
    </table> <?php endif; ?>
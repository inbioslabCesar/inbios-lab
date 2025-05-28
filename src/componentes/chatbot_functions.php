<?php function conectarDB()
{
    return new PDO("mysql:host=localhost;dbname=u330560936_laboratorio", "u330560936_inbioslab", "41950361Cesarp$");
}
function buscarRespuesta($mensaje)
{
    $conn = conectarDB();
    $mensaje = strtolower(trim($mensaje));
    // Coincidencia exacta 
    $stmt = $conn->prepare("SELECT respuesta FROM chatbot_respuestas WHERE LOWER(pregunta) = :pregunta LIMIT 1");
    $stmt->execute([':pregunta' => $mensaje]);
    $respuesta = $stmt->fetchColumn();
    if ($respuesta) return $respuesta;
    // Coincidencia por palabras clave 
    $stmt = $conn->query("SELECT palabras_clave, respuesta FROM chatbot_respuestas WHERE palabras_clave IS NOT NULL");
    foreach ($stmt as $row) {
        $claves = array_map('trim', explode(',', strtolower($row['palabras_clave'])));
        foreach ($claves as $clave) {
            if ($clave && strpos($mensaje, $clave) !== false) {
                return $row['respuesta'];
            }
        }
    }
    // Coincidencia por similitud (Levenshtein) 
    $stmt = $conn->query("SELECT pregunta, respuesta FROM chatbot_respuestas");
    $mejorRespuesta = null;
    $menorDistancia = 100;
    foreach ($stmt as $row) {
        $distancia = levenshtein($mensaje, strtolower($row['pregunta']));
        if ($distancia < $menorDistancia) {
            $menorDistancia = $distancia;
            $mejorRespuesta = $row['respuesta'];
        }
    }
    if ($menorDistancia < 10 && $mejorRespuesta) return $mejorRespuesta;
    // Registrar como no respondida 
    registrarNoRespondida($mensaje);
    return null;
}
function registrarNoRespondida($mensaje)
{
    $conn = conectarDB();
    // Evitar duplicados 
    $stmt = $conn->prepare("SELECT COUNT(*) FROM chatbot_no_respondidas WHERE pregunta = ?");
    $stmt->execute([$mensaje]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $conn->prepare("INSERT INTO chatbot_no_respondidas (pregunta) VALUES (?)");
        $stmt->execute([$mensaje]);
    }
}
function obtenerNoRespondidas()
{
    $conn = conectarDB();
    $stmt = $conn->query("SELECT * FROM chatbot_no_respondidas ORDER BY fecha DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Carga masiva de preguntas y respuestas 
function cargarMasivo($texto)
{
    $conn = conectarDB();
    $lineas = explode("\n", trim($texto));
    foreach ($lineas as $linea) {
        $partes = explode('|', $linea, 2);
        if (count($partes) == 2) {
            $pregunta = trim($partes[0]);
            $respuesta = trim($partes[1]);
            $stmt = $conn->prepare("INSERT INTO chatbot_respuestas (pregunta, respuesta) VALUES (?, ?)");
            $stmt->execute([$pregunta, $respuesta]);
        }
    }
}

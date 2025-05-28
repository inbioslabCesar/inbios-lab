<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
// Recibir los síntomas desde POST tradicional o JSON 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sintomas'])) {
    $sintomas = $_POST['sintomas'];
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $sintomas = $data['sintomas'] ?? [];
}
if (empty($sintomas)) {
    echo json_encode(['resultado' => 'Por favor, selecciona al menos un síntoma.']);
    exit;
} // Buscar enfermedades asociadas a los síntomas seleccionados 
$placeholders = implode(',', array_fill(0, count($sintomas), '?'));
$sql = " SELECT DISTINCT e.id, e.nombre, e.descripcion FROM enfermedades e JOIN enfermedad_sintoma es ON e.id = es.enfermedad_id JOIN sintomas s ON es.sintoma_id = s.id WHERE s.nombre IN ($placeholders) ";
$stmt = $pdo->prepare($sql);
$stmt->execute($sintomas);
$enfermedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
$resultado = "";
if ($enfermedades) {
    foreach ($enfermedades as $enfermedad) {
        $resultado .= "<b>" . htmlspecialchars($enfermedad['nombre']) . "</b>: " . htmlspecialchars($enfermedad['descripcion']) . "<br>Exámenes recomendados:<ul>";
        // Buscar exámenes recomendados para cada enfermedad, incluyendo el precio 
        $sql_examen = " SELECT ex.nombre, ex.descripcion, pe.precio FROM examenes ex JOIN enfermedad_examen ee ON ex.id = ee.examen_id LEFT JOIN precios_examenes pe ON ex.id = pe.examen_id WHERE ee.enfermedad_id = ? ";
        $stmt_examen = $pdo->prepare($sql_examen);
        $stmt_examen->execute([$enfermedad['id']]);
        $examenes = $stmt_examen->fetchAll(PDO::FETCH_ASSOC);
        foreach ($examenes as $examen) {
            $precio = isset($examen['precio']) && $examen['precio'] !== null ? ' - $' . number_format($examen['precio'], 2) : '';
            $resultado .= "<li>" . htmlspecialchars($examen['nombre']) . ": " . htmlspecialchars($examen['descripcion']) . $precio . "</li>";
        }
        $resultado .= "</ul><hr>";
    }
} else {
    $resultado = "No se encontraron enfermedades asociadas a los síntomas seleccionados.";
} // Guardar historial de consulta si el usuario está logueado 
if (isset($_SESSION['cliente_id'])) {
    $stmt_hist = $pdo->prepare("INSERT INTO historial_consultas (cliente_id, sintomas, resultado) VALUES (?, ?, ?)");
    $stmt_hist->execute([$_SESSION['cliente_id'], implode(', ', $sintomas), $resultado]);
}
echo json_encode(['resultado' => $resultado]);

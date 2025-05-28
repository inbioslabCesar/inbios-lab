<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } 
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['cliente_id'])) {
    echo json_encode([]);
    exit;
}
$stmt = $pdo->prepare("SELECT fecha, sintomas, resultado FROM historial_consultas WHERE cliente_id = ? ORDER BY fecha DESC LIMIT 10");
$stmt->execute([$_SESSION['cliente_id']]);
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($historial);

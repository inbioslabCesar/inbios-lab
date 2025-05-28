<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } 
require_once 'src/db.php'; 
// Ajusta la ruta si tu archivo está en otra carpeta 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nuevo_avatar'])) {
    $userId = $_SESSION['usuario_id'];
    $uploadDir = '../avatars/';
    $uploadUrl = '/avatars/';
    $file = $_FILES['nuevo_avatar'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $ext;
    $destination = $uploadDir . $newFileName;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Guarda solo la ruta en la base de datos 
        $avatarUrl = $uploadUrl . $newFileName;
        $stmt = $pdo->prepare("UPDATE clientes SET avatar = ? WHERE id = ?");
        $stmt->execute([$avatarUrl, $userId]);
        $_SESSION['avatar'] = $avatarUrl;
        header('Location: ../index.php?avatar=ok');
        exit;
    } else {
        echo "Error al subir el archivo.";
    }
}

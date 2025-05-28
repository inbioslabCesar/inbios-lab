<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } 
// Verifica si el usuario está logueado y tiene permisos 
if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
    echo "<script> alert('Acceso denegado: debes iniciar sesión para ver esta página.'); window.location.href='login.php'; </script>";
    exit();
}

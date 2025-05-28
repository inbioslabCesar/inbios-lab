<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    echo "<div class='alert alert-danger'>No tienes permisos para acceder a la configuración.</div>";
    exit;
} ?>
<?php var_dump($_SESSION); // Solo para depuración, luego bórralo 
?>
<?php // Asegúrate de proteger esta sección solo para admins 
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    echo "<div class='alert alert-danger'>No tienes permisos para acceder a la configuración.</div>";
    exit;
}
// Determina el submódulo a mostrar (roles, chatbot, etc.) 
$submodulo = $_GET['submodulo'] ?? 'roles';
// Menú de configuración 
?> <h2>Configuración General</h2>
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"> <a class="nav-link <?= $submodulo === 'roles' ? 'active' : '' ?>" href="?modulo=configuracion&submodulo=roles">Roles y usuarios</a> </li>
    <li class="nav-item"> <a class="nav-link <?= $submodulo === 'chatbot' ? 'active' : '' ?>" href="?modulo=configuracion&submodulo=chatbot">Administrar chatbot</a> </li>
    <li class="nav-item"> <a class="nav-link <?= $submodulo === 'no_respondidas' ? 'active' : '' ?>" href="?modulo=configuracion&submodulo=no_respondidas">Preguntas no respondidas</a> </li>
    <li class="nav-item"> <a class="nav-link <?= $submodulo === 'masivo' ? 'active' : '' ?>" href="?modulo=configuracion&submodulo=masivo">Carga masiva</a> </li>
</ul>
<div class="mt-3"> <?php $moduloPath = __DIR__ . "/configuracion/{$submodulo}.php";
                    if (file_exists($moduloPath)) {
                        include $moduloPath;
                    } else {
                        echo "<div class='alert alert-warning'>Módulo no encontrado.</div>";
                    } ?> </div>
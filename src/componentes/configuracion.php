<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
    echo "<div class='alert alert-danger'>No tienes permisos para acceder a la configuración.</div>";
    exit;
}

$submodulo = $_GET['submodulo'] ?? 'roles';
$submodulos = ['roles' => 'Roles', 'chatbot' => 'Chatbot', 'no_respondidas' => 'No Respondidas', 'masivo' => 'Carga Masiva']; ?> <div class="container-fluid py-4" id="contenido-principal">
    <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist"> <?php foreach ($submodulos as $id => $nombre): ?> <li class="nav-item" role="presentation"> <a class="nav-link <?php echo ($submodulo === $id) ? 'active bg-primary text-white' : ''; ?>" href="?modulo=configuracion&submodulo=<?php echo $id; ?>"> <?php echo $nombre; ?> </a> </li> <?php endforeach; ?> </ul>
    <div class="tab-content p-4 bg-white rounded shadow-sm"> <?php $ruta = __DIR__ ."/configuracion/{$submodulo}.php";
                                                                if (file_exists($ruta)) {
                                                                    include $ruta;
                                                                } else {
                                                                    echo '<div class="alert alert-warning text-center">El submódulo seleccionado no existe.</div>';
                                                                } ?> <div class="mt-4 text-end"> <a href="index.php?modulo=panel" class="btn btn-secondary"> <i class="bi bi-arrow-left"></i> Volver al panel </a> <button class="btn btn-success ms-2"> <i class="bi bi-save"></i> Guardar cambios </button> </div>
    </div>
</div>
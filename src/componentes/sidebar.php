<!-- src/componentes/sidebar.php -->
<!-- Offcanvas Sidebar (solo móvil) -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header justify-content-center">
        <div class="w-100 d-flex justify-content-center align-items-center"> <img src="/images/inbioslab-logo.png" alt="Logo" class="sidebar-logo" style="max-width: 120px;"> </div> <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item mb-2"> <a href="index.php" class="nav-link active" title="Panel"> <i class="bi bi-house me-2"></i> Panel </a> </li>
            <li class="nav-item mb-2"> <a href="#" class="nav-link" title="Historial" data-bs-toggle="modal" data-bs-target="#historialModal"> <i class="bi bi-clock-history me-2"></i> Historial </a> </li>
            <li class="nav-item mb-2"> <a class="nav-link" href="?modulo=configuracion"> <i class="bi bi-gear"></i> Configuración </a> </li>
        </ul> <!-- Botones solo en móvil -->
        <div class="mt-4 d-lg-none"> <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#historialModal"> <i class="bi bi-clock-history me-2"></i> Historial </button> <a href="logout.php" class="btn btn-danger w-100"> <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión </a> </div>
    </div>
</div> <!-- Sidebar fijo solo en escritorio -->
<div class="sidebar d-none d-lg-block">
    <div class="w-100 d-flex justify-content-center align-items-center mt-3"> <img src="/images/inbioslab-logo.png" alt="Logo" class="sidebar-logo" style="max-width: 120px;"> </div>
    <ul class="nav nav-pills flex-column mt-4">
        <li class="nav-item mb-2"> <a href="index.php" class="nav-link active" title="Panel"> <i class="bi bi-house me-2"></i> Panel </a> </li>
        <li class="nav-item mb-2"> <a href="#" class="nav-link" title="Historial" data-bs-toggle="modal" data-bs-target="#historialModal"> <i class="bi bi-clock-history me-2"></i> Historial </a> </li>
        <li class="nav-item mb-2"><a class="nav-link" href="?modulo=configuracion"> <i class="bi bi-gear"></i> Configuración </a>  </li>
    </ul>
</div>
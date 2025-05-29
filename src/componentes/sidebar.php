<!-- Sidebar fijo en escritorio -->
<div class="sidebar d-none d-lg-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 260px; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1030;" id="sidebar-desktop"> <a class="navbar-brand mb-4" href="#"> <img src="/images/inbioslab-logo.png" alt="INBIOSLAB" width="80" height="80" class="d-inline-block align-top"> <span class="fs-4">INBIOSLAB</span> </a>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item"> <a href="index.php?modulo=panel" class="nav-link">Panel</a> </li>
        <li> <a href="index.php?modulo=historial" class="nav-link">Historial</a> </li>
        <li> <a href="index.php?modulo=configuracion" class="nav-link">Configuración</a> </li>
        <li> <a href="logout.php" class="nav-link text-danger">Cerrar sesión</a> </li>
    </ul>
</div> <!-- Sidebar offcanvas para móvil -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header"> <a class="navbar-brand" href="#"> <img src="ruta/al/logo.png" alt="INBIOSLAB" width="50" height="50" class="d-inline-block align-top"> INBIOSLAB </a> <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button> </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"> <a href="index.php?modulo=panel" class="nav-link">Panel</a> </li>
            <li> <a href="index.php?modulo=historial" class="nav-link">Historial</a> </li>
            <li> <a href="index.php?modulo=configuracion" class="nav-link">Configuración</a> </li>
            <li> <a href="logout.php" class="nav-link text-danger">Cerrar sesión</a> </li>
        </ul>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3 justify-content-between">
    <div class="d-flex align-items-center"> <!-- Avatar grande y bienvenida -->
        <div class="position-relative me-3"> <?php if (!empty($avatar) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatar)): ?> <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="rounded-circle border" width="80" height="80" id="userAvatar"> <?php else: ?> <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border" style="width:80px; height:80px; color: #fff; font-size: 1rem;"> Tu foto </div> <?php endif; ?> <!-- Botón de editar avatar --> <button class="btn btn-sm btn-outline-primary position-absolute bottom-0 end-0 p-0" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#avatarModal" title="Actualizar foto"> <i class="bi bi-camera"></i> </button> </div> <span class="fw-bold fs-5">¡Bienvenido, <?php echo htmlspecialchars($nombre); ?>!</span>
    </div> <!-- Botón hamburguesa solo en móvil --> <button class="btn d-lg-none ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas"> <i class="bi bi-list fs-2"></i> </button> <!-- Botones solo en escritorio -->
    <div class="d-none d-lg-flex align-items-center ms-auto"> <button class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#historialModal">Historial</button> <a href="logout.php" class="btn btn-danger ms-2">Cerrar sesión</a> </div>
</nav> <!-- Modal para actualizar avatar -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" method="POST" action="/actualizar_avatar.php" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Actualizar foto de perfil</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center"> <!-- Vista previa del avatar --> <img id="previewAvatar" src="<?php echo (!empty($avatar) && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatar)) ? htmlspecialchars($avatar) : '/avatars/avatar1.png'; ?>" class="rounded-circle border mb-3" width="100" height="100" alt="Vista previa">
                <div class="mb-3"> <input class="form-control" type="file" accept="image/*" capture="user" name="nuevo_avatar" id="nuevo_avatar" required> </div> <small class="text-muted">Puedes tomar una foto o elegir una desde tu dispositivo.</small>
            </div>
            <div class="modal-footer"> <button type="submit" class="btn btn-primary w-100">Actualizar foto</button> </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('nuevo_avatar').addEventListener('change', function(event) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewAvatar').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>
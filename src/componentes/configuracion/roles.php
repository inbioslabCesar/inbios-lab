<?php // Conexión segura (ajusta tus datos) 
$host = 'localhost';
$db = 'u330560936_laboratorio';
$user = 'u330560936_inbioslab';
$pass = '41950361Cesarp$';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Error de conexión: ' . $e->getMessage());
}
$mensaje = '';
// Alta de usuario con verificación de email único 
if (isset($_POST['alta_usuario'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    if ($stmt->fetchColumn() > 0) {
        $mensaje = '<div class="alert alert-danger">El email ya está registrado. Usa otro correo.</div>';
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password, rol, cargo, profesion, dni, fecha_nacimiento, genero, creado_en) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_POST['nombre'], $_POST['apellido'], $_POST['email'], password_hash($_POST['contrasena'], PASSWORD_DEFAULT), $_POST['rol'], $_POST['cargo'], $_POST['profesion'], $_POST['dni'], $_POST['fecha_nacimiento'], $_POST['genero']]);
        $mensaje = '<div class="alert alert-success">Usuario registrado exitosamente.</div>';
    }
}
// Edición de usuario 
if (isset($_POST['editar_usuario'])) {
    $campos = ['nombre' => $_POST['nombre'], 'apellido' => $_POST['apellido'], 'email' => $_POST['email'], 'rol' => $_POST['rol'], 'cargo' => $_POST['cargo'], 'profesion' => $_POST['profesion'], 'dni' => $_POST['dni'], 'fecha_nacimiento' => $_POST['fecha_nacimiento'], 'genero' => $_POST['genero']];
    if (!empty($_POST['contrasena'])) {
        $campos['password'] = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    }
    $set = [];
    foreach ($campos as $k => $v) {
        $set[] = "$k = ?";
    }
    $stmt = $pdo->prepare("UPDATE usuarios SET " . implode(', ', $set) . " WHERE id = ?");
    $campos = array_values($campos);
    $campos[] = $_POST['id'];
    $stmt->execute($campos);
    $mensaje = '<div class="alert alert-success">Usuario actualizado exitosamente.</div>';
}
// Eliminar usuario 
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
    $mensaje = '<div class="alert alert-warning">Usuario eliminado.</div>';
}
// Paginación 
$por_pagina = 10;
$pagina = $_GET['pagina'] ?? 1;
$inicio = ($pagina - 1) * $por_pagina;
$total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_paginas = ceil($total_usuarios / $por_pagina);
// Obtener usuarios 
$stmt = $pdo->prepare("SELECT * FROM usuarios ORDER BY id DESC LIMIT $inicio, $por_pagina");
$stmt->execute();
$usuarios = $stmt->fetchAll();
// Para editar 
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $usuario_editar = $stmt->fetch();
} ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Gestión de Usuarios</h3>
        <div> <a href="export_excel.php" class="btn btn-outline-success btn-sm me-2"><i class="bi bi-file-earmark-excel"></i> Excel</a> <a href="export_pdf.php" class="btn btn-outline-danger btn-sm me-2"><i class="bi bi-file-earmark-pdf"></i> PDF</a> <a href="export_csv.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-filetype-csv"></i> CSV</a> </div>
    </div> <!-- Mensajes de acción --> <?= $mensaje ?> <!-- Formulario alta/edición -->
    <form class="row g-3 mb-4" method="POST" action=""> <input type="hidden" name="id" value="<?= htmlspecialchars($usuario_editar['id'] ?? '') ?>">
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Nombre</label> <input type="text" class="form-control" name="nombre" required value="<?= htmlspecialchars($usuario_editar['nombre'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Apellido</label> <input type="text" class="form-control" name="apellido" required value="<?= htmlspecialchars($usuario_editar['apellido'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Email</label> <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($usuario_editar['email'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Rol</label> <select class="form-select" name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="admin" <?= (isset($usuario_editar['rol']) && $usuario_editar['rol'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="usuario" <?= (isset($usuario_editar['rol']) && $usuario_editar['rol'] == 'usuario') ? 'selected' : '' ?>>Usuario</option>
            </select> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Cargo</label> <input type="text" class="form-control" name="cargo" value="<?= htmlspecialchars($usuario_editar['cargo'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Profesión</label> <input type="text" class="form-control" name="profesion" value="<?= htmlspecialchars($usuario_editar['profesion'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">DNI</label> <input type="text" class="form-control" name="dni" value="<?= htmlspecialchars($usuario_editar['dni'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Fecha de nacimiento</label> <input type="date" class="form-control" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario_editar['fecha_nacimiento'] ?? '') ?>"> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Género</label> <select class="form-select" name="genero">
                <option value="">Selecciona género</option>
                <option value="M" <?= (isset($usuario_editar['genero']) && $usuario_editar['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= (isset($usuario_editar['genero']) && $usuario_editar['genero'] == 'F') ? 'selected' : '' ?>>Femenino</option>
            </select> </div>
        <div class="col-12 col-md-6 col-lg-4"> <label class="form-label">Contraseña <?= $usuario_editar ? '(dejar vacío para no cambiar)' : '' ?></label> <input type="password" class="form-control" name="contrasena" <?= $usuario_editar ? '' : 'required' ?>> </div>
        <div class="col-12"> <button type="submit" class="btn btn-primary w-100" name="<?= $usuario_editar ? 'editar_usuario' : 'alta_usuario' ?>"> <?= $usuario_editar ? 'Actualizar usuario' : 'Crear usuario' ?> </button> </div>
    </form>
    <!-- Tabla de usuarios -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Cargo</th>
                    <th>Profesión</th>
                    <th>DNI</th>
                    <th>Fecha de nacimiento</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody> <?php foreach ($usuarios as $usuario): ?> <tr>
                        <td><?= htmlspecialchars($usuario['nombre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['apellido'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['rol'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['cargo'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['profesion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['dni'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['fecha_nacimiento'] ?? '') ?></td>
                        <td><?= htmlspecialchars($usuario['genero'] ?? '') ?></td>
                        <td> <a href="?modulo=configuracion&submodulo=roles&editar=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning mb-1">Editar</a> <a href="?modulo=configuracion&submodulo=roles&eliminar=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a> </td>
                    </tr> <?php endforeach; ?> </tbody>
        </table>
    </div> <!-- Paginación Bootstrap -->
    <nav>
        <ul class="pagination justify-content-center"> <?php for ($i = 1; $i <= $total_paginas; $i++): ?> <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>"> <a class="page-link" href="?modulo=configuracion&submodulo=roles&pagina=<?= $i ?>"><?= $i ?></a> </li> <?php endfor; ?> </ul>
    </nav>
</div>
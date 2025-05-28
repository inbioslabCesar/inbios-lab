<?php $conn = new PDO("mysql:host=localhost;dbname=u330560936_laboratorio", "u330560936_inbioslab", "41950361Cesarp$");
$mensaje = "";
$roles_disponibles = ['usuario', 'admin', 'colaborador'];
// Búsqueda y filtro 
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$filtro_rol = isset($_GET['filtro_rol']) ? trim($_GET['filtro_rol']) : '';
$filtro_cargo = isset($_GET['filtro_cargo']) ? trim($_GET['filtro_cargo']) : '';
// Paginación 
$por_pagina = 10;
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;
// Eliminación de usuario 
if (isset($_GET['eliminar'])) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['eliminar']]);
    $mensaje .= "<div class='alert alert-warning'>Usuario eliminado.</div>";
}
// Cargar usuario para editar 
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $usuario_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
// Proceso de edición 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_usuario'])) {
    $id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $rol = trim($_POST['rol']);
    $cargo = trim($_POST['cargo']);
    $profesion = trim($_POST['profesion']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $genero = trim($_POST['genero']);
    $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, email=?, rol=?, cargo=?, profesion=?, dni=?, fecha_nacimiento=?, genero=? WHERE id=?");
    $ok = $stmt->execute([$nombre, $apellido, $email, $rol, $cargo, $profesion, $dni, $fecha_nacimiento, $genero, $id]);
    $mensaje .= $ok ? "<div class='alert alert-success'>Usuario actualizado correctamente.</div>" : "<div class='alert alert-danger'>Error al actualizar usuario.</div>";
    $usuario_editar = null;
}
// Proceso de alta 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alta_usuario'])) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $rol = trim($_POST['rol']);
    $cargo = trim($_POST['cargo']);
    $profesion = trim($_POST['profesion']);
    $dni = trim($_POST['dni']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $genero = trim($_POST['genero']);
    $password_plano = bin2hex(random_bytes(4));
    $password_hash = password_hash($password_plano, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, password, rol, cargo, profesion, dni, fecha_nacimiento, genero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $ok = $stmt->execute([$nombre, $apellido, $email, $password_hash, $rol, $cargo, $profesion, $dni, $fecha_nacimiento, $genero]);
    if ($ok) {
        $mensaje .= "<div class='alert alert-success'>Usuario creado. Contraseña generada: <b>$password_plano</b></div>";
    } else {
        $mensaje .= "<div class='alert alert-danger'>Error al crear usuario. ¿El email ya existe?</div>";
    }
}
// Exportar a Excel (CSV) 
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=usuarios.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nombre', 'Apellido', 'Email', 'Rol', 'Cargo', 'Profesión', 'DNI', 'Fecha de nacimiento', 'Género']);
    // Exportar todos los resultados filtrados (sin paginación) 
    $sql_export = "SELECT * FROM usuarios WHERE 1";
    $params_export = [];
    if ($busqueda) {
        $sql_export .= " AND (nombre LIKE ? OR apellido LIKE ? OR email LIKE ?)";
        $params_export[] = "%$busqueda%";
        $params_export[] = "%$busqueda%";
        $params_export[] = "%$busqueda%";
    }
    if ($filtro_rol) {
        $sql_export .= " AND rol = ?";
        $params_export[] = $filtro_rol;
    }
    if ($filtro_cargo) {
        $sql_export .= " AND cargo = ?";
        $params_export[] = $filtro_cargo;
    }
    $sql_export .= " ORDER BY nombre, apellido";
    $stmt_export = $conn->prepare($sql_export);
    $stmt_export->execute($params_export);
    while ($row = $stmt_export->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [$row['nombre'], $row['apellido'], $row['email'], $row['rol'], $row['cargo'], $row['profesion'], $row['dni'], $row['fecha_nacimiento'], $row['genero']]);
    }
    fclose($output);
    exit;
}
// Obtener todos los roles y cargos distintos para los selects 
$roles_lista = $conn->query("SELECT DISTINCT rol FROM usuarios")->fetchAll(PDO::FETCH_COLUMN);
$cargos_lista = $conn->query("SELECT DISTINCT cargo FROM usuarios")->fetchAll(PDO::FETCH_COLUMN);
// Consulta con búsqueda y paginación 
$sql = "SELECT * FROM usuarios WHERE 1";
$params = [];
if ($busqueda) {
    $sql .= " AND (nombre LIKE ? OR apellido LIKE ? OR email LIKE ?)";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
}
if ($filtro_rol) {
    $sql .= " AND rol = ?";
    $params[] = $filtro_rol;
}
if ($filtro_cargo) {
    $sql .= " AND cargo = ?";
    $params[] = $filtro_cargo;
}
$sql .= " ORDER BY nombre, apellido LIMIT $inicio, $por_pagina";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Total para paginación 
$sql_total = "SELECT COUNT(*) FROM usuarios WHERE 1";
$params_total = [];
if ($busqueda) {
    $sql_total .= " AND (nombre LIKE ? OR apellido LIKE ? OR email LIKE ?)";
    $params_total[] = "%$busqueda%";
    $params_total[] = "%$busqueda%";
    $params_total[] = "%$busqueda%";
}
if ($filtro_rol) {
    $sql_total .= " AND rol = ?";
    $params_total[] = $filtro_rol;
}
if ($filtro_cargo) {
    $sql_total .= " AND cargo = ?";
    $params_total[] = $filtro_cargo;
}
$stmt_total = $conn->prepare($sql_total);
$stmt_total->execute($params_total);
$total_usuarios = $stmt_total->fetchColumn();
$total_paginas = ceil($total_usuarios / $por_pagina); ?> <?= $mensaje ?>
<?php if ($usuario_editar): ?> <form method="post" class="row g-3 mb-4"> <input type="hidden" name="editar_usuario" value="1"> <input type="hidden" name="id" value="<?= $usuario_editar['id'] ?>">
        <div class="col-md-6"> <label class="form-label">Nombre</label> <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario_editar['nombre']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">Apellido</label> <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario_editar['apellido']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">Email</label> <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario_editar['email']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">Rol</label> <select name="rol" class="form-select" required> <?php foreach ($roles_disponibles as $rol): ?> <option value="<?= $rol ?>" <?= $rol == $usuario_editar['rol'] ? 'selected' : '' ?>><?= ucfirst($rol) ?></option> <?php endforeach; ?> </select> </div>
        <div class="col-md-6"> <label class="form-label">Cargo</label> <input type="text" name="cargo" class="form-control" value="<?= htmlspecialchars($usuario_editar['cargo']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">Profesión</label> <input type="text" name="profesion" class="form-control" value="<?= htmlspecialchars($usuario_editar['profesion']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">DNI</label> <input type="text" name="dni" class="form-control" value="<?= htmlspecialchars($usuario_editar['dni']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">Fecha de nacimiento</label> <input type="date" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($usuario_editar['fecha_nacimiento']) ?>" required> </div>
        <div class="col-md-6"> <label class="form-label">Género</label> <select name="genero" class="form-select" required>
                <option value="masculino" <?= $usuario_editar['genero'] == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="femenino" <?= $usuario_editar['genero'] == 'femenino' ? 'selected' : ''; ?>>Femenino</option>
                <option value="otro" <?= $usuario_editar['genero'] == 'otro' ? 'selected' : ''; ?>>Otro</option>
            </select> </div>
        <div class="col-12"> <button type="submit" class="btn btn-primary">Actualizar usuario</button> <a href="?modulo=roles" class="btn btn-secondary">Cancelar</a> </div>
    </form> <?php endif; ?>

<?php if (!$usuario_editar): ?> <form method="post" class="row g-3 mb-4"> <input type="hidden" name="alta_usuario" value="1">
        <div class="col-md-6"> <label class="form-label">Nombre</label> <input type="text" name="nombre" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">Apellido</label> <input type="text" name="apellido" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">Email</label> <input type="email" name="email" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">Rol</label> <select name="rol" class="form-select" required> <?php foreach ($roles_disponibles as $rol): ?> <option value="<?= $rol ?>"><?= ucfirst($rol) ?></option> <?php endforeach; ?> </select> </div>
        <div class="col-md-6"> <label class="form-label">Cargo</label> <input type="text" name="cargo" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">Profesión</label> <input type="text" name="profesion" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">DNI</label> <input type="text" name="dni" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">Fecha de nacimiento</label> <input type="date" name="fecha_nacimiento" class="form-control" required> </div>
        <div class="col-md-6"> <label class="form-label">Género</label> <select name="genero" class="form-select" required>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select> </div>
        <div class="col-12"> <button type="submit" class="btn btn-success">Crear usuario</button> </div>
    </form> <?php endif; ?>
<h3>Lista de usuarios</h3>
<table class="table table-striped">
    <thead>
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
    <tbody> <?php foreach ($usuarios as $user): ?> <tr>
                <td><?= htmlspecialchars($user['nombre']) ?></td>
                <td><?= htmlspecialchars($user['apellido']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['rol']) ?></td>
                <td><?= htmlspecialchars($user['cargo']) ?></td>
                <td><?= htmlspecialchars($user['profesion']) ?></td>
                <td><?= htmlspecialchars($user['dni']) ?></td>
                <td><?= htmlspecialchars($user['fecha_nacimiento']) ?></td>
                <td><?= htmlspecialchars($user['genero']) ?></td>
                <td> <a href="?modulo=roles&editar=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Editar</a> <a href="?modulo=roles&eliminar=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">Eliminar</a> </td>
            </tr> <?php endforeach; ?> </tbody>
</table> <?php if ($total_paginas > 1): ?> <nav>
        <ul class="pagination"> <?php for ($i = 1; $i <= $total_paginas; $i++): ?> <li class="page-item <?= $i == $pagina ? 'active' : '' ?>"> <a class="page-link" href="?modulo=roles&buscar=<?= urlencode($busqueda) ?>&filtro_rol=<?= urlencode($filtro_rol) ?>&filtro_cargo=<?= urlencode($filtro_cargo) ?>&pagina=<?= $i ?>"><?= $i ?></a> </li> <?php endfor; ?> </ul>
    </nav> <?php endif; ?>
<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } 
require_once __DIR__ . '/src/db.php';
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    if (!$nombre || !$email || !$password || !$password2) {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif ($password !== $password2) {
        $mensaje = 'Las contraseñas no coinciden.';
    } else {
        $avatarName = 'avatar1.png';
        // Siempre asigna el avatar por defecto 
        $hash = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $pdo->prepare("INSERT INTO clientes (nombre, email, password, avatar) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $email, $hash, $avatarName]);
            $mensaje = '¡Registro exitoso! Ahora puedes iniciar sesión.';
        } catch (PDOException $e) {
            $mensaje = 'Error: ' . ($e->getCode() == 23000 ? 'El correo ya está registrado.' : $e->getMessage());
        }
    }
} ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - INBIOSLAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4e54c8 0%, #6fd6d6 100%);
            overflow: hidden;
            position: relative;
        }

        .bubbles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            bottom: -120px;
            background: rgba(79, 211, 211, 0.18);
            border-radius: 50%;
            opacity: 0.7;
            animation: rise 12s infinite ease-in;
        }

        @keyframes rise {
            0% {
                transform: translateY(0) scale(1);
            }

            100% {
                transform: translateY(-110vh) scale(1.2);
            }
        }

        .logo-login {
            width: 150px;
            margin-bottom: 16px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(79, 211, 211, 0.15);
            background: #fff;
            padding: 8px;
        }

        .card {
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(79, 211, 211, 0.11);
        }

        .btn-primary {
            background: linear-gradient(90deg, #4e54c8, #6fd6d6);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #6fd6d6, #4e54c8);
        }
    </style>
</head>

<body>
    <div class="bubbles"> <?php for ($i = 0; $i < 18; $i++): ?> <div class="bubble" style=" left:<?= rand(2, 98) ?>vw; width:<?= rand(30, 90) ?>px; height:<?= rand(30, 90) ?>px; animation-duration: <?= rand(8, 16) ?>s; animation-delay: -<?= rand(0, 12) ?>s; "></div> <?php endfor; ?> </div>
    <div class="container py-5 position-relative" style="z-index:1;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center"> <img src="images/inbioslab-logo.png" alt="Logo INBIOSLAB" class="logo-login"> </div>
                        <h3 class="mb-3 text-center fw-bold" style="color:#4e54c8;">Registro de Usuario</h3> <?php if ($mensaje): ?> <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div> <?php endif; ?> <form method="post" autocomplete="off">
                            <div class="mb-3"> <label class="form-label">Nombre</label> <input type="text" name="nombre" class="form-control" required> </div>
                            <div class="mb-3"> <label class="form-label">Correo electrónico</label> <input type="email" name="email" class="form-control" required> </div>
                            <div class="mb-3"> <label class="form-label">Contraseña</label> <input type="password" name="password" class="form-control" required> </div>
                            <div class="mb-3"> <label class="form-label">Repetir contraseña</label> <input type="password" name="password2" class="form-control" required> </div> <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                        </form>
                        <div class="mt-3 text-center"> <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

</html>
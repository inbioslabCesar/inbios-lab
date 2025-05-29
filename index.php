<?php include 'src/componentes/auth.php'; ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Aquí puedes cargar datos de usuario, avatar, etc. 
$nombre = $_SESSION['cliente_nombre'] ?? 'Usuario';
$avatar = $_SESSION['cliente_avatar'] ?? 'avatar1.png';
?>
<!DOCTYPE html>
<html lang="es">
<?php include 'src/componentes/head.php'; ?>

<body>
    <div class="d-flex">
        <?php include 'src/componentes/sidebar.php'; ?>
        <div class="flex-grow-1">
            <?php include 'src/componentes/navbar.php'; ?>
            <div class="dashboard-container">
                <?php if (isset($_GET['modulo'])) {
                    switch ($_GET['modulo']) {
                        case 'panel':
                            include 'src/componentes/panel.php';
                            break;
                        case 'configuracion':
                            include 'src/componentes/configuracion.php';
                            break;
                        // Agrega aquí otros módulos según necesites 
                        default:
                            echo "<div class='p-4'>Bienvenido a INBIOSLAB.</div>";
                    }
                } else {
                    echo "<div class='p-4'>Bienvenido a INBIOSLAB.</div>";
                } ?>
                <!-- Aquí puedes incluir más módulos o componentes en el futuro -->
            </div>
        </div>
    </div>
    <?php
    include 'src/componentes/chat_flotante.php';
    include 'src/componentes/historial_modal.php';
    include 'src/componentes/scripts.php';
    ?>
</body> 

</html>
<?php
// ============================================================================
// ARCHIVO: index.php
// Página de login
// ============================================================================

// Función para limpiar datos de entrada
function limpiar_dato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
    return $dato;
}

require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';

if ($_POST) {
    $usuario = limpiar_dato($_POST['usuario']);
    $clave = $_POST['clave'];
    
    if (login($usuario, $clave, $pdo)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pagos - Campo Rosso</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-graduation-cap"></i>
                <h2>Colegio Campo Rosso</h2>
                <p>Sistema de Gestión de Pagos</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="clave" name="clave" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="login-footer">
                <p>Sistema para gestión de ítems faltantes y actividades cívicas</p>
                <small>&copy; 2025 Colegio Campo Rosso</small>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// ============================================================================
// ARCHIVO: pagos.php
// Gestión de pagos (listado y búsqueda)
// ============================================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/funciones.php';

verificar_login();

// Búsqueda
$busqueda = '';
$pagos = [];

if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = limpiar_dato($_GET['buscar']);
    $pagos = buscar_pagos_estudiante($pdo, $busqueda);
} else {
    $pagos = obtener_pagos_recientes($pdo, 50);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos - Campo Rosso</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-graduation-cap"></i>
                Campo Rosso - Pagos
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user"></i>
                    <?php echo $_SESSION['usuario_nombre']; ?>
                </span>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    Salir
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card sidebar-menu">
                    <div class="card-body">
                        <h5><i class="fas fa-bars"></i> Menú Principal</h5>
                        <div class="list-group">
                            <a href="dashboard.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-chart-dashboard"></i> Dashboard
                            </a>
                            <a href="pagos.php" class="list-group-item list-group-item-action active">
                                <i class="fas fa-money-bill"></i> Gestionar Pagos
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de nuevo pago -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6><i class="fas fa-plus"></i> Registrar Nuevo Pago</h6>
                    </div>
                    <div class="card-body">
                        <form action="registrar_pago.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nombre Estudiante</label>
                                <input type="text" name="nombre_estudiante" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Curso</label>
                                <select name="curso" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1ro Secundaria">1ro Secundaria</option>
                                    <option value="2do Secundaria">2do Secundaria</option>
                                    <option value="3ro Secundaria">3ro Secundaria</option>
                                    <option value="4to Secundaria">4to Secundaria</option>
                                    <option value="5to Secundaria">5to Secundaria</option>
                                    <option value="6to Secundaria">6to Secundaria</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Concepto</label>
                                <select name="mes_pago" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Items Faltantes">Items Faltantes</option>
                                    <option value="Actividad Cívica">Actividad Cívica</option>
                                    <option value="BTH">BTH</option>
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Monto (Bs.)</label>
                                <input type="number" name="monto" step="0.01" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comprobante</label>
                                <input type="file" name="comprobante" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-muted">JPG, PNG o PDF (máx. 2MB)</small>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-save"></i> Registrar Pago
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-money-bill"></i> Gestión de Pagos</h1>
                    
                    <!-- Buscador -->
                    <form method="GET" class="d-flex">
                        <input type="text" name="buscar" class="form-control me-2" 
                               placeholder="Buscar por nombre de estudiante..." 
                               value="<?php echo $busqueda; ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if ($busqueda): ?>
                            <a href="pagos.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <?php if ($busqueda): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Resultados para: <strong><?php echo $busqueda; ?></strong>
                        (<?php echo count($pagos); ?> encontrados)
                    </div>
                <?php endif; ?>
                
                <!-- Tabla de pagos -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Estudiante</th>
                                        <th>Curso</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($pagos) > 0): ?>
                                        <?php foreach ($pagos as $pago): ?>
                                            <tr>
                                                <td><?php echo $pago['id']; ?></td>
                                                <td><?php echo $pago['nombre_estudiante']; ?></td>
                                                <td><?php echo $pago['curso']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo ($pago['mes_pago'] == 'Items Faltantes') ? 'warning' : 
                                                            (($pago['mes_pago'] == 'Actividad Cívica') ? 'info' : 'primary'); 
                                                    ?>">
                                                        <?php echo $pago['mes_pago']; ?>
                                                    </span>
                                                </td>
                                                <td>Bs. <?php echo formatear_monto($pago['monto']); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_pago'])); ?></td>
                                                <td>
                                                    <?php if ($pago['comprobante']): ?>
                                                        <a href="assets/uploads/<?php echo $pago['comprobante']; ?>" 
                                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Sin comprobante</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fas fa-search"></i>
                                                No se encontraron pagos
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>
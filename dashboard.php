<?php
// ============================================================================
// ARCHIVO: dashboard.php
// Panel principal con estadísticas
// ============================================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/funciones.php';

verificar_login();

$estadisticas = obtener_estadisticas_generales($pdo);
$pagos_recientes = obtener_pagos_recientes($pdo, 5);
$estadisticas_cursos = obtener_estadisticas_por_curso($pdo);
$datos_grafico = obtener_datos_grafico_mensual($pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Campo Rosso</title>
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
                            <a href="dashboard.php" class="list-group-item list-group-item-action active">
                                <i class="fas fa-chart-dashboard"></i> Dashboard
                            </a>
                            <a href="pagos.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-money-bill"></i> Gestionar Pagos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <h1><i class="fas fa-chart-dashboard"></i> Dashboard General</h1>
                
                <!-- Estadísticas Generales -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card stat-primary">
                            <div class="card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $estadisticas['total_pagos']; ?></h3>
                                    <p>Total Pagos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card stat-success">
                            <div class="card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>Bs. <?php echo formatear_monto($estadisticas['total_recaudado']); ?></h3>
                                    <p>Total Recaudado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card stat-info">
                            <div class="card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-month"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $estadisticas['pagos_mes_actual']; ?></h3>
                                    <p>Pagos Este Mes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card stat-warning">
                            <div class="card-body">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <h3><?php echo $estadisticas['estudiantes_con_pagos']; ?></h3>
                                    <p>Estudiantes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfico de Pagos Mensuales -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-line"></i> Pagos por Mes</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="graficoMensual" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-clock"></i> Pagos Recientes</h5>
                            </div>
                            <div class="card-body">
                                <div class="recent-payments">
                                    <?php foreach ($pagos_recientes as $pago): ?>
                                        <div class="payment-item">
                                            <div class="payment-info">
                                                <strong><?php echo $pago['nombre_estudiante']; ?></strong>
                                                <small><?php echo $pago['curso'] . ' - ' . $pago['mes_pago']; ?></small>
                                            </div>
                                            <div class="payment-amount">
                                                Bs. <?php echo formatear_monto($pago['monto']); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Estadísticas por Curso -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-school"></i> Estadísticas por Curso</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Curso</th>
                                        <th>Total Pagos</th>
                                        <th>Total Recaudado</th>
                                        <th>Promedio</th>
                                        <th>Último Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estadisticas_cursos as $curso): ?>
                                        <tr>
                                            <td><?php echo $curso['curso']; ?></td>
                                            <td><?php echo $curso['total_pagos']; ?></td>
                                            <td>Bs. <?php echo formatear_monto($curso['total_recaudado']); ?></td>
                                            <td>Bs. <?php echo formatear_monto($curso['promedio_pago']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($curso['ultimo_pago'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Gráfico de pagos mensuales
        const ctx = document.getElementById('graficoMensual').getContext('2d');
        const datosGrafico = <?php echo json_encode($datos_grafico); ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: datosGrafico.map(item => item.mes_pago),
                datasets: [{
                    label: 'Total Recaudado (Bs.)',
                    data: datosGrafico.map(item => item.total_mes),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
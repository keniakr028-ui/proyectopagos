<?php
// Función para limpiar datos de entrada
function limpiar_dato($dato) {
    return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
}

// Función para formatear montos
function formatear_monto($monto) {
    return number_format($monto, 2, ',', '.');
}

// Función para obtener estadísticas generales
function obtener_estadisticas_generales($pdo) {
    $sql = "CALL ObtenerEstadisticasGenerales()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
}

// Función para obtener pagos recientes
function obtener_pagos_recientes($pdo, $limite = 10) {
    $sql = "SELECT * FROM vista_pagos_completa LIMIT :limite";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Función para buscar pagos por estudiante
function buscar_pagos_estudiante($pdo, $nombre) {
    $sql = "CALL BuscarPagosEstudiante(:nombre)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Función para obtener estadísticas por curso
function obtener_estadisticas_por_curso($pdo) {
    $sql = "SELECT * FROM estadisticas_por_curso";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Función para obtener datos para gráficos
function obtener_datos_grafico_mensual($pdo) {
    $sql = "SELECT mes_pago, total_mes FROM pagos_por_mes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Función para validar archivo subido
function validar_comprobante($archivo) {
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    $tamaño_maximo = 2 * 1024 * 1024; // 2MB
    
    if ($archivo['size'] > $tamaño_maximo) {
        return "El archivo es demasiado grande. Máximo 2MB.";
    }
    
    if (!in_array($archivo['type'], $tipos_permitidos)) {
        return "Tipo de archivo no permitido. Solo JPG, PNG y PDF.";
    }
    
    return true;
}

// Función para subir comprobante
function subir_comprobante($archivo) {
    $directorio = "assets/uploads/";
    if (!file_exists($directorio)) {
        mkdir($directorio, 0755, true);
    }
    
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('comprobante_') . '.' . $extension;
    $ruta_destino = $directorio . $nombre_archivo;
    
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        return $nombre_archivo;
    }
    
    return false;
}
?>


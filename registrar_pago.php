<?php
// ============================================================================
// ARCHIVO: registrar_pago.php
// Procesar registro de nuevo pago
// ============================================================================
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/funciones.php';

verificar_login();

if ($_POST) {
    $nombre_estudiante = limpiar_dato($_POST['nombre_estudiante']);
    $curso = limpiar_dato($_POST['curso']);
    $mes_pago = limpiar_dato($_POST['mes_pago']);
    $monto = floatval($_POST['monto']);
    $comprobante_nombre = null;
    
    // Validaciones
    $errores = [];
    
    if (empty($nombre_estudiante)) {
        $errores[] = "El nombre del estudiante es requerido";
    }
    
    if (empty($curso)) {
        $errores[] = "El curso es requerido";
    }
    
    if (empty($mes_pago)) {
        $errores[] = "El concepto es requerido";
    }
    
    if ($monto <= 0) {
        $errores[] = "El monto debe ser mayor a cero";
    }
    
    // Validar archivo si se subió
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['size'] > 0) {
        $validacion = validar_comprobante($_FILES['comprobante']);
        if ($validacion !== true) {
            $errores[] = $validacion;
        } else {
            $comprobante_nombre = subir_comprobante($_FILES['comprobante']);
            if (!$comprobante_nombre) {
                $errores[] = "Error al subir el comprobante";
            }
        }
    }
    
    // Si no hay errores, insertar en base de datos
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO pagos (nombre_estudiante, curso, mes_pago, monto, comprobante, usuario_registro) 
                    VALUES (:nombre_estudiante, :curso, :mes_pago, :monto, :comprobante, :usuario_registro)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre_estudiante', $nombre_estudiante);
            $stmt->bindParam(':curso', $curso);
            $stmt->bindParam(':mes_pago', $mes_pago);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':comprobante', $comprobante_nombre);
            $stmt->bindParam(':usuario_registro', $_SESSION['usuario_id']);
            
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "Pago registrado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al registrar el pago";
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "danger";
        }
    } else {
        $_SESSION['mensaje'] = implode(". ", $errores);
        $_SESSION['tipo_mensaje'] = "danger";
    }
}

header("Location: pagos.php");
exit();
?>

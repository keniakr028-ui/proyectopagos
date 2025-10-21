<?php
session_start();

function verificar_login() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php");
        exit();
    }
}

function login($usuario, $clave, $pdo) {
    $sql = "SELECT id, usuario, clave FROM usuarios WHERE usuario = :usuario AND activo = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    
    $user = $stmt->fetch();
    
    if ($user && password_verify($clave, $user['clave'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['usuario'];
        return true;
    }
    
    return false;
}

function logout() {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
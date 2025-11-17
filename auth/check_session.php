<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit();
    }
    return $_SESSION['usuario'];
}

function isAdmin() {
    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin') {
        return true;
    }
    return false;
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}
?>
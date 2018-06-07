<?php declare(strict_types=1);
session_start();

if (!isset($_SESSION['session_id'])) {
    header('location: index.php');

    return;
}

$username = $_SESSION['nombre'];

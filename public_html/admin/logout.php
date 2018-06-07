<?php declare(strict_types=1);

session_start();

if (isset($_SESSION['session_id'])) {
    session_destroy();
    header('location: index.php');

    return;
}

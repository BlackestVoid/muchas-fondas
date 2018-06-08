<?php
include_once __DIR__.'/../../../config/database.php';
$ordenamientoRes = $_POST['ordenamientoRes'];
foreach ($ordenamientoRes as $key => $value) {
    $sql = "UPDATE restaurantes set orden = ? where id = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$key, $value]);
}
?>
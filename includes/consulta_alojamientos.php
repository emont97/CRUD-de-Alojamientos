<?php
require_once 'db.php';

$query = "SELECT * FROM alojamientos";
$stmt = $pdo->query($query);
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

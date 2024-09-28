<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}

$food_id = $_GET['id'] ?? null;

if ($food_id) {
    $soft_delete_query = $conn->prepare("UPDATE food SET deleted_at = NOW() WHERE id = :id");
    $soft_delete_query->bindParam(':id', $food_id);

    if ($soft_delete_query->execute()) {
        echo "<div class='alert alert-success' role='alert'>Yemek başarıyla silindi.</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Yemek silinirken bir hata oluştu.</div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Yemek ID belirtilmemiş.</div>";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Sil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Yemek Sil</h1>
    <p>Yemek başarıyla silindi.</p>
    <a href="list_foods.php" class="btn btn-primary">Geri Dön</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

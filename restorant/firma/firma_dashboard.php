<?php
session_start();
include 'db_connection.php';

 
if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}

 
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$query->bindParam(':user_id', $user_id);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);


$company_query = $conn->prepare("SELECT name FROM company WHERE id = :company_id");
$company_query->bindParam(':company_id', $user['company_id']);
$company_query->execute();
$company = $company_query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firma Kontrol Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Hoş Geldiniz, <?php echo htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['surname']); ?></h1>
    <h3>Firma Bilgileri</h3>
    <ul class="list-group">
        <li class="list-group-item"><strong>Firma Adı:</strong> <?php echo htmlspecialchars($company['name']); ?></li>
        <li class="list-group-item"><strong>Bakiyeniz:</strong> <?php echo htmlspecialchars($user['balance']); ?> TL</li>
        <li class="list-group-item"><strong>Oluşturulma Tarihi:</strong> <?php echo htmlspecialchars($user['created_at']); ?></li>
    </ul>

    <div class="mt-4">
        <h2>Yemek Yönetimi</h2>
        <a href="list_foods.php" class="btn btn-primary">Yemekleri Listele</a>
        <a href="add_food.php" class="btn btn-success">Yeni Yemek Ekle</a>
        <a href="search_foods.php" class="btn btn-info">Yemek Ara</a>
    </div>

    <div class="mt-4">
        <h2>Sipariş Yönetimi</h2>
        <a href="list_orders.php" class="btn btn-warning">Siparişleri Listele</a>
    </div>

    <div class="mt-4">
        <a href="firma_login_register.php" class="btn btn-danger">Çıkış Yap</a>
    </div>
</div>
</body>
</html>


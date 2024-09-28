<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Şirkete ait yemekleri listeleme
$query = $conn->prepare("SELECT * FROM food WHERE restaurant_id IN (SELECT id FROM restaurant WHERE company_id = (SELECT company_id FROM users WHERE id = :user_id)) AND deleted_at IS NULL");
$query->bindParam(':user_id', $user_id);
$query->execute();
$foods = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Yemek Listesi</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Açıklama</th>
                <th>Fiyat</th>
                <th>İndirim</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($foods as $food): ?>
            <tr>
                <td><?php echo htmlspecialchars($food['id']); ?></td>
                <td><?php echo htmlspecialchars($food['name']); ?></td>
                <td><?php echo htmlspecialchars($food['description']); ?></td>
                <td><?php echo htmlspecialchars($food['price']); ?> TL</td>
                <td>
                    <?php 
                    // İndirim varsa, indirim tutarını hesapla
                    if ($food['discount'] > 0) {
                        $discount_amount = $food['price'] * ($food['discount'] / 100);
                        echo htmlspecialchars(number_format($discount_amount, 2)) . " TL"; 
                    } else {
                        echo "Yok";
                    }
                    ?>
                </td>
                <td>
                    <a href="update_food.php?id=<?php echo $food['id']; ?>" class="btn btn-warning">Güncelle</a>
                    <a href="delete_food.php?id=<?php echo $food['id']; ?>" class="btn btn-danger">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="firma_dashboard.php" class="btn btn-secondary mb-3">Geri Dön</a> <!-- Geri Dön Butonu -->
</div>
</body>
</html>

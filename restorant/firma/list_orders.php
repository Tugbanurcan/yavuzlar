<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}


$stmt = $conn->prepare("SELECT * FROM `order`"); 
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişleri Listele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Siparişleri Listele</h1>
    <a href="firma_dashboard.php" class="btn btn-secondary mb-3">Geri Dön</a> 
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kullanıcı ID</th> 
                <th>Toplam</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['user_id']); ?></td>  
                    <td><?php echo htmlspecialchars($order['total']); ?></td>  
                    <td><?php echo htmlspecialchars($order['order_status']); ?></td>  
                    <td>
                        <a href="update_order_status.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-warning btn-sm">Durumu Güncelle</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include 'db_connection.php';  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Oturumdaki kullanıcının sipariş geçmişini çek
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.total_price, o.order_status, o.created_at, 
           GROUP_CONCAT(f.name SEPARATOR ', ') AS food_names, 
           GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities
    FROM `order` o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN food f ON oi.food_id = f.id
    WHERE o.user_id = :user_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute(['user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Geçmişi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Sipariş Geçmişiniz</h2>

    <?php if (empty($orders)): ?>
        <p>Sipariş geçmişiniz bulunmamaktadır.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Sipariş No</th>
                <th>Yemekler</th>
                <th>Adetler</th>
                <th>Toplam Fiyat</th>
                <th>Durum</th>
                <th>Tarih</th>
            </tr>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                <td>
                    <?php 
                    $food_names = explode(', ', $order['food_names']);
                    $quantities = explode(', ', $order['quantities']);
                    $combined = [];
                    
                    for ($i = 0; $i < count($food_names); $i++) {
                        $combined[] = htmlspecialchars($food_names[$i]) . " (Adet: " . htmlspecialchars($quantities[$i]) . ")";
                    }
                    
                    echo implode(', ', $combined);
                    ?>
                </td>
                <td><?php echo htmlspecialchars($order['quantities']); ?></td>
                <td><?php echo htmlspecialchars($order['total_price']); ?> ₺</td>
                <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>

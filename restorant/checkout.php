<?php
session_start();  
include 'db_connection.php';  

if (!isset($_SESSION['user_id'])) {
    echo "<p>Oturumunuz kapalı, lütfen giriş yapın.</p>";
    exit();
}


$user_id = $_SESSION['user_id'];

 
$stmt = $pdo->prepare('
    SELECT food.id, food.price, basket.quantity 
    FROM basket 
    JOIN food ON basket.food_id = food.id 
    WHERE basket.user_id = :user_id
');
$stmt->execute(['user_id' => $user_id]);
$basketItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
 

if (empty($basketItems)) {
    echo "<p>Sepetiniz boş.</p>";
    exit();
}

 
$totalPrice = 0;
foreach ($basketItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

 
$orderStmt = $pdo->prepare('
    INSERT INTO `order` (user_id, total, order_status, total_price) 
    VALUES (:user_id, :total, :status, :total_price)
');
$orderStmt->execute([
    'user_id' => $user_id,
    'total' => $totalPrice,
    'status' => 'beklemede',  
    'total_price' => $totalPrice
]);

// Son oluşturulan sipariş ID'sini al
$orderId = $pdo->lastInsertId();

// Order_items tablosuna sepet öğelerini ekle
foreach ($basketItems as $item) {
    $itemStmt = $pdo->prepare('
        INSERT INTO order_items (order_id, food_id, quantity, price) 
        VALUES (:order_id, :food_id, :quantity, :price)
    ');
    $itemStmt->execute([
        'order_id' => $orderId,
        'food_id' => $item['id'],
        'quantity' => $item['quantity'],
        'price' => $item['price']
    ]);
}

// Kullanıcının sepetini temizle
$clearBasketStmt = $pdo->prepare('DELETE FROM basket WHERE user_id = :user_id');
$clearBasketStmt->execute(['user_id' => $user_id]);

echo "<p>Sipariş başarıyla oluşturuldu!</p>";
?>

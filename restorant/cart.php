<?php
session_start();  
include 'db_connection.php';  
 
if (!isset($_SESSION['user_id'])) {
    echo "<p>Oturumunuz kapalı, lütfen giriş yapın.</p>";
    exit();
}


 
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('
    SELECT food.name, food.price, basket.quantity 
    FROM basket 
    JOIN food ON basket.food_id = food.id 
    WHERE basket.user_id = :user_id
');
$stmt->execute(['user_id' => $user_id]);
$basketItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (empty($basketItems)): ?>
    <p>Sepetinizde ürün yok.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Ürün Adı</th>
            <th>Fiyat</th>
            <th>Adet</th>
            <th>Toplam</th>
        </tr>
        <?php foreach ($basketItems as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo htmlspecialchars($item['price']); ?> ₺</td>
            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
            <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?> ₺</td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="checkout.php" class="button">Satın Al</a>
<?php endif; ?>

<style>
    table {
        border-collapse: collapse; /* Hücre kenarlarının birbirine yapışmasını engeller */
        width: 100%;
    }

    th, td {
        padding: 10px; /* Hücrelerin içine boşluk ekler */
        text-align: left;
    }

    th {
        background-color: #f2f2f2; /* Başlık hücrelerini vurgulamak için renk ekleyebilirsiniz */
    }
</style>

<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}

$order_id = $_GET['id'] ?? null;

if ($order_id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $status = $_POST['status'];

        
        $update_query = $conn->prepare("UPDATE `order` SET order_status = :status WHERE id = :id");
        $update_query->bindParam(':status', $status);
        $update_query->bindParam(':id', $order_id);

        if ($update_query->execute()) {
            echo "<div class='alert alert-success' role='alert'>Sipariş durumu başarıyla güncellendi.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Sipariş durumu güncellenirken bir hata oluştu.</div>";
        }
    } else {
        
        $query = $conn->prepare("SELECT * FROM `order` WHERE id = :id");
        $query->bindParam(':id', $order_id);
        $query->execute();
        $order = $query->fetch(PDO::FETCH_ASSOC);
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Sipariş ID belirtilmemiş.</div>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Durumunu Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Sipariş Durumunu Güncelle</h1>
    <form method="POST" action="update_order_status.php?id=<?php echo htmlspecialchars($order_id); ?>">
        <div class="mb-3">
            <label for="status" class="form-label">Durum:</label>
            <input type="text" class="form-control" name="status" value="<?php echo htmlspecialchars($order['order_status']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="list_orders.php" class="btn btn-secondary">Geri Dön</a> 
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


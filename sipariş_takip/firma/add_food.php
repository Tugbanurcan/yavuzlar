<?php
session_start();
include 'db_connection.php';  

 
if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");  
    exit;
}

// Yemek ekleme işlemi
$message = '';  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $restaurant_name = $_POST['restaurant_name'];  

    // Restoran ID'sini almak için restoran adını sorgula
    $stmt = $conn->prepare("SELECT id FROM restaurant WHERE name = :restaurant_name LIMIT 1");
    $stmt->bindParam(':restaurant_name', $restaurant_name);
    $stmt->execute();
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($restaurant) {
        $restaurant_id = $restaurant['id'];

        $insert_query = $conn->prepare("INSERT INTO food (restaurant_id, name, description, price, discount) VALUES (:restaurant_id, :name, :description, :price, :discount)");
        $insert_query->bindParam(':restaurant_id', $restaurant_id);
        $insert_query->bindParam(':name', $name);
        $insert_query->bindParam(':description', $description);
        $insert_query->bindParam(':price', $price);
        $insert_query->bindParam(':discount', $discount);

        if ($insert_query->execute()) {
            $message = "<div class='alert alert-success' role='alert'>Yemek başarıyla eklendi.</div>";
        } else {
            $message = "<div class='alert alert-danger' role='alert'>Yemek eklenirken bir hata oluştu.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Belirtilen restoran bulunamadı. Lütfen geçerli bir restoran adı girin.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Yemek Ekle</h1>
    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>
    <form method="POST" action="add_food.php">
        <div class="mb-3">
            <label for="name" class="form-label">Yemek Adı:</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama:</label>
            <textarea class="form-control" name="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Fiyat:</label>
            <input type="number" class="form-control" name="price" required step="0.01">
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">İndirim (%):</label>
            <input type="number" class="form-control" name="discount" required>
        </div>
        <div class="mb-3">
            <label for="restaurant_name" class="form-label">Restoran Adı:</label>
            <input type="text" class="form-control" name="restaurant_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Ekle</button>
    </form>
    <a href="firma_dashboard.php" class="btn btn-secondary mb-3">Geri Dön</a>  
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


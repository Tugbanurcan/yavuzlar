<?php
session_start();
include 'db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}

$food_id = $_GET['id'] ?? null;

if ($food_id) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];

        $update_query = $conn->prepare("UPDATE food SET name = :name, description = :description, price = :price, discount = :discount WHERE id = :id");
        $update_query->bindParam(':name', $name);
        $update_query->bindParam(':description', $description);
        $update_query->bindParam(':price', $price);
        $update_query->bindParam(':discount', $discount);
        $update_query->bindParam(':id', $food_id);

        if ($update_query->execute()) {
            echo "<div class='alert alert-success' role='alert'>Yemek başarıyla güncellendi.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Yemek güncellenirken bir hata oluştu.</div>";
        }
    } else {
        $query = $conn->prepare("SELECT * FROM food WHERE id = :id");
        $query->bindParam(':id', $food_id);
        $query->execute();
        $food = $query->fetch(PDO::FETCH_ASSOC);
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
    <title>Yemek Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Yemek Güncelle</h1>
    <form method="POST" action="update_food.php?id=<?php echo htmlspecialchars($food_id); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Yemek Adı:</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($food['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama:</label>
            <textarea class="form-control" name="description" required><?php echo htmlspecialchars($food['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Fiyat:</label>
            <input type="number" class="form-control" name="price" value="<?php echo htmlspecialchars($food['price']); ?>" required step="0.01">
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">İndirim (%):</label>
            <input type="number" class="form-control" name="discount" value="<?php echo htmlspecialchars($food['discount']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

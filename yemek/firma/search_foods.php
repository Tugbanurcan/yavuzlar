<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: firma_login_register.php");
    exit;
}

$foods = [];
$search_query = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = $_POST['search_query'];
    $stmt = $conn->prepare("SELECT * FROM food WHERE name LIKE :search_query");
    $stmt->bindValue(':search_query', '%' . $search_query . '%');
    $stmt->execute();
    $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Ara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Yemek Ara</h1>
    <form method="POST" action="search_foods.php">
        <div class="mb-3">
            <input type="text" class="form-control" name="search_query" placeholder="Yemek adı girin..." value="<?php echo htmlspecialchars($search_query); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Ara</button>
    </form>
    <a href="firma_dashboard.php" class="btn btn-secondary mb-3">Geri Dön</a>  
    <?php if (!empty($foods)): ?>
        <h2 class="mt-4">Sonuçlar:</h2>
        <ul class="list-group">
            <?php foreach ($foods as $food): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($food['name']); ?></strong><br>
                    <small>Açıklama: <?php echo htmlspecialchars($food['description']); ?></small><br>
                    <small>Fiyat: <?php echo htmlspecialchars($food['price']); ?> TL</small><br>
                    <?php if ($food['discount'] > 0): ?>
                        <small>İndirim: <?php echo htmlspecialchars($food['discount']); ?> TL</small><br>
                    <?php endif; ?>
                    <a href="update_food.php?id=<?php echo htmlspecialchars($food['id']); ?>" class="btn btn-warning btn-sm float-end">Güncelle</a>
                    <a href="delete_food.php?id=<?php echo htmlspecialchars($food['id']); ?>" class="btn btn-danger btn-sm float-end me-2">Sil</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

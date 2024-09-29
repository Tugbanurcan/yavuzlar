<?php
session_start();

$host = 'localhost';   
$dbname = 'yemekdb';  
$username = 'root';  
$password = 'admin123'; 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Güncellenecek firma verilerini alma
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM company WHERE id = :id AND deleted_at IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Firma güncelleme
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];

        $sql = "UPDATE company SET name = :name, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $_SESSION['message'] = 'Firma başarıyla güncellendi.';
        header('Location: company_management.php');
        exit();
    }
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Firma Güncelle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Firma Güncelle</h1>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Firma Adı" value="<?php echo $company['name']; ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="description" class="form-control" placeholder="Açıklama" value="<?php echo $company['description']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Güncelle</button>
            <a href="company_management.php" class="btn btn-secondary">İptal</a>
        </form>
    </div>
</body>
</html>

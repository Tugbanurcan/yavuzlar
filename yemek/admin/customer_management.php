<?php
session_start(); 

$host = 'db';   
$dbname = 'yemekdb';  
$username = 'root';  
$password = 'admin123'; 

$dsn = "mysql:host=$host;dbname=$dbname";

$search_term = isset($_POST['arama_termi']) ? $_POST['arama_termi'] : '';
$filter = isset($_POST['filter']) ? $_POST['filter'] : 'aktif'; // Varsayılan aktif kullanıcıları göster

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mesajı al ve temizle
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    unset($_SESSION['message']);

    // Kullanıcıları filtreleme 
    if ($filter === 'silinen') {
        // Silinmiş kullanıcılar
        $sql = "SELECT * FROM users WHERE deleted_at IS NOT NULL";
    } else {
        // Aktif kullanıcılar ve role kısmı boş olanlar
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL AND (role IS NULL OR role = '')";
    }

    if ($search_term) {
        $sql .= " AND (name LIKE :search OR surname LIKE :search OR username LIKE :search)";
    }

    $stmt = $pdo->prepare($sql);
    if ($search_term) {
        $search = "%$search_term%";
        $stmt->bindParam(':search', $search);
    }
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Müşteri Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Müşteri Yönetimi</h1>

        <!-- Başarı mesajı -->
        <?php if ($message): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Arama ve Filtreleme Formu -->
        <form method="POST" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <input type="text" name="arama_termi" class="form-control" placeholder="İsim, Soyisim veya Kullanıcı Adı ile Ara" value="<?php echo isset($search_term) ? $search_term : ''; ?>">
                </div>
                <div class="col">
                    <select name="filter" class="form-control">
                        <option value="aktif" <?php if ($filter === 'aktif') echo 'selected'; ?>>Aktif Kullanıcılar</option>
                        <option value="silinen" <?php if ($filter === 'silinen') echo 'selected'; ?>>Silinen Kullanıcılar</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-info">Filtrele</button>
                </div>
            </div>
        </form>

        <!-- Kullanıcılar Tablosu -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>İsim</th>
                    <th>Soyisim</th>
                    <th>Kullanıcı Adı</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['surname']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
                            <?php if ($filter === 'silinen'): ?>
                                <!-- Silinen kullanıcıyı geri yükleme -->
                                <a href="restore_user.php?id=<?php echo $user['id']; ?>" class="btn btn-success">Geri Yükle</a>
                            <?php else: ?>
                                <!-- Aktif kullanıcıyı soft silme -->
                                <a href="soft_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger">Sil</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Kullanıcı bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="admin_panel.php" class="btn btn-secondary mb-3">Admin Paneline Dön</a>  
    </div>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


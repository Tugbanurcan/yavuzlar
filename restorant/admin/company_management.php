<?php
session_start();
$host = 'localhost';   
$dbname = 'yemekdb';  
$username = 'root';  
$password = 'admin123'; 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aktif firmaları alma
    $active_companies = [];
    $deleted_companies = [];

    
    $search = '';
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        $active_sql = "SELECT * FROM company WHERE deleted_at IS NULL AND name LIKE :search";
        $deleted_sql = "SELECT * FROM company WHERE deleted_at IS NOT NULL AND name LIKE :search";
    } else {
        $active_sql = "SELECT * FROM company WHERE deleted_at IS NULL";
        $deleted_sql = "SELECT * FROM company WHERE deleted_at IS NOT NULL";
    }

    $active_stmt = $pdo->prepare($active_sql);
    $deleted_stmt = $pdo->prepare($deleted_sql);

    if ($search) {
        $search_param = "%$search%";
        $active_stmt->bindParam(':search', $search_param);
        $deleted_stmt->bindParam(':search', $search_param);
    }

    $active_stmt->execute();
    $deleted_stmt->execute();

    $active_companies = $active_stmt->fetchAll(PDO::FETCH_ASSOC);
    $deleted_companies = $deleted_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Firma ekleme işlemi
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_company'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];

        // Logo dosyasını yükleme
        $logo_path = '';
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $logo_path = $target_dir . basename($_FILES["logo"]["name"]);
            move_uploaded_file($_FILES["logo"]["tmp_name"], $logo_path);
        }

        $add_sql = "INSERT INTO company (name, description, logo_path) VALUES (:name, :description, :logo_path)";
        $add_stmt = $pdo->prepare($add_sql);
        $add_stmt->bindParam(':name', $name);
        $add_stmt->bindParam(':description', $description);
        $add_stmt->bindParam(':logo_path', $logo_path);
        $add_stmt->execute();

        $_SESSION['message'] = 'Yeni firma başarıyla eklendi.';
        header('Location: company_management.php');
        exit();
    }

    // Firma güncelleme 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_company'])) {
        $id = $_POST['update_id'];
        $name = $_POST['update_name'];
        $description = $_POST['update_description'];

        
        $logo_path = '';
        if (isset($_FILES['update_logo']) && $_FILES['update_logo']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $logo_path = $target_dir . basename($_FILES["update_logo"]["name"]);
            move_uploaded_file($_FILES["update_logo"]["tmp_name"], $logo_path);
        }

        $update_sql = "UPDATE company SET name = :name, description = :description" . ($logo_path ? ", logo_path = :logo_path" : "") . " WHERE id = :id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':name', $name);
        $update_stmt->bindParam(':description', $description);
        if ($logo_path) {
            $update_stmt->bindParam(':logo_path', $logo_path);
        }
        $update_stmt->bindParam(':id', $id);
        $update_stmt->execute();

        $_SESSION['message'] = 'Firma bilgileri başarıyla güncellendi.';
        header('Location: company_management.php');
        exit();
    }

    // Firma geri ekleme işlemi
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_id'])) {
        $id = $_POST['restore_id'];

        // Silinen firma kaydını geri ekleme
        $restore_sql = "UPDATE company SET deleted_at = NULL WHERE id = :id";
        $restore_stmt = $pdo->prepare($restore_sql);
        $restore_stmt->bindParam(':id', $id);
        $restore_stmt->execute();

        $_SESSION['message'] = 'Firma başarıyla geri eklendi.';
        header('Location: company_management.php');
        exit();
    }

    // Firma silme (soft delete)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];

        $delete_sql = "UPDATE company SET deleted_at = NOW() WHERE id = :id";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->bindParam(':id', $id);
        $delete_stmt->execute();

        $_SESSION['message'] = 'Firma başarıyla silindi.';
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
    <title>Firma Yönetimi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Firma Yönetimi</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <h2>Yeni Firma Ekle</h2>
        <form action="company_management.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Firma Adı:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Açıklama:</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Logo Yükle:</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>
            <button type="submit" name="add_company" class="btn btn-primary">Ekle</button>
        </form>

        <h2>Aktif Firmalar</h2>
        <form action="company_management.php" method="POST" class="mb-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Firma Ara" class="form-control" style="width: 300px; display: inline-block;">
            <button type="submit" class="btn btn-info">Ara</button>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>Firma Adı</th>
                    <th>Açıklama</th>
                    <th>Logo</th>
                    <th>Güncelle</th>
                    <th>Sil</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($active_companies)): ?>
                    <?php foreach ($active_companies as $company): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($company['name']); ?></td>
                            <td><?php echo htmlspecialchars($company['description']); ?></td>
                            <td>
                                <?php if ($company['logo_path']): ?>
                                    <img src="<?php echo htmlspecialchars($company['logo_path']); ?>" alt="Logo" style="width: 50px; height: 50px;">
                                <?php else: ?>
                                    Yok
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updateModal<?php echo $company['id']; ?>">Güncelle</button>

                                <!-- Güncelleme Modal -->
                                <div class="modal fade" id="updateModal<?php echo $company['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel<?php echo $company['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateModalLabel<?php echo $company['id']; ?>">Firma Güncelle</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="company_management.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="update_id" value="<?php echo $company['id']; ?>">
                                                    <div class="form-group">
                                                        <label>Firma Adı:</label>
                                                        <input type="text" name="update_name" class="form-control" value="<?php echo htmlspecialchars($company['name']); ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Açıklama:</label>
                                                        <textarea name="update_description" class="form-control" required><?php echo htmlspecialchars($company['description']); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Logo Yükle:</label>
                                                        <input type="file" name="update_logo" class="form-control" accept="image/*">
                                                    </div>
                                                    <button type="submit" name="update_company" class="btn btn-primary">Güncelle</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <form action="company_management.php" method="POST" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $company['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Herhangi bir firma bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Silinen Firmalar</h2>
        <form action="company_management.php" method="POST" class="mb-3">
            <label for="deleted_companies">Silinen Firmaları Geri Ekle:</label>
            <select name="restore_id" id="deleted_companies" class="form-control" style="width: 300px; display: inline-block;">
                <option value="">Seçin</option>
                <?php foreach ($deleted_companies as $deleted_company): ?>
                    <option value="<?php echo $deleted_company['id']; ?>"><?php echo htmlspecialchars($deleted_company['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-success">Geri Ekle</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Firma Adı</th>
                    <th>Açıklama</th>
                    <th>Logo</th>
                    <th>Geri Ekle</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($deleted_companies)): ?>
                    <?php foreach ($deleted_companies as $deleted_company): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($deleted_company['name']); ?></td>
                            <td><?php echo htmlspecialchars($deleted_company['description']); ?></td>
                            <td>
                                <?php if ($deleted_company['logo_path']): ?>
                                    <img src="<?php echo htmlspecialchars($deleted_company['logo_path']); ?>" alt="Logo" style="width: 50px; height: 50px;">
                                <?php else: ?>
                                    Yok
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="company_management.php" method="POST" onsubmit="return confirm('Bu firmayı geri eklemek istediğinize emin misiniz?');">
                                    <input type="hidden" name="restore_id" value="<?php echo $deleted_company['id']; ?>">
                                    <button type="submit" class="btn btn-success">Geri Ekle</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Herhangi bir silinen firma bulunamadı.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

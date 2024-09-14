<?php
include 'db.php';

$sorularıGetir = $pdo->query("SELECT * FROM questions")->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['soru_sil']) && !empty($_POST['soru_id'])) {
    $soru_id = $_POST['soru_id'];
    $soruSilmeSorgusu = $pdo->prepare("DELETE FROM questions WHERE question_id = ?"); // **Düzenlendi**
    $soruSilmeSorgusu->execute([$soru_id]);
    header("Location: admin.php");
    exit();
}


if (isset($_POST['soru_ekle'])) {
    $question = $_POST['soru'];
    $option1 = $_POST['şık1'];
    $option2 = $_POST['şık2'];
    $option3 = $_POST['şık3'];
    $option4 = $_POST['şık4'];
    $correct = $_POST['doğru'];
    $difficulty = $_POST['difficulty'];
    $soruEklemeSorgusu = $pdo->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?)"); // **Düzenlendi**
    $soruEklemeSorgusu->execute([$question, $option1, $option2, $option3, $option4, $correct, $difficulty]);
    $sorularıGetir = $pdo->query("SELECT * FROM questions")->fetchAll(PDO::FETCH_ASSOC);
}


if (isset($_POST['soru_duzenle']) && !empty($_POST['soru_id'])) {
    $soru_id = $_POST['soru_id'];
    $question = $_POST['soru'];
    $option1 = $_POST['şık1'];
    $option2 = $_POST['şık2'];
    $option3 = $_POST['şık3'];
    $option4 = $_POST['şık4'];
    $correct = $_POST['doğru'];
    $difficulty = $_POST['difficulty'];
    $soruDuzenlemeSorgusu = $pdo->prepare("UPDATE questions SET question = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, correct = ?, difficulty = ? WHERE question_id = ?"); // **Düzenlendi**
    $soruDuzenlemeSorgusu->execute([$question, $option1, $option2, $option3, $option4, $correct, $difficulty, $soru_id]);
    $sorularıGetir = $pdo->query("SELECT * FROM questions")->fetchAll(PDO::FETCH_ASSOC);
}

// Düzenleme için mevcut soruyu al
$duzenlemeSoruId = null;
$duzenlemeSoru = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $duzenlemeSoruId = $_GET['edit'];
    $duzenlemeSoru = $pdo->prepare("SELECT * FROM questions WHERE question_id = ?"); // **Düzenlendi**
    $duzenlemeSoru->execute([$duzenlemeSoruId]);
    $duzenlemeSoru = $duzenlemeSoru->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Yönetim Paneli</title>
</head>
<body>
    <div class="container">
        <a href="login.php"><button class="large-button">Anasayfa Dön</button></a>
        <h1>Yönetim Paneli</h1>

        <!-- Soru Ekleme Formu -->
        <form method="POST" action="admin.php">
            <input type="text" name="soru" placeholder="Soru" required>
            <input type="text" name="şık1" placeholder="Şık 1" required>
            <input type="text" name="şık2" placeholder="Şık 2" required>
            <input type="text" name="şık3" placeholder="Şık 3" required>
            <input type="text" name="şık4" placeholder="Şık 4" required>
            <input type="number" name="doğru" min="1" max="4" placeholder="Doğru Şık (1-4)" required>
            <select name="difficulty" required>
                <option value="kolay">Kolay</option>
                <option value="orta">Orta</option>
                <option value="zor">Zor</option>
            </select>
            <button type="submit" name="soru_ekle">Soru Ekle</button>
        </form>

        <h3>Mevcut Sorular</h3>
        
        <?php
        if (!empty($sorularıGetir)) {
            foreach ($sorularıGetir as $soru) {
                echo '<form method="POST" action="admin.php" style="margin-bottom: 20px;">';
                echo '<p>';
                echo '<strong>Soru:</strong> ' . htmlspecialchars($soru['question']) . '<br>';
                echo '<span style="margin-right: 20px;"><strong>Şık 1:</strong> ' . htmlspecialchars($soru['option1']) . '</span>';
                echo '<span style="margin-right: 20px;"><strong>Şık 2:</strong> ' . htmlspecialchars($soru['option2']) . '</span>';
                echo '<span style="margin-right: 20px;"><strong>Şık 3:</strong> ' . htmlspecialchars($soru['option3']) . '</span>';
                echo '<span style="margin-right: 20px;"><strong>Şık 4:</strong> ' . htmlspecialchars($soru['option4']) . '</span><br>';
                echo '<strong>Doğru Şık:</strong> ' . htmlspecialchars($soru['correct']) . '<br>';
                echo '<strong>Zorluk:</strong> ' . htmlspecialchars($soru['difficulty']) . '<br>';
                
                // Düzenleme 
                echo '<a href="admin.php?edit=' . htmlspecialchars($soru['question_id']) . '">Düzenle</a>';
                
                // Soru Silme
                echo '<form method="POST" action="admin.php" style="display:inline; margin-left: 10px;">';
                echo '<input type="hidden" name="soru_id" value="' . htmlspecialchars($soru['question_id']) . '">'; // Düzenlendi
                echo '<button type="submit" name="soru_sil">Soru Sil</button>';
                echo '</form>';
                echo '</p>';
                echo '</form>';
            }
        }
        ?>

        <!-- Soru Düzenleme Formu -->
        <?php if ($duzenlemeSoru): ?>
            <h3>Soru Düzenleme</h3>
            <form method="POST" action="admin.php">
                <input type="hidden" name="soru_id" value="<?php echo htmlspecialchars($duzenlemeSoru['question_id']); ?>"> <!-- **Düzenlendi** -->
                <input type="text" name="soru" value="<?php echo htmlspecialchars($duzenlemeSoru['question']); ?>" required>
                <input type="text" name="şık1" value="<?php echo htmlspecialchars($duzenlemeSoru['option1']); ?>" required>
                <input type="text" name="şık2" value="<?php echo htmlspecialchars($duzenlemeSoru['option2']); ?>" required>
                <input type="text" name="şık3" value="<?php echo htmlspecialchars($duzenlemeSoru['option3']); ?>" required>
                <input type="text" name="şık4" value="<?php echo htmlspecialchars($duzenlemeSoru['option4']); ?>" required>
                <input type="number" name="doğru" value="<?php echo htmlspecialchars($duzenlemeSoru['correct']); ?>" min="1" max="4" required>
                <select name="difficulty" required>
                    <option value="kolay" <?php echo ($duzenlemeSoru['difficulty'] == 'kolay' ? 'selected' : ''); ?>>Kolay</option>
                    <option value="orta" <?php echo ($duzenlemeSoru['difficulty'] == 'orta' ? 'selected' : ''); ?>>Orta</option>
                    <option value="zor" <?php echo ($duzenlemeSoru['difficulty'] == 'zor' ? 'selected' : ''); ?>>Zor</option>
                </select>
                <button type="submit" name="soru_duzenle">Düzenle</button>
            </form>
        <?php endif; ?>

    </div>
</body>
</html>

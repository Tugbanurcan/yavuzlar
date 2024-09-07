<?php
session_start();
include 'db.php';

 
if (!isset($_SESSION['user_id'])) {
    header("Location: start.php");
    exit();
}

 
if (empty($_SESSION['questions'])) {
    $sorgu = "SELECT * FROM questions ORDER BY id ASC";
    $ifade = $db->query($sorgu);
    $_SESSION['questions'] = $ifade->fetchAll(PDO::FETCH_ASSOC);
}

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $soru_id = $_POST['question_id'];
    $cevap = $_POST['answer'];
    $dogru_cevap = $db->prepare("SELECT correct FROM questions WHERE id = ?");
    $dogru_cevap->execute([$soru_id]);
    $dogru_cevap = $dogru_cevap->fetchColumn();

    if ($cevap == $dogru_cevap) {
        
        $_SESSION['puan'] += 10;
    }
    
     
    foreach ($_SESSION['questions'] as $key => $question) {
        if ($question['id'] == $soru_id) {
            unset($_SESSION['questions'][$key]);
            break;
        }
    }
    $_SESSION['questions'] = array_values($_SESSION['questions']);

    // Bir sonraki soruya yönlendir
    if (!empty($_SESSION['questions'])) {
        $current_question = $_SESSION['questions'][0];
        header("Location: quiz.php");
        exit();
    } else {
        // Tüm sorular bittiğinde sonuç sayfasına yönlendir
        header("Location: result.php");
        exit();
    }
}

$current_question = $_SESSION['questions'][0] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Yarışma</title>
</head>
<body>
    <div class="container">
        <h2>Yarışma</h2>
        <?php if ($current_question): ?>
            <form method="POST" action="quiz.php">
                <p><?= htmlspecialchars($current_question['question']) ?></p>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <input type="radio" name="answer" value="<?= $i ?>" required> <?= htmlspecialchars($current_question['option'.$i]) ?><br>
                <?php endfor; ?>
                <input type="hidden" name="question_id" value="<?= $current_question['id'] ?>">
                <button type="submit">Cevapla</button>
            </form>
        <?php else: ?>
            <p>Seçilen zorluk seviyesinde soru bulunamadı.</p>
        <?php endif; ?>
    </div>
</body>
</html>

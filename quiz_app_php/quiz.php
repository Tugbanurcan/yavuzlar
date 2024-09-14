<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Kullanıcı sınavı tamamlamışsa, sonuç sayfasına yönlendir
$stmt = $pdo->prepare("SELECT has_completed_exam FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$has_completed_exam = $stmt->fetchColumn();

if ($has_completed_exam) {
    header("Location: result.php");
    exit();
}

if (empty($_SESSION['questions'])) {
    $stmt = $pdo->query("SELECT * FROM questions ORDER BY question_id ASC");
    $_SESSION['questions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['puan'] = 0; 
}

// Soruların kalanını güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $soru_id = $_POST['question_id'];
    $cevap = $_POST['answer']; 

    // Doğru cevabı kontrol et
    $stmt = $pdo->prepare("SELECT correct FROM questions WHERE question_id = ?");
    $stmt->execute([$soru_id]);
    $dogru_cevap = $stmt->fetchColumn();

    if ($cevap === $dogru_cevap) {
        $_SESSION['puan'] += 10;
    }

    // Soruyu listeden çıkar
    foreach ($_SESSION['questions'] as $key => $question) {
        if ($question['question_id'] == $soru_id) {
            unset($_SESSION['questions'][$key]);
            break;
        }
    }
    $_SESSION['questions'] = array_values($_SESSION['questions']);

    // Bir sonraki soruya yönlendir
    if (!empty($_SESSION['questions'])) {
        header("Location: quiz.php");
        exit();
    } else {
        header("Location: result.php");
        exit();
    }
}

// Soruları al
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
                <p><?php echo htmlspecialchars($current_question['question']); ?></p>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <input type="radio" name="answer" value="<?php echo $i; ?>" required> <?php echo htmlspecialchars($current_question['option'.$i]); ?><br>
                <?php endfor; ?>
                <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($current_question['question_id']); ?>">
                <button type="submit">Cevapla</button>
            </form>
        <?php else: ?>
            <p>Puanınız: <?php echo htmlspecialchars($_SESSION['puan']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

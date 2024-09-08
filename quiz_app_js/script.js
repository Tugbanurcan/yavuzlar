let questions = [];
let score = 0;
let currentQuestionIndex = 0;

function yönetimPaneliGöster() {
    document.getElementById('adminPanel').classList.remove('hidden');
    document.getElementById('quizPanel').classList.add('hidden');
}

function yarışmaPaneliGöster() {
    document.getElementById('quizPanel').classList.remove('hidden');
    document.getElementById('adminPanel').classList.add('hidden');
}

function soruEkle() {
    const questionInput = document.getElementById('questionInput').value;
    const option1 = document.getElementById('option1').value;
    const option2 = document.getElementById('option2').value;
    const option3 = document.getElementById('option3').value;
    const option4 = document.getElementById('option4').value;
    const correctOption = document.getElementById('correctOption').value;
    const difficultySelect = document.getElementById('difficultySelect').value;

    if (questionInput.trim() !== "" && correctOption >= 1 && correctOption <= 4) {
        const question = {
            text: questionInput,
            options: [option1, option2, option3, option4],
            correct: correctOption - 1,
            difficulty: difficultySelect
        };
        questions.push(question);
        soruListele();
        formuTemizle();
    } else {
        alert("Lütfen tüm alanları doldurun ve doğru şık değerini doğru girin.");
    }
}

function soruListele() {
    const questionList = document.getElementById('questionList');
    questionList.innerHTML = '';

    questions.forEach((question, index) => {
        const li = document.createElement('li');
        li.innerHTML = `${question.text} - ${question.difficulty.toUpperCase()} 
                        <button onclick="soruDüzenle(${index})">Düzenle</button>
                        <button onclick="soruSil(${index})">Sil</button>`;
        questionList.appendChild(li);
    });
}

function formuTemizle() {
    document.getElementById('questionInput').value = '';
    document.getElementById('option1').value = '';
    document.getElementById('option2').value = '';
    document.getElementById('option3').value = '';
    document.getElementById('option4').value = '';
    document.getElementById('correctOption').value = '';
}

function soruSil(index) {
    questions.splice(index, 1);
    soruListele();
}

function soruDüzenle(index) {
    const newQuestion = prompt("Yeni soru girin:", questions[index].text);
    if (newQuestion !== null) {
        questions[index].text = newQuestion;
        soruListele();
    }
}

function soruAra() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const filteredQuestions = questions.filter(question => question.text.toLowerCase().includes(searchInput));
    filtrelenmişSorularıListele(filteredQuestions);
}

function filtrelenmişSorularıListele(filteredQuestions) {
    const questionList = document.getElementById('questionList');
    questionList.innerHTML = '';

    filteredQuestions.forEach((question, index) => {
        const li = document.createElement('li');
        li.innerHTML = `${question.text} - ${question.difficulty.toUpperCase()} 
                        <button onclick="soruDüzenle(${index})">Düzenle</button>
                        <button onclick="soruSil(${index})">Sil</button>`;
        questionList.appendChild(li);
    });
}

function startQuiz() {
    const difficultySelect = document.getElementById('difficultySelectQuiz').value;
    let filteredQuestions = questions;

    if (difficultySelect !== 'all') {
        filteredQuestions = questions.filter(q => q.difficulty === difficultySelect);
    }

    if (filteredQuestions.length === 0) {
        alert("Bu zorlukta soru bulunamadı!");
        return;
    }

    // Soruları karıştır
    filteredQuestions = shuffleArray(filteredQuestions);

    currentQuestionIndex = 0;
    score = 0;   
    document.getElementById('score').textContent = `Puan: ${score}`;
    showQuestion(filteredQuestions);
}

function showQuestion(filteredQuestions) {
    if (currentQuestionIndex >= filteredQuestions.length) {
        alert("Yarışma bitti! Toplam Puanınız: " + score);
        document.getElementById('quiz').innerHTML = '';
        return;
    }

    const currentQuestion = filteredQuestions[currentQuestionIndex];

    const quizDiv = document.getElementById('quiz');
    quizDiv.innerHTML = `<p>${currentQuestion.text}</p>
                         ${currentQuestion.options.map((option, index) => 
                            `<button onclick="checkAnswer(${index}, ${currentQuestion.correct}, ${filteredQuestions.length})">${index + 1}) ${option}</button>`
                         ).join('')}
                         <br>
                         <button onclick="nextQuestion(${filteredQuestions.length})">Sonraki Soru</button>`;
}

function checkAnswer(selectedOption, correctOption, totalQuestions) {
    if (selectedOption === correctOption) {
        score += 10;
        alert("Doğru cevap!");
    } else {
        alert("Yanlış cevap!");
    }
    document.getElementById('score').textContent = `Puan: ${score}`;
    nextQuestion(totalQuestions);
}

function nextQuestion(totalQuestions) {
    currentQuestionIndex++;

    if (currentQuestionIndex < totalQuestions) {
        showQuestion(questions);
    } else {
        alert("Yarışma tamamlandı! Toplam Puanınız: " + score);
        document.getElementById('quiz').innerHTML = '';
    }
}

// Fisher-Yates karıştırma algoritması
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

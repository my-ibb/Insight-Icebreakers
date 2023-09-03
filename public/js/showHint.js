    function showAnswer(id) {
        // 各問題に対応する答えエリアを見つける
        var answerArea = document.getElementById('answerArea-' + id);
            // 答えエリアが現在表示されているかどうかをチェック
            //もし答えが表示されていたら非表示にし、非表示だったら表示にする
            if (answerArea.style.display === 'block') {
            // 答えエリアを非表示にする
            answerArea.style.display = 'none';
        } else {
    
        // 答えエリアを表示
        answerArea.style.display = 'block';
        }
    };
    async function showHint() {
            const questionId = {{ $question->id }};  // Bladeテンプレートから問題IDを取得
    
            // API呼び出し
            const response = await fetch(`/getHint/${questionId}`);
            const data = await response.json();
    
            // ヒントを表示
            const hintElement = document.getElementById("hint");
            hintElement.textContent = data.hint;
            hintElement.style.display = "block";
        };
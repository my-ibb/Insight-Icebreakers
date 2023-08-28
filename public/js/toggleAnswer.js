document.addEventListener("DOMContentLoaded", function() {
    function toggleAnswerVisibility() {
        const showAnswerButton = document.getElementById("showAnswerButton");
        const answerArea = document.getElementById("answerArea");

        if (showAnswerButton && answerArea) {
            showAnswerButton.addEventListener("click", function() {
                answerArea.style.display = (answerArea.style.display === "none" || answerArea.style.display === "") ? "block" : "none";
            });
        }
    }
    
    toggleAnswerVisibility();
});

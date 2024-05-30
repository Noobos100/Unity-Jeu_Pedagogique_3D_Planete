function showFormFields(type) {
    const container = document.getElementById("form-fields-container");
    container.innerHTML = ""; // Clear previous fields

    let fields = "";

    if (type === "VRAIFAUX") {
        fields = `
            <label for="enonce">Enoncé:</label>
            <input type="text" id="enonce" name="enonce" required>
            <label for="orbit">Orbite (optionnel):</label>
            <input type="text" id="orbit" name="orbit">
            <label for="rotation">Rotation (optionnel):</label>
            <input type="text" id="rotation" name="rotation">
            <label for="reponse">Réponse:</label>
            <select id="reponse" name="reponse" required>
                <option value="Vrai">Vrai</option>
                <option value="Faux">Faux</option>
            </select>
        `;
    }
    else if (type === "QCU") {
        fields = `
            <label for="enonce">Enoncé:</label>
            <input type="text" id="enonce" name="enonce" required>
            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" required">
            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" required">
            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" required">
            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" required">
            <label for="correct">Correct Answer:</label>
            <select id="correct" name="correct" required">
                <option value="Rep1">Option 1</option>
                <option value="Rep2">Option 2</option>
                <option value="Rep3">Option 3</option>
                <option value="Rep4">Option 4</option>
            </select>
        `;
    }
    else if (type === "QUESINTERAC") {
        fields = `
            <label for="enonce">Enoncé:</label>
            <input type="text" id="enonce" name="enonce" required>
            <label for="orbit">Réponse orbite:</label>
            <input type="text" id="orbit" name="orbit" required>
            <label for="rotation">Réponse rotation:</label>
            <input type="text" id="rotation" name="rotation" required>
            
            <label for="margin-rotation">Marge rotation:</label>
            <input type="text" id="margin-rotation" name="margin-rotation" required>
            <label for="margin-orbit">Marge orbite:</label>
            <input type="text" id="margin-orbit" name="margin-orbit" required>
    `;
    }

    container.innerHTML = fields;
}

document.addEventListener("DOMContentLoaded", (event) => {
    const popup = document.getElementById("add-question-popup");
    const closeButton = popup.querySelector(".close");

    document.querySelector("button[onclick]").addEventListener("click", () => {
        popup.style.display = "block";
        document.body.classList.add("popup-active");
    });

    closeButton.addEventListener("click", () => {
        popup.style.display = "none";
        document.body.classList.remove("popup-active");
    });

    window.addEventListener("click", (event) => {
        if (event.target === popup) {
            popup.style.display = "none";
            document.body.classList.remove("popup-active");
        }
    });
});

document.addEventListener("DOMContentLoaded", (event) => {
    const popup = document.getElementById("add-question-popup");
    const closeButton = popup.querySelector(".close");

    document.querySelector("button[onclick]").addEventListener("click", () => {
        popup.style.display = "block";
        document.body.classList.add("popup-active");
    });

    closeButton.addEventListener("click", () => {
        popup.style.display = "none";
        document.body.classList.remove("popup-active");
    });

    window.addEventListener("click", (event) => {
        if (event.target === popup) {
            popup.style.display = "none";
            document.body.classList.remove("popup-active");
        }
    });
});

function filterQuestions() {
    let filter = document.getElementById("filter").value;
    let rows = document.querySelectorAll("#question-table .question-row");
    rows.forEach(row => {
        let type = row.cells[2].innerText;
        if (filter === "all" || type === filter) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
function deleteQuestion(qid) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette question?")) {
        location.href = "delete-question?qid=" + qid;
    }
}
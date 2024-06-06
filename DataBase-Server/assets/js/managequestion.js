import Popup from "/assets/popup/popup.js";

document.addEventListener("click", async function (e) {
  const target = e.target;
  if (!target) return;

  if (target.classList.contains("add")) {
    await addQuestion();
  }
  if (target.id === "filter" || target.parentElement.id === "filter") {
    filterQuestions();
  }
  if (target.classList.contains("edit")) {
    const qid = target.closest('tr').getAttribute("data-qid");
    await editQuestion(qid);
  }
  if (target.classList.contains("delete")) {
    const qid = target.closest('tr').getAttribute("data-qid");
    deleteQuestion(qid);
  }
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

async function addQuestion() {
  const popup = new Popup();

  popup.title = "Ajouter une question";
  popup.content = await fetch("/gui/pages/question/_add.html")
    .then(res => res.text());

  popup.addFooterBtn("Ajouter", function () {
    SubmitFormFromPopup(popup, "Question ajoutée avec succès!");
  });

  popup.addFooterBtn("Fermer", function () {
    popup.close();
  });

  popup.closingBtn = true;

  await popup.show();
}

function deleteQuestion(qid) {
  const popup = new Popup();

  popup.title = "Supprimer une question";
  popup.content = "Êtes-vous sûr de vouloir supprimer cette question ?";

  popup.addFooterBtn("Oui", function () {
    fetch("/delete-question?qid=" + qid)
      .then(res => res.text())
      .then(data => {
        if (data.error) {
          alert(data.error);
        } else {
          popup.close();
          location.reload();
        }
      });
  });

  popup.addFooterBtn("Non", function () {
    popup.close();
  });

  popup.closingBtn = true;

  popup.show();
}

async function editQuestion(qid) {
  const questionData = await fetch("/edit-question?qid=" + qid)
    .then(res => res.text());

  let popupContent = await fetch("/gui/pages/question/_edit.html")
    .then(res => res.text());

  popupContent = popupContent.replace("%content%", questionData);
  popupContent = popupContent.replace("%qid%", qid);

  const popup = new Popup();

  popup.title = "Modifier une question";
  popup.content = popupContent;

  popup.addFooterBtn("Modifier", function () {
    SubmitFormFromPopup(popup, "Question modifiée avec succès!");
  });

  popup.addFooterBtn("Fermer", function () {
    popup.close();
  });

  popup.closingBtn = true;

  await popup.show();
}

async function SubmitFormFromPopup(parentPopup, submitMsg = "", redirectUrl = "") {
  const popup = document.getElementById("popup");
  if (!popup) {
    console.error("Popup not found");
    return;
  }
  const form = popup.querySelector("form");
  if (!form) {
    console.error("Form not found");
    return;
  }

  fetch(form.action, {
    method: form.method,
    body: new FormData(form)
  }).then(response => {
    if (!response.ok) {
      return response.text().then(data => {
        alert(data);
        throw new Error(data);
      });
    }
  }).then(async () => {
    parentPopup.close();

    if (submitMsg) {
      const submitPopup = new Popup();

      submitPopup.title = "Succès";
      submitPopup.content = submitMsg;
      submitPopup.addFooterBtn("Fermer", function () {
        submitPopup.close();
        if (redirectUrl)
          location.href = redirectUrl;
        else
          location.reload();
      });

      submitPopup.closingBtn = true;

      await submitPopup.show();
    }
  });
}

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

document.getElementById('search').addEventListener('input', function() {
  const searchValue = this.value.toLowerCase();
  const rows = document.querySelectorAll('.question-row');

  rows.forEach(row => {
    const enonce = row.children[1].textContent.toLowerCase();
    if (enonce.includes(searchValue)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
});

const sortModes = {
  NONE: 'none',
  ASCENDING: 'ascending',
  DESCENDING: 'descending'
};

const sortStates = {
  id: sortModes.NONE,
  enonce: sortModes.NONE,
  type: sortModes.NONE
};

document.querySelectorAll('.sort-btn').forEach(button => {
  button.addEventListener('click', function() {
    const sortAttribute = this.getAttribute('data-sort');
    const table = document.getElementById('question-table');
    const rows = Array.from(table.querySelectorAll('.question-row'));

    // Determine the next sort state
    const nextSortState = getNextSortState(sortStates[sortAttribute]);
    sortStates[sortAttribute] = nextSortState;

    // Clear other columns' sort states
    Object.keys(sortStates).forEach(attr => {
      if (attr !== sortAttribute) sortStates[attr] = sortModes.NONE;
    });

    // Sort rows based on the next sort state
    if (nextSortState === sortModes.NONE) {
      rows.sort((a, b) => a.dataset.qid - b.dataset.qid);  // Default order by ID
    } else {
      rows.sort((a, b) => {
        const aText = a.querySelector(`td:nth-child(${getColumnIndex(sortAttribute)})`).textContent;
        const bText = b.querySelector(`td:nth-child(${getColumnIndex(sortAttribute)})`).textContent;
        const comparison = sortAttribute === 'id' ? parseInt(aText) - parseInt(bText) : aText.localeCompare(bText);
        return nextSortState === sortModes.ASCENDING ? comparison : -comparison;
      });
    }

    rows.forEach(row => table.appendChild(row));

    // Update icon based on sort state
    updateSortIcons(sortAttribute, nextSortState);
  });
});

function getNextSortState(currentState) {
  switch (currentState) {
    case sortModes.NONE:
      return sortModes.ASCENDING;
    case sortModes.ASCENDING:
      return sortModes.DESCENDING;
    case sortModes.DESCENDING:
      return sortModes.NONE;
    default:
      return sortModes.NONE;
  }
}

function getColumnIndex(sortAttribute) {
  switch (sortAttribute) {
    case 'id':
      return 1;
    case 'enonce':
      return 2;
    case 'type':
      return 3;
    default:
      return 1;
  }
}

function updateSortIcons(attribute, sortState) {
  const iconMap = {
    [sortModes.NONE]: 'fas fa-sort',
    [sortModes.ASCENDING]: 'fas fa-sort-up',
    [sortModes.DESCENDING]: 'fas fa-sort-down'
  };

  document.querySelectorAll('.sort-btn i').forEach(icon => {
    icon.className = 'fas fa-sort';
  });

  const targetIcon = document.querySelector(`.sort-btn[data-sort="${attribute}"] i`);
  if (targetIcon) {
    targetIcon.className = iconMap[sortState];
  }
}
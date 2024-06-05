const typeSelector = document.getElementById('type');

typeSelector.addEventListener('change', async () => {
  typeSelector.value === 'VRAIFAUX' ? await addVraiFauxFields() : null;
  typeSelector.value === 'QCU' ? await addQcuFields() : null;
  typeSelector.value === 'QUESINTERAC' ? await addQuesInteracFields() : null;
})

async function addVraiFauxFields() {
  const formFieldsContainer = document.getElementById('form-fields-container');
  const qcu = await fetch('/question/vraifaux');
  formFieldsContainer.innerHTML = await qcu.text();
}

async function addQcuFields() {
  const formFieldsContainer = document.getElementById('form-fields-container');
  const qcu = await fetch('/question/qcu');
  formFieldsContainer.innerHTML = await qcu.text();
}

async function addQuesInteracFields() {
  const formFieldsContainer = document.getElementById('form-fields-container');
  const qcu = await fetch('/question/quesinterac');
  formFieldsContainer.innerHTML = await qcu.text();
}
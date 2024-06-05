import Popup from "/assets/popup/popup.js";

const scoreBtn = document.getElementById("scoreBtn");

scoreBtn.addEventListener("click", async () => {
  const popup = new Popup();

  popup.title = "Score";
  popup.content = await fetch("/get-score")
    .then(res => res.text());
  popup.addFooterBtn("Fermer", function () {
    popup.close();
  });

  popup.closingBtn = true;

  await popup.show();
});


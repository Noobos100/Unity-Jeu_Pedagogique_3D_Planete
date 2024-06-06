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

if ('caches' in window) {
  // Ouvre tous les caches disponibles
  caches.keys().then(function(cacheNames) {
    // Parcourt tous les caches et les supprime
    cacheNames.forEach(function(cacheName) {
      caches.delete(cacheName).then(function(success) {
        if (success) {
          console.log('Cache ' + cacheName + ' deleted successfully.');
        } else {
          console.log('Failed to delete cache ' + cacheName + '.');
        }
      });
    });
  }).catch(function(error) {
    console.error('Error while accessing caches:', error);
  });
} else {
  console.log('Cache API is not supported in this browser.');
}

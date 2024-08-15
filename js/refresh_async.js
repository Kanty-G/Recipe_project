let lastUpdateHere = new Date();

function convertTime(date) {
    tempDate = date.split(" ")

    year = tempDate[0];
    time = tempDate[1];

    yearInfo = year.split("-").map(Number);
    // Mois - 1
    yearInfo[1] = yearInfo[1] - 1
    timeInfo = time.split(":").map(Number);

    return [...yearInfo, ...timeInfo];
}


async function checkUpdate() {
  const res = await fetch("http://localhost/tp2/api/recette/last_updated.php");
  timeRes = await res.json();
  // Aucune mise à jour dans la présente session
  if(timeRes["last_updated"] == null) {
    return;
  }
  lastUpdateDB = new Date(...convertTime(timeRes["last_updated"]));

  if (lastUpdateDB.getTime() > lastUpdateHere.getTime()) {
    // Update + récente, on l'applique
    lastUpdateHere = lastUpdateDB;
    recherche_recettes(null, currentPage);
  }
}
//checkUpdate()
setInterval(checkUpdate, 5000);

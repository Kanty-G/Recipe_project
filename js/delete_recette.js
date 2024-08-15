function deleteRecette(user_id, recette_id) {
  fetch(
    `http://localhost/tp2/api/recette/del_one.php?user_id=` +
      user_id +
      "&recette_id=" +
      recette_id,
    {
      method: "GET",
    }
  ).then((res) => {
    recherche_recettes(null, (page = 0));
  });
}

function areYouSureAlert(user_id, recette_id, recette_nom) {
  // Pop up vérification
  const verify = confirm(
    `Voulez-vous vraiment supprimer la recette "${recette_nom}" ?`
  );
  if (verify) {
    deleteRecette(user_id, recette_id);
  }
}

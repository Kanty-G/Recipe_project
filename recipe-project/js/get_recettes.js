let maxPage = 0;

const recettes_view = document.getElementById("recette_view");
const pagination = document.getElementById("nav_pag");

function deleteRecette(user_id, recette_id) {
  fetch(`http://localhost/tp2/api/recette/del_one.php?user_id=` + user_id + "&recette_id=" + recette_id, {
    method: "GET",
  }).then(() => {
    // Clear les recettes dans le HTML
    recettes_view.innerHTML = "";
    // Et la pagination (dans le cas ou on perderait une page
    // suite à la supression)
    pagination.innerHTML = "";
    setRecettes(user_id, 0)
  });
}

function areYouSureAlert(user_id, recette_id, recette_nom) {
  // Pop up vérification
  deleteRecette(user_id, recette_id);
}

// Récupération des recettes à la page PAGE
async function getRecettes(page) {
  const req = await fetch(
    `http://localhost/tp2/api/recette/read.php?page=${page}`,
    {
      method: "GET",
    }
  );
  return await req.json();
}

// On display les recettes
async function setRecettes(user_id, page) {

  // Cherche les recettes du user
  recettes = await getRecettes(page);

  maxPage = Math.ceil(parseInt(recettes.count) / 15);

  // Si on trouve des recettes
  if (!recettes.message) {
    recettes["recettes"].forEach((recette) => {
      const recetteEl = document.createElement("div");
      recetteEl.className = "recette card col-12 col-sm-6 col-md-4 col-lg-3";

      const content = document.createElement("div");
      content.className = "card-body";

      const nom = document.createElement("h4");
      nom.className = "card-title";
      nom.textContent = recette.nom;

      // Image de la recette
      const img = document.createElement("img");
      img.className = "card-img-top";
      img.src = recette.image;
      img.alt = recette.nom;

      // Btn suppression et partage de recette
      const btn_bar = document.createElement("div");
      btn_bar.className = "btn_bar card-body d-flex flex-row-reverse";

      const btn_supp = document.createElement("div");
      btn_supp.innerHTML = "X";
      btn_supp.className = "btn btn-secondary card-link";
      btn_supp.addEventListener("click", function () {
        areYouSureAlert(user_id, recette.id, recette.nom);
      });

      btn_bar.appendChild(btn_supp);

      recetteEl.appendChild(btn_bar);
      recetteEl.appendChild(img);
      content.appendChild(nom);

      recetteEl.appendChild(content);

      recettes_view.appendChild(recetteEl);
    });
  }

  // Pagination


  const list = document.createElement("ul");
  list.className = "pagination";

  i = 1;
  while (i <= maxPage) {
    let li = document.createElement("li");

    li.className = "page-item";

    // Page active
    if (i == page + 1) {
      li.className += " active";
    }
    li.innerHTML =
      `<a class="page-link" href=".././change_page.php?page=${i}">` +
      i +
      `</a>`;

    list.append(li);
    i++;
  }

  pagination.append(list);
}

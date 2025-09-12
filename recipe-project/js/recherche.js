// Filtres
const recherche = document.getElementById("recherche");

// Global ou user
const state = document.getElementById("switch_recettes");

// Catégorie
const categorie_select = document.getElementById("categorie_select");

let currentPage = 0;

let type_recherche = 0;
let categorie_id = 0;

function change_categorie(e) {
  categorie_id = categorie_select.value;

  recherche_recettes(null, (page = 0));
}

function change_type(e) {
  if (state.checked) {
    type_recherche = 1;
  } else {
    type_recherche = 0;
  }

  recherche_recettes(null, (page = 0));
}

async function recherche_recettes(e, page = 0) {
  currentPage = page;

  const req = await fetch(
    `http://localhost/tp2/api/recette/recherche.php?page=${page}&keyword=` +
      recherche.value +
      "&type=" +
      type_recherche +
      "&id=" +
      user_id +
      "&categorie=" +
      categorie_id,
    {
      method: "GET",
    }
  );
  recettes = await req.json();
  setRecettes(page, recettes);
}

recherche.addEventListener("input", recherche_recettes);

state.addEventListener("click", change_type);

categorie_select.addEventListener("change", change_categorie);

let maxPage = 0;

const recettes_view = document.getElementById("recette_view");
const pagination = document.getElementById("nav_pag");

// On display les recettes
async function setRecettes(page, recettes = null) {
  // Clear les recettes dans le HTML
  recettes_view.innerHTML = "";
  // Et la pagination (dans le cas ou on perderait une page
  // suite à la supression)
  pagination.innerHTML = "";

  maxPage = Math.ceil(parseInt(recettes.count) / 15);

  //src: https://getbootstrap.com/docs/4.0/components/modal/
  recettes_view.innerHTML = `
   <div class="recette-header text-center my-3">
    <button type="button" class="btn btn-primary btn-ajout-recette" data-toggle="modal" data-target="#recetteModal" onclick="openAddRecetteForm()">
      Ajouter une recette
    </button>
  </div>

  <!--modal pour ajouter et modifier une recette-->

  <div class="modal fade" id="recetteModal" tabindex="-1" role="dialog" aria-labelledby="recetteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="recetteForm">
          <div class="modal-header">
            <h5 class="modal-title" id="recetteModalLabel">Ajouter une recette</h5>
          </div>
          <div class="modal-body">
            <input type="hidden" id="recetteId" name="id">
            <div class="form-group">
              <label for="nom">Nom</label>
              <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="image">Image</label>
              <input type="text" id="image" name="image" class="form-control" required placeholder="http://mesrecettes/recette.jpg">
              <img id="imagePreview" src="#" alt="Aperçu de l'image" class="img-thumbnail mt-2" style="display: none; max-height: 200px;">
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="categorie">Catégorie</label>
              <select name="categorie" id="categorie" class="form-control" required>
                <option value="">Sélectionnez une catégorie</option>
                <option value="1">Entrée</option>
                <option value="2">Plat Principal</option>
                <option value="3">Dessert</option>
              </select>
            </div>
            <div class="form-group">
              <label for="ingredients">Ingrédients</label>
              <textarea id="ingredients" name="ingredients" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!--modal pour voir l'aperçu de la recette-->

  <div class="modal fade" id="detailRecetteModal" tabindex="-1" role="dialog" aria-labelledby="detailRecetteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailRecetteModalLabel">Détails de la recette</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 id="detailRecetteNom"></h4>
          <img id="detailRecetteImage" src="#" alt="Image de la recette" class="img-fluid">
          <p id="detailRecetteDate"></p>
          <h4>Ingrédients</h4>
          <p id="detailRecetteIngredients"></p>
          <h4>Description</h4>
          <p id="detailRecetteDescription"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>`;

  document.getElementById("image").onchange = function (event) {
    //const [file] = event.target.files;
    /*if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("imagePreview").src = e.target.result;
        document.getElementById("imagePreview").style.display = "block";
      };
      reader.readAsDataURL(file);
    }*/

    document.getElementById("imagePreview").src = event.target.value;
    document.getElementById("imagePreview").style.display = "block";
  };

  document.getElementById("recetteForm").onsubmit = function (event) {
    event.preventDefault();
    const recetteId = document.getElementById("recetteId").value;

    if (recetteId) {
      updateRecette(recetteId);
    } else {
      //ajouter recette
      addRecette();
    }
    $("#recetteModal").modal("hide");
  };
  const categoriesMap = {
    1: "Entrée",
    2: "Plat Principal",
    3: "Dessert",
  };
  admin_id = 1;

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
      img.addEventListener("click", function () {
        openDetailRecetteModal(recette);
      });
      //date
      const date = document.createElement("p");
      date.className = "card-text";
      date.textContent = `Date: ${new Date(
        recette.date_creation
      ).toLocaleDateString()}`;

      //categorie
      const categorie = document.createElement("p");
      categorie.className = "card-text";
      categorie.textContent = `Catégorie: ${categoriesMap[recette.categorie]}`;
      // Btn suppression
      const btn_bar = document.createElement("div");
      btn_bar.className = "btn_bar card-body d-flex flex-row-reverse gap-2";

      const btn_supp = document.createElement("button");
      btn_supp.disabled = user_id != recette.owner_id && user_id != admin_id;
      btn_supp.innerHTML = "X";
      btn_supp.className = "btn btn-danger card-link";
      btn_supp.addEventListener("click", function () {
        areYouSureAlert(user_id, recette.id, recette.nom);
      });

      //Btn modifier

      const btn_modif = document.createElement("button");
      btn_modif.disabled = user_id != recette.owner_id && user_id != admin_id;
      btn_modif.innerHTML = "Modifier";
      btn_modif.className = "btn btn-secondary card-link";
      btn_modif.addEventListener("click", function () {
        openEditRecetteForm(recette);
      });

      btn_bar.appendChild(btn_supp);
      btn_bar.appendChild(btn_modif);

      content.appendChild(nom);
      content.appendChild(categorie);

      recetteEl.appendChild(btn_bar);
      recetteEl.appendChild(img);

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
      `<div class="page-link" onclick="recherche_recettes(null, ${i - 1})">` +
      i +
      `</div>`;

    list.append(li);
    i++;
  }

  pagination.append(list);
}

function openAddRecetteForm() {
  document.getElementById("recetteForm").reset();
  document.getElementById("recetteId").defaultValue = "";
  document.getElementById("nom").defaultValue = "";
  document.getElementById("imagePreview").style.display = "none";
  document.getElementById("recetteModalLabel").innerText =
    "Ajouter une recette";
  document.getElementById("date").defaultValue = "";
  document.getElementById("image").defaultValue = "";

  document.getElementById("categorie").value = "";

  document.getElementById("ingredients").defaultValue = "";
  document.getElementById("description").defaultValue = "";
}

function openEditRecetteForm(recette) {
  document.getElementById("recetteModalLabel").innerText =
    "Modifier la recette " + recette.nom;

  document.getElementById("recetteId").defaultValue = recette.id;
  document.getElementById("nom").defaultValue = recette.nom;

  document.getElementById("date").defaultValue =
    recette.date_creation.substring(0, 10);
  document.getElementById("image").defaultValue = recette.image;

  document.getElementById("categorie").value = recette.categorie;

  document.getElementById("ingredients").defaultValue = recette.ingredients;
  document.getElementById("description").defaultValue = recette.description;

  // Gérer l'image
  document.getElementById("imagePreview").src = "";
  document.getElementById("imagePreview").style.display = "none";

  // Afficher le modal
  $("#recetteModal").modal("show");
}

function openDetailRecetteModal(recette) {
  document.getElementById("detailRecetteNom").innerText = recette.nom;
  document.getElementById("detailRecetteImage").src = recette.image;
  document.getElementById("detailRecetteDate").innerText =
    recette.date_creation;
  document.getElementById("detailRecetteIngredients").innerText =
    recette.ingredients;
  document.getElementById("detailRecetteDescription").innerText =
    recette.description;

  $("#detailRecetteModal").modal("show");
}

async function updateRecette(recetteId) {
  var recette = { id: recetteId };

  nom = document.getElementById("nom");
  date = document.getElementById("date");
  categorie = document.getElementById("categorie");
  ingredients = document.getElementById("ingredients");
  description = document.getElementById("description");
  imageURL = document.getElementById("image");

  if (nom.defaultValue !== nom.value) {
    recette.nom = nom.value;
  }

  if (date.defaultValue !== date.value) {
    recette.date_creation = date.value;
  }

  if (categorie.defaultValue !== categorie.value) {
    recette.categorie = categorie.value;
  }

  if (ingredients.defaultValue !== ingredients.value) {
    recette.ingredients = ingredients.value;
  }

  if (description.defaultValue !== description.value) {
    recette.description = description.value;
  }

  recette.image = imageURL.value;

  await fetch(`http://localhost/tp2/api/recette/modify.php`, {
    method: "POST",
    body: JSON.stringify(recette),
  });
  recherche_recettes(null, (page = 0));
}

async function addRecette() {
  // Info du form
  nom = document.getElementById("nom").value;
  date = document.getElementById("date").value;
  categorie = document.getElementById("categorie").value;
  ingredients = document.getElementById("ingredients").value;
  description = document.getElementById("description").value;
  imageURL = document.getElementById("image").value;

  await fetch(`http://localhost/tp2/api/recette/add.php`, {
    method: "POST",
    body: JSON.stringify({
      user_id: user_id,
      nom: nom,
      date: date,
      categorie: categorie,
      ingredients: ingredients,
      description: description,
      imageURL: imageURL,
    }),
  });
  recherche_recettes(null, (page = 0));
}

function init_recette(page) {
  recherche_recettes(page);
}

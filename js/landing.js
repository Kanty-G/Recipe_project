// Sélection entre Login et Register

function toggleHidden(element) {
  if (element.hasAttribute("hidden")) {
    element.removeAttribute("hidden");
  } else {
    element.setAttribute("hidden", true);
  }
}

swapConnexion.onclick = function () {
  toggleHidden(connexionForm);
  toggleHidden(inscriptionForm);
};

swapInscription.onclick = function () {
  toggleHidden(connexionForm);
  toggleHidden(inscriptionForm);
};


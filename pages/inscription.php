<div id="inscriptionForm" class="container w-25 pt-5" hidden>
  <form action="../register.php" method="post">
    <h2>Inscription</h2>
    <?php
    if ($_SESSION['errorIns']) {
      ?>
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <div>
          <?php
          echo $_SESSION['errorIns'];
          ?>
        </div>
      </div>
      <?php
    }
    ?>
    <div class="form-group mb-2">
      <label for="email" class="form-label">Adresse Courriel: </label>
      <input type="email" class="form-control" id="email" name="email" pattern="[a-z0-9._\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
        required>
    </div>
    <div class="form-group mb-4">
      <label for="prenom" class="form-label">Prénom: </label>
      <input type="text" class="form-control" id="prenom" name="prenom" required>
    </div>
    <div class="form-group mb-4">
      <label for="nom" class="form-label">Nom: </label>
      <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="form-group mb-4">
      <label for="password" class="form-label">Password: </label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group">
      <a id="swapConnexion" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Se connecter</a>
      <button type="submit" class="btn btn-primary">Inscription</button>
    </div>
  </form>
</div>
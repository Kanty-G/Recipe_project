<div id="connexionForm" class="container w-25 pt-5">
  <form action="../login.php" method="post">
    <h2>Connexion</h2>
    <?php
    if ($_COOKIE['logged_out']) {
      setcookie('logged_out', '', time() - 3600, '/');
      unset($_COOKIE['logged_out']);
      ?>
      <div class="alert alert-warning d-flex align-items-center" role="alert">
        <div>
          Vous êtes déconnecté(e)!
        </div>
      </div>
      <?php
    } else if ($_SESSION['errorLog']) {
      ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
          <div>
            <?php
            echo $_SESSION['errorLog'];
            ?>
          </div>
        </div>
      <?php
    } else if ($_SESSION['success']) {
      ?>
          <div class="alert alert-success d-flex align-items-center" role="alert">
            <div>
            <?php
            echo $_SESSION['success'];
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
      <label for="password" class="form-label">Mot de passe: </label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary">Se connecter</button>
      <a id="swapInscription" class="btn btn-secondary">Inscription <i class="bi bi-arrow-right"></i></a>
    </div>
  </form>
</div>
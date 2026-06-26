<?php
session_start();

if (!isset($_SESSION['autorise']) || $_SESSION['autorise'] !== true) {
    header('Location: ../../index.html');
    exit;
}

// (Optionnel) Vérification date expiration
if (strtotime($_SESSION['date_expiration']) < time()) {
    session_destroy();
    header('Location: ../../index.html');
    exit;
}
echo "<p>Compte Expire : {$_SESSION['date_expiration']}</p>";
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Laflhai@live</title>

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Clappr script -->
  <script src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>

  <!-- Custom CSS -->
  <link href="style.css" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" href="live.gif" type="image/gif">
</head>
<body>
  <!-- Loading Screen -->
  <div id="loading-screen">
    <img src="loading.gif" alt="Loading...">
  </div>

  <!-- Overlay -->
  <div id="overlay"></div>

  <!-- Sidebars flottantes -->
  <div id="sidebar-left">
    <h5>/////</h5><p><h5>/////</h5></p><p></p><h5><p></p> <span id="current-date"></span> </h5><p></p>
    <input type="text" id="search-input" class="form-control mt-2" placeholder="Recherche...">
   
    <button id="favorites-toggle" class="btn btn-warning btn-block mt-2">Afficher les Favoris</button>
     <ul id="playlist-sidebar" class="list-group mt-3" style="background-color:transparent;border-color: blueviolet; text-decoration-color: white; text-shadow: 0cqmax;"></ul>
  </div>

  -<div id="sidebar-right">
    <h5>⚙ Paramètres</h5>
    <div class="setting-item mt-2">
      <label style="color: aliceblue;">Compte Expire</label>
           <?php echo "<p>Compte Expire : {$_SESSION['date_expiration']}</p>";?>
    </div>
    <div class="setting-item mt-2">
      <label style="color: aliceblue;">Aspect Ratio</label>
      ⚙ Deconexion
        
    </div>
  </div>

  <!-- Toggle Buttons -->
  <button class="sidebar-btn" id="toggle-left">☰<img src="loglaf.PNG" alt="Logo"  ></button>
  <!-- Bloc central (logo + date) -->
<div id="center-logo-date">
  
  <span id="current-date"></span> 
</div>
  <button class="sidebar-btn" id="toggle-right"><img src="live.gif" alt="Logo" id="center-logo"></button>

  <!-- Player Container -->
  <div id="player-container">
    <div id="tv-screen">
      <div id="player"></div>
    </div>
  </div>

  <!-- Custom JS -->
  <script src="script.js"></script>
</body>
</html>

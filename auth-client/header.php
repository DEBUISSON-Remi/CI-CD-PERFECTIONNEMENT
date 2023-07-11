<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <title>API - Projet</title>
  <style>
    body {
      padding: 10px 15vw;
    }
    #map,
    #carte {
      height: 400px;
      width: 100%;
      max-width: 70vw;
    }
    #routes {
      display: flex;
      flex-direction: row;
      justify-content: center;
      flex-wrap: wrap;
      align-items: center;
    }
    .card {
      margin: 20px !important;
    }
  </style>
</head>

<body>
  <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="dashboard.php">
        <img src="https://s3-eu-west-1.amazonaws.com/clientsassets/digischool/alternance/prod/company/1619085924.jpg" width="28" height="28">
      </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-start">
        <a class="navbar-item" href="dashboard.php">
          Dashboard
        </a>

        <div class="navbar-item has-dropdown is-hoverable">
          <a class="navbar-link">
            Map
          </a>

          <div class="navbar-dropdown">
            <a class="navbar-item" href="routes.php">
              My routes
            </a>
            <a class="navbar-item" href="map.php">
              Add route
            </a>
          </div>
        </div>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons">
            <a class="button is-light is-danger" href="logout.php">
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
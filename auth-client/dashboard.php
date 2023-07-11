<?php
require_once('./header.php');
require 'vendor/autoload.php';

session_start();

// var_dump($_SESSION["user"]);
// var_dump($_SESSION["token"]);
?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
    <h1 class="title is-1">Bienvenu sur la plateforme de création d'itinéraires !</h1>
    <img src="https://static.mensup.fr/photo_article/751037/295139/1200-L-fortnite-le-retour-de-tilted-tower-comment-y-accder.jpg" style="max-height: 50vh;">
</div>
<?php
require_once('./footer.php');
?>
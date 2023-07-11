<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('display_startup_errors', 1);

function getRoutes(){

    // Connexion à la BDD
    $pdo = new PDO("mysql:host=localhost:3306;dbname=points", "root", "root");

    // Récupérations des routes
    $stmt = $pdo->prepare("SELECT * FROM route");
    $stmt->execute();
    $routes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si au moins une route existe
    if($routes)
    {
        echo json_encode(["message"=> "Routes récupérées", "routes"=> $routes]);
    }
    else {
        echo json_encode(["message"=> "Aucune route trouvée"]);
    }

}
getRoutes();
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('display_startup_errors', 1);

function createRoute(){
    $name = $_POST["name"];
    $points = $_POST["points"];

    // Connexion à la BDD
    $pdo = new PDO("mysql:host=localhost:3306;dbname=points", "root", "root");

    // vérifie si la route n'existe pas déjà
    $stmt = $pdo->prepare("SELECT * FROM route WHERE name = :name");
    $stmt->execute([
        ":name" => $name
    ]);
    $route = $stmt->fetch(PDO::FETCH_ASSOC);

    // si la route n'existe pas, ajoute la route
    if(!$route)
    {
        $stmt = $pdo->prepare("INSERT INTO route VALUES (NULL, :name, :points)");
        $stmt->execute([
            ":name" => $name,
            ":points" => $points
        ]);
        $route = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(["message"=> "Route crée"]);
    }
    else {
        echo json_encode(["message"=> "Cette route existe déjà"]);
    }

}
createRoute();
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require 'vendor/autoload.php';
use Firebase\JWT\JWT;

function login()
{

    $login = filter_input(INPUT_POST, "login");
    $password = filter_input(INPUT_POST, "password");

    // On vérifie que le login existe bien
    $pdo = new PDO("mysql:host=localhost:3306;dbname=api", "root", "root");
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->execute([
        ":login" => $login
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'on n'a trouvé personne dans la BDD
    if(!$user)
    {
        return [
            "status" => "error",
            "message" => "Identifiants incorrects"
        ];
    }

    // Si le mot de passe saisi est incorrect (on vérifie le mot de passe du formulaire)
    // avec le hash venant de la base de données
    if(!password_verify($password, $user["password"]))
    {
        return [
            "status" => "error",
            "message" => "Identifiants incorrects"
        ];
    }

    // Générez le JWT en utilisant les données et la clé secrète
    $secretKey = '#Y9w}xB7_C2M(9=gAwEZ+97s{66pJdU9twX23[]~$s)484c9%K*a2aX3$Y@en/!RF9:.QxutUPVzgp76e,-ET4h9V?6SvVH;n^68';
    $jwt = JWT::encode(
        [
            "login" => $login,
        ], 
        $secretKey, 
        'HS256'
    );

    // Si tout s'est bien passé on tej le password
    unset($user["password"]);

    //Retourne les infos de connexion si tout est bon
    return [
        "status" => "success",
        "user" => $user,
        "token" => $jwt
    ];
}

$data = login();
header('Content-Type: application/json');
echo json_encode($data);
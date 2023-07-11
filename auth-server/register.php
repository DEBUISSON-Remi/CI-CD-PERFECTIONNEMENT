<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
function register()
{
    $login = filter_input(INPUT_POST, "login");
    $password = filter_input(INPUT_POST, "password");

    
    // On vérifie que le login est unique
    $pdo = new PDO("mysql:host=localhost:3306;dbname=eanp2023api", "root", "");
    $stmtVerif = $pdo->prepare("SELECT * FROM users WHERE login = :login");
    $stmtVerif->execute([
        ":login" => $login
    ]);
    $usertest = $stmtVerif->fetch(PDO::FETCH_ASSOC);

    // Si l'on n'a trouvé personne dans la BDD
    if(!$usertest)
    {
        //Création de l'utilisateur
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmtInsert = $pdo->prepare("INSERT INTO users (login, password) VALUES (:login, :password)");
        $stmtInsert->execute([
            ":login" => $login,
            ":password"=> $hashedPassword
        ]);

        $user["login"] = $login;
        $user["password"] = $password;
    }
    else{
        return [
            "status" => "error",
            "message" => "Cet utilisateur existe déjà"
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

    // Si tout s'est bien passé
    unset($user["password"]);

    return [
        "status" => "success",
        "user" => $user,
        "token" => $jwt
    ];
}

$data = register();
header('Content-Type: application/json');
echo json_encode($data);
<?php
require 'vendor/autoload.php';


session_start();
function login()
{

    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        $login = filter_input(INPUT_POST, "login");
        $password = filter_input(INPUT_POST, "password");

        // S'il manque des champs, on arrête
        if (!$login || !$password) {
            return [
                "status" => "error",
                "message" => "Merci de saisir l'ensemble des informations"
            ];
        }

        // Là, il faudra appeler le serveur d'authentification
        $connexion = appelServeurAuth($login, $password);

        // Le résultat est soit vrai, soit faux.
        if ($connexion["status"] == "error") {
            return $connexion;
        }

        //Permet de verifier si le token est bon
        $token = appelServeurVerifyToken($connexion["token"]);
        if ($token["status"] == "error") {
            return $token;
        }

        // On stocke l'utilisateur dans la session
        $_SESSION["user"] = $connexion["user"];
        $_SESSION["token"] = $connexion["token"];

        header('Location: dashboard.php');
        exit();
    }
}

function appelServeurAuth($login, $password)
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'http://localhost:4001/login.php', [
        'form_params' => [
            'login' => $login,
            'password' => $password
        ]
    ]);
    $body = $response->getBody();
    return json_decode($body, true);
}
function appelServeurVerifyToken($token)
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'http://localhost:4001/verify.php', [
        'form_params' => [
            'token' => $token,
        ]
    ]);
    $body = $response->getBody();
    return json_decode($body, true);
}

$data = login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Connexion</title>
    <style>
        form * {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1 class="title is-1 has-text-centered">Login</h1>
    <?php if (is_array($data) && $data['status'] == "error") : ?>
        <p style="color: red">
            <?= $data['message'] ?>
        </p>
    <?php endif; ?>
    <form method="POST" class="box mx-auto my-6" style="max-width: 600px;">
        <label for="login" class="tag is-light">Login</label>
        <input type="text" class="input" name="login" placeholder="login" id="login" maxlength="20">

        <label for="password" class="tag is-light">Mot de passe</label>
        <input type="password" class="input" name="password" placeholder="password" id="password">

        <input type="submit" class="button is-primary" style="width: 100%;" value="S'inscrire">
    </form>
    <p class="has-text-centered"><span>You don't have an account ?</span><a href="register.php">&nbsp;Register</a></p>
    <?php
    require_once('./footer.php');
    ?>
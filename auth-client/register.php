<?php
require 'vendor/autoload.php';

session_start();
function register()
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
        $registration = appelServeurRegister($login, $password);

        // Le résultat est soit vrai, soit faux.
        if ($registration["status"] == "error") {
            return $registration;
        }

        $token = appelServeurVerifyToken($registration["token"]);
        if ($token["status"] == "error") {
            return $token;
        }
        // On stocke l'utilisateur dans la session
        $_SESSION["user"] = $registration["user"];
        $_SESSION["token"] = $registration["token"];

        header('Location: dashboard.php');
        exit();
    }
}

function appelServeurRegister($login, $password)
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'http://localhost:4001/register.php', [
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

$data = register();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Register</title>
    <style>
        form * {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1 class="title is-1 has-text-centered">Register</h1>
    <?php if (is_array($data) && $data['status'] == "error") : ?>
        <p style="color: red">
            <?= $data['message'] ?>
        </p>
    <?php endif; ?>
    <form method="POST" class="box mx-auto my-6" style="max-width: 600px;">
        <label for="login" class="tag is-light">Identifiant</label>
        <input type="text" class="input" name="login" placeholder="login" id="login" required maxlength="20">

        <label for="password" class="tag is-light">Mot de passe</label>
        <input type="password" class="input" name="password" placeholder="password" id="password" required>

        <input type="submit" class="button is-primary" style="width: 100%;" value="S'inscrire">
    </form>
    <p class="has-text-centered"><span>You already have an account ?</span><a href="login.php">&nbsp;Login</a></p>
    <?php
    require_once('./footer.php');
    ?>
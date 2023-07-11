<?php
require 'vendor/autoload.php';

session_start();
function logout()
{
    $token = appelServeurVerifyToken($_SESSION["token"]);
    if ($token["status"] == "error") {
        return $token;
    }

    session_destroy();
    header('Location: login.php');
    exit();
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
$data = logout();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>

<body>
    <?php if (is_array($data) && $data['status'] == "error") : ?>
        <p style="color: red">
            <?= $data['message'] ?>
        </p>
    <?php endif; ?>
</body>
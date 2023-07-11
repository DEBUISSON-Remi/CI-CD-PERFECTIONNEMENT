<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
function verify()
{

    $secretKey = '#Y9w}xB7_C2M(9=gAwEZ+97s{66pJdU9twX23[]~$s)484c9%K*a2aX3$Y@en/!RF9:.QxutUPVzgp76e,-ET4h9V?6SvVH;n^68';

    $jwt = filter_input(INPUT_POST, "token");
    try
    {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        return [
            'status' => 'success',
            'message' => ''
        ];
    }
    catch(\Exception $e)
    {
        return [
            'status' => 'error',
            'message' => "Jeton inconnu"
        ];
    }
}
$data = verify();
echo json_encode($data);

<?php
require_once 'vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;

session_start();

$client = new Client();
$client->setClientId('TU_CLIENT_ID');
$client->setClientSecret('TU_CLIENT_SECRET');
$client->setRedirectUri('https://abcdef.ngrok.io/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $oauth = new Oauth2($client);
    $userData = $oauth->userinfo->get();

    $_SESSION['user'] = [
        'id' => $userData->id,
        'name' => $userData->name,
        'email' => $userData->email,
        'picture' => $userData->picture
    ];

    header('Location: index.php');
    exit;
}

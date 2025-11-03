<?php
require_once 'vendor/autoload.php';

session_start();

$fb = new Facebook\Facebook([
  'app_id' => 'TU_APP_ID',
  'app_secret' => 'TU_APP_SECRET',
  'default_graph_version' => 'v19.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
  $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
  $user = $response->getGraphUser();

  $_SESSION['user'] = [
      'id' => $user->getId(),
      'name' => $user->getName(),
      'email' => $user->getEmail(),
      'picture' => $user->getPicture()['url']
  ];

  header('Location: index.php');
  exit;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}
?>

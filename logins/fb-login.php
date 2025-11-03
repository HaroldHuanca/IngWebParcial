<?php
require_once 'vendor/autoload.php';

session_start();

$fb = new Facebook\Facebook([
  'app_id' => 'TU_APP_ID',
  'app_secret' => 'TU_APP_SECRET',
  'default_graph_version' => 'v19.0',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email'];
$loginUrl = $helper->getLoginUrl('https://abcdef.ngrok.io/fb-callback.php', $permissions);

header('Location: ' . $loginUrl);
exit;
?>

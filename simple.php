<?php
require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_AnalyticsService.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google Analytics PHP Starter Application");

// Visit https://code.google.com/apis/console?api=analytics to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('524564756727.apps.googleusercontent.com');
$client->setClientSecret('IXMCbMji0Zxy86kgWfye_KKm');
$client->setRedirectUri('http://localhost/WFC/analytics/examples/analytics/demo/');
$client->setDeveloperKey('524564756727@developer.gserviceaccount.com');
$service = new Google_AnalyticsService($client);

if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  $props = $service->management_webproperties->listManagementWebproperties("~all");
  print "<h1>Web Properties</h1><pre>" . print_r($props, true) . "</pre>";

  $accounts = $service->management_accounts->listManagementAccounts();
  print "<h1>Accounts</h1><pre>" . print_r($accounts, true) . "</pre>";

  $segments = $service->management_segments->listManagementSegments();
  print "<h1>Segments</h1><pre>" . print_r($segments, true) . "</pre>";

  $goals = $service->management_goals->listManagementGoals("~all", "~all", "~all");
  print "<h1>Segments</h1><pre>" . print_r($goals, true) . "</pre>";

  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}
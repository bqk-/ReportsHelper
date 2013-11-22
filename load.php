
<?php
error_reporting(0);
ob_start();
require_once __DIR__.'/../../../src/Google_Client.php';
require_once __DIR__.'/../../../src/contrib/Google_AnalyticsService.php';
require_once __DIR__.'/storage.php';
require_once __DIR__.'/authHelper.php';
require_once __DIR__.'/shortcodes.php';
require_once __DIR__.'/html2pdf.class.php';
require_once __DIR__.'/piegraph.php';

// These must be set with values YOU obtains from the APIs console.
// See the Usage section above for details.
const REDIRECT_URL = '*************';
const CLIENT_ID = '***********************';
const CLIENT_SECRET = '********************';

// The file name of this page. Used to create various query parameters to
// control script execution.
const THIS_PAGE = 'index.php';

const APP_NAME = 'WFC Analytics';
const ANALYTICS_SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';

define('ABS_URL','*************'); //C:\\xamp\\htdocs\\www\\
define('REAL_URL','***************'); //http://localhost/WFC/
define('BASE_URL','***************'); //http://localhost/

$demoErrors = null;

$authUrl = THIS_PAGE . '?action=auth';
$revokeUrl = THIS_PAGE . '?action=revoke';

$helloAnalyticsDemoUrl = THIS_PAGE . '?demo=hello';
$mgmtApiDemoUrl = THIS_PAGE . '?demo=mgmt';
$coreReportingDemoUrl = THIS_PAGE . '?demo=reporting';

// Build a new client object to work with authorization.
$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URL);
$client->setDeveloperKey('*********');
$client->setApplicationName(APP_NAME);
$client->setScopes(
    array(ANALYTICS_SCOPE));

// Magic. Returns objects from the Analytics Service
// instead of associative arrays.
$client->setUseObjects(true);


// Build a new storage object to handle and store tokens in sessions.
// Create a new storage object to persist the tokens across sessions.
$storage = new apiSessionStorage();


$authHelper = new AuthHelper($client, $storage, THIS_PAGE);

// Main controller logic.

if ($_GET['action'] == 'revoke') {
  $authHelper->revokeToken();

} else if ($_GET['action'] == 'auth' || $_GET['code']) {
  $authHelper->authenticate();
  $_GET['refresh']=true;

} else {
  $authHelper->setTokenFromStorage();

  if ($authHelper->isAuthorized()) {
    $analytics = new Google_AnalyticsService($client);

      // Core Reporting API Reference Demo.
      require_once 'CoreReportingApiReference.php';

      $demo = new coreReportingApiReference($analytics, THIS_PAGE);

  }

  // The PHP library will try to update the access token
  // (via the refresh token) when an API request is made.
  // So the actual token in apiClient can be different after
  // a require through Google_AnalyticsService is made. Here we
  // make sure whatever the valid token in $service is also
  // persisted into storage.
  $storage->set($client->getAccessToken());
}

// Consolidate errors and make sure they are safe to write.
$errors = $demoError ? $demoError : $authHelper->getError();
$errors = htmlspecialchars($errors, ENT_NOQUOTES);


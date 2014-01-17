<?php
    //error_reporting(E_ALL);
    define('DEVELOPMENT_ENVIRONMENT', true);
    define('ROOT', dirname( __FILE__ ));
    define('DS', '/');
    function setReporting(){
        if( DEVELOPMENT_ENVIRONMENT == true ){
            error_reporting( E_ALL );
            ini_set( 'display_errors', 'On' );
        } else{
            error_reporting( E_ALL );
            ini_set( 'display_errors', 'Off' );
            ini_set( 'log_errors', 'On' );
            ini_set( 'error_log', ROOT.DS.'lm/tmp'.DS.'logs'.DS.'error.log' );
        }
    }

    setReporting();
    ob_start();
    require_once __DIR__.'/src/Google_Client.php';
    require_once __DIR__.'/src/contrib/Google_AnalyticsService.php';
    require_once __DIR__.'/lib/storage.php';
    require_once __DIR__.'/lib/authHelper.php';
    require_once __DIR__.'/shortcodes.php';
    require_once __DIR__.'/lib/html2pdf.class.php';
    require_once __DIR__.'/piegraph.php';
    require_once __DIR__.'/includes/wfc_core_class.php';
    // These must be set with values YOU obtains from the APIs console.
    // See the Usage section above for details.
    const REDIRECT_URL  = 'http://dev.smartydogdesigns.com/analytics/index.php';
    const CLIENT_ID     = '524564756727.apps.googleusercontent.com';
    const CLIENT_SECRET = 'IXMCbMji0Zxy86kgWfye_KKm';
    // The file name of this page. Used to create various query parameters to
    // control script execution.
    const THIS_PAGE = 'index.php';
    const APP_NAME        = 'WFC Analytics';
    const ANALYTICS_SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';
    define('ABS_URL', '/home/wfcdemo/public_html/analytics/');
    define('REAL_URL', 'http://dev.smartydogdesigns.com/analytics/');
    define('BASE_URL', 'http://dev.smartydogdesigns.com');
    $demoErrors = NULL;
    $authUrl   = THIS_PAGE.'?action=auth';
    $revokeUrl = THIS_PAGE.'?action=revoke';
    $helloAnalyticsDemoUrl = THIS_PAGE.'?demo=hello';
    $mgmtApiDemoUrl        = THIS_PAGE.'?demo=mgmt';
    $coreReportingDemoUrl  = THIS_PAGE.'?demo=reporting';
    // Build a new client object to work with authorization.
    $client = new Google_Client();
    $client->setClientId( CLIENT_ID );
    $client->setClientSecret( CLIENT_SECRET );
    $client->setRedirectUri( REDIRECT_URL );
    $client->setDeveloperKey( '524564756727@developer.gserviceaccount.com' );
    $client->setApplicationName( APP_NAME );
    $client->setScopes(
        array(ANALYTICS_SCOPE) );
    // Magic. Returns objects from the Analytics Service
    // instead of associative arrays.
    $client->setUseObjects( true );
    // Build a new storage object to handle and store tokens in sessions.
    // Create a new storage object to persist the tokens across sessions.
    $storage = new apiSessionStorage();
    $authHelper = new AuthHelper($client, $storage, THIS_PAGE);
    if( isset($_GET['action']) && $_GET['action'] == 'revoke' ){
        $authHelper->revokeToken();
    } else{
        if( (isset($_GET['action']) && $_GET['action'] == 'auth') || (isset($_GET['code']) && $_GET['code']) ){
            $authHelper->authenticate();
            $_GET['refresh'] = true;
        } else{
            $authHelper->setTokenFromStorage();
            if( $authHelper->isAuthorized() ){
                $analytics = new Google_AnalyticsService($client);
                // Core Reporting API Reference Demo.
                require_once __DIR__.'/lib/coreReportingApiReference.php';
                $demo = new coreReportingApiReference($analytics, THIS_PAGE);
            }
            // The PHP library will try to update the access token
            // (via the refresh token) when an API request is made.
            // So the actual token in apiClient can be different after
            // a require through Google_AnalyticsService is made. Here we
            // make sure whatever the valid token in $service is also
            // persisted into storage.
            $storage->set( $client->getAccessToken() );
        }
    }
    // Consolidate errors and make sure they are safe to write.
    $errors = isset($demoError) ? $demoError : $authHelper->getError();
    $errors = htmlspecialchars( $errors, ENT_NOQUOTES);

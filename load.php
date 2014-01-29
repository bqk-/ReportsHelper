<?php
    //error_reporting(E_ALL);
    define('DEVELOPMENT_ENVIRONMENT', true);
    define('ROOT', dirname( __FILE__ ));
    define('DS', '/');
    define('TPL_DIR',ROOT.DS.'templates');
    define('PROP_DIR',ROOT.DS.'properties');

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
    require_once __DIR__.'/src/contrib/Google_Oauth2Service.php';
    require_once __DIR__.'/src/contrib/Google_AnalyticsService.php';
    require_once __DIR__.'/lib/storage.php';
    require_once __DIR__.'/lib/authHelper.php';
    require_once __DIR__.'/shortcodes.php';
    require_once __DIR__.'/lib/html2pdf.class.php';
    require_once __DIR__.'/lib/piegraph.php';
    require_once __DIR__.'/includes/wfc_core_class.php';

    // These must be set with values YOU obtains from the APIs console.
    // See the Usage section above for details.
    const REDIRECT_URL  = 'http://dev.smartydogdesigns.com/analytics/index.php';
    const CLIENT_ID     = '***************';
    const CLIENT_SECRET = '***************';
    // The file name of this page. Used to create various query parameters to
    // control script execution.
    const THIS_PAGE = 'index.php';
    const APP_NAME        = 'WFC Analytics';
    const ANALYTICS_SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';
    define('ABS_URL', '/home/wfcdemo/public_html/analytics/');
    define('REAL_URL', 'http://dev.smartydogdesigns.com/analytics/');
    define('BASE_URL', 'http://dev.smartydogdesigns.com/');
    $authUrl   = THIS_PAGE.'?action=auth';
    $revokeUrl = THIS_PAGE.'?action=revoke';
    // Build a new client object to work with authorization.
    $client = new Google_Client();
    $client->setClientId( CLIENT_ID );
    $client->setClientSecret( CLIENT_SECRET );
    $client->setRedirectUri( REDIRECT_URL );
    $client->setDeveloperKey( '*****************' );
    $client->setApplicationName( APP_NAME );
    $client->setScopes(
        array(ANALYTICS_SCOPE,'https://www.googleapis.com/auth/userinfo.email') );
    // Magic. Returns objects from the Analytics Service
    // instead of associative arrays.
    $client->setUseObjects( true );
    // Build a new storage object to handle and store tokens in sessions.
    // Create a new storage object to persist the tokens across sessions.
    $storage = new apiSessionStorage();
    $oauth2Service = new Google_Oauth2Service($client);
    $authHelper = new AuthHelper($client, $storage, THIS_PAGE);
    if( isset($_GET['action']) && $_GET['action'] == 'revoke' ){
        $authHelper->revokeToken();
        unset($_SESSION);
    } else{
        if( (isset($_GET['action']) && $_GET['action'] == 'auth') || (isset($_GET['code']) && $_GET['code']) ){
            $authHelper->authenticate();
            $_GET['refresh'] = true;
        } else{
            $authHelper->setTokenFromStorage();
            if( $authHelper->isAuthorized() ){
                $userinfo = $oauth2Service->userinfo->get();
                $_SESSION['email'] = $userinfo->email;
                $analytics = new Google_AnalyticsService($client);
                // Core Reporting API Reference Demo.
                require_once __DIR__.'/lib/coreReportingApiReference.php';
                $demo = new coreReportingApiReference($analytics, THIS_PAGE);
            }
            $storage->set( $client->getAccessToken() );
        }
    }

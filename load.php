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
    const CLIENT_ID     = '*******';
    const CLIENT_SECRET = '*******';
    // The file name of this page. Used to create various query parameters to
    // control script execution.
    const THIS_PAGE = 'index.php';
    const APP_NAME        = 'WFC Analytics';
    const ANALYTICS_SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';
    define('ABS_URL', '*******');
    define('REAL_URL', '*******');
    define('BASE_URL', '*******');
    define('TIMEOUT',15*60); //15 minutes



    $authUrl   = THIS_PAGE.'?action=auth';
    $revokeUrl = THIS_PAGE.'?action=revoke';
    // Build a new client object to work with authorization.
    $client = new Google_Client();
    $client->setClientId( CLIENT_ID );
    $client->setClientSecret( CLIENT_SECRET );
    $client->setRedirectUri( REDIRECT_URL );
    $client->setDeveloperKey( '524564756727@developer.gserviceaccount.com' );
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
    //Is user disconnected
    $users=unserialize(file_get_contents('users.ini'));
    $revoke=false;
    //Disconnect users
    foreach($users as $k=>$u)
        if($u<time()-TIMEOUT)
            unset($users[$k]);
    /*
    if(empty($users[$_SESSION['email']]))
        unset($_SESSION);
    */
    $authHelper = new AuthHelper($client, $storage, THIS_PAGE);
    if( (isset($_GET['action']) && $_GET['action'] == 'revoke') ){
        unset($users[$_SESSION['email']]);
        file_put_contents('users.ini', serialize($users));
        $authHelper->revokeToken();
        unset($_SESSION);
    } else{
        if( (isset($_GET['action']) && $_GET['action'] == 'auth') || (isset($_GET['code']) && $_GET['code']) ){
            $authHelper->authenticate();
            $_GET['refresh'] = true;
            unset($_SESSION['list_sites']);
        } else{
            $authHelper->setTokenFromStorage();
            if( $authHelper->isAuthorized() ){
                $userinfo = $oauth2Service->userinfo->get();
                if(!isset($_SESSION['email']))
                {
                    //new user conected
                    if($users[$userinfo->email]>time()-TIMEOUT)
                        $users[$userinfo->email]=time()+TIMEOUT;
                    $_SESSION['email'] = $userinfo->email;
                }
                $users[$_SESSION['email']]=time();
                file_put_contents('users.ini', serialize($users));
                $analytics = new Google_AnalyticsService($client);
                // Core Reporting API Reference Demo.
                require_once __DIR__.'/lib/coreReportingApiReference.php';
                $demo = new coreReportingApiReference($analytics, THIS_PAGE);
            }
            $storage->set( $client->getAccessToken() );
        }
    }
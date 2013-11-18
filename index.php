<?php
require_once 'load.php';
ob_start();
set_time_limit(300);

// Print out authorization URL.
if ($authHelper->isAuthorized()) {
  $revoke= "<p id=\"revoke\"><a href='$revokeUrl'>Revoke access</a></p>";
} else {
  echo "<p id=\"revoke\"><a href='$authUrl'>Grant access to Google Analytics data</a></p>";
  exit;
}

//Deal with POST datas
require_once './src/datas.php';
if(!isset($_POST['export']))
{
    header('Content-Type: text/html; charset=utf-8');
       ?> <!DOCTYPE>
      <html>
      <head>
        <title>WFC</title>
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/scripts.js"></script>
        <script type="text/javascript" src="js/form.js"></script>
        <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="style.css" />
      </head>
      <body>
      <?php
}
if(!empty($notif))
  echo '<div id="notifs"><p>'.$notif.'</p></div>';

if(isset($_GET['create_new']))
    require_once './includes/new.php';
else if(isset($_GET['export']) && isset($_GET['name']))
    require_once './includes/export.php';
else
    require_once './includes/index.php';




?>

    </body>
    </html>


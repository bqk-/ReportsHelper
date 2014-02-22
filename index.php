<?php

    require_once 'load.php';

    // Print out authorization URL.
    if( $authHelper->isAuthorized() ){
        $revoke = "<a data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Refresh\" href=\"index.php?refresh\">
              <span class=\"glyphicon glyphicon-retweet\"></span>
            </a>
            <a class=\"wfc-documentation\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Documentation\" href=\"https://github.com/bqk-/ReportsHelper/wiki\" target=\"_blank\">
              <span class=\"glyphicon glyphicon-list-alt\"></span>
            </a>
            <a class=\"wfc-logout\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Logout\" href='$revokeUrl'>
              <span class=\"glyphicon glyphicon-log-out\"></span>
            </a>
            ";
    } else{
        echo "<p id=\"revoke\"><a href='$authUrl'>Grant access to Google Analytics data</a></p>";
        exit;
    }

    //Deal with POST datas
    require_once './includes/datas.php';

    if (!isset($_GET['export']))
    {
    header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE>
<html>
<head>
    <title>WFC</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="css/toastr.min.css"/>

</head>
<body>
<?php
    }

    if( isset($_GET['create_new']) ){
        require_once './includes/new.php';
    } else{
        if( isset($_GET['export']) && isset($_POST['code']) ){
            require_once './includes/export.php';
        } else{
            require_once './includes/index.php';
        }
    }
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/form.js"></script>
<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="js/toastr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootbox.min.js"></script>
<?php
    if(isset($notif))
        echo '<script type="text/javascript">toastr.success(\''.$notif.'\',\'Information\');</script>';

    if($users[$_SESSION['email']]>time())
        echo '<script type="text/javascript">$("#notalone").modal("show");</script>';

    require_once './includes/app_tour_guide.php';
?>
</body>
</html>


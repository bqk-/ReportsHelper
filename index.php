<?php
    ob_start();
    require_once 'load.php';
    set_time_limit( 300 );

    // Print out authorization URL.
    if( $authHelper->isAuthorized() ){
        $revoke = "<p id=\"revoke\">
            <a href='$revokeUrl'>
              <img src=\"./images/revoke.png\" alt=\"Revoke access\" />
            </a> |
            <a href=\"./images/doc.png\" target=\"_blank\">
              <img src=\"./images/documentation.png\" alt=\"Documentation\" />
            </a> |
            <a href=\"index.php?refresh\">
              <img src=\"./images/refresh.png\" alt=\"Documentation\" />
            </a>

  </p>";
    } else{
        echo "<p id=\"revoke\"><a href='$authUrl'>Grant access to Google Analytics data</a></p>";
        exit;
    }

    //Deal with POST datas
    require_once './src/datas.php';
    if (!isset($_GET['export']))
    {
    header( 'Content-Type: text/html; charset=utf-8' );
?> <!DOCTYPE>
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
</body>
</html>


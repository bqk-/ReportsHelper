<?php
define('MONTH',sprintf("%02s", intval($_POST['month'])));
define('YEAR',intval($_POST['year']));
define('TABLE_ID',intval($_POST['code']));

require_once 'load.php';
//error_reporting(0);


    exit('<!DOCTYPE>
    <html>
      <head>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
      </head>
    <body>
        '.parse_content(file_get_contents(__DIR__.'/'.TABLE_ID.'/template.tpl')).'
    </body>
    </html>');

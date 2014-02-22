<?php

$awr=__DIR__.'/../awr_reports/'.$_POST['file'];
$prop=$_POST['path'];

rename($awr,$prop.'/awr_report.html');
echo 'moved';
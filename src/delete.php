<?php

if(isset($_GET['tpl']))
    @unlink(__DIR__.'/../templates/'.$_GET['tpl']);
else if(isset($_GET['site']))
    @unlink(__DIR__.'/../'.$_GET['site'].'/template.tpl');
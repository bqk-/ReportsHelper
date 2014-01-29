<?php
/*
* All the ajax actions are here
*
*/
include_once __DIR__.'/../load.php';


if(isset($_POST['fnc']))
    $fnc='ajax_'.preg_replace('#[^a-zA-Z0-9\\-]#i', '', $_POST['fnc']);
else if(isset($_FILES))
    $fnc='ajax_uploadImg';
else
    exit('What do we say to the hacker ? Not today !');

if(function_exists($fnc))
    call_user_func($fnc);
else
    exit('What do we say to the hacker ? Not today !');

function ajax_tplOnly()
{
    $name=preg_replace('#[^.a-zA-Z0-9_-]#i', '', $_POST['name']);
    if(file_exists(TPL_DIR.DS.$_SESSION['email'].DS.$name))
        include_once TPL_DIR.DS.$_SESSION['email'].DS.$name;
    else
        exit('What do we say to the hacker ? Not today !');
}

function ajax_tplValues()
{
    define('MONTH',sprintf("%02s", intval($_POST['month'])));
    define('YEAR',intval($_POST['year']));
    define('TABLE_ID',intval($_POST['code']));
    exit(parse_content(file_get_contents(PROP_DIR.DS.$_SESSION['properties'][TABLE_ID].DS.TABLE_ID.DS.'template.tpl')));
}

function ajax_customTpl()
{
    if(file_exists(PROP_DIR.DS.$_SESSION['properties'][$_POST['code']].DS.$_POST['code'].DS.'template.tpl'))
        include_once PROP_DIR.DS.$_SESSION['properties'][$_POST['code']].DS.$_POST['code'].DS.'template.tpl';
    else
        exit('What do we say to the hacker ? Not today !');
}

function ajax_new()
{
    include_once ROOT.DS.'includes'.DS.'new.php';
}

function ajax_delete()
{
    if(isset($_POST['name']))
    {
        $name=preg_replace('#[^.a-z0-9_-]#i', '', $_POST['name']);
        @unlink(TPL_DIR.DS.$_SESSION['email'].DS.$name);
        echo '<script type="text/javascript">toastr.success(\'Template deleted\', \'Information\');</script>';
    }
    else if(isset($_POST['code']))
    {
        $code=intval($_POST['code']);
        deleteDirectory(PROP_DIR.DS.$_SESSION['properties'][$code].DS.$code.DS);
        echo '<script type="text/javascript">toastr.success(\'Customized Template deleted\', \'Information\');</script>';
    }
    else
        exit('What do we say to the hacker ? Not today !');
}

function ajax_email()
{
    include_once ROOT.DS.'includes'.DS.'email.php';
}

function ajax_uploadImg()
{
    $path=PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS;
    if(!file_exists(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])]))
        mkdir(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])]);
    if(!file_exists($path))
        mkdir($path);
    if(isset($_FILES['logo_upload']) && $_FILES['logo_upload']['size']>0)
    {
        move_uploaded_file($_FILES['logo_upload']['tmp_name'], $path.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo_upload']['name'],strrpos($_FILES['logo_upload']['name'],'.')+1,strlen($_FILES['logo_upload']['name'])));
        $img=imagecreatefromstring(file_get_contents($path.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo_upload']['name'],strrpos($_FILES['logo_upload']['name'],'.')+1,strlen($_FILES['logo_upload']['name']))));
        imagepng($img,$path.'logo-'.intval($_POST['codenew']).'.png');
        if(substr($path.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo_upload']['name'],strrpos($_FILES['logo_upload']['name'],'.')+1,strlen($_FILES['logo_upload']['name'])),-3)!='png')
            @unlink($path.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo_upload']['name'],strrpos($_FILES['logo_upload']['name'],'.')+1,strlen($_FILES['logo_upload']['name'])));
        $infos['logo']=str_replace(ABS_URL,REAL_URL,$path.'logo-'.intval($_POST['codenew'])).'.png';
        echo $infos['logo'];
    }
    else
        exit('What do we say to the hacker ? Not today !');
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return @unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return @rmdir($dir);
}
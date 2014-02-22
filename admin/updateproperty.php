<?php
if(isset($_POST['path']) && isset($_POST['option']) && isset($_POST['value'])){
    $a=unserialize(file_get_contents($_POST['path'].'/profile.ini'));
    $a[$_POST['option']]=($_POST['value']=='true'?true:false);
    file_put_contents($_POST['path'].'/profile.ini', serialize($a));
    exit('success');
}
else
    exit;
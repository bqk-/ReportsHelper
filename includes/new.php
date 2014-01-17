<?php
include_once __DIR__.'/../load.php';

//$remove : remove or not first page from editor view
function parseTpl($f,$remove=false)
{
    $is_intextarea=false;
    $textarea='';
    $i=0;
    while (($buffer = fgets($f, 4096)) !== false) {
        if(preg_match('#<page #', $buffer) && !$is_intextarea)
        {
            if(!$remove)
            {
                $textarea.='<textarea name="content['.$i.']" id="content'.$i.'" style="width:100%;height:400px;">';
                $is_intextarea=true;
                $i++;
            }
 
        }
        else if(preg_match('#</page>#', $buffer))
        {
            if(!$remove)
            {
                $textarea.='</textarea>';
                $is_intextarea=false;
            }
            else
                $remove=false;
            
        }
        else if(!preg_match('#<page_header#', $buffer) && !preg_match('#</page_header#', $buffer) && $is_intextarea)
        {
            $textarea.=str_replace('<br />','',$buffer);
        }
    }
    return $textarea.'<input type="hidden" name="nb_textarea" id="nb_textarea" value="'.$i.'" />';
}
if(isset($_GET['name']) || isset($_GET['code'])){ //EDIT
    if(isset($_GET['code'])) //Customized template
    {
        $f=fopen(__DIR__.'/../'.intval($_GET['code']).'/template.tpl','r');
        $infos=unserialize(file_get_contents(__DIR__.'/../'.intval($_GET['code']).'/profile.ini'));
        $textarea=parseTpl($f,true);
    }
    else //Template
    {
        $f= fopen(__DIR__.'/../templates/'.$_GET['name'],'r');
        $textarea=parseTpl($f);
    }

    
    if(isset($_GET['code']))
        $hidden='<input type="hidden" name="code" value="'.$_GET['code'].'" />';
    else
        $hidden='<input type="hidden" name="tpl_exists" value="'.$_GET['name'].'" />';
}
else if(isset($_POST) && !empty($_POST))
{
    if(!is_dir(__DIR__.'/../'.intval($_POST['code'])))
            mkdir(__DIR__.'/../'.intval($_POST['code']));
    if(isset($_FILES['logo']) && $_FILES['logo']['size']>0)
    {
        move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $img=imagecreatefromstring(file_get_contents(__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name']))));
        imagepng($img,__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.png');
        if(substr(__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])),-3)!='png')
            @unlink(__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $infos['logo']=str_replace(ABS_URL,REAL_URL,__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code'])).'.png';
    }
    $infos['name']=$_POST['name'];
    $infos['url']=$_POST['url'];
    $infos['code']=$_POST['code'];
    $infos['emails']=str_replace(' ','',(isset($_POST['emails'])?$_POST['emails']:''));
    $f=fopen(__DIR__.'/../'.intval($_POST['code']).'/profile.ini','w+');
    fwrite($f,serialize($infos));
    fclose($f);
    $f=fopen(__DIR__.'/../templates/'.$_POST['template'],'r');
    $textarea=parseTpl($f);
    $hidden='<input type="hidden" name="code" id="code" value="'.intval($_POST['code']).'" />';
}
else {
    $textarea='<textarea name="content[0]" id="content0" style="width:100%;height:400px;"></textarea>';
    $hidden='<input type="hidden" name="nb_textarea" id="nb_textarea" value="1" />';
}

?>

<a href="#" onclick="emptyView(); return false;" style="float:left;position:absolute;top:0;left:-60px;">&lt; Back</a> 
<div id="editor">
  <form method="POST" enctype="multipart/form-data" action="index.php<?php if(!isset($infos)) echo '?view=templates'; ?>" style="width:100%;float:left;" id="form_tpl">
    <?php
    if(isset($infos) && is_array($infos))
    {
        echo '<div id="profile">
            '.(!empty($infos['logo'])?
                    '<span class="box_logo"><img style="float:left;"src="'.$infos['logo'].'?t='.time().'" alt="Logo" />
                        </span><input type="hidden" value="1" name="keep_logo" />':
                    '').'
            (If there is no logo, the name will be used)
            Upload a new one : <input type="file" name="logo" style="float:right;" /><br />
            Name : <input type="text" name="sitename" value="'.stripslashes($infos['name']).'" /> <br />
            URL : <input type="text" name="url" value="'.stripslashes($infos['url']).'" /> <br />
            Code : '.$infos['code'].'<br />
            Emails :<br />
            <input type="text" style="width:70%;" value="'.$infos['emails'].'" name="emails" />
            <div style="clear: both;"></div> 
            <input style="text-align:center;margin:0 auto;display:block;" type="submit" id="save_button" value="Save" />
        </div>';
    }
    else
        echo 'Name : <input type="text" name="template_name" value="'.(isset($_GET['name']) ? substr($_GET['name'],0,-4):'').'" /><br />';
    ?>
    <?php echo $textarea; ?>
    <input style="text-align:center;margin:0 auto;display:block;" type="submit" id="save_button" value="Save" />
    <?php echo $hidden; ?>
    <span id="addTextarea"><a class="addTextarea" href="#">[+] Page</a> | <a href="#" onclick="document.getElementById('form_tpl').submit();">Save</a></span> 
</form>
<script type="text/javascript">
    refreshTiny();

    $('.box_logo').hover(function(){
        $(this).append('<span class="del_img">REMOVE</span>');
    },
    function(){
        $(this).find('span').remove();
    });

    $('.box_logo').click(function(){
        $(this).parent().find('input[name="keep_logo"]').val(0);
        $(this).remove();
    });


</script>
</div>
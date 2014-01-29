<?php

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

if(isset($_POST) && !empty($_POST['codenew'])) //NEW CUSTOMIZED TPL
{
    if(!is_dir(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew'])))
            mkdir(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']));
    if(isset($_FILES['logo']) && $_FILES['logo']['size']>0)
    {
        move_uploaded_file($_FILES['logo']['tmp_name'], PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $img=imagecreatefromstring(file_get_contents(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name']))));
        imagepng($img,PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS.'logo-'.intval($_POST['codenew']).'.png');
        if(substr(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])),-3)!='png')
            @unlink(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS.'logo-'.intval($_POST['codenew']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $infos['logo']=str_replace(ABS_URL,REAL_URL,PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).'logo-'.intval($_POST['codenew'])).'.png';
    }
    elseif(isset($_POST['logo']))
        $infos['logo']=$_POST['logo'];
    $infos['name']=$_POST['name'];
    $infos['url']=$_POST['url'];
    $infos['code']=$_POST['codenew'];
    $infos['emails']=str_replace(' ','',(isset($_POST['emails'])?$_POST['emails']:''));
    $f=fopen(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['codenew'])].DS.intval($_POST['codenew']).DS.'profile.ini','w+');
    fwrite($f,serialize($infos));
    fclose($f);
    $f=fopen(TPL_DIR.DS.$_SESSION['email'].DS.$_POST['template'],'r');
    $textarea=parseTpl($f);
    $hidden='<input type="hidden" name="code" id="code" value="'.intval($_POST['codenew']).'" />';
}
else if(isset($_POST['name']) || isset($_POST['code'])){ //EDIT
    if(isset($_POST['code'])) //Customized template
    {
        $f=fopen(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['code'])].DS.intval($_POST['code']).DS.'template.tpl','r');
        $infos=unserialize(file_get_contents(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['code'])].DS.intval($_POST['code']).DS.'profile.ini'));
        $textarea=parseTpl($f,true);
    }
    else //Template
    {
        $f= fopen(TPL_DIR.DS.$_SESSION['email'].DS.$_POST['name'],'r');
        $textarea=parseTpl($f);
    }

    
    if(isset($_POST['code']))
        $hidden='<input type="hidden" name="code" value="'.$_POST['code'].'" />';
    else
        $hidden='<input type="hidden" name="tpl_exists" value="'.$_POST['name'].'" />';
}
else { //NEW TPL
    $textarea='<textarea name="content[0]" id="content0" style="width:100%;height:400px;"></textarea>';
    $hidden='<input type="hidden" name="nb_textarea" id="nb_textarea" value="1" />';
}

?>
<div id="editor">
  <form method="POST" enctype="multipart/form-data" action="index.php<?php if(!isset($infos)) echo '?view=templates'; ?>" style="width:100%;float:left;" id="form_tpl">
    <?php
    if(isset($infos) && is_array($infos))
    {
        echo '<div id="profile">
            <span class="box_logo">
            '.(!empty($infos['logo'])?
                    '<img src="'.$infos['logo'].'?t='.time().'" alt="Logo" />':
                    'Name is used, upload a logo instead <input type="file" name="logo" />').'
            </span>
            <input type="hidden" value="1" name="keep_logo" />
            <div class="input-group">
                <span class="input-group-addon">Name</span>
                <input type="text" class="form-control" name="sitename" value="'.stripslashes($infos['name']).'" />
            </div>
            <div class="input-group">
                <span class="input-group-addon">URL</span>
                <input type="text" class="form-control" name="url" value="'.stripslashes($infos['url']).'" />
            </div>
            <div class="input-group">
                <span class="input-group-addon">Code</span>
                <input type="text" readonly="readonly" class="form-control" name="sitemap" value="'.$infos['code'].'" />
            </div>';
            echo '<div class="emails-list">
            <button type="button" class="btn plusemail btn-success">+</button>';
        $emails=explode(',',$infos['emails']);
        foreach($emails as $e){
            echo ' <div class="input-group">
                <span class="input-group-addon">Email</span>
                <input type="text" class="form-control" name="email[]" value="'.$e.'" />
                <span class="input-group-addon deleteemail btn-danger">-</span>
            </div>';
        }
        echo '</div>
            <div style="clear: both;"></div> 
        </div>';
    }
    else
        echo 'Name : <input type="text" name="template_name" value="'.(isset($_POST['name']) ? substr($_POST['name'],0,-4):'').'" /><br />';
    ?>
    <?php echo $textarea; ?>
    <input style="text-align:center;margin:0 auto;display:block;" type="submit" id="save_button" class="btn btn-default" value="Save" />
    <?php echo $hidden; ?>
</form>
<script type="text/javascript">

    refreshTiny();
    $('.box_logo img').hover(function(){
        $(this).parent().append('<span class="del_img">REMOVE</span>');
    });
    $('.box_logo').mouseleave(function(){
        $(this).find('span').remove();
    });

    $('.box_logo img').click(function(){
        $(this).parent().parent().find('input[name="keep_logo"]').val(0);
        $(this).parent().append('Name is used, upload a logo instead <input type="file" name="logo" />');
        $(this).remove();
    });

    $('.toolbar').append('<span class="right"><a class="pluspage">[+] Page</a><span class="separator"></span><a onclick="document.getElementById(\'form_tpl\').submit();">Save</a></span>');
</script>
</div>
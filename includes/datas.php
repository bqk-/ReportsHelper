<?php
if(isset($_POST['content']))
{
  if(isset($_POST['code'])&& intval($_POST['code'])>0) //SITE
  {
    $path=PROP_DIR.DS.$_SESSION['properties'][intval($_POST['code'])].DS.intval($_POST['code']).DS;
    if(!file_exists(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['code'])]))
        mkdir(PROP_DIR.DS.$_SESSION['properties'][intval($_POST['code'])]);
    if(!file_exists($path))
        mkdir($path);
    $infos=unserialize(file_get_contents($path.'profile.ini'));
    $infos['emails']=str_replace(' ','',implode(',',$_POST['email']));
    $infos['name']=addslashes($_POST['sitename']);
    $infos['url']=addslashes($_POST['url']);
    if(isset($_POST['keep_logo'])&&intval($_POST['keep_logo'])===0&&!empty($infos['logo']))
    {
      @unlink($infos['logo']);
      unset($infos['logo']);
    }
    if(isset($_FILES['logo']) && $_FILES['logo']['size']>0)
    {
        move_uploaded_file($_FILES['logo']['tmp_name'], $path.'logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $img=imagecreatefromstring(file_get_contents($path.'logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name']))));
        imagepng($img,$path.'logo-'.intval($_POST['code']).'.png');
        if(substr($path.'logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])),-3)!='png')
            @unlink($path.'logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $infos['logo']=str_replace(ABS_URL,REAL_URL,$path.'logo-'.intval($_POST['code'])).'.png';
    }

    $f=fopen($path.'profile.ini','w+');
    fwrite($f,serialize($infos));
    fclose($f);
    //Delete old template
    @unlink($path.'template.tpl');
    $content='';
    //FRONT PAGE
    $content.=PHP_EOL.'<page class="page" style="width:100%" backimg="images/backgroundwfc.png" backcolor="white" backimgy="top" backimgx="center" backtop="26mm" backbottom="18mm">'.PHP_EOL;
          //$content.='<page_header backtop="30mm" class="page_header">'.PHP_EOL;
          //$content.='</page_header>'.PHP_EOL;
    $content.='<p style="text-align: center;">';
    if(!empty($infos['logo']))
      $content.='<img src="'.$infos['logo'].'" alt="Logo" />';
    else
      $content.='<strong><span style="color: #f57900;"><span style="font-size: 24px;">'.$infos['name'].'</span></span></strong>';
    $content.='</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: center;"><strong><span style="color: #f57900;"><span style="font-size: 24px;">[month] [year]</span></span></strong></p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: left;">&nbsp;</p>
        <p style="text-align: center;"><strong><span style="color: #f57900;"><span style="font-size: 24px;">'.$_POST['url'].'</span> </span> </strong></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>';
    $content.=PHP_EOL.'</page>'.PHP_EOL;
    foreach($_POST['content'] as $c)
    {
      $content.=PHP_EOL.'<page class="page" style="width:100%" backimg="images/backgroundwfc.png" backcolor="white" backimgy="top" backimgx="center" backtop="26mm" backbottom="18mm">'.PHP_EOL;
          //$content.='<page_header backtop="30mm" class="page_header">'.PHP_EOL;
          //$content.='</page_header>'.PHP_EOL;
      $content.=$c;
      $content.=PHP_EOL.'</page>'.PHP_EOL;
    }
    $f=fopen($path.'template.tpl','w+');
    if($f)
    {
      fwrite($f, $content);
      fclose($f);
      $notif= 'Saved !';
    }
    else
      $notif= 'Saving failed...';
  }
  else //TEMPLATE
  {
    $path=TPL_DIR.DS.$_SESSION['email'].DS;
    $content='';
    if(!file_exists($path))
        mkdir($path);
    if(isset($_POST['tpl_exists']) && !empty($_POST['tpl_exists']))
        @unlink($path.$_POST['tpl_exists']);
    $name=(isset($_POST['template_name'])? preg_replace('#[/?*:;{}\\\ ]+#', '', $_POST['template_name']):substr(md5(rand()), 0, 7));
    foreach($_POST['content'] as $c)
    {
        $content.=PHP_EOL.'<page class="page" style="width:100%" backimg="images/backgroundwfc.png" backcolor="white" backimgy="top" backimgx="center" backtop="26mm" backbottom="18mm">'.PHP_EOL;
          //$content.='<page_header backtop="30mm" class="page_header">'.PHP_EOL;
          //$content.='</page_header>'.PHP_EOL;
        $content.=$c;
        $content.=PHP_EOL.'</page>'.PHP_EOL;
    }
    $f=fopen($path.$name.'.tpl','w+');
    if($f)
    {
        fwrite($f, $content);
        fclose($f);
        $notif= 'Template saved !';
    }
    else
        $notif= 'Template saving failed...';
  }

}
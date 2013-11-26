<?php
if(isset($_POST['content']))
{
  if(isset($_POST['code'])&& intval($_POST['code'])>0) //SITE
  {
    $infos=unserialize(file_get_contents(__DIR__.'/../'.intval($_POST['code']).'/profile.ini'));
    $infos['emails']=str_replace(' ','',$_POST['emails']);
    $infos['name']=addslashes($_POST['sitename']);
    $infos['url']=addslashes($_POST['url']);
    if(isset($_POST['keep_logo'])&&intval($_POST['keep_logo'])===0&&!empty($infos['logo']))
    {
      @unlink($infos['logo']);
      unset($infos['logo']);
    }
    if(isset($_FILES['logo']) && $_FILES['logo']['size']>0)
    {
        move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $img=imagecreatefromstring(file_get_contents(__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name']))));
        imagepng($img,__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.png');
        if(substr(__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])),-3)!='png')
            @unlink(__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code']).'.'.substr($_FILES['logo']['name'],strrpos($_FILES['logo']['name'],'.')+1,strlen($_FILES['logo']['name'])));
        $infos['logo']=str_replace(ABS_URL,REAL_URL,__DIR__.'/../'.intval($_POST['code']).'/logo-'.intval($_POST['code'])).'.png';
    }

    $f=fopen(__DIR__.'/../'.intval($_POST['code']).'/profile.ini','w+');
    fwrite($f,serialize($infos));
    fclose($f);
    //Delete old template
    @unlink(__DIR__.'/../'.intval($_POST['code']).'/template.tpl');
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
    $f=fopen(__DIR__.'/../'.intval($_POST['code']).'/template.tpl','w+');
    if($f)
    {
      fwrite($f, $content);
      fclose($f);
      $notif= 'Saved !<br />';
    }
    else
      $notif= 'Saving failed..<br />';
  }
  else //TEMPLATE
  {
    $content='';
    if(isset($_POST['tpl_exists']) && !empty($_POST['tpl_exists']))
      @unlink(__DIR__.'/../templates/'.$_POST['tpl_exists']);
    $name=(isset($_POST['template_name'])? $_POST['template_name']:substr(md5(rand()), 0, 7));
    define('FILE',$name);
    foreach($_POST['content'] as $c)
    {
      $content.=PHP_EOL.'<page class="page" style="width:100%" backimg="images/backgroundwfc.png" backcolor="white" backimgy="top" backimgx="center" backtop="26mm" backbottom="18mm">'.PHP_EOL;
          //$content.='<page_header backtop="30mm" class="page_header">'.PHP_EOL;
          //$content.='</page_header>'.PHP_EOL;
      $content.=$c;
      $content.=PHP_EOL.'</page>'.PHP_EOL;
    }
    $f=fopen(__DIR__.'/../templates/'.$name.'.tpl','w+');
    if($f)
    {
      fwrite($f, $content);
      fclose($f);
      $notif= 'Template saved !<br />';
    }
    else
      $notif= 'Template saving failed..<br />';
  }

}

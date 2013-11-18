<?php
if(isset($_POST['content']))
{
  if(isset($_POST['code'])&& intval($_POST['code'])>0) //SITE
  {
    $infos=unserialize(file_get_contents(__DIR__.'/../'.intval($_GET['code']).'/profile.ini'));
    $infos['emails']=str_replace(' ','',$_POST['emails']);
    $f=fopen(__DIR__.'/../'.intval($_POST['code']).'/profile.ini','w+');
    fwrite($f,serialize($infos));
    fclose($f);
    $content='';
    @unlink(__DIR__.'/../'.intval($_POST['code']).'/template.tpl');
    foreach($_POST['content'] as $c)
    {
      $content.=PHP_EOL.'<page class="page" style="width:100%" backimg="backgroundwfc.png" backcolor="white" backimgy="top" backimgx="center" backtop="26mm" backbottom="18mm">'.PHP_EOL;
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
      $content.=PHP_EOL.'<page class="page" style="width:100%" backimg="backgroundwfc.png" backcolor="white" backimgy="top" backimgx="center" backtop="26mm" backbottom="18mm">'.PHP_EOL;
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

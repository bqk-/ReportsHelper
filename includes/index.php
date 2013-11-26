<?php
function getSitesList(&$analytics) {

    if(isset($_GET['refresh']) || empty($_SESSION['list_sites']))
    {
        $result=array();
        $accounts = $analytics->management_accounts->listManagementAccounts();
        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            foreach($items as $a)
            {
                $webproperties = $analytics->management_webproperties->listManagementWebproperties($a->getId());
                if (count($webproperties->getItems()) > 0) {
                    $items_p = $webproperties->getItems();
                    foreach($items_p as $b)
                    {
                        $profiles = $analytics->management_profiles->listManagementProfiles($a->getId(), $b->getId());
                        if (count($profiles->getItems()) > 0) {
                            $items_s = $profiles->getItems();
                            foreach ($items_s as $c) {
                                $result[]=$c;
                            }
                        }
                    }
                }
            }
        }
        $_SESSION['list_sites']=serialize($result);
    }
    else
        $result=unserialize($_SESSION['list_sites']);       
    return $result;
}
echo '<div id="overlay"></div>';
echo    '<div id="left-box">
            <div class="nav">
                <h1><a href="index.php">Sites</a></h1>
                <h1><a href="?view=templates">Templates</a></h1>
            </div>';
echo $revoke;

if(isset($_GET['view'])&&$_GET['view']=='templates')
{
    echo '<div id="templates">';
    echo '<a href="?create_new" class="create_new" title="Create a new template">Create a new template</a>';
    echo '<ul id="templates_list">';
    $temp=scandir('./templates/');
    if(is_array($temp))
        foreach($temp as $e) if($e != '.' && $e != '..')if(substr($e,-3)=='tpl')
        {
             echo '<li>'.$e.'<br />
             <a href="#" data-name="'.$e.'" class="template_link" ><img src="./images/view.png" /></a> | <a href="#" data-name="'.$e.'" class="template_edit"><img src="./images/edit.png" /></a> | <a href="#" data-name="'.$e.'" class="delete_tpl"><img src="./images/delete.png" /></a></li>';
        }
    echo '</ul>';
    echo '</div>';
    if(isset($_POST['tpl_exists']))
        echo '<script type="text/javascript">
            $.ajax({
                url: "'.__DIR__.'/../templates/'.$_POST['tpl_exists'].'",
                type: "GET",
                dataType: "html",
                success: function(data) {            
                    $(\'#right-wrapper\').html(data);
                }
            });
        </script>';
    else if(isset($_POST['template_name']))
        echo '<script type="text/javascript">
            $.ajax({
                url: "'.__DIR__.'/../templates/'.$_POST['template_name'].'.tpl",
                type: "GET",
                dataType: "html",
                success: function(data) {            
                    $(\'#right-wrapper\').html(data);
                }
            });
        </script>';
}
else
{
    $months=array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
    $sites=getSitesList($analytics);
    $popups='<div id="popups">';
    echo '<input type="text" value="filter" id="filter" /><br />';
    echo '<ul class="list-sites">';
    $t=scandir('./templates');
    foreach($sites as $s)
    {
        if(file_exists(__DIR__.'/../'.$s->getId().'/template.tpl'))
        {
            $popups.='<div id="'.$s->getId().'" class="popup"><h3>Datas</h3>
            <form method="POST" action="./index.php?export" id="form_'.$s->getId().'" class="view_data">
                For : <select name="month">';
                foreach($months as $k=>$m)
                    {
                        $popups.= '<option '.($k==date('n')-1?'selected="selected"':'').' value="'.$k.'">'.$m.'</option>';
                    }
                    $popups.='</select>
                    <select name="year">';
                    for($i=2007;$i<=date('Y');$i++)
                    {
                        $popups.='<option '.($i==date('Y')?'selected="selected"':'').' value="'.$i.'">'.$i.'</option>';
                    }
                    $popups.='</select>
                    <br />
                    <input type="hidden" name="code" value="'.$s->getId().'" />
                    <input type="submit" value="View" /><input type="button" value="Template only" class="tplOnly" />
            </form>
            </div>';
            echo '<li>'.$s->getWebsiteUrl().'<br />
             <a href="#" class="viewtemplate" data-code="'.$s->getId().'"><img src="./images/view.png" /></a> | 
             <a href="#" onclick="edit('.$s->getId().'); return false;" ><img src="./images/edit.png" /></a> | 
             <a href="#" class="export_pdf" data-code="'.$s->getId().'"><img src="./images/export.png" /></a> | 
             <a href="#"><img src="./images/email.png" /></a> | 
             <a href="#" data-code="'.$s->getId().'" class="delete_site"><img src="./images/delete.png" /></a></li>';
        }
        else
        {
            $popups.='<div id="'.$s->getId().'" class="popup">
            <form method="POST" class="new" action="?create_new" enctype="multipart/form-data">
                Name : <input type="text" name="name" value="'.$s->getName().'" /><br />
                URL  : <input type="text" name="url" value="'.$s->getWebsiteUrl().'" /><br />
                Code : <input type="text" readOnly="true" name="code" value="'.$s->getId().'" /><br />
                Logo : <input type="file" name="logo" /> (if no logo, name will be use, you can still upload a logo later on)<br />
                Template <select name="template">';
            foreach($t as $v) if(substr($v,-3)=='tpl' && $v!='.' && $v!='..') {
                $popups.= '<option value="'.$v.'">'.substr($v,0,-4).'</option>';
            }
            $popups.='            </select><br />
            Emails : <input type="text" name="emails" value="marketing@webfullcircle.com, dean.vong@webfullcircle.com" style="width:100%" /><br />
                <input type="submit" value="Create" class="new_button" /></form></div>';
            echo '<li>'.$s->getWebsiteUrl().'<br />
                        <a href="" onclick="popupsite('.$s->getId().'); return false;" ><img src="./images/new.png" /></a></li>';
        }
    }
    echo '</ul>';
    echo '</div>';
}
echo '</div>';
echo '<div id="right-box"><div id="right-wrapper">';
if(isset($_POST['code'])&& intval($_POST['code'])>0)
{
    $html=file_get_contents(__DIR__.'/../'.intval($_POST['code']).'/template.tpl');
    echo $html;
}


echo '</div></div>';
echo $popups;
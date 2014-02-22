<?php

$propertiesPath=__DIR__.'/../properties/';
$awrdir=__DIR__.'/../awr_reports/';
class WFCPDF{
    private
        $auto,
        $name,
        $url,
        $path,
        $emails,
        $code,
        $ready,
        $file;

    public function __construct(){
        $this->ready=0;
    }

    public function __get($n){
        return $this->$n;
    }

    public function __set($n, $v){
        $this->$n=$v;
    }

    public function __isset($n){
        return isset($this->$n);
    }
}

class WFC_Send{
    public $pdfs=array(),
            $count=0;

    public function __construct($path){
        $this->searchTpl($path);
    }

    private function searchTpl($p){
        $dir=scandir($p);
        if($dir){
            $tpl_ok=false;
            $profile_ok=false;
            foreach ($dir as $e) if($e!='.' && $e!='..') {
                if(is_dir($p.'/'.$e))
                    $this->searchTpl($p.'/'.$e);
                else if($e=='template.tpl')
                    $tpl_ok=true;  
                else if($e=='profile.ini')
                    $profile_ok=true;
            }
            if($profile_ok||$tpl_ok){
                $this->pdfs[$this->count]=new WFCPDF();
                if($profile_ok)
                {
                    $this->setProfileInfos($p,$this->count);
                    $this->pdfs[$this->count]->ready=1;
                    if($tpl_ok && !empty($this->pdfs[$this->count]->emails))
                        $this->pdfs[$this->count]->ready=2;
                    else if($tpl_ok)
                        $this->pdfs[$this->count]->ready=3;
                }
                else
                    $this->pdfs[$this->count]->name='No Profile';
                $this->possibleAWR($this->count);
                $this->count++;
            }
        }
    }

    private function setProfileInfos($p,$n){
        $o=$this->pdfs[$n];
        $infos=@unserialize(file_get_contents($p.'/profile.ini'));
        if(is_array($infos))
        {
            $o->name=$infos['name'];
            $o->url=$infos['url'];
            $o->auto=$infos['auto'];
            $o->emails=$infos['emails'];
            $o->code=$infos['code'];
            $o->path=$p;
        }
    }

    private function possibleAWR($n){
        global $awrdir;
        $p=$this->pdfs[$n];
        $s2=$this->cleanString($p->url);
        $dir=scandir($awrdir);
        if($dir)
            foreach($dir as $e) if($e!='.' && $e!='..') {
                $s1=$this->cleanString($e);
                if(stripos($s1,$s2)>0) //we found a file !
                {
                    $p->file=$e;
                    break;
                }
            }
    }

    private function cleanString($s) {
        $s=str_replace('http://','',$s);
        $s=str_replace('www','',$s);
        $s=preg_replace('#\\.com$#','',$s); //we don't want www.comfortaire.com to be fortaire at the end so we use a regex to match the end of the string only
        $s=preg_replace('#\\.org$#','',$s); //same
        $s=str_replace('-','',$s);
        $s=str_replace('_','',$s);
        $s=str_replace('.','',$s);
        $s=str_replace(' ','',$s);
        $s=str_replace('+','',$s); //why not
        $s=str_replace('=','',$s); //who knows
        return $s;
    } 

    public function displayTable(){
        echo '<table>
                    <tr>
                        <th>Send</th>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Cron send</th>
                        <th>Emails</th>
                        <th>Possible AWR file</th>
                        <th>Ready</th>
                    </tr>
            ';
        if($this->pdfs)
            foreach($this->pdfs as $pdf){ if(!(empty($pdf->name)&&empty($pdf->url)&&empty($pdf->emails)&&empty($pdf->path)))
                echo '<tr>
                            <td><input type="checkbox" name="send[]" value="'.$pdf->path.'" /></td>
                            <td>'.$pdf->name.'</td>
                            <td>'.$pdf->url.'</td>
                            <td><input type="checkbox" data-path="'.$pdf->path.'" '.($pdf->auto?'checked="checked"':'').' /></td>
                            <td>'.$pdf->emails.'</td>
                            <td><a class="validAWR" href="#" data-path="'.$pdf->path.'" data-awr="'.$pdf->file.'">'.$pdf->file.'</a></td>
                            <td>'.($pdf->ready==2?'Yes':($pdf->ready==1?'No template':($pdf->ready==3?'No email':'No profile'))).'</td>
                    </tr>';
                }
        else
            echo 'No datas';
        echo '</table>';
    }
}
$a=new WFC_Send($propertiesPath);
if(isset($_POST))
{
    include 'emails.php'; 
}
else if(isset($_GET['cron'])){
    include 'cron.php';
    exit;
}

?>
<form method="post" action="index.php?action=pdfs">
Month (ex: 04 = avril) <input type="text" name="month" /> - Year (ex: 1992) <input type="text" name="year" />
<?php
$a->displayTable();

?>
<input type="submit" value="Send reports" />
</form>

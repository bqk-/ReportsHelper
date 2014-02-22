<?php

$propertiesPath=__DIR__.'/../properties/';

class WFCPDF{
    private
        $auto,
        $name,
        $url,
        $path,
        $emails,
        $code,
        $ready;

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
    private $pdfs=array(),
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
                    else
                        $this->pdfs[$this->count]->ready=3;
                }
                else
                    $this->pdfs[$this->count]->name='No Profile';

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

    public function accessbyCode($c) {
        foreach ($this->pdfs as $pdf) {
            if($pdf->code==$c)
                return $pdf;
        }
    }

    public function displayTable(){
        echo '<table>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>URL</th>
                    </tr>
            ';
        if($this->pdfs)
            foreach($this->pdfs as $pdf){ if(!(empty($pdf->code)&&empty($pdf->name)&&empty($pdf->url)))
                echo '<tr>
                            <td><a href="?action=profiles&edit='.$pdf->code.'">'.$pdf->code.'</a></td>
                            <td><a href="?action=profiles&edit='.$pdf->code.'">'.$pdf->name.'</a></td>
                            <td><a href="?action=profiles&edit='.$pdf->code.'">'.$pdf->url.'</a></td>
                    </a></tr>';
                }
        else
            echo 'No datas';
        echo '</table>';
    }
}
$a=new WFC_Send($propertiesPath);
if(isset($_GET['edit']))
{
    $infos=unserialize(file_get_contents($a->accessbyCode($_GET['edit'])->path.'/profile.ini'));
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
{
    $a->displayTable();
}
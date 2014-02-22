<?php
require_once __DIR__.'/../phpmailer/class.phpmailer.php';
//require_once __DIR__.'/../load.php';

define('MONTH',sprintf("%02s", intval(date("m", strtotime("first day of previous month") ))));
define('YEAR',intval(date("Y", strtotime("first day of previous month") )));

if(intval(MONTH)>0 && intval(MONTH)<13 && intval(YEAR)>0)
{
  $success=0;
  $fail=0;
  foreach ($a->pdfs as $pdf) {
    $path=$pdf->path;
    $months=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
    $datas=file_get_contents($path.DS.'profile.ini');
    $infos=unserialize($datas);
    $emails=explode(',',$infos['emails']);
    $html='<link type="text/css" href="'.REAL_URL.'css/style.css" rel="stylesheet" >';
    $html.=file_get_contents($path.DS.'template.tpl');

    $pdf=new HTML2PDF('P','A4','en');
    $html=parse_content($html);
    //Fixing some links
    $html=str_replace('<img src="./', '<img src="'.REAL_URL, $html);
    $html=str_replace('backimg="', 'backimg="'.REAL_URL.'/', $html);
    $pdf->WriteHTML($html);
    //Create a temp file to use as attachment
    $pdfcontent=$pdf->Output(ROOT.DS.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf','S');
    @unlink(ROOT.DS.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf');
    $f=fopen(ROOT.DS.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf','w+');
    fwrite($f, $pdfcontent);
    fclose($f);

    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

    $mail->IsSMTP(); // telling the class to use SMTP

    try {
      $mail->Host       = "smtpcorp.com"; // SMTP server
      $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
      $mail->Username   = "wfcreports"; // SMTP account username
      $mail->Password   = "!!WFC2014";        // SMTP account password
      $mail->AddReplyTo('marketing@webfullcircle.com', 'Marketing Web Full Circle');
      foreach ($emails as $value) {
           $mail->AddAddress($value);
      }
      $mail->SetFrom('marketing@webfullcircle.com', 'Marketing Web Full Circle');
      $mail->Subject = 'Your '.$months[intval(MONTH)].' '.YEAR.' report';
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      $mail->MsgHTML('Hello,<br />Please find your monthly report attached to this email.<br /><br />
        Regards,<br />
        Web Full Circle Marketing Team<br />');
      $mail->AddAttachment(ROOT.DS.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf');      // attachment
      $mail->Send();
      $success++;
      echo '<script type="text/javascript">toastr.success(\'To : '.$infos['emails'].'\', \'Information\');</script>';
      echo '<script type="text/javascript">toastr.success(\'PDF sent !\', \'Information\');</script>';
      
      //Delete temp file
      unlink(ROOT.DS.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf');
      flush();
    } catch (phpmailerException $e) {
      $fail++;
      $err.=$e->errorMessage().'<br />'; //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      $fail++;
      $err.=$e->errorMessage().'<br />'; //Boring error messages from anything else!
    }
  }
  echo $success.' success.<br />';
  echo $fail.' fail.<br />';
  if($err)
    echo '<pre>Errors : <br />
        '.$err.'</pre>';
}
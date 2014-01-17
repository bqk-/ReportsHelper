<?php
require_once __DIR__.'/../phpmailer/class.phpmailer.php';
require_once __DIR__.'/../load.php';

define('MONTH',sprintf("%02s", intval($_POST['month'])));
define('YEAR',intval($_POST['year']));
define('TABLE_ID',intval($_POST['code']));

$months=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
$datas=file_get_contents(__DIR__.'/../'.TABLE_ID.'/profile.ini');
$infos=unserialize($datas);
$emails=explode(',',$infos['emails']);
$html='<link type="text/css" href="./css/style.css" rel="stylesheet" >';
$html.=file_get_contents(__DIR__.'/../'.TABLE_ID.'/template.tpl');
$pdf=new HTML2PDF('P','A4','en');
//Fixing some links
$html=str_replace('<img src="', '<img src="'.BASE_URL, $html);
$html=parse_content($html);
$pdf->WriteHTML($html);
//Create a temp file to use as attachment
$pdf->Output(__DIR__.'/../'.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf', 'F');

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP

try {
  $mail->Host       = "smtpcorp.com"; // SMTP server
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
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
  $mail->AddAttachment(__DIR__.'/../'.$months[intval(MONTH)].'_'.YEAR.'_Report.pdf');      // attachment
  $mail->Send();
  echo "Message Sent OK</p>\n";
  echo "TO : ".$infos['emails'];
  //Delete temp file
  unlink(__DIR__.'/../Report.pdf');
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
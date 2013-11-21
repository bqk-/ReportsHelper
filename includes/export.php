<?php
define('MONTH',sprintf("%02s", intval($_POST['month'])));
define('YEAR',intval($_POST['year']));
define('TABLE_ID',intval($_POST['code']));

$html='<link type="text/css" href="./style.css" rel="stylesheet" >';
$html.=file_get_contents(__DIR__.'/../'.TABLE_ID.'/template.tpl');
$pdf=new HTML2PDF('P','A4','en');
$html=str_replace('<img src="', '<img src="'.BASE_URL, $html);
$html=parse_content($html);
$pdf->WriteHTML($html);
$pdf->Output('export.pdf');

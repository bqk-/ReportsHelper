<?php

$html='<link type="text/css" href="./style.css" rel="stylesheet" >';
$html.=file_get_contents(__DIR__.'/../'.intval($_GET['code']).'/template.tpl');
ob_flush();
$pdf=new HTML2PDF('P','A4','en');
$pdf->WriteHTML(parse_content($html));
$pdf->Output('export.pdf');
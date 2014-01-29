<?php // content="text/plain; charset=utf-8"
/**
*	PIE GRAPH LIB
*	Thibault Miclo
*/
require_once (__DIR__.'/../src/jpgraph.php');
require_once (__DIR__.'/../src/jpgraph_pie.php');

function draw_pie($d,$l,$title) {
	$data = $d;
	$filename=MONTH.'-'.YEAR.'-pie';
	if(!file_exists($filename))
	{
		// A new pie graph
		$graph = new PieGraph(500,400);
		$graph->SetShadow();

		// Title setup
		$graph->title->Set($title);
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->SetBox(true);
		$graph->img->SetTransparent("white");
		
		// Setup the pie plot
		$p1 = new PiePlot($data);

		// Adjust size and position of plot
		$p1->SetSize(0.35);
		$p1->SetCenter(0.5,0.4);

		// Setup slice labels and move them into the plot
		$p1->value->SetFont(FF_FONT1,FS_BOLD);
		$p1->value->SetColor("darkred");
		$p1->SetLabelPos(0.5);
		$p1->SetLabels($l);
		$p1->SetLabelType(PIE_VALUE_PER);


		// Explode all slices
		$p1->ExplodeAll(10);

		// Add drop shadow
		$p1->SetShadow();

		// Finally add the plot
		$graph->Add($p1);


		// ... and stroke it
		@unlink(PROP_DIR.DS.$_SESSION['properties'][TABLE_ID].DS.TABLE_ID.DS.'cache'.DS.MONTH.'-'.YEAR.'-pie.png');
		$graph->Stroke(PROP_DIR.DS.$_SESSION['properties'][TABLE_ID].DS.TABLE_ID.DS.'cache'.DS.MONTH.'-'.YEAR.'-pie.png');
	}
	return '<img src=".'.DS.'properties'.DS.$_SESSION['properties'][TABLE_ID].DS.TABLE_ID.DS.'cache'.DS.MONTH.'-'.YEAR.'-pie.png" />';
}
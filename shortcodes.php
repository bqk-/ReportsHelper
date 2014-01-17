<?php

//https://developers.google.com/analytics/devguides/reporting/core/dimsmets

error_reporting(0);
$list_shortcodes=array(
  'unique_visitors',
  'visits',
  'from_google',
  'new_vs_returning_pie',
  'top_pages_table',
  'month',
  'year'
);
function unique_visitors()
{
  global $demo;
  //unique visitors
  $data=cache_data('unique_visitors',MONTH,YEAR);
  if(!$data)
  {
    $from=YEAR.'-'.MONTH.'-01';
    $to=YEAR.'-'.MONTH.'-'.cal_days_in_month(CAL_GREGORIAN, MONTH, YEAR);
    $metric='ga:visitors';
    $optParams = array(
     // 'dimensions' => 'ga:visitorType',
     // 'sort' => '-ga:visitorType'
      );
    $data=$demo->getHtmlOutput('ga:'.TABLE_ID,$from,$to,$metric,$optParams);
    add_cache($data,'unique_visitors',MONTH,YEAR);
  }
  return intval($data[1][0]);
}
//Visits
function visits()
{
  global $demo;
  $data=cache_data('visits',MONTH,YEAR);
  if(!$data)
  {
    $from=YEAR.'-'.MONTH.'-01';
    $to=YEAR.'-'.MONTH.'-'.cal_days_in_month(CAL_GREGORIAN, MONTH, YEAR);
    $metric='ga:visits';
    $optParams = array(
     // 'dimensions' => 'ga:visitors',
     // 'sort' => '-ga:visitorType'
      );
    $data=$demo->getHtmlOutput('ga:'.TABLE_ID,$from,$to,$metric,$optParams);
    add_cache($data,'visits',MONTH,YEAR);
  }
  return intval($data[1][0]);
}
function from_google()
{
    global $demo;
   //Source = GOOGLE
  $data=cache_data('from_google',MONTH,YEAR);
  if(!$data)
  {
    $from=YEAR.'-'.MONTH.'-01';
    $to=YEAR.'-'.MONTH.'-'.cal_days_in_month(CAL_GREGORIAN, MONTH, YEAR);
    $metric='ga:visits';
    $optParams = array(
      'dimensions' => 'ga:source',
      'filters' => 'ga:source==google'
     // 'sort' => '-ga:visitorType'
      );
    $data=$demo->getHtmlOutput('ga:'.TABLE_ID,$from,$to,$metric,$optParams);
    add_cache($data,'from_google',MONTH,YEAR);
  }
  return intval($data[1][1]);
}

function new_vs_returning_pie()
{
  global $demo;
   //New vs Returning
  $data=cache_data('new_vs_returning_pie',MONTH,YEAR);
  if(!$data && !file_exists(__DIR__.'/'.TABLE_ID.'/cache/'.MONTH.'-'.YEAR.'-pie.png'))
  {
    $from=YEAR.'-'.MONTH.'-01';
    $to=YEAR.'-'.MONTH.'-'.cal_days_in_month(CAL_GREGORIAN, MONTH, YEAR);
    $metric='ga:visits'; 
     $optParams = array(
      'dimensions' => 'ga:visitorType',
     // 'filters' => 'ga:source==google'
     // 'sort' => '-ga:visitorType'
      );
      $data=$demo->getHtmlOutput('ga:'.TABLE_ID,$from,$to,$metric,$optParams);
      add_cache($data,'new_vs_returning_pie',MONTH,YEAR);
      if(!empty($data))
        return draw_pie(array($data[1][1],$data[2][1]),array($data[1][0].' - %.1f%%',$data[2][0].' - %.1f%%'),'New vs. Returning');
      else
        return 'No datas';
  }
  else if(file_exists(__DIR__.'/'.TABLE_ID.'/cache/'.MONTH.'-'.YEAR.'-pie.png'))
      return '<img src="./'.TABLE_ID.'/cache/'.MONTH.'-'.YEAR.'-pie.png" />';
  else
    if(!empty($data))
      return draw_pie(array($data[1][1],$data[2][1]),array($data[1][0].' - %.1f%%',$data[2][0].' - %.1f%%'),'New vs. Returning');
    else
        return 'No datas';
}

function top_pages_table()
{
  global $demo;
  //Top pages
  $from=YEAR.'-'.MONTH.'-01';
  $to=YEAR.'-'.MONTH.'-'.cal_days_in_month(CAL_GREGORIAN, MONTH, YEAR);
  if(MONTH>1)
  {
    $monthp=MONTH-1;
    $yearp=YEAR;
  }
  else
  {
    $monthp=12;
    $yearp=YEAR-1;
  }
  $fromp=$yearp.'-'.sprintf('%02s',$monthp).'-01';
  $top=$yearp.'-'.sprintf('%02s',$monthp).'-'.cal_days_in_month(CAL_GREGORIAN, $monthp, $yearp);
  $metric='ga:pageviews,ga:uniquePageviews,ga:avgtimeOnPage,ga:entrances,ga:visitBounceRate,ga:exitRate';
  $optParams = array(
    'dimensions' => 'ga:pagePath',
    'max-results' => 10,
    'sort' => '-ga:pageviews'
    );
  $data=cache_data('pages',MONTH,YEAR);
  if(!$data)
  {
   $data=$demo->getHtmlOutput('ga:'.TABLE_ID,$from,$to,$metric,$optParams);
   add_cache($data,'pages',MONTH,YEAR);
  }
  $datap=cache_data('pages',sprintf('%02s',$monthp),$yearp);
  if(!$datap)
  {
   $datap=$demo->getHtmlOutput('ga:'.TABLE_ID,$fromp,$top,$metric,$optParams);
   add_cache($datap,'pages',sprintf('%02s',$monthp),$yearp);
  }
   array_shift($data);
   array_shift($datap);
   $total=array();
   $return='';
   $i=1;
   foreach($data as $k=>$row)
   {
      $total[0]+=$row[1];
      $total[1]+=$row[2];
      $total[2]+=$row[3];
      $total[3]+=$row[4];
      $total[4]+=$row[5];
      $total[5]+=$row[6];
      $totalp[0]+=$datap[$k][1];
      $totalp[1]+=$datap[$k][2];
      $totalp[2]+=$datap[$k][3];
      $totalp[3]+=$datap[$k][4];
      $totalp[4]+=$datap[$k][5];
      $totalp[5]+=$datap[$k][6];
      $return.='<tr>
              <td>'.$i++.'</td>
              <td>'.$row[0].'</td>
              <td style="background-color:#5A788C;"></td>
              <td style="background-color:#5A788C;"></td>
              <td style="background-color:#5A788C;"></td>
              <td style="background-color:#5A788C;"></td>
              <td style="background-color:#5A788C;"></td>
              <td style="background-color:#5A788C;"></td>
              </tr>';
      $return.= '<tr>
              <td>&nbsp;</td>
              <td>'.date('M j, Y',mktime(0,0,0,MONTH,1,YEAR)).' - '.date('M j, Y',mktime(0,0,0,MONTH,cal_days_in_month(CAL_GREGORIAN, MONTH, YEAR),YEAR)).'</td>
              <td class="right">'.number_format($row[1]).'</td>
              <td class="right">'.number_format($row[2]).'</td>
              <td class="right">'.sprintf('%02s',intval($row[3]/3600)).':'.sprintf('%02s',intval($row[3]/60)).':'.sprintf('%02s',intval($row[3]%60)).'</td>
              <td class="right">'.number_format($row[4]).'</td>
              <td class="right">'.round($row[5],2).'%</td>
              <td class="right">'.round($row[6],2).'%</td>
            </tr>';
      $return.= '<tr>
              <td>&nbsp;</td>
              <td>'.date('M j, Y',mktime(0,0,0,$monthp,1,$yearp)).' - '.date('M j, Y',mktime(0,0,0,$monthp,cal_days_in_month(CAL_GREGORIAN, $monthp, $yearp),$yearp)).'</td>
              <td class="right">'.number_format($datap[$k][1]).'</td>
              <td class="right">'.number_format($datap[$k][2]).'</td>
              <td class="right">'.sprintf('%02s',intval($datap[$k][3]/3600)).':'.sprintf('%02s',intval($datap[$k][3]/60)).':'.sprintf('%02s',intval($datap[$k][3]%60)).'</td>
              <td class="right">'.number_format($datap[$k][4]).'</td>
              <td class="right">'.round($datap[$k][5],2).'%</td>
              <td class="right">'.round($datap[$k][6],2).'%</td>
            </tr>';
      $return.='<tr>
              <td>&nbsp;</td>
              <td><strong>% Change</strong></td>';
              for($j=1;$j<7;$j++){
                if(($row[$j]==='0' || $row[$j]==='0.0') && ($datap[$k][$j]==='0' || $datap[$k][$j]==='0.0'))
                  $return.='<td class="right">0.00%</td>';
                else if($datap[$k][$j]==='0' || $datap[$k][$j]==='0.0')
                  $return.='<td class="right">100.00%</td>';
                 else
                  $return.='<td class="right">'.round(($row[$j]-$datap[$k][$j])/$datap[$k][$j]*100,2).'%</td>';
              }
      $return.='</tr>';
   }
   $return='<table>
                <tr>
                  <th>&nbsp;</th>
                  <th>Page</th>
                  <th>Pageviews</th>
                  <th>Unique<br />Pageviews</th>
                  <th>Avg. Time<br />on Page</th>
                  <th>Entrances</th>
                  <th>Bounce Rate</th>
                  <th>% Exit</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="right">'.round(($total[0]-$totalp[0])/$totalp[0]*100,2).'%</td>
                    <td class="right">'.round(($total[1]-$totalp[1])/$totalp[1]*100,2).'%</td>
                    <td class="right">'.round(($total[2]-$totalp[2])/$totalp[2]*100,2).'%</td>
                    <td class="right">'.round(($total[3]-$totalp[3])/$totalp[3]*100,2).'%</td>
                    <td class="right">'.round(($total[4]-$totalp[4])/$totalp[4]*100,2).'%</td>
                    <td class="right">'.round(($total[5]-$totalp[5])/$totalp[5]*100,2).'%</td>
                </tr>
                <tr style="font-size:10px;">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="right">'.number_format($total[0]).' vs '.number_format($totalp[0]).'</td>
                    <td class="right">'.number_format($total[1]).' vs '.number_format($totalp[1]).'</td>
                    <td class="right">'.sprintf('%02s',intval($total[2]/3600)).':'.sprintf('%02s',intval($total[2]/60)).':'.sprintf('%02s',intval($total[2]%60)).' vs '.sprintf('%02s',intval($totalp[2]/3600)).':'.sprintf('%02s',intval($totalp[2]/60)).':'.sprintf('%02s',intval($totalp[2]%60)).'</td>
                    <td class="right">'.number_format($total[3]).' vs '.number_format($totalp[3]).'</td>
                    <td class="right">'.round($total[4]/10,2).'% vs '.round($totalp[4]/10,2).'%</td>
                    <td class="right">'.round($total[5]/10,2).'% vs '.round($totalp[5]/10,2).'%</td>
                </tr>
                '.$return.'
            </table>';
    return $return;
}
function month()
{
  $months=array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
  return $months[intval(MONTH)];
}
function year()
{
  return YEAR;
}
function parse_content($content) {
  global $list_shortcodes;
    foreach ($list_shortcodes as $value) {
      $content = preg_replace_callback('#\['.$value.'\]#',$value,$content);
    }
    return $content;
}
function cache_data($shortcode,$m,$y) {
    if(file_exists(__DIR__.'/'.TABLE_ID.'/cache/'.$shortcode.'-'.$m.'-'.$y.'.ini'))
      return unserialize(file_get_contents(__DIR__.'/'.TABLE_ID.'/cache/'.$shortcode.'-'.$m.'-'.$y.'.ini'));
    else
      return false;
}

function add_cache($data,$shortcode,$m,$y)
{
  if(!file_exists(__DIR__.'/'.TABLE_ID))
    mkdir(__DIR__.'/'.TABLE_ID);
  if(!file_exists(__DIR__.'/'.TABLE_ID.'/cache'))
    mkdir(__DIR__.'/'.TABLE_ID.'/cache');
  $f=fopen(__DIR__.'/'.TABLE_ID.'/cache/'.$shortcode.'-'.$m.'-'.$y.'.ini','w+');
  fwrite($f,serialize($data));
  fclose($f);
}

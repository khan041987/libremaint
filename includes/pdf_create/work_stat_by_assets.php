<?php

require_once(VENDORS_PATH.'tcpdf/tcpdf.php');
require_once(INCLUDES_PATH.'work_stat_query.php');

$date_termin=str_replace('-', '.', $_POST['start_date'])." - ".str_replace('-', '.', $_POST['end_date']);
ob_end_clean();

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetFont("freeserif", '', 10);
$pdf->setPrintHeader(false);
$pdf->AddPage();
$pdf->Bookmark(gettext("Diagrams"), 0, 0, '', '', array(0,64,128));
if (isset($_POST['chart1']))
{
$imgData = str_replace(' ','+',$_POST['chart1']);
$imgData =  substr($imgData,strpos($imgData,",")+1);
$imgData = base64_decode($imgData);
// Path where the image is going to be saved
$filePath1 = $_SERVER['DOCUMENT_ROOT']. 'temp1.png';
$file = fopen($filePath1, 'w');
fwrite($file, $imgData);
fclose($file);
}

if (isset($_POST['chart2']))
{
$imgData = str_replace(' ','+',$_POST['chart2']);
$imgData =  substr($imgData,strpos($imgData,",")+1);
$imgData = base64_decode($imgData);
// Path where the image is going to be saved
$filePath2 = $_SERVER['DOCUMENT_ROOT']. 'temp2.png';
$file = fopen($filePath2, 'w');
fwrite($file, $imgData);
fclose($file);
}

if (isset($_POST['chart3']))
{
$imgData = str_replace(' ','+',$_POST['chart3']);
$imgData =  substr($imgData,strpos($imgData,",")+1);
$imgData = base64_decode($imgData);
// Path where the image is going to be saved
$filePath3 = $_SERVER['DOCUMENT_ROOT']. 'temp3.png';
$file = fopen($filePath3, 'w');
fwrite($file, $imgData);
fclose($file);
}
// The '@' character is used to indicate that follows an image data stream and not an image file name
//$html = '<img src="'.$filePath . '">';
$startdate=DateTime::createFromFormat('Y-m-d', $_POST['start_date']);
$enddate=DateTime::createFromFormat('Y-m-d', $_POST['end_date']);

$date_termin=$startdate->format($lang_date_format)." - ".$enddate->format($lang_date_format);



$html='<h2>'.gettext('Workhours by priority').' '.$date_termin.'</h2>';
//$pdf->Image('@'.$imgData,'C');
$html.="<IMG src=\"".$filePath1."\">";

$html.='<h2>'.gettext('Workhours by activity type').' '.$date_termin.'</h2>';
//$pdf->Image('@'.$imgData,'C');
$html.="<IMG src=\"".$filePath2."\">";

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->AddPage();



$html='<h2>'.gettext('Workhours by assets').' '.$date_termin.'</h2>';
//$pdf->Image('@'.$imgData,'C');
$html.="<IMG src=\"".$filePath3."\">";


if (isset($_POST['start_date']) && validateDate($_POST['start_date'],"Y-m-d"))
$start=$dba->escapeStr($_POST['start_date']);
else 
$start="";

if (isset($_POST['end_date']) && validateDate($_POST['end_date'],"Y-m-d"))
$end=$dba->escapeStr($_POST['end_date']);
else 
$end="";

if (isset($_POST['priority']) && $_POST['priority']>0)
$priority=(int) $_POST['priority'];
else
$priority=0;

if (isset($_POST['request_type']) && $_POST['request_type']>0)
$request_type=(int) $_POST['request_type'];
else
$request_type=0;

if (isset($_POST['important_only']) && $_POST['important_only']==1)
$important_only=1;
else
$important_only=0;

$pdf->writeHTML($html, true, false, true, false, '');
$SQL="select SUM(TIME_TO_SEC(workorder_worktime)/3600) as workhour, asset_name_".$lang." FROM workorder_works LEFT JOIN assets ON workorder_works.main_asset_id=assets.asset_id WHERE workorder_works.deleted<>1 AND DATE(workorder_work_start_time) >= DATE('".$start."') AND DATE(workorder_work_end_time) <= DATE('".$end."') AND workorder_works.asset_id>0";

if ($important_only==1)
$SQL.=" AND asset_importance=1";
$SQL.=" GROUP BY main_asset_id ORDER BY workhour DESC" ; 
$result=$dba->Select($SQL);
$html='<table border="0" cellspacing="2" cellpadding="2"><thead><tr><td width="20"></td><td><strong>'.gettext("Asset name").'</strong></td><td width="60" style="text-align:right"><strong>'.gettext("Workhours").'</strong></td></tr></thead>';
$i=0;
$total=0;
if ($dba->affectedRows()>0){
foreach ($result as $row){
$html.='<tr><td width="20">'.++$i.'</td><td><strong>'.$row['asset_name_'.$lang].'</strong></td><td width="60" style="text-align:right">'.round($row['workhour'],1).'</td></tr>';
$total+=round($row['workhour'],1);
}
}

if ($important_only==0)
{
    $SQL="select SUM(TIME_TO_SEC(workorder_worktime)/3600) as workhour, product_id_to_refurbish FROM workorder_works LEFT JOIN workorders ON workorder_works.workorder_id=workorders.workorder_id WHERE workorder_works.deleted<>1 AND DATE(workorder_work_start_time) >= DATE('".$start."') AND DATE(workorder_work_end_time) <= DATE('".$end."') AND workorders.product_id_to_refurbish>0 GROUP BY product_id_to_refurbish ORDER BY workhour DESC" ; 
    $result=$dba->Select($SQL);
    if ($dba->affectedRows()>0){
        foreach ($result as $row){
        $html.='<tr><td width="20">'.++$i.'</td><td><strong>'.get_product_name_from_id($row['product_id_to_refurbish'],$lang).'</strong></td><td width="60" style="text-align:right">'.round($row['workhour'],1).'</td></tr>';
        $total+=round($row['workhour'],1);
        }
    }
}

$html.='<tr><td width="20"></td><td><strong>'.gettext("Total").':</strong></td><td width="60" style="text-align:right">'.$total.' '.gettext('hours').'</td></tr>';

$html.='</table>';
$pdf->writeHTML($html, true, false, false, false, '');


$pdf->AddPage();
$pdf->Bookmark(gettext("Workhours by priority"), 0, 0, '', '', array(0,64,128));
$html="<h1>".gettext("Workhours by priority")."</h1><br/>";
$pdf->writeHTML($html, true, false, true, false, '');

foreach ($priority_types as $index=>$priority){

$h=work_stat_query($start,$end,0,++$index,'','',1);
$h.=work_stat_query($start,$end,0,$index,'','',0);
if (!empty($h))
{
$pdf->Bookmark($priority, 1, 0, '', '', array(0,64,128));
$html="<h1><u>".$priority."</u></h1>";
$html.=$h;

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->AddPage();
}
}




if (isset($_POST['request_type']) && $_POST['request_type']>0){


$html="<h2>".$activity_types[(int) $_POST['request_type']-1]."</h2>";

$pdf->writeHTML($html, true, false, true, false, '');
}else
{
$pdf->Bookmark(gettext("Workhours by activity_type"), 0, 0, '', '', array(0,64,128));
$html="<h1>".gettext("Workhours by activity type")."</h2><br/>";
$pdf->writeHTML($html, true, false, true, false, '');
foreach ($activity_types as $index=>$activity){


$h=work_stat_query($start,$end,++$index,0,'','',1);
$h.=work_stat_query($start,$end,$index,0,'','',0);
if ($h!='')
{
$pdf->Bookmark(ucfirst($activity), 1, 0, '', '', array(0,64,128));
$html="<h1><u>".ucfirst($activity)."</u></h1>";
$html.=$h;
$pdf->writeHTML($html, true, false, true, false, '');
}

}

$pdf->AddPage();
}
//$pdf->writeHTML($img, true, false, true, false, '');

if (isset($_POST['start_date']) && isset($_POST['end_date']))
{
$pdf->Bookmark(gettext("Workhours by assets"), 0, 0, '', '', array(0,64,128));
$html='<h1>'.gettext('Workhours by assets')."</H1><H2>".$date_termin.'</H2><br/>';

}
else{
    $html='<h1>'.gettext('Workhours by assets (last 
    month)').'</h1><br/>';
}
$html.=work_stat_query($start,$end,$request_type,$priority,'','',1);
$html.=work_stat_query($start,$end,$request_type,$priority,'','',0);



// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


//notification
$pdf->AddPage();
$pdf->Bookmark(gettext("Notifications"), 0, 0, '', '', array(0,64,128));

$pdf->Bookmark(gettext("Active notifications"), 1, 0, '', '', array(0,64,128));
$html='<H1>'.gettext("Active notifications")." ".$date_termin.'</H1>';


$SQL="SELECT * FROM notifications LEFT JOIN assets ON notifications.main_asset_id=assets.asset_id WHERE notification_status<4 ORDER BY asset_name_".$lang;
$result=$dba->Select($SQL);

if ($dba->affectedRows())
{
    $main_asset_id=0;
    foreach ($result as $row){

    if ($main_asset_id!=$row["main_asset_id"])
    {
        if ($main_asset_id>0)
        $html.= "<hr size='1'>";
    $html.="<H2>".$row["asset_name_".$lang]."<br/></H2>";

    }
    $html.="<strong>".gettext("Notification time:")." </strong>".date($lang_date_format." h:i", strtotime($row["notification_time"]))."<br/>";
    $html.="<strong>".gettext("Notifier:")." </strong>".get_user_full_name_from_id($row['user_id'])."<br/>";
    $html.="<strong>".gettext("Priority:")." </strong>".$priority_types[$row["priority"]-1]."<br/>";

    $html.="<strong>".gettext("Type:")." </strong>".$notification_types[$row["notification_type"]-1]."<br/>";

    $html.="<strong>".gettext("Status:")." </strong>".$notification_statuses[$row["notification_status"]-1]."<br/>";
    $html.="<strong>".gettext("Title:")." </strong>".$row['notification_short_'.$lang]."<br/>";
    if ($row['notification_'.$lang]!="")
    $html.="<strong>".gettext("Notification:")." </strong><p>".$row['notification_'.$lang]."</p><br/>";
    $html.= "<hr size='1'>";


    $main_asset_id=$row["main_asset_id"];

    }
    //$html.= "<hr size='1'>";
    $pdf->writeHTML($html, true, false, true, false, '');
}

$SQL="SELECT * FROM notifications LEFT JOIN assets ON notifications.main_asset_id=assets.asset_id WHERE DATE(notification_closing_time) <= DATE('".$end."') AND DATE(notification_closing_time) >= DATE('".$start."') AND notification_status=4 ORDER BY asset_name_".$lang;
$result=$dba->Select($SQL);
if (LM_DEBUG)
error_log($SQL,0);
if ($dba->affectedRows()>0)
{
    $pdf->Bookmark(gettext("Solved notifications"), 1, 0, '', '', array(0,64,128));
    $html='<H1>'.gettext('Solved notifications')." ".$date_termin.'</H1>';

    $main_asset_id=0;
    foreach ($result as $row){
    if ($main_asset_id!=$row["main_asset_id"]){
        if ($main_asset_id>0)
        $html.= "<hr size='1'><br/>";
    $html.="<H2>".$row["asset_name_".$lang]."</H2>";
    }
    $html.="<strong>".gettext("Notification time:")." </strong>".date($lang_date_format." h:i", strtotime($row["notification_time"]))."<br/>";
    $html.="<strong>".gettext("Notifier:")." </strong>".get_user_full_name_from_id($row['user_id'])."<br/>";

    $html.="<strong>".gettext("Priority:")." </strong>".$priority_types[$row['priority']-1]."<br/>";

    $html.="<strong>".gettext("Type:")." </strong>".$notification_types[$row['notification_type']-1]."<br/>";

    $html.="<strong>".gettext("Status:")." </strong>".$notification_statuses[$row['notification_status']-1]."<br/>";
    
    $html.="<strong>".gettext("Title:")." </strong>".$row['notification_short_'.$lang]."<br/>";
    if ($row['notification_'.$lang]!="")
    $html.="<strong>".gettext("Notification:")." </strong>".$row['notification_'.$lang]."<br/>";
    
     if (!empty($row['reason_to_close_'.$lang]))
    $html.="<strong>".gettext("Reason to close").": </strong>".$row['reason_to_close_'.$lang]."<br/>";
    
    if (!empty($row['notification_closing_time']))
    $html.="<strong>".gettext("Closing time").": </strong>".date($lang_date_format." H:i", strtotime($row["notification_closing_time"]))."<br/>";
    
    $html.= "<hr size='1'>";

    $main_asset_id=$row["main_asset_id"];

    }
    //$html.= "<hr size='1'>";
    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
}



$SQL="SELECT * FROM notifications LEFT JOIN assets ON notifications.main_asset_id=assets.asset_id WHERE DATE(notification_closing_time) <= DATE('".$end."') AND DATE(notification_closing_time) >= DATE('".$start."') AND notification_status=5 ORDER BY asset_name_".$lang;
$result=$dba->Select($SQL);
if (LM_DEBUG)
error_log($SQL,0);
if ($dba->affectedRows()>0)
{
    $pdf->Bookmark(gettext("Closed notifications"), 1, 0, '', '', array(0,64,128));
    $html='<H1>'.gettext('Closed notifications')." ".$date_termin.'</H1>';

    $main_asset_id=0;
    foreach ($result as $row){
    if ($main_asset_id!=$row["main_asset_id"]){
        if ($main_asset_id>0)
        $html.= "<hr size='1'><br/>";
    $html.="<H2>".$row["asset_name_".$lang]."</H2>";
    }
    $html.="<strong>".gettext("Notification time:")." </strong>".date($lang_date_format." h:i", strtotime($row["notification_time"]))."<br/>";
    $html.="<strong>".gettext("Notifier:")." </strong>".get_user_full_name_from_id($row['user_id'])."<br/>";

    $html.="<strong>".gettext("Priority:")." </strong>".$priority_types[$row['priority']-1]."<br/>";

    $html.="<strong>".gettext("Type:")." </strong>".$notification_types[$row['notification_type']-1]."<br/>";

    $html.="<strong>".gettext("Status:")." </strong>".$notification_statuses[$row['notification_status']-1]."<br/>";
    
    $html.="<strong>".gettext("Title:")." </strong>".$row['notification_short_'.$lang]."<br/>";
    if ($row['notification_'.$lang]!="")
    $html.="<strong>".gettext("Notification:")." </strong>".$row['notification_'.$lang]."<br/>";
    
     if (!empty($row['reason_to_close_'.$lang]))
    $html.="<strong>".gettext("Reason to close").": </strong>".$row['reason_to_close_'.$lang]."<br/>";
    
    if (!empty($row['notification_closing_time']))
    $html.="<strong>".gettext("Closing time").": </strong>".date($lang_date_format." H:i", strtotime($row["notification_closing_time"]))."<br/>";
    
    $html.= "<hr size='1'>";

    $main_asset_id=$row["main_asset_id"];

    }
    //$html.= "<hr size='1'>";
    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
}



$SQL="SELECT * FROM notifications LEFT JOIN assets ON notifications.main_asset_id=assets.asset_id WHERE DATE(notification_time) <= DATE('".$end."') AND DATE(notification_time) >= DATE('".$start."') AND notification_status<4 ORDER BY asset_name_".$lang;
$result=$dba->Select($SQL);

if ($dba->affectedRows()>0)
{
    $pdf->Bookmark(gettext("New notifications"), 1, 0, '', '', array(0,64,128));
    $html='<H1>'.gettext('New notifications')." ".$date_termin.'</H1>';

    $main_asset_id=0;
    foreach ($result as $row){
    if ($main_asset_id!=$row["main_asset_id"]){
        if ($main_asset_id>0)
        $html.= "<hr size='1'><br/>";
    $html.="<H2>".$row["asset_name_".$lang]."</H2>";
    }
    $html.="<strong>".gettext("Notification time:")." </strong>".date($lang_date_format." h:i", strtotime($row["notification_time"]))."<br/>";
    $html.="<strong>".gettext("Notifier:")." </strong>".get_user_full_name_from_id($row['user_id'])."<br/>";

    $html.="<strong>".gettext("Priority:")." </strong>".$priority_types[$row['priority']-1]."<br/>";

    $html.="<strong>".gettext("Type:")." </strong>".$notification_types[$row['notification_type']-1]."<br/>";

    $html.="<strong>".gettext("Status:")." </strong>".$notification_statuses[$row['notification_status']-1]."<br/>";
    $html.="<strong>".gettext("Title:")." </strong>".$row['notification_short_'.$lang]."<br/>";
    if ($row['notification_'.$lang]!="")
    $html.="<strong>".gettext("Notification:")." </strong><p>".$row['notification_'.$lang]."</p><br/>";
    $html.= "<hr size='1'>";

    $main_asset_id=$row["main_asset_id"];

    }
    //$html.= "<hr size='1'>";
    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
}


$pdf->lastPage();
$pdf->addTOCPage();

// write the TOC title
$pdf->SetFont('times', 'B', 16);
$pdf->MultiCell(0, 0, gettext('Table Of Content'), 0, 'C', 0, 1, '', '', true, 0);
$pdf->Ln();

$pdf->SetFont('dejavusans', '', 12);




// add a simple Table Of Content at first page
// (check the example n. 59 for the HTML version)
$pdf->addTOC(1, 'courier', '.', 'INDEX', 'B', array(128,0,0));

// end of TOC page
$pdf->endTOCPage();

 //ob_end_clean();
$pdf->Output('maintenance_report_'.$date_termin.'_'.$lang.'.pdf', 'I');

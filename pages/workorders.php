<script>
function visibility(divClass)
{
    
	var els= document.querySelectorAll("."+divClass);
	
  for(var i = 0; i< els.length; i++)
        {
    
    if (els[i].style.display == "none")
        els[i].style.display = "";
    else
    els[i].style.display = "none"
    }
}


</script>
<div id='for_ajaxcall'>
</div>

<?php
if (isset($_POST['page']) && isset($_POST['new']) && isset($_POST["workorder_work"]) && isset($_SESSION['ADD_WORK']) && is_it_valid_submit()){// add new work form 

$start_time=new DateTime($dba->escapeStr($_POST['workorder_work_start_date'])." ".$dba->escapeStr($_POST['workorder_work_start_time']));
$end_time=new DateTime($dba->escapeStr($_POST['workorder_work_end_date'])." ".$dba->escapeStr($_POST['workorder_work_end_time']));

if ($_SESSION['user_level']<3)
    $user_id=(int) $_POST['workorder_user_id'];
else if ($_SESSION['user_level']>2)
    $user_id=(int) $_SESSION['user_id'];

if (is_it_valid_worktime_period($_POST['workorder_work_start_date']." ".$_POST['workorder_work_start_time'],$_POST['workorder_work_end_date']." ".$_POST['workorder_work_end_time'],$user_id,(int) $_POST['workorder_id']))
{
    
    $SQL="SELECT * FROM workorders WHERE workorder_id=".(int) $_POST['workorder_id'];
    $workorder_row=$dba->getRow($SQL);
if (LM_DEBUG)
error_log($SQL,0);
    $SQL="INSERT INTO workorder_works (workorder_id,workorder_work_start_time,workorder_work_end_time,workorder_worktime,workorder_work,main_asset_id,asset_id,workorder_user_id,workorder_status";

    if ($workorder_row['workorder_partner_id']>0)
    $SQL.=",workorder_partner_id";

    $SQL.=") VALUES ";
    $SQL.="(";
    $SQL.=$workorder_row['workorder_id'].",";
    $SQL.="'".$dba->escapeStr($_POST['workorder_work_start_date'])." ".$dba->escapeStr($_POST['workorder_work_start_time'])."',";
    $SQL.="'".$dba->escapeStr($_POST['workorder_work_end_date'])." ".$dba->escapeStr($_POST['workorder_work_end_time'])."',";

    $interval=date_diff($start_time,$end_time);

    $SQL.="'".$interval->format('%h:%i:%s')."',";
    $SQL.="'".$dba->escapeStr($_POST['workorder_work'])."',";
    $SQL.=$workorder_row['main_asset_id'].",";
    $SQL.=$workorder_row['asset_id'].",";
    
    if ($_SESSION['user_level']<3)
    $SQL.=(int) $_POST['workorder_user_id'].",";
    else
    $SQL.=(int) $_SESSION['user_id'].",";
    $SQL.=(int) $_POST['workorder_status'];

    if ($workorder_row['workorder_partner_id']>0)
    $SQL.=",".(int) $_POST['workorder_partner_id'];

    $SQL.=")";
    
if (LM_DEBUG)
error_log($SQL,0);
    if ($dba->Query($SQL))
            {
            $SQL="UPDATE workorders SET workorder_status=".(int) $_POST['workorder_status']." WHERE workorder_id=".$workorder_row['workorder_id'];
            $dba->Query($SQL);
            echo "<div class=\"card\">".gettext("The activity has been attached to the workorder.")."</div>";
                if (5==(int) $_POST['workorder_status'])//the workorder is ready for this employee we need check the others whether we can close the workorder
                //check_workorder_to_close($workorder_row['workorder_id'],$_POST['workorder_work_end_date']." ".$_POST['workorder_work_end_time']);
            check_workorder_to_close($workorder_row['workorder_id']);
            
            }
            else
            echo "<div class=\"card\">".gettext("Failed to attach activity to the workorder ").$SQL." ".$dba->err_msg."</div>";
        
            }
            else
            echo "<div class=\"card\">".gettext("Failed to attach activity to the workorder (wrong time period)")."</div>";
        
}
else if (isset($_POST['product_to_workorder']) && is_it_valid_submit())
{
    foreach ($_POST as $key=>$value){
        if (strstr($key,"stock_id") && $value>0){
        $SQL="UPDATE stock SET stock_quantity=stock_quantity-'".(float) $value."' WHERE stock_id='".intval(substr($key,8))."'";
        $result=$dba->Query($SQL);
        if (LM_DEBUG)
        error_log($SQL,0);
        $SQL="SELECT stock_location_id FROM stock WHERE stock_id='".intval(substr($key,8))."'";
        $row=$dba->getRow($SQL);
        $SQL="SELECT asset_id FROM workorders WHERE workorder_id='".intval($_POST['workorder_id'])."'";
        $row1=$dba->getRow($SQL);
        $SQL="INSERT INTO stock_movements (workorder_id,product_id,to_asset_id,stock_movement_quantity,from_stock_location_id) VALUES ";
$SQL.="('".(int) $_POST['workorder_id']."',";
$SQL.="'".(int) $_POST['product_id']."',";
$SQL.="'".(int) $row1['asset_id']."',";
$SQL.="'".(float) $value."',";
$SQL.="'".$row['stock_location_id']."')";
$dba->Query($SQL);
if (LM_DEBUG)
error_log($SQL,0);
        }
    
    }

}
        
        
        
if (isset($_POST['page']) && isset($_POST["workorder"]) && is_it_valid_submit() && !empty($_POST['employee_ids'])){ //it is from the new workorder form

    if (isset($_POST['modify_workorder']) && isset($_SESSION['MODIFY_WORKORDER']))
    {
    $SQL="UPDATE workorders SET";
    $SQL.=" priority=".(int) $_POST['priority'].",";
    $SQL.=" request_type=".(int) $_POST['request_type'].",";
    if (!empty($_POST['workorder_deadline']))
    $SQL.=" workorder_deadline='".$dba->escapeStr($_POST['workorder_deadline'])."',";
    else
    $SQL.=" workorder_deadline=null,";
    
    $SQL.=" workorder_short='".$dba->escapeStr($_POST["workorder_short"])."',";
    $SQL.=" workorder='".$dba->escapeStr($_POST["workorder"])."',";
    $SQL.=" workorder_partner_id=".(int) $_POST['workorder_partner_id'].",";
    if ($_POST['workorder_partner_id']>0)
    $SQL.=" workorder_partner_supervisor_user_id=".(int) $_POST['workorder_partner_supervisor_user_id'].",";
    else
    $SQL.=" workorder_partner_supervisor_user_id=0,";

    if(!empty($_POST['employee_ids']))
        {
       
            foreach (json_decode($_POST["employee_ids"],true) as $employee_id)
            {
            $SQL.="employee_id".$employee_id."=1,";
            }
       
        }
            
    if(!empty($_POST['not_employee_ids']))
        {
            foreach (json_decode($_POST["not_employee_ids"],true) as $employee_id)
            {
            $SQL.="employee_id".$employee_id."=0,";
            }
       
        }
    $SQL.=" replace_to_product_id='".(int) $_POST["replace_to_product_id"]."',";
    $SQL.=" work_details_required=".(int) $_POST['work_details_required'];
    $SQL.=" WHERE workorder_id='".(int) $_POST['workorder_id']."'";
    if (LM_DEBUG)
    error_log($SQL,0);
    
    if ($dba->Query($SQL))
            echo "<div class=\"card\">".gettext("The new workorder has been modified.")."</div>";
            else
            echo "<div class=\"card\">".gettext("Failed to modify workorder ").$SQL." ".$dba->err_msg."</div>";
    check_workorder_to_close((int) $_POST['workorder_id']);
    if ($_POST['product_id_to_refurbish']>0 && (int) $_POST["workorder_partner_id"]>0)
    {
    $SQL="UPDATE stock_movements SET to_partner_id=".(int) $_POST["workorder_partner_id"]." WHERE workorder_id=".(int) $_POST["workorder_id"];
    if (!$dba->Query($SQL))
    lm_die($SQL." ".$dba->err_msg);
    $SQL="UPDATE stock SET stock_location_partner_id=".(int) $_POST["workorder_partner_id"]." WHERE product_id=".(int) $_POST['product_id_to_refurbish'];
    if (!$dba->Query($SQL))
    lm_die($SQL." ".$dba->err_msg);

    }
 
 
    
    
  

   
    
    }
else if (isset($_POST['new_workorder']) && isset($_SESSION['ADD_WORKORDER']) && isset($_POST['asset_id']) && isset($_POST['request_type']))
    {
    
    $SQL="INSERT INTO workorders (asset_id,workrequest_id,notification_id,main_asset_id,priority,workorder_deadline,workorder_short,workorder,user_id,workorder_time,request_type,replace_to_product_id,workorder_partner_id,workorder_partner_supervisor_user_id,work_details_required";
    if(!empty($_POST['employee_ids']))
        {
       
            foreach (json_decode($_POST["employee_ids"],true) as $employee_id)
            {
            $SQL.=",employee_id".$employee_id;
            }
       
        }
    if(!empty($_POST['not_employee_ids']))
        {
            foreach (json_decode($_POST["not_employee_ids"],true) as $employee_id)
            {
            $SQL.=",employee_id".$employee_id;
            }
       
        }
    $SQL.=")";
    $SQL.=" VALUES ";
    $SQL.="(".(int) $_POST["asset_id"].",0,0,";//workrequest_id=0 since there was no workrequest
    $SQL.="'".get_whole_path("asset",$_POST['asset_id'],1)[0]."',";
    
    $SQL.="'".$_POST["priority"]."',";
    if (validateDate($_POST["workorder_deadline"],$lang_date_format))
    $SQL.="'".$_POST["workorder_deadline"]."',";
    else
    $SQL.="NULL,";
    $SQL.="'".$dba->escapeStr($_POST["workorder_short"])."',";
    $SQL.="'".$dba->escapeStr($_POST["workorder"])."',";
    $SQL.=(int) $_SESSION["user_id"].",";
    $SQL.="now(),";
    $SQL.=(int) $_POST["request_type"].",";
    $SQL.=(int) $_POST["replace_to_product_id"].",";
    $SQL.=(int) $_POST["workorder_partner_id"].",";
    $SQL.=(int) $_POST["workorder_partner_supervisor_user_id"].",";
    $SQL.=(int) $_POST["work_details_required"];
    if(!empty($_POST['employee_ids']))
        {
       
            foreach (json_decode($_POST["employee_ids"],true) as $employee_id)
            {
            $SQL.=",'1'";
            }
       
        }
    if(!empty($_POST['not_employee_ids']))
        {
            foreach (json_decode($_POST["not_employee_ids"],true) as $employee_id)
            {
            $SQL.=",'0'";
            }
       
        }
    $SQL.=")";
    if ($dba->Query($SQL))
            echo "<div class=\"card\">".gettext("The new workorder has been saved.")."</div>";
            else
            echo "<div class=\"card\">".gettext("Failed to save new workorder ").$SQL." ".$dba->err_msg."</div>";
    if (LM_DEBUG)
    error_log($SQL,0);
}
}

if ((isset($_GET["new"]) && isset($_SESSION['ADD_WORKORDER']) && isset($_GET['asset_id'])) || (isset($_GET['modify']) && isset($_GET['workorder_id']) && $_SESSION['MODIFY_WORKORDER']) ){
//from the asset_tree a direct workorder without workrequest
if (isset($_GET['asset_id']) && $_GET['asset_id']>0)
$asset_id=$_GET['asset_id'];
?>

<?php 

$SQL="SELECT workorder_short,asset_id,workorder_id FROM workorders WHERE main_asset_id=".get_whole_path_ids('asset',$asset_id,1)[0]." AND workorder_status<5";
$result=$dba->Select($SQL);
if ($dba->affectedRows()){
        echo "<div class=\"card\">\n<div class=\"card-header\">\n";
        echo gettext("Active workorders for this asset");
        echo "</div>\n";

        if ($asset_id==$row['asset_id'])
        echo "<ul class='alert-danger'>";
        foreach ($result as $row){
        if ($asset_id==$row['asset_id'])
        echo "<li class='alert-danger'>";
        else
        echo '<li>';
        $n="";
        foreach (get_whole_path("asset",$row['asset_id'],1) as $k){
        if ($n=="") // the first element is the main asset_id -> ignore it
        $n=" ";
        else
        $n.=$k."-><wbr>";}

        if ($n!="")
        echo substr($n,0,-7);


        echo ' '.$row['workorder_short'].'</li>';

        }
        echo '</ul></div>';
}
?>
<div class="card">
<div class="card-header">
<strong><?php 
if (isset($_GET["new"]))
echo gettext("New workorder ");
else if (isset($_GET['modify'])){
echo gettext("Alter workorder ");
$SQL="SELECT * FROM workorders WHERE workorder_id='".(int) $_GET['workorder_id']."'";
$row=$dba->getRow($SQL);
$asset_id=$row['asset_id'];
}

if ($asset_id>0){
echo gettext("to");

$n="";
foreach (get_whole_path("asset",$asset_id,1) as $k){
if ($n=="") // the first element is the main asset_id -> ignore it
$n=" ";
else
$n.=$k."-><wbr>";}

if ($n!="")
echo substr($n,0,-7);

}
?></strong>
</div><?php //card header ?>
<div class="card-body card-block">
<form action="index.php" id="workorder_form" method="post" enctype="multipart/form-data" class="form-horizontal">

<?php



echo "<div class=\"row form-group\">";
    echo "<div class=\"col col-md-2\">\n";
        echo "<label for=\"priority\" class=\" form-control-label\">".gettext("Priority:")."</label>";
    echo "</div>\n";

    echo "<div class=\"col col-md-3\">";
        echo "<select name=\"priority\" id=\"priority\" class=\"form-control\" >";
    
        foreach($priority_types as $id => $priority_type) //$priority_types from config/lm-settings.php
        {
        echo "<option value=\"".++$id."\"";
        if (isset($_GET['modify']) && $row['priority']==$id)
        echo " selected";
        echo ">".$priority_type."</option>\n";
        }
        echo "</select>\n";
    echo "</div>";
echo "</div>";


 echo "<div class=\"row form-group\">\n";
        echo "<div class=\"col col-md-2\"><label for=\"workorder_deadline\" class=\"form-control-label\">".gettext("Deadline:")."</label></div>\n";
        echo "<div class=\"col-12 col-md-3\">";
        echo "<input type='checkbox' onchange=\"if (this.checked)
        {
        document.getElementById('workorder_deadline').style.visibility='visible';
        }else
        {
        document.getElementById('workorder_deadline').style.visibility='hidden';
        document.getElementById('workorder_deadline').value=null;
        }
        \"";
        if (isset($_GET['modify']) && isset($row['workorder_deadline']))
        echo " checked";
        echo "> ";
        echo "<input type=\"date\" id=\"workorder_deadline\" name=\"workorder_deadline\" value=\"";
        if (isset($_GET['new']))
        echo "";
        else if (isset($_GET['modify']) && isset($row['workorder_deadline']))
        echo date("Y-m-d", strtotime($row['workorder_deadline']));
        echo "\"";
        if (isset($_GET['new']))
        echo " STYLE=\"visibility:hidden\"";
        echo ">";
       
        echo "</div></div>\n";

        
echo "<div class=\"row form-group\">\n";
        echo "<div class=\"col col-md-2\"><label for=\"work_details_required\" class=\"form-control-label\">".gettext("Work details required:")."</label></div>\n";
        echo "<div class=\"col-12 col-md-3\">";
        echo "<input type='checkbox' id='work_details_required' name='work_details_required' value='1'";
        if (isset($_GET['modify']) && $row['work_details_required']==1)
        echo " checked";
        echo "></div></div>";        

echo "<div class=\"row form-group\">";
    echo "<div class=\"col col-md-2\">\n";
        echo "<label for=\"employee\" class=\" form-control-label\">".gettext("Employee:")."</label>";
    echo "</div>\n";
?><script>
function employees_to_json()
{
var checkboxes = document.querySelectorAll('input[name="employee_id[]"]');
var employee_ids=[];
var not_employee_ids=[];
checkboxes.forEach(e => { 
if (e.checked)
employee_ids.push(e.value);
else
not_employee_ids.push(e.value);
 }) 
document.getElementById("employee_ids").value=JSON.stringify(employee_ids);
document.getElementById("not_employee_ids").value=JSON.stringify(not_employee_ids);

}
</script>
  <?php  echo "<div class=\"col col-md-9\">";
    foreach (get_employees_from_id($_SESSION['user_id']) as $user_id =>$name){
        echo "<INPUT TYPE=\"CHECKBOX\" onClick=\"employees_to_json()\" NAME=\"employee_id[]\" VALUE=\"".$user_id."\"";
        if (isset($_GET['modify']) && $row['employee_id'.$user_id]==1)
        echo " checked";
        echo "> ".$name." \n";
        }
        
    echo "</div>";
echo "</div>";
echo "<div class=\"row form-group\">\n";
        echo "<div class=\"col-5 col-md-2\"><label for=\"workorder_partner_id\" class=\"form-control-label\">";
        echo gettext("Partner involved in this workorder:")."</label></div>\n";
        echo "<div class=\"col-5 col-md-3\">\n";
        echo "<select id=\"workorder_partner_id\" name=\"workorder_partner_id\" class=\"form-control\" ";
        echo "onChange=\"if (this.value>0) document.getElementById('workorder_partner_supervisor_user').style.display = 'block';
        else document.getElementById('workorder_partner_supervisor_user').style.display = 'none';\"";
        echo ">\n";

        $SQL="SELECT partner_name, partner_id FROM partners WHERE active=1 ORDER BY partner_name";
        $result=$dba->Select($SQL);
        echo "<option value=\"0\"";
        if (isset($_GET['modify']) && $row['workorder_partner_id']==0)
        echo " selected";
        echo ">".gettext("Please select")."</option>\n";
        foreach ($result as $row1){
        echo "<option value=\"".$row1["partner_id"]."\"";
        if (isset($_GET['modify']) && $row['workorder_partner_id']==$row1['partner_id'])
        echo " selected";
        echo ">".$row1["partner_name"]."</option>\n";
        }
        echo "</select>\n</div></div>\n";

echo "<div class=\"row form-group\" id='workorder_partner_supervisor_user'";
if (isset($_GET['modify']) && $row['workorder_partner_supervisor_user_id']>0)
echo ">\n";
else
echo " style=\"display:none;\">\n";
echo "<div class=\"col-5 col-md-2\"><label for=\"workorder_partner_supervisor_user_id\" class=\"form-control-label\">";
        echo gettext("Partner supervisor:")."</label></div>\n";
        echo "<div class=\"col-5 col-md-3\">\n";
        echo "<select id=\"workorder_partner_supervisor_user_id\" name=\"workorder_partner_supervisor_user_id\" class=\"form-control\"";
        if (!isset($_SESSION['MODIFY_WORKORDER']))
        echo " disabled";
        echo ">\n";
        echo "<option value='0'>".gettext("Select");
        foreach (get_employees_from_id($_SESSION['user_id']) as $user_id =>$name){
        echo "<option value='".$user_id."'";
        if (isset($_GET['modify']) && $row['workorder_partner_supervisor_user_id']==$user_id)
        echo " selected";

        echo ">".$name."\n";
        }
        echo "</select>\n";
        echo "</div>\n";
echo "</div>\n";
        
echo "<div class=\"row form-group\">";
echo "<div class=\"col-5 col-md-2\"><label for=\"workorder_short\" class=\"form-control-label\">".gettext("Workorder (max.".$dba->get_max_fieldlength('workorders','workorder_short')."):")."</label></div>\n";
echo "<div class=\"col-5 col-md-3\"><input type=\"text\" id=\"workorder_short\" name=\"workorder_short\" class=\"form-control\" ";
if (isset($_GET['modify']))
echo "VALUE='".$row['workorder_short']."'";
echo " required></div>\n";
echo "</div>";   
 
 
echo "<div class=\"row form-group\">";
    echo "<div class=\"col-5 col-md-2\">\n";
        echo "<label for=\"request_type\" class=\" form-control-label\">".gettext("Activity type:")."</label>";
    echo "</div>\n";

    echo "<div class=\"col-5 col-md-3\">";
        echo "<select name=\"request_type\" id=\"request_type\" class=\"form-control\" >";
     foreach($activity_types as $id => $activity_type) //$activity_types from config/lm-settings.php
     {
     echo "<option value=\"".++$id."\"";
     if (isset($_GET['modify']) && $id==$row['request_type'])
     echo " selected";
     echo ">".$activity_type."</option>\n";
      }  
        echo "</select>\n";
    echo "</div>";
echo "</div>"; 

if (isset($_GET['modify']) && $row["product_id_to_refurbish"]>0)
        {
            echo "<div class=\"row form-group\">\n";
            echo "<div class=\"col col-md-2\"><label for=\"product_id_to_refurbish\" class=\"form-control-label\">".gettext("Refurbish:")."</label></div>\n";
            echo "<div class=\"col-12 col-md-3\">".get_product_name_from_id($row["product_id_to_refurbish"],$lang)."</div></div>\n";
             echo "<INPUT TYPE=\"hidden\" name=\"product_id_to_refurbish\" id=\"product_id_to_refurbish\" VALUE=\"".$row["product_id_to_refurbish"]."\">";
        }else
        echo "<INPUT TYPE=\"hidden\" name=\"product_id_to_refurbish\" id=\"product_id_to_refurbish\" VALUE=\"0\">";


if ((isset($_GET['modify']) && $row['asset_id']>0) || (isset($_GET['asset_id']) && $_GET['asset_id']>0)){
$SQL="SELECT product_id FROM stock WHERE stock_location_asset_id=";
if (isset($_GET['modify']))
$SQL.= $row['asset_id'];
else
$SQL.= (int) $_GET['asset_id'];
$SQL.= " AND stock_quantity>0";
$result=$dba->Select($SQL);
if ($dba->affectedRows()>0)
    {
    echo "<div class=\"row form-group\">\n";
    echo "<div class=\"col col-md-2\"><label class=\"form-control-label\">\n";
    echo gettext("The part built in:");
    echo "</label></div>\n";
   
    echo "<div class=\"col col-md-2\">";
    
    foreach ($result as $row1){
    echo get_product_name_from_id($row1['product_id'],$lang)."\n";
      
    }
    
    echo "</div>\n";
    echo "</div>\n";
    }
}    
$SQL="SELECT * FROM assets WHERE asset_id=";
if (isset($_GET['modify']))
$SQL.=$row['asset_id'];
else
$SQL.= (int) $_GET['asset_id'];
$row1=$dba->getRow($SQL);
if (LM_DEBUG)
    error_log("connections ".$SQL,0);
$products_can_connect=array();
foreach($row1 as $key => $value)
    {
    if (strstr($key,"connection_id") && $value>0)
        $products_can_connect=array_merge(get_products_id_can_connect($value,$row1['connection_type'.substr($key,13)]),$products_can_connect);
    
    }
    
if (count($products_can_connect)>0){
//remove the part from the list if it is not at stock
foreach ($products_can_connect as $k=>$pr){
$SQL="SELECT 1 FROM stock WHERE product_id=".$pr." AND stock_location_id=0";
$r=$dba->Select($SQL);

if ($dba->affectedRows()>0){
unset($products_can_connect[$k]);

}
}

}    
if (count($products_can_connect)>1)
{
    echo "<div class=\"row form-group\">\n";
    echo "<div class=\"col col-md-2\"><label for=\"replace_to_product_id\" class=\"form-control-label\">\n";
    echo gettext("Replace to:");
    echo "</label></div>\n";
   
echo "<div class=\"col col-md-3\">";
    echo "<select name=\"replace_to_product_id\" id=\"replace_to_product_id\" class=\"form-control\">";
    echo "<option value='0'>".gettext("No");
    foreach ($products_can_connect as $pr){
        if ($pr!=$row1['asset_product_id'])
        {
        echo "<option value='".$pr."'";
        if (isset($_GET['modify']) && $pr==$row['replace_to_product_id'])
        echo " selected";
        echo ">".get_product_name_from_id($pr,$lang);
        $SQL="SELECT 1 FROM workorders WHERE replace_to_product_id=".$pr." AND workorder_status<5";
        if (isset($_GET['modify']))
        $SQL.=" AND workorder_id!=".$row["workorder_id"];
        $res=$dba->Select($SQL);
        if ($dba->affectedRows()>0)
        echo " ".gettext("THERE IS AN ACTIVE WORKORDER FOR THIS PRODUCT!");
        echo "\n";
        }   
    }  
    echo "</select>\n";
    echo "</div>\n";
    echo "</div>\n";

}
else
echo "<INPUT TYPE=\"hidden\" name=\"replace_to_product_id\" id=\"replace_to_product_id\" VALUE=\"0\">";

echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-2\"><label for=\"workorder\" class=\" form-control-label\">".gettext("Workorder:")."</label></div>";
echo "<div class=\"col-12 col-md-9\"><textarea name=\"workorder\" id=\"workorder\" rows=\"9\" placeholder=\"".gettext("workorder")."\" class=\"form-control\">";
if (isset($_GET['modify']))
echo $row['workorder'];
echo "</textarea></div>\n";
echo "</div>\n";

//echo "<input type=\"file\" accept=\"image/*\" capture=\"camera\" />";

echo "<INPUT TYPE=\"hidden\" name=\"page\" id=\"page\" VALUE=\"workorders\">";
echo "<input type=\"hidden\" name=\"valid\" id=\"valid\" value=\"".$_SESSION["tit_id"]."\">";

if (isset($_GET['modify']))
{
echo "<INPUT TYPE=\"hidden\" name=\"modify_workorder\" id=\"modify_workorder\" VALUE=\"1\">";
echo "<INPUT TYPE=\"hidden\" name=\"workorder_id\" id=\"workorder_id\" VALUE=\"".(int) $_GET["workorder_id"]."\">";
}
if (isset($_GET['new']))
echo "<INPUT TYPE=\"hidden\" name=\"new_workorder\" id=\"new_workorder\" VALUE=\"1\">";

echo "<INPUT TYPE=\"hidden\" name=\"employee_ids\" id=\"employee_ids\" VALUE=\"\">";
echo "<INPUT TYPE=\"hidden\" name=\"not_employee_ids\" id=\"not_employee_ids\" VALUE=\"\">";


if (isset($_GET['asset_id']))
$asset_id=$_GET['asset_id'];
else if (isset($row['asset_id']) && $row['asset_id']>0)
$asset_id=$row['asset_id'];
echo "<INPUT TYPE=\"hidden\" name=\"asset_id\" id=\"asset_id\" VALUE=\"".$asset_id."\">";
if (isset($_GET['location_id']))
$location_id=$_GET['location_id'];
else if (isset($row['location_id']) && $row['location_id']>0)
$location_id=$row['location_id'];
echo "<INPUT TYPE=\"hidden\" name=\"location_id\" id=\"location_id\" VALUE=\"".$location_id."\">";
echo "<div class=\"card-footer\"><button type=\"submit\" class=\"btn btn-primary btn-sm\">\n";
echo "<i class=\"fa fa-dot-circle-o\"></i> Submit </button>\n";
echo "<button type=\"reset\" class=\"btn btn-danger btn-sm\"><i class=\"fa fa-ban\"></i> Reset </button></div>\n";
echo "</form></div>";
echo "<script>\n";
echo "$( document ).ready(function() {
employees_to_json();
});";


echo "$(\"#workorder_form\").validate({
  rules: {
    workorder_short: {
      required: true,
      maxlength: ".$dba->get_max_fieldlength('workorders','workorder_short')."
    },
    workorder: {
      maxlength: ".$dba->get_max_fieldlength('workorders','workorder')."
    }
  }
})\n";
echo "</script>\n";
}




echo "<div class=\"card\">";
echo "<div class=\"card-header\">";
echo "<h2 style='display:inline;'>".gettext("Workorders")." </h2>";

?>
</div>
<div class="card-body">
<table id="workorder_table" class="table table-striped table-bordered">
<thead>
<tr>

<?php
$there_no_partner_at_all=true;
$pagenumber=lm_isset_int('pagenumber');
if ($pagenumber<1)
$pagenumber=1;

if (!isset($_POST["workorder_work"]))
{
$main_asset_id=lm_isset_int('main_asset_id');
 if (isset($_GET["main_asset_id"]) && $_GET["main_asset_id"]=='all')
    unset($_SESSION['main_asset_id']);
     
 else if ($main_asset_id>0)
    $_SESSION['main_asset_id']=$main_asset_id;

    else if(isset($_POST['asset_id'])){
    $_SESSION['main_asset_id']=get_whole_path("asset",$_POST['asset_id'],1)[0];}
   
}

$SQL="SELECT";
//if (!isset($_SESSION['main_asset_id']))
$SQL.=" DISTINCT";
$SQL.=" main_asset_id,asset_name_".$lang;
$i=1;
    $SQL1="SELECT user_id FROM users WHERE active=1";
    $result1=$dba->Select($SQL1);
   $employees=array(); 
    foreach ($result1 as $row1)
    {
    $employees[]=$row1['user_id'];
    //03.21 $SQL.=',employee_id'.$row1['user_id'];

    }
$SQL.=" FROM workorders LEFT JOIN assets ON workorders.main_asset_id=assets.asset_id";
$SQL.=" WHERE 1=1";

$asset_id=lm_isset_int('asset_id');
if ($asset_id>=0)
$_SESSION['asset_id']=$asset_id;

if ($_SESSION['asset_id']>0 && !isset($_GET['new']) && !isset($_POST['modify_workorder']) && !isset($_POST['new_workorder']) && !isset($_POST["workorder_work"])){
$SQL.=" AND workorders.asset_id=".$_SESSION['asset_id'];}

if (!isset($_SESSION['workorder_status']))
$_SESSION['workorder_status']=1;

if (!isset($_POST["workorder_work"])){
$workorder_status=lm_isset_int('workorder_status');

if ($workorder_status>0)
$_SESSION['workorder_status']=$workorder_status;
else if (isset($_GET['workorder_status']) && $_GET['workorder_status']==0){
$_SESSION['workorder_status']=0;}
}
//echo  $workorder_statuses[$_SESSION['workorder_status']];

/*
$workorder_status=lm_isset_int('workorder_status');
if ($workorder_status>0)
$_SESSION['workorder_status']=$workorder_status;

if ($_SESSION['workorder_status']>=0)
{
    $SQL.=" AND workorder_status=".$_SESSION['workorder_status'];
}
*/

if (isset($_SESSION['main_asset_id']) && $_SESSION['main_asset_id']>0)
{
    $SQL.=" AND main_asset_id=".$_SESSION['main_asset_id'];
}
else if (isset($_GET['new']) && isset($_GET['asset_id'])){
$SQL.=" AND main_asset_id=".get_whole_path("asset",$_GET['asset_id'],1)[0];

}
else if (isset($_GET['main_asset_id']) && $_GET["main_asset_id"]!='all')
    $SQL.=" AND  product_id_to_refurbish>0";
/*
if (!isset($_SESSION['show_all_workorders']))
    $SQL.=" AND workorder_status<5";
*/

if ((isset($_SESSION['workorder_status']) && $_SESSION['workorder_status']==1) || isset($_GET['new']))
    $SQL.=" AND workorder_status<5";
else if(isset($_SESSION['workorder_status']) && $_SESSION['workorder_status']>1)
    $SQL.=" AND workorder_status=".$_SESSION['workorder_status'];

if ($_SESSION['user_level']>2){
$SQL.=" AND (employee_id".$_SESSION['user_id']."=1";
$SQL.=" OR workorder_partner_supervisor_user_id=".$_SESSION['user_id'].")";
}


$SQL.=" ORDER BY asset_name_".$lang;

if (!isset($_SESSION['main_asset_id'])){

$result_all=$dba->Select($SQL);
$number_all=$dba->affectedRows();
$from=($pagenumber-1)*ROWS_PER_PAGE;
$SQL.=" limit $from,".ROWS_PER_PAGE;
}
$result=$dba->Select($SQL);

if (LM_DEBUG)
error_log($SQL,0);

echo "<th colspan='2'>";
?>
 <button class="btn btn-secondary dropdown-toggle" type="button" id="wo_stat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            <?php 
                            if (isset($_SESSION['workorder_status']) && $_SESSION['workorder_status']>0){
                            echo " STYLE=\"background-color:orange;\"";
                            }
                            echo ">";
                               
if (isset($_SESSION['workorder_status']))
echo  $workorder_statuses[$_SESSION['workorder_status']-1];
else
echo gettext("Stat.");
                            ?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="wo_stat">
<?php 
echo "<a class=\"dropdown-item media bg-flat-color-10\" href=\"index.php?page=workorders&workorder_status=0\">\n";
echo "<i class=\"fa fa-warning\"></i>\n";
echo "<p>".gettext("All")."</p></a>";

foreach ($workorder_statuses as $key => $value){
echo "<a class=\"dropdown-item media bg-flat-color-10\"";
if (isset($_SESSION['workorder_status']) && $_SESSION['workorder_status']==($key+1))
echo " style=\"background-color:orange;\"";
echo " href=\"index.php?page=workorders&workorder_status=".($key+1)."\">\n";
echo "<i class=\"fa fa-warning\"></i>\n";

echo $value."</a>";
                            
}

?>
                           
                            </div>
<?php
echo gettext("Date")."</th>";
if ((!lm_isset_int('asset_id')>0 || isset($_POST['modify_workorder']) || isset($_POST['new_workorder']) || isset($_POST["workorder_work"])) && !isset($_GET['new'])){
        echo "<th";
        if (isset($_SESSION['main_asset_id']) && $_SESSION['main_asset_id']>0)
        echo " STYLE=\"background-color:orange\"";
        echo ">".gettext("Asset");
        $SQL="SELECT DISTINCT main_asset_id, asset_name_".$lang." FROM workorders LEFT JOIN assets on assets.asset_id=workorders.main_asset_id ORDER BY asset_name_".$lang;
            $result1=$dba->Select($SQL);
            echo " <select name=\"main_asset_id\" id=\"main_asset_id\" class=\"form-control\"";
                    echo " onChange=\"{location.href='index.php?page=workorders&main_asset_id='+this.value;
                    };\"";
                    echo " style='display:inline;width:200px;'>\n";
            echo "<option value='all'>".gettext("All assets");
            foreach($result1 as $row1){
            echo "<option value='".$row1['main_asset_id']."'";
            if (isset($_SESSION['main_asset_id']) && $row1['main_asset_id']==$_SESSION['main_asset_id'] )
            echo " selected";
            echo ">";
            if ($row1['main_asset_id']==0)
            echo gettext("Refurbish");
            else
            echo $row1['asset_name_'.$lang]."\n";
            }
            echo "</select>\n"; 

        echo "</th>";
}
$e=0;
$user_column_to_hide=$employees;
foreach ($employees as $user_id){
    echo "<th class='user_".$user_id."'>".get_username_from_id($user_id)."</th>";
    $e++;
    }
echo "<th class='partner_col'>".gettext("Company")."</th>";
echo "<th>".gettext("Workorder")."</th></tr>";
?>
</thead>
<tbody>
<?php
$main_asset_id="";
$request_type="";
if (!empty($result)){
    foreach ($result as $row)
    {
       
        if ($row['main_asset_id']!=$main_asset_id && !isset($_SESSION['main_asset_id']))
        
        {
            echo "<tr class=\"table-primary\" STYLE=\"border-left: 0.25em solid;border-left-color:#0275d8;border-top: 10px solid;border-top-color: #0275d8;border-radius: 0.4em;\" onClick=\"visibility('workorder_".$row['main_asset_id']."')\">";
            echo "<td colspan=".(5+$e).">";
           
            $main_asset_id=$row['main_asset_id'];
          if ($row['main_asset_id']>0)
            echo get_asset_name_from_id($row['main_asset_id'],$lang);
            else 
            echo gettext("Refurbish");;
     
            $SQL="SELECT COUNT(workorder_id) as count FROM workorders WHERE main_asset_id=".$row['main_asset_id']."  AND workorder_status<5";
            if ($_SESSION['user_level']>2){
            $SQL.=" AND (employee_id".$_SESSION['user_id']."=1";
            $SQL.=" OR workorder_partner_supervisor_user_id=".$_SESSION['user_id'].")";
            }
            $row1=$dba->getRow($SQL);
            echo " ".$row1['count'];
           $SQL="SELECT COUNT(workorder_id) as count FROM workorders WHERE main_asset_id=".$row['main_asset_id']."  AND workorder_status=0";
            if ($_SESSION['user_level']>2){
            $SQL.=" AND (employee_id".$_SESSION['user_id']."=1";
            $SQL.=" OR workorder_partner_supervisor_user_id=".$_SESSION['user_id'].")";
            }
            $row1=$dba->getRow($SQL);
            echo " / <span style=\"color:red;\">".$row1['count']."</span>";
            echo "</td></tr>\n";
             }
             //list of child workorders
                        $SQL="SELECT workorder_id,main_asset_id,asset_id,main_location_id,location_id,workorder_short,workorder_time,workorder_status,request_type,workrequest_id,workorder_partner_id,product_id_to_refurbish,priority";
                        $SQL1="SELECT COLUMN_NAME as info FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name ='workorders'
                        AND table_schema = '".DATABASE."' AND column_name LIKE 'info_id%'";
                        $result1=$dba->Select($SQL1);
                        if (!empty($result1)){
                        foreach ($result1 as $row1){
                        $SQL.=",".$row1['info'];
                        }}
                        
                        $i=1;
                        $SQL1="SELECT user_id FROM users WHERE active=1";
                        $result1=$dba->Select($SQL1);
                        $employees=array(); 
                        foreach ($result1 as $row1)
                        {
                        $employees[]=$row1['user_id'];
                        $SQL.=',employee_id'.$row1['user_id'];

                        }
                        $SQL.=" FROM workorders";
                        $SQL.=" WHERE 1=1";

                        if ($asset_id>0 && !isset($_POST['modify_workorder']) && !isset($_GET['new']) && !isset($_POST["workorder_work"]))
                        $SQL.=" AND asset_id=".$asset_id;

                       // if ($_SESSION['workorder_status']>0)
                        //$SQL.=" AND workorder_status=".$_SESSION['workorder_status'];
                            

                     //03.21   if (isset($_SESSION['main_asset_id']) && $_SESSION['main_asset_id']>0)
                        //{
                            $SQL.=" AND main_asset_id=".$row['main_asset_id'];
                        //}
                        if ($row['main_asset_id']==0)
                            $SQL.=" AND  product_id_to_refurbish>0";

                        
                    if (isset($_SESSION['workorder_status']) && $_SESSION['workorder_status']==1)
                        $SQL.=" AND workorder_status<5";
                    else if(isset($_SESSION['workorder_status']) && $_SESSION['workorder_status']>0)
                        $SQL.=" AND workorder_status=".$_SESSION['workorder_status'];

                        if ($_SESSION['user_level']>2){
                        $SQL.=" AND (employee_id".$_SESSION['user_id']."=1";
                        $SQL.=" OR workorder_partner_supervisor_user_id=".$_SESSION['user_id'].")";
                        }
                        
                       $SQL.=" ORDER BY workorder_time DESC";
                      
                        if (isset($_SESSION['main_asset_id'])){
                        $result_all=$dba->Select($SQL);
                        $number_all=$dba->affectedRows();
                        $from=($pagenumber-1)*ROWS_PER_PAGE;
                        $SQL.=" limit $from,".ROWS_PER_PAGE;
                        }
                        if (LM_DEBUG)
                        error_log($SQL,0);
                        $result1=$dba->Select($SQL);

  foreach($result1 as $row1){  
       
      
                                echo "<tr";
                        
                        echo " class=\"workorder_".$row['main_asset_id']."\"";
                        if (!isset($_SESSION['main_asset_id']))
                        echo " STYLE='display:none;'";
                        //else
                
                        echo ">\n<td";
                        echo " STYLE=\"border-left: 0.25em solid;border-left-color: #0275d8;\"";
                        if (5==$row1['workorder_status'])
                        echo " class='bg-flat-color-5'";
                        else if (0==$row1["workorder_status"])
                        echo " class='bg-flat-color-4'";
                        echo ">\n<div class='d-flex justify-content-between'>";
                        //echo "->";
                        if ($row1['priority']==1)
                        echo "! ";
                        if (5>$row1['workorder_status'])
                        echo "<a href=\"index.php?page=works&new=1&workorder_id=".$row1['workorder_id']."\" title=\"".gettext("add new activity")."\"> <i class=\"fa fa-clock-o\" style='color:blue'></i></a> ";
                    
                       
                    
                        if (isset($_SESSION['TAKE_PRODUCT_FROM_STOCK']))
                        echo "<a href=\"javascript:ajax_call('product_to_workorder','','','','".$row1["workorder_id"]."','".URL."index.php','for_ajaxcall')\" title=\"".gettext("Product to workorder")."\"> <i class=\"fa fa-cart-plus\" style='color:red'></i></a> ";
             
             $info_exist=0;
             foreach($row1 as $key=>$value){
		if (strstr($key,"info_file_id") && $value>0)
		$info_exist++;
		}
		
                    
                        if (isset($_SESSION["SEE_FILE_OF_WORKORDER"]) && $info_exist>0){
                                        
                        echo "<a href=\"javascript:ajax_call('show_info_files','".$row1["workorder_id"]."','assets','','','".URL."index.php','for_ajaxcall')\" title=\"".gettext("Show files")."\"> <i class=\"fa fa-file\" style='color:grey'></i> ";
                                            echo "</a>\n";}
                        if (isset($_SESSION["MODIFY_WORKORDER"])){
                        echo "<a href=\"index.php?page=workorders&modify=1&workorder_id=".$row1['workorder_id']."\" title=\"".gettext("alter workorder")."\"> <i class=\"fa fa-wrench\" style='color:brown'></i></a> ";
                        }
                                            
                        
                        

                        echo "</div></td><td";
                    
                        //echo " onClick=\"visibility('workorder_".$row1['main_asset_id']."')\"";
                        echo ">\n";
                        if ($lang=="hu")
                        echo date("Y.m.d", strtotime($row1["workorder_time"]))."</td>\n";
                        else
                            echo date("m.d.y", strtotime($row1["workorder_time"]))."</td>\n";
                            if (!lm_isset_int('asset_id')>0 || isset($_POST["workorder_work"])|| isset($_POST['modify_workorder']))
                            {
                                echo "<td>";
                                if (5>$row1['workorder_status'])
                                echo "<a href=\"index.php?page=works&new=1&workorder_id=".$row1['workorder_id']."\" title=\"".gettext("add new activity")."\">";
                                if ($row1['asset_id']>0){
                                $k="";
                                $n="";

                                foreach (get_whole_path("asset",$row1['asset_id'],1) as $k){
                                    if ($n=="") // the first element is the main asset_id -> ignore it
                                    $n=" ";
                                    else
                                    $n.=$k."-><wbr>";
                                }
                                
                                echo substr($n,0,-7);
                                }else if ($row1['product_id_to_refurbish']>0)
                                echo gettext("Refurbish").": ".get_product_name_from_id($row1['product_id_to_refurbish'],$lang);
                                //echo " ".$row1['workorder_short'];
                                if ($row1['workrequest_id']>0 && $row1['request_type']==1){
                                $SQL2="SELECT finish_time FROM finished_workrequests WHERE workrequest_id=".$row1['workrequest_id']." ORDER BY finish_time DESC LIMIT 0,1";
                                $row2=$dba->getRow($SQL2);
                                if (!(empty($row2['finish_time'])))
                                echo " <small style=\"color:red;\" title='".gettext("Last")."'>".date("Y.m.d", strtotime($row2['finish_time']))."</small>";
                                }
                                
                                if (5>$row1['workorder_status'])
                                echo "</a>";
                                echo "</td>\n";
                            }
                        
                            foreach ($employees as $user_id)
                            {
                            if ($row1['employee_id'.$user_id]==1){
                            if (($key= array_search($user_id,$user_column_to_hide)) !==false )
                            unset($user_column_to_hide[$key]);
                            echo "<td>X</td>";
                            }
                            else
                            echo "<td class='user_".$user_id."'></td>";
                            }
                            
                        echo "<td class='partner_col'>";
                        if ($row1['workorder_partner_id']>0)
                        {
                        $there_no_partner_at_all=false;
                        echo get_partner_name_from_id($row1['workorder_partner_id']);
                        }
                        echo "</td>";
                            echo "<td>";
                             if (isset($_SESSION['SEE_WORKORDER_DETAIL']))
                        echo "<a href=\"javascript:ajax_call('show_workorder_detail','".$row1["workorder_id"]."','".$row1['asset_id']."','','','".URL."index.php','for_ajaxcall')\" title=\"".gettext("Show details")."\"><i class='fa fa-info-circle'></i> ".$row1['workorder_short']."</a></td></tr> ";
                        else
                            echo $row1['workorder_short']."</td></tr>\n";
                    
                  }     
                        
    }
}else
echo "<tr><td colspan=5>".gettext("No data")."</td></tr>";
echo "</tbody>\n</table>\n</div>\n";


    echo "<script>\n";
    if ($there_no_partner_at_all)
   echo "$(\".partner_col\").hide();";
    
    if (!empty($user_column_to_hide))
    {
    foreach ($user_column_to_hide as $value)
    echo  "$(\".user_".$value."\").hide();";
    
    }
    echo "</script>\n";
    include(INCLUDES_PATH."pagination.php");
 
    echo "</div>"; //card
?>





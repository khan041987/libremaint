
<?php
if (isset($_POST['connection_name_en']) && is_it_valid_submit() && isset($_SESSION['ADD_NEW_CONNECTION_TYPE'])){

$SQL="INSERT INTO connections (connection_name_en,connection_name_".$lang.",connection_type,connection_category_id,connection_review_en,connection_review_".$lang.") VALUES ";
$SQL.="('".$dba->escapeStr($_POST['connection_name_en'])."',";
$SQL.="'".$dba->escapeStr($_POST['connection_name_'.$lang])."',";
$SQL.=(int) $_POST['connection_type'].",";
$SQL.=(int) $_POST['connection_category_id'].",";
$SQL.="'".$dba->escapeStr($_POST['connection_review_en'])."',";
$SQL.="'".$dba->escapeStr($_POST['connection_review_'.$lang])."'";
$SQL.=")";


if ($dba->Query($SQL))
        lm_info(gettext("The new connection has been saved."));
                else
        lm_info(gettext("Failed to save new connection.".$dba->err_msg));
if (LM_DEBUG)
error_log($SQL,0);
}

if (isset($_GET["new"]) ){
?>
<div class="card">
<div class="card-header">
<strong><?php echo gettext("New connection");?></strong>
</div><?php //card header ?>
<div class="card-body card-block">
<form action="index.php" id="conn_form" method="post" enctype="multipart/form-data" class="form-horizontal">

<?php
if (!isset($_SESSION['ADD_NEW_CONNECTION_TYPE']))
lm_die(gettext("You have no permission!"));
    
echo "<div class=\"row form-group\">\n";
        echo "<div class=\"col col-md-2\"><label for=\"connection_category_id\" class=\"form-control-label\">".gettext("Connection category:")."</label></div>\n";
        echo "<div class=\"col-8 col-md-6\">";
        echo "<select name=\"connection_category_id\" id=\"connection_category_id\" class=\"form-control\"";
        echo " required>\n";
        $SQL="SELECT connection_category_".$lang.", connection_category_id FROM connection_categories ORDER BY connection_category_".$lang;
        if (LM_DEBUG)
        error_log($SQL,0);
        $result=$dba->Select($SQL);
        echo "<option value=\"\">".gettext("Please select")."</option>\n";
        foreach ($result as $row)
        {
            echo "<option value=\"".$row["connection_category_id"]."\"";
           
            echo ">".$row["connection_category_".$lang]."</option>\n";
        }
        echo "</select></div></div>";

echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-2\"><label for=\"connection_name_en\" class=\"form-control-label\">".gettext("Connection name en:")."</label></div>\n";
echo "<div class=\"col-8 col-md-6\"><input type=\"text\" id=\"connection_name_en\" name=\"connection_name_en\" placeholder=\"".gettext("connection name en")."\" class=\"form-control\" required></div>\n";
echo "</div>";

if ($lang!="en"){
echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-2\"><label for=\"connection_name_".$lang."\" class=\"form-control-label\">".gettext("Connection name:")."</label></div>\n";
echo "<div class=\"col-8 col-md-6\"><input type=\"text\" id=\"connection_name_".$lang."\" name=\"connection_name_".$lang."\" placeholder=\"".gettext("connection name")."\" class=\"form-control\" required></div>\n";
echo "</div>";
}
echo "<div class=\"row form-group\">\n";
                echo "<div class=\"col col-md-2\"><label for=\"connection_type\" class=\"form-control-label\">".gettext("Connection type:")."</label></div>\n";
                echo "<div class=\"col col-md-2\">";
                echo "<select name=\"connection_type\" id=\"connection_type\" class=\"form-control\">\n";
                $i=1;
                //$connection_types from lm-settings.php
                foreach ($connection_types as $connection_type)
                echo "<option value=\"".$i++."\">".$connection_type."</option>\n";
                                
                echo "</select>";
                echo "</div>\n";
echo "</div>\n";

echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-2\"><label for=\"connection_review_en\" class=\"form-control-label\">".gettext("Connection review en:")."</label></div>\n";
echo "<div class=\"col-8 col-md-6\"><input type=\"text\" id=\"connection_review_en\" name=\"connection_review_en\" placeholder=\"".gettext("connection review en")."\" class=\"form-control\"></div>\n";
echo "</div>";

echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-2\"><label for=\"connection_review_".$lang."\" class=\"form-control-label\">".gettext("Connection review:")."</label></div>\n";
echo "<div class=\"col-8 col-md-6\"><input type=\"text\" id=\"connection_review_".$lang."\" name=\"connection_review_".$lang."\" placeholder=\"".gettext("connection review")."\" class=\"form-control\"></div>\n";
echo "</div>";


echo "<INPUT TYPE=\"hidden\" name=\"page\" id=\"page\" value=\"connections\">";
echo "<input type=\"hidden\" name=\"valid\" id=\"valid\" value=\"".$_SESSION["tit_id"]."\">";

echo "<div class=\"card-footer\"><button type=\"submit\" class=\"btn btn-primary btn-sm\">\n";
echo "<i class=\"fa fa-dot-circle-o\"></i> Submit </button>\n";
echo "<button type=\"reset\" class=\"btn btn-danger btn-sm\"><i class=\"fa fa-ban\"></i> Reset </button></div>\n";
echo "</form></div>";
echo "<script>\n";
echo "$(\"#conn_form\").validate()\n";
echo "</script>\n";
}

if (isset($_SESSION['SEE_CONNECTION_TYPE'])){
$pagenumber=lm_isset_int('pagenumber');
if ($pagenumber<1)
$pagenumber=1;
$from=1;
$SQL="SELECT connection_id,connection_category_".$lang.",connection_name_".$lang.",connection_review_".$lang.",connection_type FROM connections LEFT JOIN connection_categories ON connection_categories.connection_category_id=connections.connection_category_id ORDER BY connection_category_".$lang;
$result_all=$dba->Select($SQL);
$number_all=$dba->affectedRows();
$from=($pagenumber-1)*ROWS_PER_PAGE;
$SQL.=" limit $from,".ROWS_PER_PAGE;
$result=$dba->Select($SQL);
if (LM_DEBUG)
error_log("page:".$pagenumber." ".$SQL,0);

?>
<div id='for_ajaxcall'>
</div>
<div class="card-body">
<table id="bootstrap-data-table" class="table table-striped table-bordered">
<thead>
<tr>
<th></th>
<?php 


echo "<th>".gettext("Connection category")."</th><th>".gettext("Connection name")."</th>";
echo "<th>".gettext("Connection type")."</th>";
echo "<th>".gettext("Connection review")."</th></tr>";
?>
</thead>
<tbody>
<?php

foreach ($result as $row)
{
$from++;
echo "<tr><td>";
echo "<div class=\"user-area dropdown float-right\">\n";
                            
                             echo "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
                             echo $from;
                             echo "</a>\n";
                             
                            echo "<div class=\"user-menu dropdown-menu\">";

                             if (isset($_SESSION["SEE_CONNECTION_OF_PRODUCT"]) && isset($_SESSION["SEE_CONNECTION_OF_ASSET"])){
                            echo "<a class=\"nav-link\" href=\"javascript:ajax_call('show_assets_products_with_this_connection','".$row['connection_id']."','','','','".URL."index.php','for_ajaxcall')\"><i class=\"fa fa-user\"></i> ";
                             echo gettext("Show assets, product with this connection")."</a>";}
                       
                             echo "</div>";
    echo "</div>";
 
echo "</td><td>".$row['connection_category_'.$lang]."</td>\n";
echo "<td>".$row['connection_name_'.$lang]."</td>\n";
echo "<td>";
if ($row['connection_type']<3)
echo gettext("Male-female");
else
echo gettext("Same");
echo "</td>\n";

echo "<td>".$row['connection_review_'.$lang]."</td>\n";
echo "</tr>\n";

}
echo "</tbody></table></div>";
include(INCLUDES_PATH."pagination.php");
}
else
echo gettext("You have no permission!");
?>



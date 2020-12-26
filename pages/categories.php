
<div id='for_ajaxcall'>
</div>
<?php 
if (isset($_POST['page']) && isset($_POST['category_parent_id']) && is_it_valid_submit()){ //it is from the form
$SQL="INSERT INTO categories (";
if (ENGLISH_AS_SECOND_LANG && $lang!="en")
$SQL.="category_name_en,";

$SQL.="category_name_$lang,category_parent_id) VALUES (";
if (ENGLISH_AS_SECOND_LANG && $lang!="en")
$SQL.="'".$_POST["category_name_en"]."',";
$SQL.="'".$_POST["category_name_".$lang]."',".(int)$_POST["category_parent_id"].")";
if (LM_DEBUG_LOG)
error_log($SQL,0); 
if ($dba->Query($SQL))
echo "<div class=\"card\">".gettext("The new category has been saved.")."</div>";
else
echo "<div class=\"card\">".gettext("Failed to save new category ").$dba->err_msg."</div>";

}

else if (isset($_POST['page']) && isset($_POST["new_name_".$lang]) && !empty($_POST["new_name_".$lang]) && is_it_valid_submit()){ //it is from the rename category form
    $SQL="UPDATE categories SET category_name_".$lang."='".$_POST["new_name_".$lang]."'";
    if (isset($_POST['new_name_en']) && !empty($_POST['new_name_en']))
    $SQL.=",category_name_en='".$dba->escapeStr($_POST["new_name_en"])."'";
   
    $SQL.=" WHERE category_id='".(int) $_POST["category_id"]."'";
    if (LM_DEBUG)
        error_log($SQL,0); 
    if ($dba->Query($SQL))
        echo "<div class=\"card\">".gettext("The category has been renamed.")."</div>";
        else
        echo "<div class=\"card\">".gettext("Failed to rename category ").$dba->err_msg."</div>";
}

else if (isset($_GET["new"])){
?>

<div class="card">
<div class="card-header">
<strong><?php echo gettext("New category");?></strong>
</div><?php //card header ?>
<div class="card-body card-block">
<form action="index.php" id="category_form" method="post" enctype="multipart/form-data" class="form-horizontal">

<?php
//if ($_GET["new"]=="category" && isset($_GET["parent_id"]) && ($_GET["parent_id"]>0)){
//$SQL="SELECT category_name_$lang FROM categories WHERE category_id='".$_GET["parent_id"]."'";


    echo "<div class=\"row form-group\">";
    echo "<div class=\"col col-md-3\"><label for=\"category_parent_id\" class=\" form-control-label\">".gettext("Parent category:")."</label></div>";

    echo "<div class=\"col col-md-2\">";
    echo "<select name=\"category_parent_id\" id=\"category_parent_id\" class=\"form-control\">\n";
    $SQL="SELECT category_id, category_name_en, category_name_".$lang." FROM categories WHERE category_parent_id=0";
    $SQL.=" ORDER BY category_name_".$lang;
    error_log($SQL,0);
    $result=$dba->Select($SQL);
    echo "<option value=\"0\">".gettext("Please select")."</option>\n";
    foreach ($result as $row){
    if ($row["category_name_".$lang]!="")
    {
    echo "<option value=\"".$row["category_id"]."\"";
    if (isset($_GET['parent_id']) && $_GET['parent_id']==$row["category_id"])
    echo " selected";
    echo ">".$row["category_name_".$lang]."</option>\n";
     }
    else
    {
    echo "<option value=\"".$row["category_id"]."\"";
    if (isset($_GET['parent_id']) && $_GET['parent_id']==$row["category_id"])
    echo " selected";
    echo ">".$row["category_name_en"]."</option>\n";
    }
    }
    echo "</select></div></div>";
  
if (ENGLISH_AS_SECOND_LANG && $lang!="en"){  
echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-3\"><label for=\"category_name_en\" class=\"form-control-label\">".gettext("Category name (English):")."</label></div>\n";
echo "<div class=\"col-12 col-md-9\"><input type=\"text\" id=\"category_name_en\" name=\"category_name_en\" placeholder=\"".gettext("Category name (English)")."\" class=\"form-control\" required><small class=\"form-text text-muted\">".gettext("Category name")."</small></div>\n";
echo "</div>";}
 
 
echo "<div class=\"row form-group\">";
echo "<div class=\"col col-md-3\"><label for=\"category_name_".$lang."\" class=\"form-control-label\">".gettext("Name:")."</label></div>\n";
echo "<div class=\"col-12 col-md-9\"><input type=\"text\" id=\"category_name_".$lang."\" name=\"category_name_".$lang."\" placeholder=\"".gettext("Name")."\" class=\"form-control\" required><small class=\"form-text text-muted\">".gettext("Category name")."</small></div>\n";
echo "</div>";


?>

</div><?php //card-body card-block  ?>
<div class="card-footer">
<button type="submit" class="btn btn-primary btn-sm">
<i class="fa fa-dot-circle-o"></i><?php echo gettext(" Submit ");?>
</button>
<button type="reset" class="btn btn-danger btn-sm">
<i class="fa fa-ban"></i><?php echo gettext(" Reset ");?>
</button>
</div>
<input type="hidden" name="page" id="page" value="categories">
<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_GET["parent_id"];?>">
<?php
echo "<input type=\"hidden\" name=\"valid\" id=\"valid\" value=\"".$_SESSION["tit_id"]."\">";
?>
</form>
</div>
<?php //card  
echo "<script>\n";
echo "$(\"#category_form\").validate({
  rules: {";
  if (ENGLISH_AS_SECOND_LANG && $lang!="en")

  echo  "category_name_en: {
        required: true,
        maxlength: ".$dba->get_max_fieldlength('categories','category_name_en')."
    }";
   
    echo ",category_name_".$lang.": {
        required: true,
        maxlength: ".$dba->get_max_fieldlength('categories','category_name_'.$lang)."
    }   
  }
})\n";
echo "</script>\n";
}//if (isset($_GET["new"]))


include(INCLUDES_PATH."show_categories_tree_menu.php");
include(INCLUDES_PATH."category_tree.php");

?>

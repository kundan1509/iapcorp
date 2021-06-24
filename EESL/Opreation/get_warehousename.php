<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["warehouseid"])) {	
	$query ="select WareHouseName FROM tblwarehouseregistration where WarehouseId='".$_POST["warehouseid"]."'";
	$results = $db_handle->runQuery($query);
	?>
  <?php
	foreach($results as $Loc) {	
?>

	<?php echo $Loc["WareHouseName"];?>
    
<?php
	
	}
}
?>
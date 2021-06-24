<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["state_Id"])) {	
	//$query ="SELECT warehouseid,warehousecode,WareHouseName FROM tblwarehouseregistration where WareHouseLocationID='".$_POST["location"]."' AND stateid='".$_POST["state_Id"]."'";
        $query ="SELECT warehouseid,warehousecode,WareHouseName FROM tblwarehouseregistration where stateid='".$_POST["state_Id"]."'";
	$results = $db_handle->runQuery($query);
	
?>
	<option value="">Select Warehousecode Code</option>
<?php
	foreach($results as $Loc) {	
?>
	<option value="<?php echo $Loc["warehouseid"]; ?>"><?php echo $Loc["warehousecode"]; ?></option>   
    
<?php
	
	}
}
?>

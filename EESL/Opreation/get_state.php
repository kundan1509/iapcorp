<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["country_id"])) {
	$query ="SELECT * FROM tblcity WHERE RecStatus='A' AND StateId= '" . $_POST["country_id"] . "'";
	$results = $db_handle->runQuery($query);
?>
	<option value="">Select District</option>
<?php
	foreach($results as $state) {
?>
	<option value="<?php echo $state["CityId"]; ?>"><?php echo $state["CityName"]; ?></option>
    
   
<?php
	}
}
?>
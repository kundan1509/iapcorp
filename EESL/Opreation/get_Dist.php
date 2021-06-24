<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["state_Id"])) {	
	$query ="SELECT LocId,LocationName,LocationCode FROM led.tbllocation WHERE RecStatus='A' AND CityId='".$_POST["state_Id"]."' AND LocId NOT IN (SELECT locationid FROM tblvendorlocation WHERE RecStatus='A')";
        echo $query;
	$results = $db_handle->runQuery($query);
?>
	<option value="">Select Location</option>
<?php
	foreach($results as $Loc) {
?>
	<option value="<?php echo $Loc["LocId"]; ?>"><?php echo $Loc["LocationName"]; ?></option>
<?php
	}
}
?>
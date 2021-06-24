<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["Loc_Id"])) {	
	$query ="SELECT v.VendorId,v.VendorCode,v.VendorName,vl.ID,vl.LocationId,l.LocId,l.LocationName,l.LocationCode
FROM tblvendorregistration AS v JOIN tblvendorlocation AS vl ON v.VendorId=vl.VendorId
JOIN tbllocation AS l ON vl.LocationId=l.LocId WHERE  v.RecStatus='A'AND l.LocId='".$_POST["Loc_Id"]."'";
	$results = $db_handle->runQuery($query);
?>
	<option value="">Select Distributor Code</option>
<?php
	foreach($results as $Loc) {	
?>
	<option value="<?php echo $Loc["VendorId"]; ?>"><?php echo $Loc["VendorCode"]; ?></option>    
<?php
	
	}
}
?>
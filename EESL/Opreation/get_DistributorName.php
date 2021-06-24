<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["vendorid"])) {	
	$query ="select VendorName from tblvendorregistration where VendorId='".$_POST["vendorid"]."'";
	$results = $db_handle->runQuery($query);
?>
	<?php
	foreach($results as $Loc) {	
?>
	<?php echo $Loc['VendorName']; ?>
<?php
	
	}
}
?>
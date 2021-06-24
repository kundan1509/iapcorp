<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["acnumber"])) {
	
	
	$acnumber=$_POST['acnumber'];
	$query ="SELECT IFSCCode FROM tblpollaccount WHERE RecStatus='A' AND AccountNo= '$acnumber'";
	$results = $db_handle->runQuery($query);
	
?>
	<option value="">Select Pool Account IFSC Code</option>
<?php
	foreach($results as $state) {
?>
	<option value="<?php echo $state["IFSCCode"]; ?>"><?php echo $state["IFSCCode"]; ?></option>
<?php
	}
}
?>
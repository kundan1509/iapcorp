<?php
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["Refferalid"])) {
	$type=$_POST['Refferalid'];
	
	if($type=='1')
	{
	$query ="SELECT * FROM tblreferral WHERE RecStatus='A' AND ReferralType= 'Employee'";
	$results = $db_handle->runQuery($query);
	
	}
	else
	{
		$query ="SELECT * FROM tblreferral WHERE RecStatus='A' AND ReferralType= 'Consultant'";
	    $results = $db_handle->runQuery($query);
		
	}
?>
	<option value="">Select Referral Name</option>
<?php
	foreach($results as $state) {
?>
	<option value="<?php echo $state["Name"]; ?>"><?php echo $state["Name"]; ?></option>
<?php
	}
}
?>
<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_POST["sid"])) {
	$query ="SELECT sellrate FROM tblstate WHERE RecStatus='A' AND StateId= '" . $_POST["sid"] . "'";
	$results = $db_handle->runQuery($query);
?>
<?php
	foreach($results as $state) {
?>
   <?php echo number_format($state["sellrate"],2);?>
    
   
<?php
	}
}
?>
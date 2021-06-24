<?php
include("../Connection/Connection.php");
$sql = "SELECT emailid FROM tblvendorregistration WHERE emailid = " .$_POST['email'];
$select = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($select);

if (mysqli_num_rows > 0) {
    echo "exist";
}else echo 'notexist';
?>

    
   

	
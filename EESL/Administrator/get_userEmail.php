<?php
include("../Connection/Connection.php");
if(isset($_POST['email']))
{
$email=$_POST['email'];
$query=mysqli_query($con,"SELECT UserId	FROM tbluser WHERE UserId='$email'");
$row=mysqli_num_rows($query);
if($row==0)
{
echo "<span style='color:green; font-size:18px;'>Available</span>";
}
else
{
echo "<span style='color:red; font-size:18px;'>This Email id already exist</span>";
}
}
?>

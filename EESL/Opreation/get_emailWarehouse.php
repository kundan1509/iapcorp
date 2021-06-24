<?php
include("../Connection/Connection.php");
// warehouse registration

if(isset($_POST['email']))
{
$Email=$_POST['email'];
$query1=mysqli_query($con,"SELECT COUNT(*)AS counter, EmailId FROM tbldispatcherregistraion WHERE EmailId='$Email'");
$row1=mysqli_fetch_array($query1);
if($row1['counter']>=1)
{
echo "<span style='color:red; font-size:18px;'>This email id is already exists</span>";
}
else
{
	echo "<span style='color:green; font-size:18px;'>Available</span>";

}
}
if(isset($_POST['Contactno']))
{
$Contact=$_POST['Contactno'];
$query1=mysqli_query($con,"SELECT COUNT(*)AS counter, ContactNumber FROM tbldispatcherregistraion WHERE ContactNumber='$Contact'");
$row1=mysqli_fetch_array($query1);
if($row1['counter']>=1)
{
echo "<span style='color:red; font-size:18px;'>This mobile number is already exists</span>";
}
else
{
	echo "<span style='color:green; font-size:18px;'>Available</span>";

}
}
?>
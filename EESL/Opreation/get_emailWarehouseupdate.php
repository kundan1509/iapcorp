<?php
include("../Connection/Connection.php");
// warehouse registration

if(isset($_POST['email']))
{
$Email=$_POST['email'];
$oldmail=$_POST['oldmail'];
$query1=mysqli_query($con,"SELECT COUNT(*)AS counter, EmailId FROM tbldispatcherregistraion WHERE EmailId <>'$oldmail' AND EmailId='$Email'");
$row1=mysqli_fetch_array($query1);
if($row1['counter']>=1)
{
echo "<span style='color:red; font-size:18px;'>This email id already exists</span>";
}
else
{
	echo "<span style='color:green; font-size:18px;'>Available</span>";

}
}
if(isset($_POST['Contactno']))
{
$Contact=$_POST['Contactno'];
$oldmb=$_POST['oldmobile'];
$query1=mysqli_query($con,"SELECT COUNT(*)AS counter, ContactNumber FROM tbldispatcherregistraion WHERE ContactNumber <>'$oldmb' AND ContactNumber='$Contact'");
$row1=mysqli_fetch_array($query1);
if($row1['counter']>=1)
{
echo "<span style='color:red; font-size:18px;'>This mobile number already exists</span>";
}
else
{
	echo "<span style='color:green; font-size:18px;'>Available</span>";

}
}
?>
<?php
include("../Connection/Connection.php");
if(isset($_POST['name']))
{
$name=$_POST['name'];
$queryexistingmail=$_POST['name1'];



$query=mysqli_query($con,"SELECT emailid FROM tblvendorregistration WHERE emailid <>'$queryexistingmail' AND emailid ='$name'");
$row=mysqli_num_rows($query);
if($row==0)
{
echo "<span style='color:green; font-size:18px;'>Available</span>";
}
else
{
echo "<span style='color:red; font-size:18px;'>This email id already exists</span>";
}
}

if(isset($_POST['pan']))
{
$pan=$_POST['pan'];
$oldpan=$_POST['panold'];
$query=mysqli_query($con,"SELECT panno FROM tblvendorregistration WHERE panno<>'$oldpan' AND panno='$pan'");
$row=mysqli_num_rows($query);
if($row==0)
{
echo "<span style='color:green; font-size:18px;'>Available</span>";
}
else
{
echo "<span style='color:red; font-size:18px;'>This pan card already exists</span>";
}
}

if(isset($_POST['mob']))
{
$pan=$_POST['mob'];
$oldmob=$_POST['mobold'];
$query=mysqli_query($con,"SELECT contactnumber FROM tblvendorregistration WHERE contactnumber<>'$oldmob' AND contactnumber='$pan'");
$row=mysqli_num_rows($query);
if($row==0)
{
echo "<span style='color:green; font-size:18px;'>Available</span>";
}
else
{
echo "<span style='color:red; font-size:18px;'>This moblile number already exist</span>";
}
}



?>





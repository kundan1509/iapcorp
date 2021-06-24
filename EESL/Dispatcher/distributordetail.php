<?php
include("../Connection/Connection.php");
if(isset($_GET['distributorid']))
{
	$distributorid=$_GET['distributorid'];
	$selectqry="SELECT tblvendorregistration.VendorCode,tblvendorregistration.VendorName,tblvendorregistration.ContactNumber,tblvendorregistration.EmailId,tbllocation.Locationname FROM tblvendorregistration,tbllocation,tblvendorlocation WHERE 
tblvendorregistration.VendorId=tblvendorlocation.VendorId AND tbllocation.LocId=tblvendorlocation.LocationId AND tblvendorregistration.recstatus='A' AND tblvendorregistration.vendorid='$distributorid'";
	
	$result=mysqli_query($con,$selectqry);
	$row=mysqli_fetch_array($result);
	$distcode=$row['VendorCode'];
	$distname=$row['VendorName'];
	$contactnumber=$row['ContactNumber'];
	$email=$row['EmailId'];
	$address=$row['Locationname'];
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Distributor Detail</title>
</head>

<body bgcolor="#C4FFFF";>
<div style="height:auto; width:100%;">
<div style="height:auto; padding-top:5px; width:100%; text-align:center; font-size:24px; "><u>Distributor Detail</u></div>

<div style="height:auto; padding-left:20px; padding-top:10px; float:left; width:95%;">
<table width="100%" border="1px">
<tr>
<td>Distributor Code</td>
<td><?php echo $distcode;?></td>
</tr>

<tr>
<td>Distributor Name</td>
<td><?php echo $distname;?></td>
</tr>

<tr>
<td>E-mail</td>
<td><?php echo $email;?></td>
</tr>

<tr>
<td>Contact No.</td>
<td><?php echo $contactnumber;?></td>
</tr>

<tr>
<td>Address</td>
<td><?php echo $address;?></td>
</tr>
</table>

</div>

</div>
</body>
</html>
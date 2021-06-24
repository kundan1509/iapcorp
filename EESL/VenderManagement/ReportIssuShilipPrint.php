<?php
session_start();
$inactive =900;
if(isset($_SESSION['timeout']))
{
	$session_life = time() - $_SESSION['timeout'];
	if($session_life > $inactive)
	{
		header("location:../Logout.php");
	}
	else
	{
		include("../Connection/Connection.php");			
		$UserId=$_SESSION['UserID'];
		$Name=$_SESSION['Name'];
		$UserType=$_SESSION['Type'];
		$Refid=$_SESSION['Ref'];
		$FVerigy=$_SESSION['FV'];
		if(!($_SESSION['Name']&& $_SESSION['Type']=='VN'))
		{
			header("location:../Logout.php");
		}
	}
}
else
{
	header("location:../Logout.php");
}
$_SESSION['timeout'] = time();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Issueslip Proof</title>
		<script src="Reporting/jspdf/jquery-1.11.0.min.js"></script>
		<link rel="stylesheet" href="Reporting/jspdf/bootstrap.min.css"/>
		<script type="text/javascript" src="Reporting/tableExport.js"></script>
		<script type="text/javascript" src="Reporting/jquery.base64.js"></script>
		<script type="text/javascript" src="Reporting/html2canvas.js"></script>
		<script type="text/javascript" src="Reporting/jspdf/libs/sprintf.js"></script>
		<script type="text/javascript" src="Reporting/jspdf/jspdf.js"></script>
		<script type="text/javascript" src="Reporting/jspdf/libs/base64.js"></script>
		<script type="text/javascript" src="Reporting/jspdf/bootstrap.min.js"></script>
	</head>
    

 <body>
<div class="container">
<?php
	if(isset($_POST['PrintIssuShilip']))
    {
		$IssuId=$_POST['IssueSlipId'];
		$VEId=$_POST['VeId'];		
		$sqlQuery ="SELECT tblissueslip.IssueSlipId,tblvendorregistration.VendorName,tblvendorregistration.VendorCode,DATE_FORMAT(tblissueslip.IssueDate,'%d-%m-%y') AS IssueDate,
tblissueslip.Rate,tblwarehouseregistration.WareHouseName,tblissueslip.IssueCode,tblissueslip.VendorRepresentativeName,tblissueslip.ContactNo,
DATE_FORMAT(tblissueslip.DispatchDate,'%d-%m-%y') AS DispatchDate,tblissueslip.DispatchProof,tbllocation.LocationName,tblissueslip.Quantity,tblissueslip.TotalAmount	
FROM tblissueslip,tblwarehouseregistration,tblvendorregistration,tbllocation,tblvendorlocation WHERE
tblissueslip.VendorID = tblvendorregistration.VendorId AND
tblissueslip.WareHouseId = tblwarehouseregistration.WarehouseId AND
tblissueslip.LocationId = tblvendorlocation.locationid AND
tblissueslip.LocationId = tbllocation.LocId AND
tblissueslip.RecStatus = 'A' AND
tblvendorregistration.RecStatus = 'A' AND
tblissueslip.IssueStatus='C' AND tblissueslip.IssueSlipId='$IssuId' AND tblissueslip.VendorID='$VEId'";
		
		
		$resultQuery=mysqli_query($con, $sqlQuery);                
        $rowQuery = mysqli_fetch_array($resultQuery);
		
	       $records[] = $rowQuery;
       
		echo "<div class='row'>	<div class='x_title' style='color:#FFF; background-color:#889DC6;'>	</div></div>
	          <div class='row' style='height:450px !important;overflow:scroll;'>
		      	<table id='employees' class='table table-striped'>
			    	<thead>
						<tr class='success'>
					 		<th colspan='3'><center>Distributor IssueSlip Proof</center></th>
                        </tr>						
						</thead>
						<tbody>";
?>
<tr style='visibility:hidden;'><td>----------------------------------------------------------------------------------------------------------------------------------------</td><tr/>		
<?php foreach($records as $rec):?>
	
	<tr><td>Distributor Code </td><td></td><td><?php echo $rec['VendorCode']?></td><tr/>
    <tr><td>Distributor Name </td><td></td><td><?php echo $rec['VendorName']?></td></tr>
    <tr><td>IssueSlip Date </td><td></td><td><?php echo $rec['IssueDate']?></td></tr>
	<tr><td>Quantity</td><td></td><td><?php echo $rec['Quantity']?></td></tr>   
    <tr><td>Rate </td><td></td><td><?php echo $rec['Rate']?></td></tr>
	  <tr><td>Total Amount</td><td></td><td><?php echo $rec['TotalAmount']?></td></tr>
	 <tr><td>Location Name</td><td></td><td><?php echo $rec['LocationName']?></td></tr>
  
    <tr><td>Issue Code </td><td></td><td><?php echo $rec['IssueCode']?></td></tr>
    <tr><td>Representative Name</td><td></td><td><?php echo $rec['VendorRepresentativeName']?></td></tr>
    <tr><td>Contact No </td><td></td><td><?php echo $rec['ContactNo']?></td></tr>
 	 

	
	<?php endforeach; ?>
	<?php
    echo "</tbody>
		</table>
	</div>
	<br>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-6 col-sm-6 col-xs-12 col-sm-offset-6'>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a href='IssueSlip.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'12',escape:'false'});\" class='btn btn-default' target='_blank' href='ReportIssueslip.php'>
						<img src='Reporting/images/pdf.png' width='24px'> PDF Print
					</a>
				</div>								
			</div>
		</div>
	</div>";
         }		              


?>				
</div>
</body>
</html>
<script type="text/javascript">
//$('#employees').tableExport();
$(function(){
	$('#example').DataTable();
      }); 
</script>
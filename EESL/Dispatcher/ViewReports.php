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
		$RefId=$_SESSION['Ref'];
		$FVerigy=$_SESSION['FV'];
		if(!($_SESSION['Name']&& $_SESSION['Type']=='DS'))
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

				if(isset($_POST['Print']))
				{
					if(isset($_POST['MisReport']))
					{
						date_default_timezone_set("Asia/Kolkata"); 
    					$CurrentDate=date('Y-m-d H:i:s A');		
						$Accept=$_POST['MisReport'];
				if($Accept=='WarehouseDetails')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3>Warehouse Stock Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					
					<th>Warehouse Code</th>
					<th>Current Stock</th>
					<th>Distribute </th>
					<th>Damage/Faulty</th>
					<th>Replacment</th>
					<th>Stock in Hand</th>
				</tr>
			</thead>
			<tbody>";
			?>
            <?php
			$locid="SELECT DISTINCT warehouseid from tblwarehouseinventory where recstatus = 'A' AND WareHouseId='$RefId'";
            $result=mysqli_query($con,$locid);
            while($row=mysqli_fetch_array($result))
            {		
				$warehouseid=$row['warehouseid'];
		      
			$Location= "SELECT DISTINCT COUNT(wi.WareHouseLocationID) AS noCount,l.LocationName 
FROM tblwarehouseregistration AS wi JOIN tbllocation AS l ON wi.WareHouseLocationID=l.LocId
WHERE l.RecStatus='A' AND WareHouseId='$warehouseid'";
			
			$Locquery=mysqli_query($con,$Location);
		     $row0=mysqli_fetch_array($Locquery);
			 if($row0['noCount']>0)
			 {
			 	$LocationName=$row0['LocationName'];				
			 }
			 else
			 {
				 $LocationName=$row0['LocationName'];				 
			 }
			
            $stockDetail= "SELECT COUNT(wi.quantity)AS counter,w.WarehouseCode,SUM(wi.Quantity)AS Totalin
FROM tblwarehouseregistration AS w JOIN tblwarehouseinventory AS wi ON w.warehouseid=wi.warehouseid 
WHERE wi.TXNTypein_out='IN' AND w.RecStatus='A' AND w.warehouseid='$warehouseid'";
			
			$query=mysqli_query($con,$stockDetail);
		     $row1=mysqli_fetch_array($query);
			 if($row1['counter']>0)
			 {
			 	$warehousename=$row1['WarehouseCode'];
				$quantity=$row1['Totalin'];
			 }
			 else
			 {
				 $warehousename=$row1['WarehouseCode'];
				 $quantity=0;
			 }
				
			$totalStockDetail= "SELECT count(Quantity)totalQuantity,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0))+SUM(IF(TXNTypein_out = 'R',quantity,0)) AS StockInHand FROM tblwarehouseinventory WHERE WareHouseId='$RefId'";
			        
					$query1=mysqli_query($con,$totalStockDetail);
		     $row2=mysqli_fetch_array($query1);
			 	if($row2['totalQuantity']>0)
				{
				$outquantity=$row2['StockInHand'];
				}
				else
				{
					$outquantity=0;
				}


				
				$salesdetail= "SELECT COUNT(DamageId) AS DamageCount,SUM(IF(damagestatus = 'Y',Quatity,0)) - SUM(IF(damagestatus = 'O',Quatity,0)) AS DamageQuantity FROM warehousedamagestock WHERE warehouseid='$RefId'";

			
			$query2=mysqli_query($con,$salesdetail);
		     $row3=mysqli_fetch_array($query2);
			 	if($row3['DamageCount']>0)
				{
				$salequantity=$row3['DamageQuantity'];
				}
				else
				{
					$salequantity=0;
				}
				
				
				$distributedetail="select count(IssueSlipId) as isid,SUM(Quantity)as qty from tblissueslip where IssueStatus='D' and WarehouseId='$RefId'";
				$distdetail=mysqli_query($con,$distributedetail);
				$row4=mysqli_fetch_array($distdetail);
				if($row4['isid'])
				{
					$qtyDist=$row4['qty'];
				}
				else
				{
					$qtyDist=0;
				}
				
				
				
				$replacementsdetail= "SELECT count(Quatity)as rep,SUM(Quatity)AS repqty FROM tblwarehousereplacement WHERE warehouseid='$RefId' and DamageStatus='Y'";
			
			$query4=mysqli_query($con,$replacementsdetail);
		     $row5=mysqli_fetch_array($query4);
			 	if($row5['rep']>0)
				{
				$repquantity=$row5['repqty'];
				
				}
				else
				{
					$repquantity=0;
					$findamqty=0;
				}
				
				
				
				
								
								echo"<tr>								
									<td>$warehousename</td>
				 					<td>$quantity</td>
									<td>$qtyDist</td>
				 					<td>$salequantity</td>
									<td>$repquantity</td>				 
				 					<td>$outquantity</td>
								</tr>";
							}
							echo "</tbody>
		</table>
	</div>
	<br>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-6 col-sm-6 col-xs-12 col-sm-offset-6'>
				<div class='col-lg-4 col-sm-4 col-xs-4'>
					<a href='MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'10',escape:'false'});\" class='btn btn-default' target='_blank' href='MISReport.php'>
						<img src='Reporting/images/pdf.png' width='24px'> PDF Print
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-4'>
					<a onclick=\"$('#employees').tableExport({type:'excel',escape:'false'});\" class='btn btn-default'>
						<img src='Reporting/images/xls.png' width='24px'> XLS Print
					</a>
				</div>
			</div>
		</div>
	</div>";
			
						}
						else if($Accept=='EESLDetails')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		                          <center><h3 >Consignment Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					<th>Consignment Code</th>
					<th>Date</th>
					<th>Received Stock</th>
					<th>Indent Qty</th>					
				</tr>
			</thead>
			<tbody>";
			?>
            <?php
			$ConsignMent="SELECT c.CosigmentId,c.ConsignemtNo,c.Quantity AS RecivedQty,c.IndentQuantity,c.Consignmentdate,wr.WarehouseId,wr.WareHouseName,wr.WareHouseCode
FROM tblconsignment AS c JOIN tblwarehouseregistration AS wr ON c.WarehouseId=wr.WarehouseId
WHERE c.AllotmentStatus='D' AND c.RecStatus='A' AND c.WareHouseId='$RefId'";
            $resultCosign=mysqli_query($con,$ConsignMent);
            while($rowConsign=mysqli_fetch_array($resultCosign))
            {	
				$ConNo=$rowConsign['ConsignemtNo'];
				$ReciveQty=$rowConsign['RecivedQty'];
				$IdentQty=$rowConsign['IndentQuantity'];
				$Date =$rowConsign['Consignmentdate'];
				$ReciveDate = date("d-m-Y", strtotime($Date ));
				$warehouseid=$rowConsign['WarehouseId'];	
				$WareHouseName=$rowConsign['WareHouseName'];
	$Location= "SELECT DISTINCT COUNT(wr.WareHouseLocationID)AS CounterNo,l.LocId,l.LocationName FROM tbllocation AS l
JOIN tblwarehouseregistration AS wr ON l.LocId=wr.WareHouseLocationID WHERE wr.WarehouseId='$warehouseid' and l.RecStatus='A' ";
			
			$Locquery=mysqli_query($con,$Location);
		     $row0=mysqli_fetch_array($Locquery);
			 if($row0['CounterNo']>0)
			 {
			 	$LocationName=$row0['LocationName'];				
			 }
			 else
			 {
				 $LocationName=$row0['LocationName'];				 
			 }
			
			echo"<tr>
				
					<td>$ConNo</td>
				 	<td>$ReciveDate</td>
				 	<td>$ReciveQty</td>				 
				 	<td>$IdentQty</td>
				</tr>";
				}
			echo "</tbody>
		</table>
	</div>
	<br>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-6 col-sm-6 col-xs-12 col-sm-offset-6'>
				<div class='col-lg-4 col-sm-4 col-xs-4'>
					<a href='MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'10',escape:'false'});\" class='btn btn-default' target='_blank' href='MISReport.php'>
						<img src='Reporting/images/pdf.png' width='24px'> PDF Print
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-4'>
					<a onclick=\"$('#employees').tableExport({type:'excel',escape:'false'});\" class='btn btn-default'>
						<img src='Reporting/images/xls.png' width='24px'> XLS Print
					</a>
				</div>
			</div>
		</div>
	</div>";
			
						}
					}
					else
				{
					echo "<script>alert('Please select any one!')</script>";
					echo "<script>window.open('MISReport.php','_self')</script>";
				}
				}
				else
				{
					echo "<script>window.open('MISReport.php','_self')</script>";
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
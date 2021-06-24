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
		if(!($_SESSION['Name']&& $_SESSION['Type']=='SA'))
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
				include("../Connection/Connection.php"); 

				if(isset($_POST['Print']))
				{
					if(isset($_POST['MisReport']))
					{
						date_default_timezone_set("Asia/Kolkata"); 
    					$CurrentDate=date('Y-m-d H:i:s A');		
						$Accept=$_POST['MisReport'];
						if($Accept=='PaymentDetails')
						{
							$qry="SELECT vn.VendorId,vn.VendorCode,vn.VendorName,vn.PoolACNo,vp.PaymentId,vp.Amount,vp.ModeofPayment,vp.TaxnRefNo,vn.BankName,DATE_FORMAT(vp.PaymentDate,'%d-%m-%y') AS PaymentDate,vp.PaymentType,vp.DepositBankName
FROM tblvendorregistration AS vn 
JOIN tblpayment AS vp ON vn.VendorId=vp.VendorId
WHERE vn.VendorStatus='A' AND vn.RecStatus='A' AND vp.FinanceVerification='A' AND vp.RecStatus='A' AND vp.PaymentType='A' ORDER BY vp.PaymentId DESC";
	$result=mysqli_query($con, $qry);
	$records = array();
	while($row = mysqli_fetch_assoc($result)){ 
	$records[] = $row;
  }
  echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3 >Distributor Payment Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					<th>Distributor Code</th>
					<th>Distributor Name</th>
					<th>Pool A/C No.</th>
					<th>Amount</th>
					<th>Pay Date</th>
					<th>Pay Mode</th>
					<th>Deposit Bank</th>
				</tr>
			</thead>
			<tbody>";
			?>
				<?php foreach($records as $rec):?>
					<tr>
						<td><?php echo $rec['VendorCode']?></td>
                        <td><?php echo $rec['VendorName']?></td>
                        <td><?php echo $rec['PoolACNo']?></td>
						<td><?php echo $rec['Amount']?></td>
						<td><?php echo $rec['PaymentDate']?></td>
                        <td><?php echo $rec['ModeofPayment']?></td>
						<td><?php echo $rec['DepositBankName']?></td>
					</tr>
				<?php endforeach; ?>
                <?php
			echo "</tbody>
		</table>
	</div>
	<br>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-6 col-sm-6 col-xs-12 col-sm-offset-6'>
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				<a href='../Administrator/MISReport.php' class='btn btn-primary'>
							<i class='fa fa-arrow-left'> Go Back </i>
						</a>				
				</div>				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
						else if($Accept=='StockDetails')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3 >Distributor Stock Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
				
					<th>Distributor Name</th>
					<th>LED Stock</th>
					<th>LED Distributed</th>
					<th>Damage/Faulty</th>
					<th>Replacement</th>
					<th>Stock in Hand</th>
				</tr>
			</thead>
			<tbody>'";
			?>
            <?php
							$locid="SELECT vendorid,VendorName from tblvendorregistration where recstatus = 'A'";
							$result=mysqli_query($con,$locid);
							while($row=mysqli_fetch_array($result))
							{		
								$VendorId=$row['vendorid'];
								$vname=$row['VendorName'];
								$stockDetail= "SELECT count(vi.quantity)as counter,v.vendorname,v.VendorCode, SUM(vi.Quantity)AS Totalin FROM tblvendorregistration v,tblvendorinventory vi WHERE v.vendorid=vi.vendorid AND vi.TXNTypein_out='IN' AND v.RecStatus='A' AND v.Vendorid='$VendorId'";
			
								$query=mysqli_query($con,$stockDetail);
								$row1=mysqli_fetch_array($query);
								if($row1['counter']>0)
								{
									$vendorname=$row1['vendorname'];
									$VendorCode=$row1['VendorCode'];
									$quantity=$row1['Totalin'];
								}
								else
								{
									$vendorname=$row1['vendorname'];
									$quantity=0;
								}
				
								$totalStockDetail= "SELECT count(Quantity)totalQuantity,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0))+ SUM(IF(TXNTypein_out = 'R',quantity,0)) AS StockInHand FROM tblvendorinventory WHERE vendorid='$VendorId'";
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
								$salesdetail= "SELECT count(Quantity)as saleqty,SUM(Quantity)AS SaleQuantity FROM vendorsales WHERE vendorid='$VendorId'";
								$query2=mysqli_query($con,$salesdetail);
								$row3=mysqli_fetch_array($query2);
								if($row3['saleqty']>0)
								{
									$salequantity=$row3['SaleQuantity'];
								}
								else
								{
									$salequantity=0;
								}
					
					
								$damagesdetail= "SELECT count(Quatity)as damqty,SUM(IF(damagestatus = 'Y',Quatity,0)) - SUM(IF(damagestatus = 'O',Quatity,0))AS damageQuantity FROM vendordamagestock WHERE vendorid='$VendorId'";
								$query3=mysqli_query($con,$damagesdetail);
								$row4=mysqli_fetch_array($query3);
								if($row4['damqty']>0)
								{
									$damagequantity=$row4['damageQuantity'];
								}
								else
								{
									$damagequantity=0;
								}
								
								
								$replacementsdetail= "SELECT count(Quatity)as rep,SUM(Quatity)AS repqty FROM tblvendorreplacement WHERE vendorid='$VendorId' and DamageStatus='Y'";
			
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
									
									<td>$vname</td>
									<td>$quantity</td>
									<td>$salequantity</td>
									<td>$damagequantity</td>
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
					<a href='../Administrator/MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
						else if($Accept=='WarehouseDetails')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3 >Warehouse Stock Details</h3></center>
	</div>    
	<div class='row' style='height:500px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					
					<th>Warehouse Code</th>
					<th>Warehouse Name</th>
					<th>Opening Stock</th>
					<th>Damage/Faulty</th>
					<th>Replacement</th>
					<th>Distributed</th>
					<th>Stock in Hand</th>
				</tr>
			</thead>
			<tbody>'";
			?>
            <?php
			$locid="SELECT DISTINCT warehouseid,warehousename,warehouselocationid from tblwarehouseregistration where recstatus = 'A'";
            $result=mysqli_query($con,$locid);
            while($row=mysqli_fetch_array($result))
            {		
				$warehouseid=$row['warehouseid'];
				$lid=$row['warehouselocationid'];
				$whname=$row['warehousename'];
		      
			$Location= "SELECT DISTINCT COUNT(wi.WareHouseLocationID) AS noCount,l.LocationName 
FROM tblwarehouseregistration AS wi JOIN tbllocation AS l ON wi.WareHouseLocationID=l.LocId
WHERE LocId='$lid' AND l.RecStatus='A'";
			
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
			
            $stockDetail= "SELECT COUNT(wi.quantity)AS counter,w.warehousename,w.warehousecode,SUM(wi.Quantity)AS Totalin
FROM tblwarehouseregistration AS w JOIN tblwarehouseinventory AS wi ON w.warehouseid=wi.warehouseid 
WHERE wi.TXNTypein_out='IN' AND w.RecStatus='A' AND w.warehouseid='$warehouseid'";
			
			$query=mysqli_query($con,$stockDetail);
		     $row1=mysqli_fetch_array($query);
			 if($row1['counter']>0)
			 {
			 	$warehousename=$row1['warehousename'];
				$wcode=$row1['warehousecode'];
				$quantity=$row1['Totalin'];
			 }
			 else
			 {
				 $warehousename=$row1['warehousename'];
				 $wcode=$row1['warehousecode'];
				 $quantity=0;
			 }
				
			$totalStockDetail= "SELECT count(Quantity)totalQuantity,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0))+SUM(IF(TXNTypein_out = 'R',quantity,0)) AS StockInHand FROM tblwarehouseinventory WHERE warehouseid='$warehouseid'";
			        
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

			
				
				
				$salesdetail= "SELECT COUNT(DamageId) AS DamageCount,SUM(IF(damagestatus = 'Y',Quatity,0)) - SUM(IF(damagestatus = 'O',Quatity,0)) AS DamageQuantity FROM warehousedamagestock WHERE RecStatus='A' AND WareHouseId='$warehouseid'";

			
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
				
				
				
				$replacementsdetail= "SELECT count(Quatity)as rep,SUM(Quatity)AS repqty FROM tblwarehousereplacement WHERE WareHouseId='$warehouseid' and DamageStatus='Y'";
			
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
				
				$salesdetail1= "SELECT COUNT(quantity)AS COUNT,SUM(quantity)AS StockIssued FROM tblissueslip WHERE issuestatus='D' AND warehouseid='$warehouseid'";

			
			$query5=mysqli_query($con,$salesdetail1);
		     $row5=mysqli_fetch_array($query5);
			 	if($row5['COUNT']>0)
				{
				$salequantity1=$row5['StockIssued'];
				}
				else
				{
					$salequantity1=0;
				}
				
								
								echo"<tr>
								<td>$wcode</td>
									<td>$whname</td>
									<td>$quantity</td>
				 					<td>$salequantity</td>	
									<td>$repquantity</td>
									<td>$salequantity1</td>				 
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
					<a href='../Administrator/MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
		<center><h3 >EESL Stock Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					<th>Warehouse Code</th>				
					<th>Warehouse Name</th>
					<th>Consign. Code</th>
					<th>Date</th>
					<th>Indent Qty</th>	
					<th>Recived Stock</th>
									
				</tr>
			</thead>
			<tbody>'";
			?>
            <?php
			$ConsignMent="SELECT c.CosigmentId,c.ConsignemtNo,c.Quantity AS RecivedQty,c.IndentQuantity,c.Consignmentdate,wr.WarehouseId,wr.WareHouseName,wr.WareHouseCode
FROM tblconsignment AS c JOIN tblwarehouseregistration AS wr ON c.WarehouseId=wr.WarehouseId
WHERE c.AllotmentStatus='D' AND c.RecStatus='A'";
            $resultCosign=mysqli_query($con,$ConsignMent);
            while($rowConsign=mysqli_fetch_array($resultCosign))
            {	
				$cono=$rowConsign['ConsignemtNo'];
				$ReciveQty=$rowConsign['RecivedQty'];
				$IdentQty=$rowConsign['IndentQuantity'];
				$Date =$rowConsign['Consignmentdate'];
				$ReciveDate = date("d-m-Y", strtotime($Date ));
				$warehouseid=$rowConsign['WarehouseId'];	
				$WareHouseName=$rowConsign['WareHouseName'];
				$whcode=$rowConsign['WareHouseCode'];
		      
			$Location= "SELECT DISTINCT COUNT(wr.WareHouseLocationID)AS CounterNo,l.LocId,l.LocationName FROM tbllocation AS l
JOIN tblwarehouseregistration AS wr ON l.LocId=wr.WareHouseLocationID WHERE wr.WarehouseId='$warehouseid' and l.RecStatus='A'";
			
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
			<td>$whcode</td>
			<td>$WareHouseName</td>
				
					<td>$cono</td>
				 	<td>$ReciveDate</td>
					<td>$IdentQty</td>
				 	<td>$ReciveQty</td>				 
				 	
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
					<a href='../Administrator/MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
						else if($Accept=='distSecurity')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3 >Distributor Security Payment And Wallet</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					<th>Distributor Code</th>
					<th>Distributor Name</th>
					<th>Activation Date</th>
					<th>Security Amount</th>
					<th>Current Eligibility</th>
					<th>Wallet Amount</th>					
				</tr>
			</thead>
			<tbody>'";
			?>
            <?php
			$DistSecurity="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ActivationDate,w.WalletId,w.Balance,l.Id,l.DailyLimit,l.TotalSecurity
FROM tblvendorregistration AS v 
JOIN tblwallet AS w ON v.VendorId=w.VendorId
JOIN tblvendoreligibility AS l ON v.VendorId=l.VendorId
WHERE v.VendorStatus='A'";
            $resultSecurity=mysqli_query($con,$DistSecurity);
            while($rowSec=mysqli_fetch_array($resultSecurity))
            {	
				$VCode=$rowSec['VendorCode'];
				$RDate=$rowSec['ActivationDate'];
				$ActDate = date("d-m-Y", strtotime($RDate));
				$Security=$rowSec['TotalSecurity'];
				$Limit =$rowSec['DailyLimit'];
				$Wallet =$rowSec['Balance'];
				$vname=$rowSec['VendorName'];
			
			echo"<tr>
					<td>$VCode</td>
					<td>$vname</td>
					<td>$ActDate</td>
				 	<td>$Security</td>
				 	<td>$Limit</td>				 
				 	<td>$Wallet</td>
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
					<a href='../Administrator/MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
						else if($Accept=='LEDDistDetails')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3 >LED Distribution Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>			
				<tr class='success'>
					<th>Dist. Code</th>
					<th>Distributor Name</th>
					<th>Issued Stock</th>
					<th>LED Distributed</th>
					<th>Stock in Hand</th>
				</tr>
			</thead>
			<tbody>'";
			?>
            <?php
							$DistCode="SELECT VendorId,VendorCode,VendorName FROM tblvendorregistration WHERE VendorStatus='A'";
							$DistQuery=mysqli_query($con,$DistCode);
							while($rowDist=mysqli_fetch_array($DistQuery))
							{		
								$DistID=$rowDist['VendorId'];
								$DistCode=$rowDist['VendorCode'];
								$venname=$rowDist['VendorName'];
								
								$IssuStock= "SELECT DISTINCT COUNT(VendorID) as IssuCount, SUM(Quantity) AS IssuQty FROM tblissueslip WHERE IssueStatus='C' AND VendorID='$DistID' ";
			
								$Issuquery=mysqli_query($con,$IssuStock);
								$rowQty=mysqli_fetch_array($Issuquery);
								if($rowQty['IssuCount']>0)
								{
									$IssuQty=$rowQty['IssuQty'];
								}
								else
								{
									$IssuQty=0;
								}
								
				
								$SaleQty= "SELECT DISTINCT COUNT(VendorID) as SaleCount,SUM(IF(TXNTypein_out = 'OUT',quantity,0)) AS DistributedQty,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0)) AS UsDistQty FROM tblvendorinventory WHERE VendorId='$DistID'";
								$SaleQuery=mysqli_query($con,$SaleQty);
								$rowSale=mysqli_fetch_array($SaleQuery);
								if($rowSale['SaleCount']>0)
								{
									$DistributedQty=$rowSale['DistributedQty'];
									$UnDistributedQty=$rowSale['UsDistQty'];
									
								}
								else
								{
									$DistributedQty=0;
									$UnDistributedQty=0;
								}								
								
								echo"<tr>
									<td>$DistCode</td>
									<td>$venname</td>
									<td>$IssuQty</td>
									<td>$DistributedQty</td>
									<td>$UnDistributedQty</td>									
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
					<a href='../Administrator/MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
				
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
						else if($Accept=='EESLPaymentsDetails')
						{
							echo "<br><div class='row' style='color:#FFF; background-color:#889DC6;'>
		<center><h3 >EESL Payment Details</h3></center>
	</div>    
	<div class='row' style='height:400px !important;overflow:scroll;'><br>
		<table id='employees' class='table table-striped'>
			<thead>		
				<tr class='success'>
					<th>State</th>
					<th>Bank Name</th>
					<th>Pay Date</th>
					<th>Txt/RefNo.</th>
					<th>Amount </th>				
				</tr>
			</thead>
			<tbody>	";
			?>
            <?php
			$RateQuery="SELECT COUNT(Id) AS ratCount,Rate FROM tblmasterissue WHERE RecStatus='A'";
			$runRate=mysqli_query($con,$RateQuery);
			$rowRate=mysqli_fetch_array($runRate);
			if($rowRate>0)
			{
				$Rate=$rowRate['Rate'];
			}
			
			$QuantityQuery="SELECT COUNT(InventoryId) AS CountQuantity,SUM(Quantity) AS TotQty FROM tblwarehouseinventory WHERE TXNTypein_out='IN' AND RecStatus='A'
";
			$runQuantity=mysqli_query($con,$QuantityQuery);
			$rowQuantity=mysqli_fetch_array($runQuantity);
			if($rowQuantity>0)
			{
				$totQty=$rowQuantity['TotQty'];
			}
			
			$SumQuery="SELECT COUNT(PaymentId ) AS PayCount, SUM(Ammount) AS TotalEESLPay FROM tbleeslpayment WHERE RecStatus='A' ";
			$runSum=mysqli_query($con,$SumQuery);
			$rowSum=mysqli_fetch_array($runSum);
			if($rowSum>0)
			{
				$totEESLPay=$rowSum['TotalEESLPay'];	
			}
			$outStandingPay=$Rate*$totQty;			
			$totalOutStandingPayment=$totEESLPay-$outStandingPay;
			
							$EESLPayment="SELECT PaymentId,State,DepositBankName,PaymentDate,Txn_RefNo,Ammount,ModeOfPayment FROM tbleeslpayment WHERE RecStatus='A' ORDER BY PaymentId DESC";
							$EESLPayQuery=mysqli_query($con,$EESLPayment);
							while($rowEESL=mysqli_fetch_array($EESLPayQuery))
							{		
								$State=$rowEESL['State'];
								$BankName=$rowEESL['DepositBankName'];
								$BankPayDate=$rowEESL['PaymentDate'];
								$ActDateBank = date("d-m-Y", strtotime($BankPayDate));
								$TxtNo=$rowEESL['Txn_RefNo'];
								$Amount=$rowEESL['Ammount'];												
								
								echo"<tr>
									<td>$State</td>
									<td>$BankName</td>
									<td>$ActDateBank</td>
									<td>$TxtNo</td>									
									<td>$Amount</td>																			
								</tr>";
							}
							echo"<tr><td colspan='5' style='visibility:hidden'> </td></tr>";
							echo "<tr><td> </td> <td> </td><td>Total EESL Payment </td> <td></td><td>$totEESLPay</td></tr>";
							echo"<tr><td colspan='5' style='visibility:hidden'> </td></tr>";
							/*echo "<tr><td> </td><td> </td><td>Total Outstanding Payment </td><td></td><td>$totalOutStandingPayment</td></tr>";*/
							echo "</tbody>
		</table>
	</div>
	<br>
	<div class='container'>
		<div class='row'>
			<div class='col-lg-6 col-sm-6 col-xs-12 col-sm-offset-6'>
				<div class='col-lg-4 col-sm-4 col-xs-4'>
					<a href='../Administrator/MISReport.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				
				<div class='col-lg-4 col-sm-4 col-xs-4'>
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank' href='#'>
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
							echo "<script>alert('Please select at least one !')</script>";
							echo "<script>window.open('../Administrator/MISReport.php','_self')</script>";
						}
				}
				else
				{
					echo "<script>window.open('../Administrator/MISReport.php','_self')</script>";
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
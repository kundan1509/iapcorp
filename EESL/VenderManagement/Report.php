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
		<title> Mis Report </title>
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
 
 
 if (isset($_POST['printpreview']))
    {
$str="";		
      $selected_radio=$_POST['report'];					
         if ($selected_radio == 'stock_report')
	      {
		   $counter=0; 
            $locid="SELECT  Locationid FROM tblvendorlocation WHERE vendorid = $Refid AND Recstatus = 'A'";
            $result=mysqli_query($con,$locid);
         while($row=mysqli_fetch_array($result))
            {		
		          if($counter>0)
		            {
			           $str=$str . " UNION ";		
		            } 		
		$str = $str. "(SELECT (SELECT LocationName FROM tbllocation WHERE Locid =" . $row['Locationid'] . ") AS LocationName," . 
			"(SELECT SUM(IF (vi.TXNTypein_out = 'IN',vi.quantity,0)) FROM tblvendorinventory AS vi WHERE vendorId =".$Refid."  AND LocationId =".$row['Locationid'] ." AND vi.RecStatus = 'A') AS LedStock," .
			"(SELECT SUM(IF (vr.DamageStatus = 'Y',vr.Quatity,0)) FROM tblvendorreplacement AS vr WHERE vendorId =".$Refid." AND LocationId =".$row['Locationid'] . ") AS Replacement," .
			"(SELECT SUM(IF (vs.RecStatus = 'A',vs.quantity,0)) FROM vendorsales AS vs WHERE vendorId =".$Refid." AND LocationId =".$row['Locationid'] . ") AS LedDistruted," .
			"( SELECT SUM(IF (vd.DamageStatus ='Y',vd.Quatity,0))- SUM(IF(damagestatus = 'O',Quatity,0)) FROM vendordamagestock AS vd WHERE Vendorid =".$Refid."  AND LocationId =" .$row['Locationid'] . " AND RecStatus = 'A') AS LedDamage," .
			"( SELECT SUM(IF (TXNTypein_out = 'IN',quantity,0)) - SUM(IF (TXNTypein_out = 'OUT',quantity,0))+SUM(IF (TXNTypein_out = 'R',quantity,0)) FROM tblvendorinventory WHERE vendorId =".$Refid."  AND LocationId =" .$row['Locationid'] ." AND RecStatus = 'A') AS StockInHand)";
		     $counter=$counter+ 1;
		
              }
             $str = $str. " UNION (SELECT 'TOTAL' AS LocationName," . 
			"(SELECT SUM(IF (vi.TXNTypein_out = 'IN',vi.quantity,0)) FROM tblvendorinventory AS vi WHERE vendorId =".$Refid." AND vi.RecStatus = 'A') AS LedStock," .
			
			"(SELECT SUM(IF (vr.DamageStatus = 'Y',vr.Quatity,0)) FROM tblvendorreplacement AS vr WHERE vendorId =".$Refid.") AS Replacement," .
			
			"(SELECT SUM(IF (vs.RecStatus = 'A',vs.quantity,0)) FROM vendorsales AS vs WHERE vendorId =".$Refid. ") AS LedDistruted," .
			"( SELECT SUM(IF (vd.DamageStatus ='Y',vd.Quatity,0))-SUM(IF(damagestatus = 'O',Quatity,0)) FROM vendordamagestock AS vd WHERE Vendorid =".$Refid."  AND RecStatus = 'A') AS LedDamage," .
			"( SELECT SUM(IF (TXNTypein_out = 'IN',quantity,0)) - SUM(IF (TXNTypein_out = 'OUT',quantity,0))+SUM(IF (TXNTypein_out = 'R',quantity,0)) FROM tblvendorinventory WHERE vendorId =".$Refid."  AND RecStatus = 'A') AS StockInHand)";
       $result=mysqli_query($con, $str);
          $records = array(); 
          while($row = mysqli_fetch_assoc($result))
           { 
	        $records[] = $row;
           }
		   
		  
   	
       echo "<div class='row'>
              <div class='x_title' style='color:#FFF; background-color:#889DC6;'>	   
               <h3><center>&nbsp; Distributor Stock Details</center> </h3> 
               </div>			   
              </div>
	<div class='row' style='height:300px !important;overflow:scroll;'>
		<table id='employees' class='table table-striped'>
			<thead>	
			<tr class='success'>
             		
			    <th> Location Code</th>
                <th>LED Stock</th>
                <th>LED Distributed</th>
                <th>Damage/Faulty</th>
				<th>Replacement</th>
                <th>Stock In Hand</th>      	
					</tr>
			</thead>
			<tbody>";
			?>
			<?php foreach($records as $rec):?>
					<tr>
                       
						<td><?php echo $rec['LocationName']?></td>
						<td><?php echo $rec['LedStock']?></td>
						<td><?php echo $rec['LedDistruted']?></td>
						<td><?php echo $rec['LedDamage']?></td>
						
                        <td><?php echo $rec['Replacement']?></td>
                        <td><?php echo $rec['StockInHand']?></td>
						
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
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a href='ReportMis.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'10',escape:'false'});\" class='btn btn-default' target='_blank' href='Report.php'>
						<img src='Reporting/images/pdf.png' width='24px'> PDF Print
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a onclick=\"$('#employees').tableExport({type:'excel',escape:'false'});\" class='btn btn-default'>
						<img src='Reporting/images/xls.png' width='24px'> XLS Print
					</a>
				</div>				
			</div>
		</div>
	</div>";
}
      else if($selected_radio=='payment_report')
          {
		       $sql = "SELECT v.VendorId,v.VendorName,v.VendorCode,v.PoolACNo,p.Amount,p.ModeofPayment,p.TaxnRefNo,DATE_FORMAT(p.PaymentDate,'%d-%m-%y') AS PaymentDate,p.DepositBankName  
		        FROM  tblvendorregistration AS v JOIN tblpayment AS p  ON v.VendorId=p.VendorId 
		        WHERE p.RecStatus='A' AND v.RecStatus='A' AND p.PaymentType='A'  AND v.VendorId=p.VendorId  AND p.VendorId='$Refid' ORDER BY p.PaymentId DESC ";
				
				$result=mysqli_query($con, $sql);
                $records = array();
                while($row = mysqli_fetch_assoc($result))
				{ 
	              $records[] = $row;
                 }
				   echo "<div class='row'>	
                     <div class='x_title' style='color:#FFF; background-color:#889DC6;'>				   
               <h3><center>&nbsp; Distributor Payment Details</center> </h3>
                </div>			   
              </div>
	          <div class='row' style='height:500px !important;overflow:scroll;'>
		         <table id='employees' class='table table-striped'>
			          <thead>
					  <tr class='success'>
					  <th>Distributor Code</th>
                       <th>Payment Mode</th> 					  
                        <th>Amount</th>                      
                        <th>Txn Ref No</th>
                        <th>Payment Date</th>
                         <th>Deposit Bank</th> 
						<th>Pool Ac No</th>
					</tr>
			</thead>
			
			<tbody>";
			?>
		
				<?php foreach($records as $rec):?>
				
					<tr>                
						<td><?php echo $rec['VendorCode']?></td>
						<td><?php echo $rec['ModeofPayment']?></td>
						<td><?php echo $rec['Amount']?></td>						
						<td><?php echo $rec['TaxnRefNo']?></td>
					    <td><?php echo $rec['PaymentDate']?></td>					
					    <td><?php echo $rec['DepositBankName']?></td>
                        <td><?php echo $rec['PoolACNo']?></td>						
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
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a href='ReportMis.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});\" class='btn btn-default' target='_blank' href='Report.php'>
						<img src='Reporting/images/pdf.png' width='24px'> PDF Print
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a onclick=\"$('#employees').tableExport({type:'excel',escape:'false'});\" class='btn btn-default'>
						<img src='Reporting/images/xls.png' width='24px'> XLS Print
					</a>
				</div>				
			</div>
		</div>
	</div>";
         }
		 else if(isset($_POST['PrintIssuShilip']))
          {
			  $IssuId=$_POST['IssueSlipId'];
			  $VEId=$_POST['VeId'];
		       $sqlQuery = "SELECT
	   tblissueslip.IssueSlipId,
	   tblvendorregistration.VendorName,
	   tblvendorregistration.VendorCode,
	   DATE_FORMAT(tblissueslip.IssueDate,'%d-%m-%y') AS IssueDate,
		tblissueslip.Rate,
		tblwarehouseregistration.WareHouseName,
		tblissueslip.IssueCode,
		tblissueslip.VendorRepresentativeName,
		tblissueslip.ContactNo,
		DATE_FORMAT(tblissueslip.DispatchDate,'%d-%m-%y') AS DispatchDate,
		tblissueslip.DispatchProof,
		tbllocation.LocationName,
		tblissueslip.Quantity,
		tblissueslip.TotalAmount	
        FROM tblissueslip,tblwarehouseregistration,tblvendorregistration,tbllocation,tblvendorlocation WHERE
              tblissueslip.VendorID = tblvendorregistration.VendorId AND 
              tblissueslip.WareHouseId = tblwarehouseregistration.WarehouseId AND
              tblissueslip.LocationId = tblvendorlocation.ID AND
              tblissueslip.LocationId = tbllocation.LocId AND
              tblIssueSlip.RecStatus = 'A' AND
              tblvendorregistration.RecStatus = 'A' AND 
              tblIssueSlip.IssueStatus='C' AND tblIssueSlip.IssueSlipId='$IssuId' AND tblIssueSlip.VendorID ='$VEId'";
				
				$resultQuery=mysqli_query($con, $sqlQuery);                
                while($rowQuery = mysqli_fetch_assoc($resultQuery))
				{ 
	              $records[] = $rowQuery;
                 }
				   echo "<div class='row'>	
                     <div class='x_title' style='color:#FFF; background-color:#889DC6;'>				   
               
                </div>			   
              </div>
	          <div class='row' style='height:300px !important;overflow:scroll;'>
		         <table id='employees' class='table table-striped'>
			          <thead>
					  <tr class='success'>
					  <th>Distributor Issushlip Details</th>
                         
					</tr>
			</thead>
			
			<tbody>";
			?>
		
				<?php foreach($records as $rec):?>
				
					<tr>                
						<td><?php echo $records['VendorCode']?></td>
						<td><?php echo $records['VendorName']?></td>
						<td><?php echo $records['IssueDate']?></td>
						<td><?php echo $records['Rate']?></td>
						<td><?php echo $records['WareHouseName']?></td>
					    <td><?php echo $records['IssueCode']?></td>					
					    <td><?php echo $records['VendorRepresentativeName']?></td>
                        <td><?php echo $records['ContactNo']?></td>						
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
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a href='ReportMis.php' class='btn btn-primary'>
						<i class='fa fa-arrow-left'> Go Back </i>
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
					<a onclick=\"$('#employees').tableExport({type:'pdf',pdfFontSize:'8',escape:'false'});\" class='btn btn-default' target='_blank'>
						<img src='Reporting/images/pdf.png' width='24px'> PDF Print
					</a>
				</div>
				<div class='col-lg-4 col-sm-4 col-xs-6'>
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
					echo "<script>window.open('ReportMis.php','_self')</script>";
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

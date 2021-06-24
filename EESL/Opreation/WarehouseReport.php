
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
		if(!($_SESSION['Name']&&$_SESSION['Type']=='RO' || $_SESSION['Type']=='SA' ))
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





<?php

include("../connection/connection.php");
 	
require('../pdfGenerat/fpdf.php');


	
	
?>



<div class="container">
	<div class="row">
     <div class="x_title"style="color:#FFF; background-color:#889DC6">
                                    <h3><center>Warehouse Stock Details</center></h3>
                                    </div>
		<div class="btn-group pull-right" style=" padding: 10px;">
			<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
     <span class="glyphicon glyphicon-th-list"></span>Download As
   
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    
							
								
								
								
								
								
								<li class="divider"></li>				
								
								<li><a href="#" onclick="$('#employees').tableExport({type:'excel',escape:'false'});" > <img src="Reporting/images/xls.png" width="24px">XLS</a></li>
								
								
								
								<li><a onclick="$('#employees').tableExport({type:'pdf',pdfFontSize:'10',escape:'false'});" target="_blank" href="ReportMis.php"> <img src="Reporting/images/pdf.png" width="24px"> PDF</a></li>
								
  </ul>
</div>
		</div>
	</div>	
    
	<div class="row" style="height:300px !important;overflow:scroll;">
						<table id="employees" class="table table-striped">
				<thead>			
					<tr class="warning">
						<th>Warehouse Name</th>
						<th>LedStock</th>
                        <th>Stock Issued</th>
                        <th>Damage Stock</th>
                         <th>Replacement Stock</th>
                        <th>Stock in Hand</th>
                        
						
					</tr>
				</thead>
				<tbody>
				<?php 
				 $locid="SELECT DISTINCT warehouseid from tblwarehouseinventory where recstatus = 'A'";
            $result=mysqli_query($con,$locid);
            while($row=mysqli_fetch_array($result))
            {		
				$warehouseid=$row['warehouseid'];
		      
		
			
            $stockDetail= "SELECT COUNT(wi.quantity)AS counter,w.warehousecode, SUM(wi.Quantity)AS Totalin FROM tblwarehouseregistration AS w,tblwarehouseinventory AS wi WHERE w.warehouseid=wi.warehouseid AND wi.TXNTypein_out='IN' AND w.RecStatus='A' AND w.warehouseid='$warehouseid'";
			
			$query=mysqli_query($con,$stockDetail);
		     $row1=mysqli_fetch_array($query);
			 if($row1['counter']>0)
			 {
			 	$warehousename=$row1['warehousecode'];
				$quantity=$row1['Totalin'];
			 }
			 else
			 {
				 $warehousename=$row1['warehousecode'];
				 $quantity=0;
			 }
				
			$totalStockDetail= "SELECT count(Quantity)AS totalQuantity,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0))+SUM(IF(TXNTypein_out = 'R',quantity,0)) AS StockInHand FROM tblwarehouseinventory WHERE warehouseid='$warehouseid'";
			        
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

				
				$salesdetail= "SELECT COUNT(quantity)AS COUNT,SUM(quantity)AS StockIssued FROM tblissueslip WHERE issuestatus='D' AND warehouseid='$warehouseid'";

			
			$query2=mysqli_query($con,$salesdetail);
		     $row3=mysqli_fetch_array($query2);
			 	if($row3['COUNT']>0)
				{
				$salequantity=$row3['StockIssued'];
				}
				else
				{
					$salequantity=0;
				}
				
				
				$DamageDetail= "SELECT COUNT(DamageId) AS DamageCount,SUM(IF(damagestatus = 'Y',Quatity,0)) - SUM(IF(damagestatus = 'O',Quatity,0))AS Damage FROM warehousedamagestock WHERE WareHouseId='$warehouseid'";

			
			$querydDetail2=mysqli_query($con,$DamageDetail);
		     $row3=mysqli_fetch_array($querydDetail2);
			 	if($row3['DamageCount']>0)
				{
				$damageqty=$row3['Damage'];
				}
				else
				{
					$damageqty=0;
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
				
				
				
			
				
					 
			
				
			
				echo "
            <tr>
                 <td>$warehousename</td>
				 <td>$quantity</td>
				 <td>$salequantity</td>
				 <td>$damageqty</td>
				 <td>$repquantity</td>
				 <td>$outquantity</td>
				
				                       				
            </tr>";
			
			}
				?>
                
                
					</tbody>
					</table>
</div>
<br>
<a href="ReportMis.php" class="btn btn-info">Go Back</a>
</div>

</body>
</html>
<script type="text/javascript">
//$('#employees').tableExport();
$(function(){
	$('#example').DataTable();
      }); 
</script>

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

include("../Connection/Connection.php");

	
require('../pdfGenerat/fpdf.php');


	
	
?>



<div class="container">
	<div class="row">
     <div class="x_title"style="color:#FFF; background-color:#889DC6">
                                    <h3><center>Distributor Stock Details</center></h3>
                                    </div>
		<div class="btn-group pull-right" style=" padding: 10px;">
			<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
     <span class="glyphicon glyphicon-th-list"></span>Download as
   
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    
							
								
								
								
								
								
								<li class="divider"></li>				
								
								<li><a onclick="$('#employees').tableExport({type:'excel',escape:'false'});"> <img src="Reporting/images/xls.png" width="24px">XLS</a></li>
								
								
								
								<li><a onclick="$('#employees').tableExport({type:'pdf',pdfFontSize:'10',escape:'false'});" target="_blank" href="ReportMis.php"> <img src="Reporting/images/pdf.png" width="24px"> PDF</a></li>
								
  </ul>
</div>
		</div>
	</div>	
    
	<div class="row" style="height:300px !important;overflow:scroll;">
						<table id="employees" class="table table-striped">
				<thead>			
					<tr class="warning">
						<th>Distributor Name</th>
						<th>Total Stock</th>
                        <th>Led Distributed</th>
                        <th>Damage</th>
                        <th>Replacment</th>
                        <th>Stock InHand</th>
						
					</tr>
				</thead>
				<tbody>
				<?php 
				 $locid="SELECT DISTINCT vendorid,VendorName from tblvendorregistration where recstatus = 'A'";
            $result=mysqli_query($con,$locid);
            while($row=mysqli_fetch_array($result))
            {		
				$vendorid=$row['vendorid'];
				$vname=$row['VendorName'];
		      
		
			
            $stockDetail= "SELECT count(vi.quantity)as counter,v.vendorname, SUM(vi.Quantity)AS Totalin FROM tblvendorregistration v,tblvendorinventory vi WHERE v.vendorid=vi.vendorid AND vi.TXNTypein_out='IN' AND v.RecStatus='A' AND v.Vendorid='$vendorid'";
			
			$query=mysqli_query($con,$stockDetail);
		     $row1=mysqli_fetch_array($query);
			 if($row1['counter']>0)
			 {
			 	$vendorname=$row1['vendorname'];
				$quantity=$row1['Totalin'];
			 }
			 else
			 {
				 $vendorname=$row1['vendorname'];
				 $quantity=0;
			 }
				
			$totalStockDetail= "SELECT count(Quantity)totalQuantity,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0))+SUM(IF(TXNTypein_out = 'R',quantity,0)) AS StockInHand FROM tblvendorinventory WHERE vendorid='$vendorid'";
			        
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

				
				$salesdetail= "SELECT count(Quantity)as saleqty,SUM(Quantity)AS SaleQuantity FROM vendorsales WHERE vendorid='$vendorid'";
			
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
				
				
				$damagesdetail= "SELECT COUNT(Quatity)AS damqty,SUM(IF(damagestatus = 'Y',Quatity,0)) - SUM(IF(damagestatus = 'O',Quatity,0)) AS damageQuantity FROM vendordamagestock WHERE vendorid='$vendorid'";
			
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
				
				
				
				$replacementsdetail= "SELECT count(Quatity)as rep,SUM(Quatity)AS repqty FROM tblvendorreplacement WHERE vendorid='$vendorid' and DamageStatus='Y'";
			
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
                 <td>$vname</td>
				 <td>$quantity</td>
				 <td>$salequantity</td>
				 <td>$damagequantity</td>
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
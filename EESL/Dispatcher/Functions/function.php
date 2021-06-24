<?php
function consignmentDetails(){
$RefId=$_SESSION['Ref'];
$con=mysqli_connect("127.0.0.1","root","","led");
$per_page=5;
                   if (isset($_GET["page"]))
	                     {
                        $page = $_GET["page"];
                        }
                    else
                        {
                     $page=1;

                       }
                    $start_from = ($page-1) * $per_page;
	
	$Consignment="SELECT C.CosigmentId,C.ConsignemtNo,W.WareHouseCode,DATE_FORMAT(C.Consignmentdate,'%d-%m-%Y') as Consignmentdate,C.IndentQuantity,C.Quantity
	FROM 
	tblconsignment AS C JOIN tblwarehouseregistration AS W ON C.WarehouseId=W.WarehouseId WHERE W.RecStatus='A' AND C.RecStatus='A' 
	AND C.AllotmentStatus='P' AND C.WarehouseId='$RefId'
	ORDER BY C.CosigmentId DESC LIMIT $start_from,$per_page";
	$RunQuery=mysqli_query($con,$Consignment);
	while($row=mysqli_fetch_array($RunQuery))
	  {
		$ConsignmentId=$row['CosigmentId'];
		$ConsignmentNo=$row['ConsignemtNo'];
		$Wcode=$row['WareHouseCode'];
		$ConsignmentDate=$row['Consignmentdate'];
		$IndentQuant=$row['IndentQuantity'];
		$Quant=$row['Quantity'];
		echo"<tr>
                <td>$ConsignmentNo</td>
				 <td>$Wcode</td>
				<td>$ConsignmentDate</td>
				<td>$IndentQuant</td>
				<td>$Quant</td>
				<td>
				<a href='Consignment.php?id=$ConsignmentId' class='btn btn-danger' title='Delete Conisgnment Records' onclick = 'return confirm('Are you sure want to delete record')'><i class='fa fa-trash'></i></a> 
                </td>
				</tr>";
	}
   }
 ?>
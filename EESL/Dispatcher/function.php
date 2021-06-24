
<?php
function consignmentDetails(){
	$con=mysqli_connect("127.0.0.1","root","","led");
	
	$Consignment="select C.ConsignmentNo,C.Consignmentdate,C.Quantity as W.WareHouseName from
	tblconsignment as C join tblwarehouseregistration as W on C.WarehouseId=W.WarehouseId where W.RecStatus='A' and C.RecStatus='A'";
	
	$RunQuery=mysqli_query($con,$Consignment);
	while($row=mysqli_fetch_array($RunQuery))
	{
		$ConsignmentNo=$row['consignmentNo'];
		$ConsignmentDate=$row['consignmentdate'];
		$Quantity=$row['Quantity'];
		$WarehouseName=$row['WareHouseName'];
		
		echo"<tr>
        
				<td>$ConsignmentNo</td>
				<td>$ConsignmentDate</td>
				<td>$Quantity</td>
				<td>$WarehouseName</td>
				
				<td><button type='Submit'>Edit</button></td>
			</tr>";
	}
}?>
<?php
function issueslip(){
	$RefId=$_SESSION['Ref'];
	$con=mysqli_connect("localhost","root","","led");	
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
					
	$issue="SELECT tblissueslip.IssueSlipId,
	  tblissueslip.IssueCode,
	  tblissueslip.VendorRepresentativeName,
	  tblissueslip.Quantity,
	  tblissueslip.DispatchDate,
	  tblissueslip.IssueDate,
	  tblissueslip.DispatchProof,
      tblissueslip.IssueCode,
      tblissueslip.Rate,
	  tblissueslip.ContactNo,
	  tblwarehouseregistration.WareHouseCode,
	  tblvendorregistration.VendorName
	 FROM tblissueslip,tblwarehouseregistration,tblvendorregistration,tbldispatcherregistraion WHERE
	 tblissueslip.VendorId = tblvendorregistration.VendorId AND tblissueslip.WarehouseId = tblwarehouseregistration.warehouseId
	 AND tblissueslip.WarehouseId = tbldispatcherregistraion.DispatcherId
	 AND tblIssueSlip.RecStatus='A' AND tblwarehouseregistration.DisPatcherId ='$RefId' AND tblIssueSlip.IssueStatus ='C' 
	 ORDER BY tblissueslip.IssueSlipId DESC LIMIT $start_from,$per_page";
	
	$RunQuery=mysqli_query($con,$issue);
	
	while($row=mysqli_fetch_array($RunQuery))
	{
		$IssueSlipId=$row['IssueSlipId'];
		$IssueCode=$row['IssueCode'];
		$VendorName=$row['VendorName'];
		$WareHousecode=$row['WareHouseCode'];
		$Quantity=$row['Quantity'];
		$VendorRepresentativeName=$row['VendorRepresentativeName'];
		$ContactNO=$row['ContactNo'];
		$rate=$row['Rate'];
		echo"<tr>
                <td>$IssueCode</td>
				<td>$VendorName</td>
				<td>$WareHousecode</td>
				<td>$Quantity</td>
				<td>$VendorRepresentativeName</td>
				<td>$ContactNO</td>
				<td>
				<a href='../Dispatcher/viewissueSlip.php?IssueSlipId=$IssueSlipId' class='btn btn-info'> Dispatch</a>
				</td>
				</tr>";
	}
	
}





?>

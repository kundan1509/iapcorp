<?php
function getDistributor()
{
	$con=mysqli_connect("127.0.0.1","root","","led");
	$getQuery="SELECT 	v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.VendorStatus,v.ApplicationDate,p.PaymentId,p.PaymentType   
			FROM tblvendorregistration AS v JOIN tblpayment AS p ON  v.VendorId=p.VendorId
			WHERE v.RecStatus='A' AND v.VendorStatus='P' AND p.PaymentType='S'";
	$runQuery=mysqli_query($con,$getQuery);
	if($runQuery)
	{
		while($row=mysqli_fetch_array($runQuery))
		{
			$DistId=$row['VendorId'];			
			$DistCode=$row['VendorCode'];
			$DistName=$row['VendorName'];
			$DistContactNo=$row['ContactNumber'];
			$DistEmail=$row['EmailId'];
			$DsitStatus=$row['VendorStatus'];
			$originalAppDate =$row['ApplicationDate'];
			$newAppDate = date("d-m-Y", strtotime($originalAppDate));
			echo"<tr>
				<td>$DistCode</td>				
				<td>$DistName</td>
				<td>$DistContactNo</td>
				<td>$DistEmail</td>
				<td>$newAppDate</td>
				<td>
					<a href='../Finance Management/DistributionDetails.php?DistId=$DistId' class='btn btn-info'><i class='fa fa-list'></i></a>
				</td>
			</tr>";
	}
	}		
}


?>
<?php
function getAdvancePayment()
{
	$con=mysqli_connect("127.0.0.1","root","","led");
	$getQuery="SELECT 	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus 
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P'";
				
				$runQuery=mysqli_query($con,$getQuery);
				if($runQuery)
				{
					while($row=mysqli_fetch_array($runQuery))
					{
						$VendorName=$row['VendorName'];
						$VendorCode=$row['VendorCode'];
						$ContactNumber=$row['ContactNumber'];
						$emailId=$row['emailId'];
						$Amount=$row['Amount'];
						$PaymentId=$row['PaymentId'];			
						$ModeofPayment=$row['ModeofPayment'];
						$TaxnRefNo=$row['TaxnRefNo'];
						$PaymentDate=$row['PaymentDate'];
						$PaymentProof=$row['PaymentProof'];
						$PaymentType=$row['PaymentType'];
						$DepositBankName=$row['DepositBankName'];
						$originalActDate =$row['ActivationDate'];
						$newActDate = date("d-m-Y", strtotime($originalActDate));
												
						echo"<tr>
								<td>$VendorCode</td>				
								<td>$VendorName</td>
								<td>$ContactNumber</td>
								<td>$emailId</td>
								<td>$newActDate</td>
								<td>
									<a href='../Finance Management/PaymentVerificationDetails.php?PaymentId=$PaymentId' class='btn btn-info'><i class='fa fa-list'></i></a>
								</td>
							</tr>";
					}
			}
}
?>


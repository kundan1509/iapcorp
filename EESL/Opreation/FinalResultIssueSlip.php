<?php
session_start();
$inactive = 600;
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
		if(!($_SESSION['Name']&&$_SESSION['Type']=='RO' || $_SESSION['Type']=='SA'))
		{
			header("location:../Logout.php");
		}
		else
		{
			if(isset($_POST['SaveFinalIssueShilip']))
			{
				$totalPendingCurrentStock=$_POST['totpenstock'];
				$grandtotalpending=$_POST['gtstock'];
				$DistId=$_POST['VendorId'];
				$DistEmail=$_POST['VendorEmail'];
				$DistName=$_POST['VendorName'];
				$DistCode=$_POST['VendorCode'];
				$IssueDate=$_POST['IssueDate'];
				$Rate=$_POST['Rate'];
				$WarehouseId=$_POST['WarehouseId'];
				$WarehouseCode=$_POST['WarehouseCode'];
				$IssueCode=$_POST['IssueCode'];
				$Quantity=$_POST['Quantity'];
				$MaxAllowdQty=$_POST['AllotedQuantity'];
				$WalletAmount=$_POST['WalletAmount'];
				$VendorLocation=$_POST['DistCurrentLocId'];
				$DailyLimit=$_POST['DailyLimit'];
				$IssueCodeInc=$_POST['IssueCodeInc'];
	
	
				$totalWallet=$Quantity*$Rate;
				$CurrentWallet=$WalletAmount-$totalWallet;
	
				date_default_timezone_set("Asia/Kolkata"); 
				$CurrentDate=date('Y-m-d');
				if($WalletAmount<=0)
				{
					echo "<script>alert('Please Recharge Your Wallet !')</script>";
					echo "<script>window.open('IssueSlip.php','_self')</script>";	
				}
				else
				{
					$selectManager="SELECT d.dispatchername,d.emailid,d.contactnumber,d.mailingaddress FROM tbldispatcherregistraion AS d
					JOIN tblwarehouseregistration AS  w ON d.DispatcherId=w.DisPatcherId
					WHERE w.dispatcherid=d.dispatcherid AND w.Recstatus='A' AND w.WarehouseId='$WarehouseId'";
					$runManager=mysqli_query($con,$selectManager);
					$rowManager=mysqli_fetch_array($runManager);
					$DispatchMail=$rowManager['emailid'];
					$DispatchName=$rowManager['dispatchername'];
					$DispatchContact=$rowManager['contactnumber'];
					$DispatchAddress=$rowManager['mailingaddress'];	

					if($Quantity<=$MaxAllowdQty)
					{   
		
						if($Quantity<=$DailyLimit)
						{		
							if($totalPendingCurrentStock<=$DailyLimit)
							{
								if($Quantity<=$grandtotalpending)
								{
									$updatewallet="UPDATE tblwallet SET Balance='$CurrentWallet' WHERE VendorId='$DistId'";
									$runWallet=mysqli_query($con,$updatewallet);
									if($runWallet)
									{
					
					
										$insertIssueSlip="INSERT INTO tblissueslip(VendorID,IssueDate,Rate,WareHouseId,IssueStatus,IssueCode,LocationId,Quantity,TotalAmount,RecStatus,LastUpdateOn,UpdatebyID,IssueNumber)VALUES('$DistId','$IssueDate','$Rate','$WarehouseId','P','$IssueCode','$VendorLocation','$Quantity','$totalWallet','A','$CurrentDate','$UserId','$IssueCodeInc')";
										$RunIssueShilip=mysqli_query($con,$insertIssueSlip);
										if($RunIssueShilip)
										{
						                    
											$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
											$q=mysqli_query($con,$selectMail);
											$records = array();
											while($row = mysqli_fetch_assoc($q)){ 
												$records[] = $row;
											}
			   
											require_once('../PHPMailer_5.2.4/class.phpmailer.php');
											//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

											$mail = new PHPMailer();

											$mail->IsSMTP(); // telling the class to use SMTP
											$mail->Host  = 'mail.iapcorp.com'; // SMTP server
											//$mail->SMTPDebug  = 2;  

											// enables SMTP debug information (for testing)
											// 1 = errors and messages
											// 2 = messages only

											$mail->SetFrom('projectled@iapcorp.com', 'Opreation Team');
											$mail->AddReplyTo("projectled@iapcorp.com","Opreation Team");


											$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
											$mail->Subject    = "This is confirmation Message";
											$mail->Body = "<h3>Dear Distributor</h3>
											</br><h4>Please Check the Details of IssueSlip</h4>
												

											</br><p>IssueSlip Code: $IssueCode </p>
												

											</br><p>VendorName : $DistName</p>
												

											</br><p>Quantity: $Quantity</p>
												

											</br><p>Warehouse Code: $WarehouseCode</p>
												

											</br><p>Warehouse Manager Name: $DispatchName</p>
												

											</br><p>Warehouse Contact Number: $DispatchContact</p>
												

											</br><p>Warehouse Address: $DispatchAddress</p>
											
												

											<br><br><br>
												

											<h4>Thanks & Regards </h4>
												

											<p>OpreationTeam</p><br>";
											$mail->AddAddress($DistEmail);
											$mail->addCC($DispatchMail);
											foreach($records as $rec)
											{	  
												$romail=$rec['RO'];
												$adminmail=$rec['SuperAdmin'];
												$finmail=$rec['Finance'];				
												$mail->addCC($romail);	
												$mail->addCC($adminmail);										
												$mail->Send();
												$mail->ClearAllRecipients();
											}
											if(!$mail->Send()) {
  									
												echo "<script>alert('Issue slip created successfully !')</script>";
												echo "<script>window.open('IssueSlip.php','_self')</script>";
											}
											else
											{
												echo "<script>alert('Issue slip created successfully !')</script>";
												echo "<script>window.open('IssueSlip.php','_self')</script>";
											}
										}
										else
										{
											echo "<script>alert('Data not inserted !')</script>";
											echo "<script>window.open('IssueSlip.php','_self')</script>";
										}
									}
									else
									{
				
										echo "<script>alert('Wallet Not deducted !')</script>";
										echo "<script>window.open('IssueSlip.php','_self')</script>";
									}
								}
								else
								{
									echo "<script>alert('Your Stock is full please check Stock or pending issue')</script>";
									echo "<script>window.open('IssueSlip.php','_self')</script>";
						
								}
							}
							else
							{
								echo "<script>alert('Quantity could not be greater than stock limit!Pleae check pending issue')</script>";
								echo "<script>window.open('IssueSlip.php','_self')</script>";
							}
				
		
						}
						else
						{
							echo "<script>alert('You have Max. Stock Limit $DailyLimit !')</script>";
							echo "<script>window.open('IssueSlip.php','_self')</script>";
						}
					}
					else
					{
						echo "<script>alert('You have entered more than Max. allowed quantity $MaxAllowdQty!')</script>";
						echo "<script>window.open('IssueSlip.php','_self')</script>";
					}	
				}
			}
			else
			{
				echo "<script>window.open('IssueSlip.php','_self')</script>";
			}
		}
	}
}
$_SESSION['timeout'] = time();
?>
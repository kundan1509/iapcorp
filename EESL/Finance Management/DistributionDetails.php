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
		if(!($_SESSION['Name']&&$_SESSION['Type']=='FA' || $_SESSION['Type']=='SA'))
		{
			header("location:../Logout.php");
		}
		else
		{		
			if(isset($_GET['DistId']))
			{
				$VID=$_GET['DistId'];
				/*$VenderId='';$VCode='';$VName='';$VContact='';$VEmail='';$VCorpStatus='';$VPanNo='';$VSTexNo='';$AppDate='';$ADD1='';$ADD2='';
				$TownCity='';$Distric='';$State='';$PinCode='';$ACHolderName='';$ACNumber='';$BankName='';$IFSC='';$BankCity_Town='';
				$PoolACNo='';$PoolACIFSC='';$Amount='';$ModeofPayment='';$TaxnRefNo='';$PaymentDate='';$Refferedby='';$ReferalName='';	*/
		
				$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,v.RecStatus,
				v.LastUpdateOn,v.UpdatebyID,v.Refferedby ,v.ReferalName   ,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.Address1,v.Address2,v.Town_City,v.Distt,
				v.State,Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,v.PoolACNo,v.PoolACIFSC,v.Refferedby, 
				v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,
				p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,p.DepositBankName,p.VerificationRemark
				FROM tblvendorregistration AS v 
				JOIN tblpayment AS p ON  v.VendorId=p.VendorId		
				WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VID'";
				$runQuery=mysqli_query($con,$getQuery);	
				if($runQuery)
				{						
					while($row=mysqli_fetch_array($runQuery))
					{
						$VenderId=$row['VendorId'];
						$VCode=$row['VendorCode'];
						$VName=$row['VendorName'];
						$VContact=$row['ContactNumber'];
						$VEmail=$row['EmailId'];
						$VCorpStatus=$row['CorporateStatus'];
						$VPanNo=$row['PanNo'];
						$VSTexNo=$row['ServiceTaxNo'];
				
						$ADD1=$row['Address1'];
						$ADD2=$row['Address2'];
						$TownCity=$row['Town_City'];
						$Distric=$row['Distt'];
						$State=$row['State'];
						$PinCode=$row['Pin'];
							
						$ACHolderName=$row['ACHolderName'];
						$ACNumber=$row['ACNumber'];
						$BankName=$row['BankName'];
						$IFSC=$row['IFSC'];
						$BankCity_Town=$row['BankCity_Town'];
						$PaymentId=$row['PaymentId'];
						$payProof=$row['PaymentProof'];
						
						$PoolACNo=$row['PoolACNo'];
						$PoolACIFSC=$row['PoolACIFSC'];
						$Amount=$row['Amount'];
						$ModeofPayment=$row['ModeofPayment'];
						$TaxnRefNo=$row['TaxnRefNo'];
						$originalAppDate =$row['ApplicationDate'];
						$AppDate = date("d-m-Y", strtotime($originalAppDate));
						$originalPayDate =$row['PaymentDate'];
						$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
						$PayType=$row['PaymentType'];
						if($PayType=='S')
						{
							$PayType='Security';
						}
				
						$Refferedby=$row['Refferedby'];
						$ReferalName=$row['ReferalName'];												
					}
				}
				$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode
				FROM tblvendorlocation AS vl
				JOIN tbllocation AS l ON vl.LocationId=l.LocId
				WHERE vl.RecStatus='A' AND vl.VendorId='$VID' ORDER BY vl.ID ASC";
				$RunLocation=mysqli_query($con,$VendorLocation);
				$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VID' AND DocumentName='PanCard'";
				$RunDocument=mysqli_query($con,$DocQuery);
				$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VID' AND DocumentName='Address'";
				$RunAddress=mysqli_query($con,$DocAddress);
				$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VID' AND DocumentName='Cheque'";
				$RunCanceled=mysqli_query($con,$DocCanceledCheque);
			}
			else if(isset($_POST['SaveChanges']))
			{		
				if(isset($_POST['Rbtn']))
				{
					date_default_timezone_set("Asia/Kolkata"); 
					$CurrentDate=date('Y-m-d H:i:s A');		
					$Accept=$_POST['Rbtn'];
					$FillSecAmount=$_POST['Amount'];			
					$VEnid=$_POST['VId'];
					$VECode=$_POST['VCODE'];
					$PaYid=$_POST['PId'];
					$distEmail=$_POST['VMAIL'];
					if(($Accept=='A'))
					{
						$selectStdEligibility="SELECT count(Id) as cnt,Id,dailylimit AS DailyLimit,SecurityAmmount AS SecurityAmmount
FROM  tblstanderdeligibility
WHERE  SecurityAmmount=(SELECT MAX(SecurityAmmount)AS SecurityAmmount
              FROM tblstanderdeligibility
              WHERE SecurityAmmount<='$FillSecAmount')";
			  
						$RunEligibility=mysqli_query($con,$selectStdEligibility);
						$checkbal=mysqli_fetch_array($RunEligibility);
                           if($checkbal['cnt']>0)
							{						
						
					    $SecId=$checkbal['Id'];
						$DailyLimit1=$checkbal['DailyLimit'];
						$SecAmount=$checkbal['SecurityAmmount'];
						
							if($RunEligibility)
							{
								$AcceptQuery="UPDATE tblvendorregistration SET VendorStatus='A',Description='Distributor Verification Accepted',ActivationDate='$CurrentDate',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',MailStatus='Y',RecStatus='A'
								WHERE VendorId='$VEnid' AND RecStatus='A' AND VendorStatus='P'";
								$runAccept=mysqli_query($con,$AcceptQuery);
								if($runAccept)
								{
									$AcceptPayment="UPDATE tblpayment SET FinanceVerification='A',VerificationRemark='Security Payment Verified',
									LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',RecStatus='A'
									WHERE VendorId='$VEnid' AND RecStatus='A' AND PaymentType='S' AND PaymentId='$PaYid'";
									$runPayment=mysqli_query($con,$AcceptPayment);
									if($runPayment)
									{
										$insVendorEligability="INSERT INTO tblvendoreligibility (VendorId,DailyLimit,TotalSecurity,Lastupdateon,updatebyid) VALUES ('$VEnid','$DailyLimit1','$FillSecAmount','$CurrentDate','$UserId')";
										
										$RunEligability=mysqli_query($con,$insVendorEligability);
										if($RunEligability)	
										{
											$insWallet="INSERT INTO tblwallet(VendorId,Balance,LastUpdateOn,UpdatebyID) VALUES ('$VEnid','00','$CurrentDate','$UserId')";
											$RunWallet=mysqli_query($con,$insWallet);
											if($RunWallet)
											{
												$UpdateUser="UPDATE tbluser SET FinanceVerificationStatus='A',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId' WHERE RefId='$VEnid' AND RecStatus='A' AND UserTypes='VN'";
												$runUser=mysqli_query($con,$UpdateUser);
												if($runUser)
												{
													$selectUsers="SELECT u.Id,u.UserId,u.PASSWORD,u.RefId,v.VendorCode,v.VendorName FROM tbluser AS u JOIN tblvendorregistration AS v ON v.VendorId=u.RefId WHERE u.RefId='$VEnid' AND u.UserTypes='VN' AND v.VendorStatus='A'";
													$runSelectUser=mysqli_query($con,$selectUsers);
													if($runSelectUser)
													{
														while($rowUser=mysqli_fetch_array($runSelectUser))
														{
															$userEmail=$rowUser['UserId'];
															$userPassword=$rowUser['PASSWORD'];
															$DistName=$rowUser['VendorName'];
															$DistCode=$rowUser['VendorCode'];
															$descode=base64_decode($userPassword);
														}														
													$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
													$q=mysqli_query($con,$selectMail);
													$records = array();
													while($row = mysqli_fetch_assoc($q)){ 
														$records[] = $row;
													}
														
														require_once('../PHPMailer_5.2.4/class.phpmailer.php');
														//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

														$mail             = new PHPMailer();

														$mail->IsSMTP(); // telling the class to use SMTP
														$mail->Host       = 'mail.iapcorp.com'; // SMTP server
														             // enables SMTP debug information (for testing)
														// 1 = errors and messages
														// 2 = messages only

														$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
														$mail->AddReplyTo('projectled@iapcorp.com','IAP Corporation');


														$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
														$mail->Subject    = "Regarding registration with LED distribution system";
														$mail->Body = "<h3>Dear Operation Team,</h3>
															</br>
															</br><p>The Distributor code <b>$VECode</b> has been accepted. The detailed document proof has been verified and the security money has been realized.</p>
															<br><p>Distributor Name :	$DistName</p>
															<p>User ID :	$userEmail</p>
															<p>Password :	$descode</p>
															<br><br><br>
															<h4>Regards, </h4>
															<p>Accounts Team</p>												
															";							
								$mail->AddAddress($distEmail);												
								foreach($records as $rec)
								{	  
									$romail=$rec['RO'];
									$adminmail=$rec['SuperAdmin'];
									$finmail=$rec['Finance'];				
									
									$mail->addCC($romail);
									$mail->addCC($adminmail);									
									$mail->addCC($finmail);
									$mail->Send();
									$mail->ClearAllRecipients();
								}
								if(!$mail->Send()) {  									
									echo "<script>alert(' Approval status accepted successfully !')</script>";
									echo "<script>window.open('DistributorVerify.php','_self')</script>";
								} 
								else 
								{
									
									echo "<script>alert(' Approval status accepted successfully !')</script>";
									echo "<script>window.open('DistributorVerify.php','_self')</script>";
								}
							}																		
						}
						else
						{
							echo "<script>window.open('DistributorVerify.php','_self')</script>";	
						}
					} 
					else
					{
						echo "<script>window.open('DistributorVerify.php','_self')</script>";	
					}
				}
				else
				{
											$VEnid=$_POST['VId'];			
											$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
											v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
											v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
											v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
											p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
											p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
											JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
											JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
											WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
											$runQuery=mysqli_query($con,$getQuery);	
											if($runQuery)
											{
												while($row=mysqli_fetch_array($runQuery))
												{
													$VenderId=$row['VendorId'];
													$VCode=$row['VendorCode'];
													$VName=$row['VendorName'];
													$VContact=$row['ContactNumber'];
													$VEmail=$row['EmailId'];
													$VCorpStatus=$row['CorporateStatus'];
													$VPanNo=$row['PanNo'];
													$VSTexNo=$row['ServiceTaxNo'];
								
							
													$DocumentName=$row['DocumentName'];
													$DocumentURL=$row['DocumentURL'];
								
													$ADD1=$row['Address1'];
													$ADD2=$row['Address2'];
													$TownCity=$row['Town_City'];
													$Distric=$row['Distt'];
													$State=$row['State'];
													$PinCode=$row['Pin'];
							
													$ACHolderName=$row['ACHolderName'];
													$ACNumber=$row['ACNumber'];
													$BankName=$row['BankName'];
													$IFSC=$row['IFSC'];
													$BankCity_Town=$row['BankCity_Town'];
													$PaymentId=$row['PaymentId'];
													$payProof=$row['PaymentProof'];
							
													$PoolACNo=$row['PoolACNo'];
													$PoolACIFSC=$row['PoolACIFSC'];
													$Amount=$row['Amount'];
													$ModeofPayment=$row['ModeofPayment'];
													$TaxnRefNo=$row['TaxnRefNo'];
													$originalAppDate =$row['ApplicationDate'];
													$AppDate = date("d-m-Y", strtotime($originalAppDate));
													$originalPayDate =$row['PaymentDate'];
													$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
													$PayType=$row['PaymentType'];
													if($PayType=='S')
													{
														$PayType='Security';
													}
													$Refferedby=$row['Refferedby'];
													$ReferalName=$row['ReferalName'];
												}
											}
											$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
											JOIN tbllocation AS l ON vl.LocationId=l.LocId
											WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
											$RunLocation=mysqli_query($con,$VendorLocation);
											$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
											$RunDocument=mysqli_query($con,$DocQuery);
		
											$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
											$RunAddress=mysqli_query($con,$DocAddress);
		
											$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
											$RunCanceled=mysqli_query($con,$DocCanceledCheque);
										}
									}
									else
									{
										$VEnid=$_POST['VId'];			
										$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
										v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
										v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
										v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
										p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
										p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
										JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
										JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
										WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
										$runQuery=mysqli_query($con,$getQuery);	
										if($runQuery)
										{
											while($row=mysqli_fetch_array($runQuery))
											{
												$VenderId=$row['VendorId'];
												$VCode=$row['VendorCode'];
												$VName=$row['VendorName'];
												$VContact=$row['ContactNumber'];
												$VEmail=$row['EmailId'];
												$VCorpStatus=$row['CorporateStatus'];
												$VPanNo=$row['PanNo'];
												$VSTexNo=$row['ServiceTaxNo'];
								
							
												$DocumentName=$row['DocumentName'];
												$DocumentURL=$row['DocumentURL'];
							
												$ADD1=$row['Address1'];
												$ADD2=$row['Address2'];
												$TownCity=$row['Town_City'];
												$Distric=$row['Distt'];
												$State=$row['State'];
												$PinCode=$row['Pin'];
							
												$ACHolderName=$row['ACHolderName'];
												$ACNumber=$row['ACNumber'];
												$BankName=$row['BankName'];
												$IFSC=$row['IFSC'];
												$BankCity_Town=$row['BankCity_Town'];
												$PaymentId=$row['PaymentId'];
												$payProof=$row['PaymentProof'];
							
												$PoolACNo=$row['PoolACNo'];
												$PoolACIFSC=$row['PoolACIFSC'];
												$Amount=$row['Amount'];
												$ModeofPayment=$row['ModeofPayment'];
												$TaxnRefNo=$row['TaxnRefNo'];
												$originalAppDate =$row['ApplicationDate'];
												$AppDate = date("d-m-Y", strtotime($originalAppDate));
												$originalPayDate =$row['PaymentDate'];
												$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
												$PayType=$row['PaymentType'];
												if($PayType=='S')
												{
													$PayType='Security';
												}
												$Refferedby=$row['Refferedby'];
												$ReferalName=$row['ReferalName'];
											}
										}
										$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
										JOIN tbllocation AS l ON vl.LocationId=l.LocId
										WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
										$RunLocation=mysqli_query($con,$VendorLocation);
										$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
										$RunDocument=mysqli_query($con,$DocQuery);
		
										$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
										$RunAddress=mysqli_query($con,$DocAddress);
		
										$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
										$RunCanceled=mysqli_query($con,$DocCanceledCheque);
									}
								}
								else
								{
									$VEnid=$_POST['VId'];			
									$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
									v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
									v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
									v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
									p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
									p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
									JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
									JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
									WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
									$runQuery=mysqli_query($con,$getQuery);	
									if($runQuery)
									{
										while($row=mysqli_fetch_array($runQuery))
										{
											$VenderId=$row['VendorId'];
											$VCode=$row['VendorCode'];
											$VName=$row['VendorName'];
											$VContact=$row['ContactNumber'];
											$VEmail=$row['EmailId'];
											$VCorpStatus=$row['CorporateStatus'];
											$VPanNo=$row['PanNo'];
											$VSTexNo=$row['ServiceTaxNo'];
								
							
											$DocumentName=$row['DocumentName'];
											$DocumentURL=$row['DocumentURL'];
							
											$ADD1=$row['Address1'];
											$ADD2=$row['Address2'];
											$TownCity=$row['Town_City'];
											$Distric=$row['Distt'];
											$State=$row['State'];
											$PinCode=$row['Pin'];
							
											$ACHolderName=$row['ACHolderName'];
											$ACNumber=$row['ACNumber'];
											$BankName=$row['BankName'];
											$IFSC=$row['IFSC'];
											$BankCity_Town=$row['BankCity_Town'];
											$PaymentId=$row['PaymentId'];
											$payProof=$row['PaymentProof'];
							
											$PoolACNo=$row['PoolACNo'];
											$PoolACIFSC=$row['PoolACIFSC'];
											$Amount=$row['Amount'];
											$ModeofPayment=$row['ModeofPayment'];
											$TaxnRefNo=$row['TaxnRefNo'];
											$originalAppDate =$row['ApplicationDate'];
											$AppDate = date("d-m-Y", strtotime($originalAppDate));
											$originalPayDate =$row['PaymentDate'];
											$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
											$PayType=$row['PaymentType'];
											if($PayType=='S')
											{
												$PayType='Security';
											}
											$Refferedby=$row['Refferedby'];
											$ReferalName=$row['ReferalName'];
										}
									}
									$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
									JOIN tbllocation AS l ON vl.LocationId=l.LocId
									WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
									$RunLocation=mysqli_query($con,$VendorLocation);
									$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
									$RunDocument=mysqli_query($con,$DocQuery);
		
									$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
									$RunAddress=mysqli_query($con,$DocAddress);
		
									$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
									$RunCanceled=mysqli_query($con,$DocCanceledCheque);
								}
							}
							else
							{
								$VEnid=$_POST['VId'];			
								$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
								v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
								v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
								v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
								p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
								p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
								JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
								JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
								WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
								$runQuery=mysqli_query($con,$getQuery);	
								if($runQuery)
								{
									while($row=mysqli_fetch_array($runQuery))
									{
										$VenderId=$row['VendorId'];
										$VCode=$row['VendorCode'];
										$VName=$row['VendorName'];
										$VContact=$row['ContactNumber'];
										$VEmail=$row['EmailId'];
										$VCorpStatus=$row['CorporateStatus'];
										$VPanNo=$row['PanNo'];
										$VSTexNo=$row['ServiceTaxNo'];
							
										$DocumentName=$row['DocumentName'];
										$DocumentURL=$row['DocumentURL'];
							
										$ADD1=$row['Address1'];
										$ADD2=$row['Address2'];
										$TownCity=$row['Town_City'];
										$Distric=$row['Distt'];
										$State=$row['State'];
										$PinCode=$row['Pin'];
							
										$ACHolderName=$row['ACHolderName'];
										$ACNumber=$row['ACNumber'];
										$BankName=$row['BankName'];
										$IFSC=$row['IFSC'];
										$BankCity_Town=$row['BankCity_Town'];
										$PaymentId=$row['PaymentId'];
										$payProof=$row['PaymentProof'];
							
										$PoolACNo=$row['PoolACNo'];
										$PoolACIFSC=$row['PoolACIFSC'];
										$Amount=$row['Amount'];
										$ModeofPayment=$row['ModeofPayment'];
										$TaxnRefNo=$row['TaxnRefNo'];
										$originalAppDate =$row['ApplicationDate'];
										$AppDate = date("d-m-Y", strtotime($originalAppDate));
										$originalPayDate =$row['PaymentDate'];
										$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
										$PayType=$row['PaymentType'];
										if($PayType=='S')
										{
											$PayType='Security';
										}
										$Refferedby=$row['Refferedby'];
										$ReferalName=$row['ReferalName'];
									}
								}
								$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
								JOIN tbllocation AS l ON vl.LocationId=l.LocId
								WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
								$RunLocation=mysqli_query($con,$VendorLocation);
								$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
								$RunDocument=mysqli_query($con,$DocQuery);
		
								$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
								$RunAddress=mysqli_query($con,$DocAddress);
		
								$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
								$RunCanceled=mysqli_query($con,$DocCanceledCheque);
						
								echo "<script>alert(' Security Amount is not Sufficient  !')</script>";
							}
						
							}
							else
							{
								$VEnid=$_POST['VId'];			
								$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
								v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
								v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
								v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
								p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
								p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
								JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
								JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
								WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
								$runQuery=mysqli_query($con,$getQuery);	
								if($runQuery)
								{
									while($row=mysqli_fetch_array($runQuery))
									{
										$VenderId=$row['VendorId'];
										$VCode=$row['VendorCode'];
										$VName=$row['VendorName'];
										$VContact=$row['ContactNumber'];
										$VEmail=$row['EmailId'];
										$VCorpStatus=$row['CorporateStatus'];
										$VPanNo=$row['PanNo'];
										$VSTexNo=$row['ServiceTaxNo'];
							
										$DocumentName=$row['DocumentName'];
										$DocumentURL=$row['DocumentURL'];
							
										$ADD1=$row['Address1'];
										$ADD2=$row['Address2'];
										$TownCity=$row['Town_City'];
										$Distric=$row['Distt'];
										$State=$row['State'];
										$PinCode=$row['Pin'];
							
										$ACHolderName=$row['ACHolderName'];
										$ACNumber=$row['ACNumber'];
										$BankName=$row['BankName'];
										$IFSC=$row['IFSC'];
										$BankCity_Town=$row['BankCity_Town'];
										$PaymentId=$row['PaymentId'];
										$payProof=$row['PaymentProof'];
							
										$PoolACNo=$row['PoolACNo'];
										$PoolACIFSC=$row['PoolACIFSC'];
										$Amount=$row['Amount'];
										$ModeofPayment=$row['ModeofPayment'];
										$TaxnRefNo=$row['TaxnRefNo'];
										$originalAppDate =$row['ApplicationDate'];
										$AppDate = date("d-m-Y", strtotime($originalAppDate));
										$originalPayDate =$row['PaymentDate'];
										$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
										$PayType=$row['PaymentType'];
										if($PayType=='S')
										{
											$PayType='Security';
										}
										$Refferedby=$row['Refferedby'];
										$ReferalName=$row['ReferalName'];
									}
								}
								$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
								JOIN tbllocation AS l ON vl.LocationId=l.LocId
								WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
								$RunLocation=mysqli_query($con,$VendorLocation);
								$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
								$RunDocument=mysqli_query($con,$DocQuery);
		
								$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
								$RunAddress=mysqli_query($con,$DocAddress);
		
								$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
								$RunCanceled=mysqli_query($con,$DocCanceledCheque);
									echo "<script>alert(' Security Amount is not Sufficient  !')</script>";
							}
					}
					else if($Accept=='R')
					{
						$Reasons=$_POST['RejectReasons'];
						$ReasonsText=$_POST['ReasonText'];
						if($Reasons!='0' && $Reasons!='Other')
						{
							$RejectQuery="UPDATE tblvendorregistration SET VendorStatus='R',Description='$Reasons',ActivationDate='$CurrentDate',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',RecStatus='D'
							WHERE VendorId='$VEnid' AND RecStatus='A' AND VendorStatus='P'";
                                                        
                                                        
                                                        $RejectLocFree="DELETE FROM tblvendorlocation WHERE VendorId='$VEnid'";
                                                        mysqli_query($con,$RejectLocFree);
                                                        
							$runReject=mysqli_query($con,$RejectQuery);
							if($runReject)
							{
								$RejectPayment="UPDATE tblpayment SET FinanceVerification='R',VerificationRemark='$Reasons',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',RecStatus='D'
								WHERE VendorId='$VEnid' AND RecStatus='A' AND PaymentType='S' AND PaymentId='$PaYid'";
								$runPayment=mysqli_query($con,$RejectPayment);
								if($runPayment)
								{
									$RejectUser="UPDATE tbluser SET FinanceVerificationStatus='R',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId',RecStatus='D' WHERE RefId='$VEnid' AND RecStatus='A' AND UserTypes='VN'";
									$runRejectuser=mysqli_query($con,$RejectUser);
									if($runRejectuser)
									{
										$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
													$q=mysqli_query($con,$selectMail);
													$records = array();
													while($row = mysqli_fetch_assoc($q)){ 
														$records[] = $row;
													}
													
											require_once('../PHPMailer_5.2.4/class.phpmailer.php');
												//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

											$mail             = new PHPMailer();

											$mail->IsSMTP(); // telling the class to use SMTP
											$mail->Host       = 'mail.iapcorp.com'; // SMTP server
											//$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
											// 1 = errors and messages
												// 2 = messages only

											$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
											$mail->AddReplyTo('projectled@iapcorp.com');


											$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
											$mail->Subject    = "Regarding registration with LED distribution system";
											$mail->Body = "<h3>Dear Operation Team,</h3>
											</br>
											</br><p>The Distributor <b>$VECode</b> has been rejected due to below reason:</p>
											<br><br>
											<p><b>=> $Reasons</b></p>
											<br><br><br>
											<h4>Regards, </h4>
											<p>Accounts Team</p>
											";								
								$mail->AddAddress($distEmail);															
								foreach($records as $rec)
								{	  
									$romail=$rec['RO'];
									$adminmail=$rec['SuperAdmin'];
									$finmail=$rec['Finance'];				
									
									$mail->addCC($romail);
									$mail->addCC($adminmail);									
									$mail->addCC($finmail);
									$mail->Send();
									$mail->ClearAllRecipients();
								}
										if(!$mail->Send()) 
											{
												echo "<script>alert(' Approval status rejected successfully !')</script>";
												echo "<script>window.open('DistributorVerify.php','_self')</script>";
											} 
											else 
											{
												echo "<script>alert(' Approval status rejected successfully !')</script>";
												echo "<script>window.open('DistributorVerify.php','_self')</script>";	
											}								
									}
									else
									{
										$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
													$q=mysqli_query($con,$selectMail);
													$records = array();
													while($row = mysqli_fetch_assoc($q)){ 
														$records[] = $row;
													}		
											require_once('../PHPMailer_5.2.4/class.phpmailer.php');
											//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
											$mail             = new PHPMailer();

											$mail->IsSMTP(); // telling the class to use SMTP
											$mail->Host       = 'mail.iapcorp.com'; // SMTP server
											//$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
											// 1 = errors and messages
											// 2 = messages only
											$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
											$mail->AddReplyTo('projectled@iapcorp.com');


											$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
											$mail->Subject    = "Regarding registration with LED distribution system";
											$mail->Body = "<h3>Dear Operation Team,</h3>
											</br>
											</br><p>The Distributor <b>$VECode</b> has been rejected due to below reason:</p>
											<br><br>
											<p><b>=> $Reasons</b></p>
											<br><br><br>
											<h4>Regards, </h4>
											<p>Accounts Team</p>
											";								
								$mail->AddAddress($distEmail);															
								foreach($records as $rec)
								{	  
									$romail=$rec['RO'];
									$adminmail=$rec['SuperAdmin'];
									$finmail=$rec['Finance'];				
									
									$mail->addCC($romail);
									$mail->addCC($adminmail);									
									$mail->addCC($finmail);
									$mail->Send();
									$mail->ClearAllRecipients();
								}
										if(!$mail->Send()) 
											{
												echo "<script>alert(' Approval status rejected successfully !')</script>";
												echo "<script>window.open('DistributorVerify.php','_self')</script>";
											} 
											else 
											{
												echo "<script>alert(' Approval status rejected successfully !')</script>";
												echo "<script>window.open('DistributorVerify.php','_self')</script>";	
											}
								
									}			
								}
								else
								{
									$VEnid=$_POST['VId'];			
									$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
									v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
									v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
									v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
									p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
									p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
									JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
									JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
									WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
									$runQuery=mysqli_query($con,$getQuery);	
									if($runQuery)
									{
										while($row=mysqli_fetch_array($runQuery))
										{
											$VenderId=$row['VendorId'];
											$VCode=$row['VendorCode'];
											$VName=$row['VendorName'];
											$VContact=$row['ContactNumber'];
											$VEmail=$row['EmailId'];
											$VCorpStatus=$row['CorporateStatus'];
											$VPanNo=$row['PanNo'];
											$VSTexNo=$row['ServiceTaxNo'];
							
											$DocumentName=$row['DocumentName'];
											$DocumentURL=$row['DocumentURL'];
							
											$ADD1=$row['Address1'];
											$ADD2=$row['Address2'];
											$TownCity=$row['Town_City'];
											$Distric=$row['Distt'];
											$State=$row['State'];
											$PinCode=$row['Pin'];
							
											$ACHolderName=$row['ACHolderName'];
											$ACNumber=$row['ACNumber'];
											$BankName=$row['BankName'];
											$IFSC=$row['IFSC'];
											$BankCity_Town=$row['BankCity_Town'];
											$PaymentId=$row['PaymentId'];
											$payProof=$row['PaymentProof'];
							
											$PoolACNo=$row['PoolACNo'];
											$PoolACIFSC=$row['PoolACIFSC'];
											$Amount=$row['Amount'];
											$ModeofPayment=$row['ModeofPayment'];
											$TaxnRefNo=$row['TaxnRefNo'];
											$originalAppDate =$row['ApplicationDate'];
											$AppDate = date("d-m-Y", strtotime($originalAppDate));
											$originalPayDate =$row['PaymentDate'];
											$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
											$PayType=$row['PaymentType'];
											if($PayType=='S')
											{
												$PayType='Security';
											}
											$Refferedby=$row['Refferedby'];
											$ReferalName=$row['ReferalName'];
										}
									}
									$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
									JOIN tbllocation AS l ON vl.LocationId=l.LocId
									WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
									$RunLocation=mysqli_query($con,$VendorLocation);
									$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
									$RunDocument=mysqli_query($con,$DocQuery);
		
									$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
									$RunAddress=mysqli_query($con,$DocAddress);
			
									$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
									$RunCanceled=mysqli_query($con,$DocCanceledCheque);
									echo "<script>alert('Security Payment Not Rejected Check Details !')</script>";
								}
							}
							else
							{
								$VEnid=$_POST['VId'];			
								$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
								v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
								v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
								v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
								p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
								p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
								JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
								JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
								WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
								$runQuery=mysqli_query($con,$getQuery);	
								if($runQuery)
								{
									while($row=mysqli_fetch_array($runQuery))
									{
										$VenderId=$row['VendorId'];
										$VCode=$row['VendorCode'];
										$VName=$row['VendorName'];
										$VContact=$row['ContactNumber'];
										$VEmail=$row['EmailId'];
										$VCorpStatus=$row['CorporateStatus'];
										$VPanNo=$row['PanNo'];
										$VSTexNo=$row['ServiceTaxNo'];
							
										$DocumentName=$row['DocumentName'];
										$DocumentURL=$row['DocumentURL'];
							
										$ADD1=$row['Address1'];
										$ADD2=$row['Address2'];
										$TownCity=$row['Town_City'];
										$Distric=$row['Distt'];
										$State=$row['State'];
										$PinCode=$row['Pin'];
							
										$ACHolderName=$row['ACHolderName'];
										$ACNumber=$row['ACNumber'];
										$BankName=$row['BankName'];
										$IFSC=$row['IFSC'];
										$BankCity_Town=$row['BankCity_Town'];
										$PaymentId=$row['PaymentId'];
										$payProof=$row['PaymentProof'];
							
										$PoolACNo=$row['PoolACNo'];
										$PoolACIFSC=$row['PoolACIFSC'];
										$Amount=$row['Amount'];
										$ModeofPayment=$row['ModeofPayment'];
										$TaxnRefNo=$row['TaxnRefNo'];
										$originalAppDate =$row['ApplicationDate'];
										$AppDate = date("d-m-Y", strtotime($originalAppDate));
										$originalPayDate =$row['PaymentDate'];
										$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
										$PayType=$row['PaymentType'];
										if($PayType=='S')
										{
											$PayType='Security';
										}
										$Refferedby=$row['Refferedby'];
										$ReferalName=$row['ReferalName'];
									}
								}
								$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
								JOIN tbllocation AS l ON vl.LocationId=l.LocId
								WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
								$RunLocation=mysqli_query($con,$VendorLocation);
								$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
								$RunDocument=mysqli_query($con,$DocQuery);
		
								$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
								$RunAddress=mysqli_query($con,$DocAddress);
			
								$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
								$RunCanceled=mysqli_query($con,$DocCanceledCheque);
								echo "<script>alert(' Approval Status Not Rejected Check Details and Try Again !')</script>";
							}
						}
						else if($Reasons=='Other')
						{
							if($ReasonsText!='')
							{
								$RejectQuery="UPDATE tblvendorregistration SET VendorStatus='R',Description='$ReasonsText',ActivationDate='$CurrentDate',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',RecStatus='D'
								WHERE VendorId='$VEnid' AND RecStatus='A' AND VendorStatus='P'";
								$runReject=mysqli_query($con,$RejectQuery);
								if($runReject)
								{
									$RejectPayment="UPDATE tblpayment SET FinanceVerification='R',VerificationRemark='$ReasonsText',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',RecStatus='D'
									WHERE VendorId='$VEnid' AND RecStatus='A' AND PaymentType='S' AND PaymentId='$PaYid'";
									$runPayment=mysqli_query($con,$RejectPayment);
									if($runPayment)
									{
										$RejectUser="UPDATE tbluser SET FinanceVerificationStatus='R',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId',RecStatus='D' WHERE RefId='$VEnid' AND RecStatus='A' AND UserTypes='VN'";
										$runRejectuser=mysqli_query($con,$RejectUser);
										if($runRejectuser)
										{
											$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
													$q=mysqli_query($con,$selectMail);
													$records = array();
													while($row = mysqli_fetch_assoc($q)){ 
														$records[] = $row;
													}
												require_once('../PHPMailer_5.2.4/class.phpmailer.php');
												//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

												$mail             = new PHPMailer();

												$mail->IsSMTP(); // telling the class to use SMTP
												$mail->Host       = 'mail.iapcorp.com'; // SMTP server
												//$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
												// 1 = errors and messages
												// 2 = messages only

												$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
												$mail->AddReplyTo('projectled@iapcorp.com');


												$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
												$mail->Subject    = "Regarding registration with LED distribution system";
												$mail->Body = "<h3>Dear Operation Team,</h3>
												</br>
												</br><p>The Distributor <b>$VECode</b> has been rejected due to below reason:</p>
												<br><br>
												<p><b>=> $ReasonsText</b></p>
												<br><br><br>
												<h4>Regards, </h4>
												<p>Accounts Team</p>
												
												";
								$mail->AddAddress($distEmail);							
								foreach($records as $rec)
								{	  
									$romail=$rec['RO'];
									$adminmail=$rec['SuperAdmin'];
									$finmail=$rec['Finance'];				
									
									$mail->addCC($romail);
									$mail->addCC($adminmail);									
									$mail->addCC($finmail);
									$mail->Send();
									$mail->ClearAllRecipients();
								}											
											if(!$mail->Send()) 
											{
												echo "<script>alert(' Approval status rejected successfully !')</script>";
												echo "<script>window.open('DistributorVerify.php','_self')</script>";
											} 
											else 
											{
												echo "<script>alert(' Approval status rejected successfully !')</script>";
												echo "<script>window.open('DistributorVerify.php','_self')</script>";	
											}
								
										}
									else
									{
										$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
													$q=mysqli_query($con,$selectMail);
													$records = array();
													while($row = mysqli_fetch_assoc($q)){ 
														$records[] = $row;
													}		
											require_once('../PHPMailer_5.2.4/class.phpmailer.php');
											//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

											$mail             = new PHPMailer();

											$mail->IsSMTP(); // telling the class to use SMTP
											$mail->Host       = 'mail.iapcorp.com'; // SMTP server
											//$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
											// 1 = errors and messages
											// 2 = messages only

											$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
											$mail->AddReplyTo('projectled@iapcorp.com');


											$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
											$mail->Subject    = "Regarding registration with LED distribution system";
											$mail->Body = "<h3>Dear Operation Team,</h3>
											</br>
											</br><p>The Distributor <b>$VECode</b> has been rejected due to below reason:</p>
											<br><br>
											<p><b>=> $ReasonsText</b></p>
											<br><br><br>
											<h4>Regards, </h4>
											<p>Accounts Team</p>												
											";	
								$mail->AddAddress($distEmail);				
								foreach($records as $rec)
								{	  
									$romail=$rec['RO'];
									$adminmail=$rec['SuperAdmin'];
									$finmail=$rec['Finance'];				
									
									$mail->addCC($romail);
									$mail->addCC($adminmail);									
									$mail->addCC($finmail);
									$mail->Send();
									$mail->ClearAllRecipients();
								}											
										if(!$mail->Send()) 
										{
											
											echo "<script>alert(' Approval status rejected successfully !')</script>";
											echo "<script>window.open('DistributorVerify.php','_self')</script>";
										} 
										else 
										{
											echo "<script>alert(' Approval status rejected successfully !')</script>";
											echo "<script>window.open('DistributorVerify.php','_self')</script>";	
										}
								
									}							
								}
								else
								{
									$VEnid=$_POST['VId'];			
									$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
									v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
									v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
									v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
									p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
									p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
									JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
									JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
									WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
									$runQuery=mysqli_query($con,$getQuery);	
									if($runQuery)
									{
										while($row=mysqli_fetch_array($runQuery))
										{
											$VenderId=$row['VendorId'];
											$VCode=$row['VendorCode'];
											$VName=$row['VendorName'];
											$VContact=$row['ContactNumber'];
											$VEmail=$row['EmailId'];
											$VCorpStatus=$row['CorporateStatus'];
											$VPanNo=$row['PanNo'];
											$VSTexNo=$row['ServiceTaxNo'];
							
											$DocumentName=$row['DocumentName'];
											$DocumentURL=$row['DocumentURL'];
							
											$ADD1=$row['Address1'];
											$ADD2=$row['Address2'];
											$TownCity=$row['Town_City'];
											$Distric=$row['Distt'];
											$State=$row['State'];
											$PinCode=$row['Pin'];
							
											$ACHolderName=$row['ACHolderName'];
											$ACNumber=$row['ACNumber'];
											$BankName=$row['BankName'];
											$IFSC=$row['IFSC'];
											$BankCity_Town=$row['BankCity_Town'];
											$PaymentId=$row['PaymentId'];
											$payProof=$row['PaymentProof'];
							
											$PoolACNo=$row['PoolACNo'];
											$PoolACIFSC=$row['PoolACIFSC'];
											$Amount=$row['Amount'];
											$ModeofPayment=$row['ModeofPayment'];
											$TaxnRefNo=$row['TaxnRefNo'];
											$originalAppDate =$row['ApplicationDate'];
											$AppDate = date("d-m-Y", strtotime($originalAppDate));
											$originalPayDate =$row['PaymentDate'];
											$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
											$PayType=$row['PaymentType'];
											if($PayType=='S')
											{
												$PayType='Security';
											}
											$Refferedby=$row['Refferedby'];
											$ReferalName=$row['ReferalName'];
										}
									}
									$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
									JOIN tbllocation AS l ON vl.LocationId=l.LocId
									WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
									$RunLocation=mysqli_query($con,$VendorLocation);
									$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
									$RunDocument=mysqli_query($con,$DocQuery);
		
									$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
									$RunAddress=mysqli_query($con,$DocAddress);
			
									$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
									$RunCanceled=mysqli_query($con,$DocCanceledCheque);
									echo "<script>alert('Security Payment Not Rejected Check Details !')</script>";
								}
							}
						}
						else
						{
							$VEnid=$_POST['VId'];			
							$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
							v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
							v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
							v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
							p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
							p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
							JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
							JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
							WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
							$runQuery=mysqli_query($con,$getQuery);	
							if($runQuery)
							{
								while($row=mysqli_fetch_array($runQuery))
								{
									$VenderId=$row['VendorId'];
									$VCode=$row['VendorCode'];
									$VName=$row['VendorName'];
									$VContact=$row['ContactNumber'];
									$VEmail=$row['EmailId'];
									$VCorpStatus=$row['CorporateStatus'];
									$VPanNo=$row['PanNo'];
									$VSTexNo=$row['ServiceTaxNo'];
							
									$DocumentName=$row['DocumentName'];
									$DocumentURL=$row['DocumentURL'];
							
									$ADD1=$row['Address1'];
									$ADD2=$row['Address2'];
									$TownCity=$row['Town_City'];
									$Distric=$row['Distt'];
									$State=$row['State'];
									$PinCode=$row['Pin'];
							
									$ACHolderName=$row['ACHolderName'];
									$ACNumber=$row['ACNumber'];
									$BankName=$row['BankName'];
									$IFSC=$row['IFSC'];
									$BankCity_Town=$row['BankCity_Town'];
									$PaymentId=$row['PaymentId'];
									$payProof=$row['PaymentProof'];
							
									$PoolACNo=$row['PoolACNo'];
									$PoolACIFSC=$row['PoolACIFSC'];
									$Amount=$row['Amount'];
									$ModeofPayment=$row['ModeofPayment'];
									$TaxnRefNo=$row['TaxnRefNo'];
									$originalAppDate =$row['ApplicationDate'];
									$AppDate = date("d-m-Y", strtotime($originalAppDate));
									$originalPayDate =$row['PaymentDate'];
									$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
									$PayType=$row['PaymentType'];
									if($PayType=='S')
									{
										$PayType='Security';
									}
									$Refferedby=$row['Refferedby'];
									$ReferalName=$row['ReferalName'];
									}
								}
								$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
								JOIN tbllocation AS l ON vl.LocationId=l.LocId
								WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
								$RunLocation=mysqli_query($con,$VendorLocation);
								$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
								$RunDocument=mysqli_query($con,$DocQuery);
		
								$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
								$RunAddress=mysqli_query($con,$DocAddress);
			
								$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
								$RunCanceled=mysqli_query($con,$DocCanceledCheque);
								echo "<script>alert('Please Fill the Reason Rejection !')</script>";
							}					
						}
						else
						{
							$VEnid=$_POST['VId'];			
							$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
							v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
							v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
							v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
							p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
							p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
							JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
							JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
							WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
							$runQuery=mysqli_query($con,$getQuery);	
							if($runQuery)
							{
								while($row=mysqli_fetch_array($runQuery))
								{
									$VenderId=$row['VendorId'];
									$VCode=$row['VendorCode'];
									$VName=$row['VendorName'];
									$VContact=$row['ContactNumber'];
									$VEmail=$row['EmailId'];
									$VCorpStatus=$row['CorporateStatus'];
									$VPanNo=$row['PanNo'];
									$VSTexNo=$row['ServiceTaxNo'];
							
									$DocumentName=$row['DocumentName'];
									$DocumentURL=$row['DocumentURL'];
							
									$ADD1=$row['Address1'];
									$ADD2=$row['Address2'];
									$TownCity=$row['Town_City'];
									$Distric=$row['Distt'];
									$State=$row['State'];
									$PinCode=$row['Pin'];
							
									$ACHolderName=$row['ACHolderName'];
									$ACNumber=$row['ACNumber'];
									$BankName=$row['BankName'];
									$IFSC=$row['IFSC'];
									$BankCity_Town=$row['BankCity_Town'];
									$PaymentId=$row['PaymentId'];
									$payProof=$row['PaymentProof'];
							
									$PoolACNo=$row['PoolACNo'];
									$PoolACIFSC=$row['PoolACIFSC'];
									$Amount=$row['Amount'];
									$ModeofPayment=$row['ModeofPayment'];
									$TaxnRefNo=$row['TaxnRefNo'];
									$originalAppDate =$row['ApplicationDate'];
									$AppDate = date("d-m-Y", strtotime($originalAppDate));
									$originalPayDate =$row['PaymentDate'];
									$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
									$PayType=$row['PaymentType'];
									if($PayType=='S')
									{
										$PayType='Security';
									}
									$Refferedby=$row['Refferedby'];
									$ReferalName=$row['ReferalName'];
								}
							}
							$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode FROM tblvendorlocation AS vl
							JOIN tbllocation AS l ON vl.LocationId=l.LocId
							WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
							$RunLocation=mysqli_query($con,$VendorLocation);
							$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
							$RunDocument=mysqli_query($con,$DocQuery);
		
							$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
							$RunAddress=mysqli_query($con,$DocAddress);
		
							$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
							$RunCanceled=mysqli_query($con,$DocCanceledCheque);
							echo "<script>alert(' Please Select Reason of Rejection !')</script>";
						}
					}
			
				}
				else
				{
					$VEnid=$_POST['VId'];			
					$getQuery="SELECT v.VendorId,v.VendorCode,v.VendorName,v.ContactNumber,v.EmailId,v.MailStatus,v.Description,v.VendorStatus,
					v.RecStatus,v.LastUpdateOn,v.UpdatebyID,v.Refferedby,v.ReferalName,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,
					v.Address1,v.Address2,v.Town_City,v.Distt,v.State,v.Pin,v.ACHolderName,v.ACNumber,v.BankName,v.IFSC,v.BankCity_Town,
					v.PoolACNo,v.PoolACIFSC,v.Refferedby,v.ReferalName,v.ApplicationDate,v.ActivationDate,p.PaymentId,p.Amount,
					p.ModeofPayment,p.TaxnRefNo,p.PaymentProof,p.PaymentType,p.FinanceVerification,p.PaymentDate,p.EmailStatus,
					p.DepositBankName,p.VerificationRemark,d.DocId,d.DocumentName,d.DocumentURL FROM tblvendorregistration AS v 
					JOIN tblpayment AS p ON  v.VendorId=p.VendorId 
					JOIN tblvendordocument AS d ON v.VendorId=d.VendorId 
					WHERE v.RecStatus='A' AND p.PaymentType='S' AND v.VendorId='$VEnid'";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VenderId=$row['VendorId'];
							$VCode=$row['VendorCode'];
							$VName=$row['VendorName'];
							$VContact=$row['ContactNumber'];
							$VEmail=$row['EmailId'];
							$VCorpStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							
							$DocumentName=$row['DocumentName'];
							$DocumentURL=$row['DocumentURL'];
						
							$ADD1=$row['Address1'];
							$ADD2=$row['Address2'];
							$TownCity=$row['Town_City'];
							$Distric=$row['Distt'];
							$State=$row['State'];
							$PinCode=$row['Pin'];
						
							$ACHolderName=$row['ACHolderName'];
							$ACNumber=$row['ACNumber'];
							$BankName=$row['BankName'];
							$IFSC=$row['IFSC'];
								$BankCity_Town=$row['BankCity_Town'];
							$PaymentId=$row['PaymentId'];
							$payProof=$row['PaymentProof'];
							
							$PoolACNo=$row['PoolACNo'];
							$PoolACIFSC=$row['PoolACIFSC'];
							$Amount=$row['Amount'];
							$ModeofPayment=$row['ModeofPayment'];
							$TaxnRefNo=$row['TaxnRefNo'];
							$originalAppDate =$row['ApplicationDate'];
							$AppDate = date("d-m-Y", strtotime($originalAppDate));
							$originalPayDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalPayDate));
							$PayType=$row['PaymentType'];
							if($PayType=='S')
							{
								$PayType='Security';
							}							
							$Refferedby=$row['Refferedby'];
							$ReferalName=$row['ReferalName'];
						}
					}
					$VendorLocation="SELECT vl.ID,vl.LocationId,vl.NumberOFCenter,vl.SPOCName,vl.MobileNo,l.LocId,l.LocationName,LocationCode
					FROM tblvendorlocation AS vl
					JOIN tbllocation AS l ON vl.LocationId=l.LocId
					WHERE vl.RecStatus='A' AND vl.VendorId='$VEnid' ORDER BY vl.ID ASC";
					$RunLocation=mysqli_query($con,$VendorLocation);
					$DocQuery="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='PanCard'";
					$RunDocument=mysqli_query($con,$DocQuery);
					$DocAddress="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Address'";
					$RunAddress=mysqli_query($con,$DocAddress);
					$DocCanceledCheque="SELECT Docid,VendorId,DocumentName,DocumentURL FROM tblvendordocument WHERE VendorId='$VEnid' AND DocumentName='Cheque'";
					$RunCanceled=mysqli_query($con,$DocCanceledCheque);
					echo "<script>alert('Please Select Approval Status !')</script>";
				}
			}
		$_SESSION['timeout'] = time();
		}
	}
}
$_SESSION['timeout'] = time();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Distributor Details</title>
                 
                
                <link rel="stylesheet" href="../dist/css/lightbox.min.css" >
                
				<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">

    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Designing/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="../Designing/css/floatexamples.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
				<script type="text/javascript">
				$(document).ready(function () {
					$("#btnDistributorVerify").click(function () {						
						if($("input[type='radio']#r1").is(':checked')) {
							var txtAccept=$("#r1").val();													
							return true;
						}
						else if($("input[type='radio']#r2").is(':checked')){
							 var txtReject=$("#r2").val();
							 var selectReject=$("#RReason").val();
							 var txtRReason=$("#txtRejectReason").val();
							 if(selectReject != 0 && selectReject != "Other" ){								 								
								return true; 
							}
							else if(selectReject == "Other" && txtRReason!=""){																
								return true;
							}
							else{
								alert('Please select disaproval reason fill the reason ');
								return false;
							}
						}
						else{
							alert('Please select any one approval status !');
            				return false;
						}
					});
				});				
			</script>
   <script src="../Designing/js/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{    
    	$("#r1").click(function()
		{		
			document.getElementById('dvPassport').style.display = 'none';					
			document.getElementById('RReason').style.display = 'none';
    	});
    	$("#r2").click(function()
		{        					
        	document.getElementById('RReason').style.display = 'block';
    	});
	});
				</script>
                <script type="text/javascript">
    				$(function () {
        			$("#RReason").change(function () {
            		if ($(this).val() == "Other") {
                		$("#dvPassport").show();
            		} else {
                		$("#dvPassport").hide();
            			}
        			});
    			});
				</script>			
    			<!--[if lt IE 9]>
        			<script src="../assets/js/ie8-responsive-file-warning.js"></script>
        		<![endif]-->
				<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    			<!--[if lt IE 9]>
          			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        		<![endif]-->    			
			</head>
			<body class="nav-md">
    			<div class="container body">
        			<div class="main_container">
            			<div class="col-md-3 left_col">
                			<div class="left_col scroll-view">
                    			<img src="../Designing/img/HeaderLogo.png" alt="..." class="img-responsive">
                    			<div class="clearfix"></div><br />
                    			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        			<div class="menu_section">                            
                            			<ul class="nav side-menu">
                                			<li><a href="FinanceManagementDSB.php"><i class="fa fa-home"></i> Home </a></li>
                                			<li><a href="DistributorVerify.php"><i class="fa fa-check"></i>Distributor Verification </a></li>
                                			<li><a href="PaymentVerification.php"><i class="fa fa-inr"></i> Payment Verification </a></li>                                			<li><a href="EslPayment.php"><i class="fa fa-inr"></i> EESL Payment </a></li>
                                			<li><a href="MISReport.php"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;&nbsp;MIS Report</a></li>                        
                            			</ul>
                        			</div>
                    			</div>                    
                			</div>
            			</div>
            			<div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $Name ?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li><a href="ChangePassword.php">Change Password</a></li>
                                    
                                    <li><a href="../Logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->


            <!-- page content -->
<div class="right_col" role="main">
	<div class="">                    
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
            <form action="DistributionDetails.php" method="post">
            	<div class="x_panel">                	
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Application Details</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">                                    
                                    <div class="form-horizontal form-label-left">
                                    	<div class="form-group">
                                        	<input type="hidden" value="<?php echo $VenderId;  ?>" name="VId" />
                                            <input type="hidden" value="<?php echo $PaymentId;  ?>" name="PId" />
                                            <input type="hidden" value="<?php echo $VCode;  ?>" name="VCODE" />
                                            <input type="hidden" value="<?php echo $Amount;  ?>" name="Amount" />
                                            <input type="hidden" value="<?php echo $VEmail;  ?>" name="VMAIL" />
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Code</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VCode; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">                                        	
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VName; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Email Id </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VEmail; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mobile No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VContact; ?>
                                            </div>
                                        </div>                              
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">                                    
                                    <div class="form-horizontal form-label-left">
                                    	<div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Application Date</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $AppDate; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Corporate Status</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VCorpStatus; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">PAN No. </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VPanNo; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Service Tax No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VSTexNo; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>                        
                        </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Address Details</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">
                                    
                                    <div class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Line 1 </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ADD1; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Line 2</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ADD2; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">City/Town</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $TownCity; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">
                                    
                                    <div class="form-horizontal form-label-left">

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">District </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $Distric ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">State </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $State ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">PIN Code</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $PinCode; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>                        
                        </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Work Station Details</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 col-xs-12">
                                <div class="x_content">
                                 <div class="table-responsive">
                  	<table class="table table-striped">
                    	<thead>
                        	<tr>
                            	<th>Distric</th>
                            	<th>No. of Centers</th>
                                <th>SPOC Name</th>
                                <th>Mobile No.</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php while($rowLocation=mysqli_fetch_array($RunLocation)):?>
							<tr>
								<td><?php echo $rowLocation['LocationName'];?></td>
								<td><?php echo $rowLocation['NumberOFCenter'];?></td>
								<td><?php echo $rowLocation['SPOCName'];?></td>
								<td><?php echo $rowLocation['MobileNo'];?></td>								
							</tr>
							<?php endwhile;?> 
                        </tbody>
                      </table>          
                  	
                  </div>  
                                </div>
                                </div>
                                
                                </div>
                            </div>
                        </div>                        
                        </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Bank Details</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">                                    
                                    <div class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Account Holder Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ACHolderName; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Account No. </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ACNumber; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Bank Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $BankName; ?>
                                            </div>
                                        </div>                                       
                                        
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">
                                    <div class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">IFSC Code.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $IFSC; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">City/Town </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $BankCity_Town; ?>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>                        
                        </div>
                        <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Security Deposit Details</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">
                                    <div class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pool Account No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $PoolACNo; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">IFSC Code </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $PoolACIFSC; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mode Of Deposit</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ModeofPayment; ?>
                                            </div>
                                        </div>                                       
                                        
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">
                                    <div class="form-horizontal form-label-left">
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Amount </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $Amount; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">DD / CHQ / TXN No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $TaxnRefNo; ?>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Date of Deposit</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $PaymentDate; ?>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>                        
                        </div>
                        <div class="row">
                        	<div class="col-md-12 col-xs-12">
                            	<div class="x_panel">
                                	<div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    	<h2>Proof Details</h2>
                                    	<ul class="nav navbar-right panel_toolbox">
                                        	<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                    	</ul>                                    
                                    	<div class="clearfix"></div>
                                	</div>
                                	<div class="row">
                                		<div class="col-md-2 col-xs-12">
                                			<div class="x_content">
                                            <?php
											while($DocRow=mysqli_fetch_array($RunDocument))
											{
												$docUrl=$DocRow['DocumentURL'];
												$docName=$DocRow['DocumentName'];
											}
												if($docName=='PanCard')
												{
												echo"<b> Pan Card</b><br><br><a class='example-image-link' href='$docUrl' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='$docUrl' alt='image-1' style='max-height:100px; min-height:100px;' />";}
												else
												{
													echo"<b> Pan Card</b><br><br><a class='example-image-link' href='../Proof_img/image5.jpg' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='../Proof_img/image5.jpg' alt='image-1' style='max-height:100px; min-height:100px;' />
      											</a>";
												}
											
												?>
                                			</div>
                                		</div>
                                		<div class="col-md-2 col-xs-12 col-sm-offset-1">
                                			<div class="x_content">
                                    			<?php
												if($payProof!=""){
                                				echo"<b> Security Proof</b><br><br><a class='example-image-link' href='$payProof' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='$payProof' alt='image-1' style='max-height:100px; min-height:100px;' />
      											</a>";
												}
												else
												{
													echo"<b> Security Proof</b><br><br><a class='example-image-link' href='../Proof_img/image5.jpg' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='../Proof_img/image5.jpg' alt='image-1' style='max-height:100px; min-height:100px;' />
      											</a>";
												}
												?>
                                			</div>
                                		</div>
                                		<div class="col-md-2 col-xs-12 col-sm-offset-1">
                                			<div class="x_content">
                                    			<?php
												while($DocRow=mysqli_fetch_array($RunAddress))
												{
												$docUrl=$DocRow['DocumentURL'];
												$docName=$DocRow['DocumentName'];
												}
												if($docName=='Address')
												{
												echo"<b> Address Proof</b><br><br><a class='example-image-link' href='$docUrl' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='$docUrl' alt='image-1' style='max-height:100px; min-height:100px;' />";}
													else
												{
													echo"<b> Address Proof</b><br><br><a class='example-image-link' href='../Proof_img/image5.jpg' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='../Proof_img/image5.jpg' alt='image-1' style='max-height:100px; min-height:100px;' />
      											</a>";
												}
										
												?>
                                			</div>
                                		</div>
                                		<div class="col-md-2 col-xs-12 col-sm-offset-1">
                                			<div class="x_content">
                                    			<?php
													
		while($DocRow=mysqli_fetch_array($RunCanceled))
		{
			$CheckUrl=$DocRow['DocumentURL'];
			$CheckName=$DocRow['DocumentName'];
		}
												if($CheckName=='Cheque')
												{
												echo"<b>Canceled Cheque</b><br><br><a class='example-image-link' href='$CheckUrl' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='$CheckUrl' alt='image-1' style='max-height:100px; min-height:100px;' /></a>";}
													else
												{
													echo"<b> Canceled Cheque</b><br><br><a class='example-image-link' href='../Proof_img/image5.jpg' data-lightbox='example-1'>
      												<img class='example-image img-responsive' src='../Proof_img/image5.jpg' alt='image-1' style='max-height:100px; min-height:100px;' />
      											</a>";
												}
												?>
                                			</div>
                                		</div>
                                	</div>
                            	</div>
                        	</div>                        
                        </div>
                        <div class="row">
                        	<div class="col-md-6 col-xs-12">
                            	<div class="x_panel">
                                	<div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    	<h2>Approval Status</h2>
                                    	<ul class="nav navbar-right panel_toolbox">
                                        	<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                                        </ul>
                                     	<div class="clearfix"></div>
                                	</div>
                                	<div class="x_content">                                    
                                   		<div class="form-horizontal form-label-left">
                                        	<div class="form-group">
                                        		<div class="row">
        											<label class="col-md-6 col-sm-6 col-xs-6"> Approval</label>
        											<label class="col-md-6 col-sm-6 col-xs-6"> Disapproval</label>
    											</div>
                                            	<div class="row">
        											<div class="col-md-6 col-sm-6 col-xs-6">
                                            			<input type="radio" id="r1" name="Rbtn" value="A">
                                                    </div>
        											<div class="col-md-6 col-sm-6 col-xs-6">
                                            			<input type="radio" id="r2" name="Rbtn" value="R">
                                                    </div>
    											</div>
                                            	<div class="row"><br />
                                            		
                                            		<div class="col-md-6 col-sm-6 col-xs-12 col-sm-offset-6" >
    													
                                                    <?php
													$ReasonQuery="SELECT RejectedId,RejectedReason FROM tblrejectedreasons WHERE ResStatus='A' and type='F'";
													$RunReason=mysqli_query($con,$ReasonQuery);
													echo "<select name='RejectReasons' id='RReason' style='display:none;' class='form-control input-sm' id='ddlPassport'>
														 <option value='0'>Select disapproval Reasons</option>";
														 while($ReasonRow=mysqli_fetch_array($RunReason))
														 {
															 $Reasons=$ReasonRow['RejectedReason'];
															 echo "<option value='$Reasons'> $Reasons</option>";
														 }
														 echo "
														 		<option value='Other'> Other </option>
														 		</select>";
													?>
                                                    <br />
                                                    <span id="dvPassport" style="display: none">
                                                    <input type="text" name="ReasonText" class="form-control input-sm" style="resize:none" id="txtRejectReason" maxlength="100" placeholder="Disapproval reason"/>
                                                	</span>	
                                                    </div>
                                            	</div>
    										</div>
                                    	</div>
                                	</div>
                            	</div>
                        	</div>
                        	<div class="col-md-6 col-xs-12">
                            	<div class="x_panel">
                                	<div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    	<h2>References</h2>
                                    	<ul class="nav navbar-right panel_toolbox">
                                        	<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        </ul>
                                    	<div class="clearfix"></div>
                                	</div>
                                	<div class="x_content">
                                   		<div class="form-horizontal form-label-left">
                                        	<div class="form-group">
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Referred By</label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                                	 <?php echo $Refferedby; ?>
                                            	</div>
                                        	</div>
                                        	<div class="form-group">
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Referral Name </label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                                	 <?php echo $ReferalName; ?>
                                            	</div>
                                        	</div>
                                    	</div>
                                	</div>
                            	</div>
                        	</div>
                        </div>
                        <div class="row">
							<div class="ln_solid"></div>
                        		<div class="form-group">
                            		<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-5">
                                		<button type="submit" name="SaveChanges" class="btn btn-primary" onclick="return confirm('Are you sure to verify record ?');" id="btnDistributorVerify">Save Changes</button>
                                    	<a href="DistributorVerify.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
                            		</div>
                        		</div>
                    		</div> 
              			</div>
             </form>           
            		</div>
         		</div>
       		</div>
            <!-- footer content -->
            <footer>
            	<div class="row">
                	<div class="">
                        <p class="col-sm-offset-3">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd</p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </footer>
                <!-- /footer content -->
		</div>
            <!-- /page content -->
    </div>
</div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>
	<script src="../dist/js/lightbox.min.js"></script>
    <script src="../Designing/js/bootstrap.min.js"></script>
    <!-- chart js -->
    <script src="../Designing/js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="../Designing/js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="../Designing/js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="../Designing/js/icheck/icheck.min.js"></script>
    <!-- gauge js -->
    <script type="text/javascript" src="../Designing/js/gauge/gauge.min.js"></script>
    <script type="text/javascript" src="../Designing/js/gauge/gauge_demo.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript" src="../Designing/js/moment.min2.js"></script>
    <script type="text/javascript" src="../Designing/js/datepicker/daterangepicker.js"></script>
    <!-- sparkline -->
    <script src="../Designing/js/sparkline/jquery.sparkline.min.js"></script>

    <script src="../Designing/js/custom.js"></script>
    <!-- skycons -->
    <script src="../Designing/js/skycons/skycons.js"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/date.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.spline.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.stack.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/curvedLines.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.resize.js"></script>

    <script>
        //random data
        var d1 = [
        [0, 1], [1, 9], [2, 6], [3, 10], [4, 5], [5, 17], [6, 6], [7, 10], [8, 7], [9, 11], [10, 35], [11, 9], [12, 12], [13, 5], [14, 3], [15, 4], [16, 9]
    ];

        //flot options
        var options = {
            series: {
                curvedLines: {
                    apply: true,
                    active: true,
                    monotonicFit: true
                }
            },
            colors: ["#26B99A"],
            grid: {
                borderWidth: {
                    top: 0,
                    right: 0,
                    bottom: 1,
                    left: 1
                },
                borderColor: {
                    bottom: "#7F8790",
                    left: "#7F8790"
                }
            }
        };
        var plot = $.plot($("#placeholder3xx3"), [{
            label: "Registrations",
            data: d1,
            lines: {
                fillColor: "rgba(150, 202, 89, 0.12)"
            }, //#96CA59 rgba(150, 202, 89, 0.42)
            points: {
                fillColor: "#fff"
            }
                }], options);
    </script>
    <!-- /flot -->
    <!--  -->
    <script>
        $('document').ready(function () {
            $(".sparkline_one").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
                type: 'bar',
                height: '40',
                barWidth: 9,
                colorMap: {
                    '7': '#a1a1a1'
                },
                barSpacing: 2,
                barColor: '#26B99A'
            });

            $(".sparkline_two").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
                type: 'line',
                width: '200',
                height: '40',
                lineColor: '#26B99A',
                fillColor: 'rgba(223, 223, 223, 0.57)',
                lineWidth: 2,
                spotColor: '#26B99A',
                minSpotColor: '#26B99A'
            });

            var doughnutData = [
                {
                    value: 30,
                    color: "#455C73"
                },
                {
                    value: 30,
                    color: "#9B59B6"
                },
                {
                    value: 60,
                    color: "#BDC3C7"
                },
                {
                    value: 100,
                    color: "#26B99A"
                },
                {
                    value: 120,
                    color: "#3498DB"
                }
    ];
            var myDoughnut = new Chart(document.getElementById("canvas1").getContext("2d")).Doughnut(doughnutData);


        })
    </script>
    <!-- -->
    <!-- datepicker -->
    <script type="text/javascript">
        $(document).ready(function () {

            var cb = function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
            }

            var optionSet1 = {
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2015',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Clear',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            };
            $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            $('#reportrange').daterangepicker(optionSet1, cb);
            $('#reportrange').on('show.daterangepicker', function () {
                console.log("show event fired");
            });
            $('#reportrange').on('hide.daterangepicker', function () {
                console.log("hide event fired");
            });
            $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
                console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
            });
            $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
                console.log("cancel event fired");
            });
            $('#options1').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function () {
                $('#reportrange').data('daterangepicker').remove();
            });
        });
    </script>
    <!-- /datepicker -->

    
    <!-- skycons -->
    <script>
        var icons = new Skycons({
                "color": "#73879C"
            }),
            list = [
                "clear-day", "clear-night", "partly-cloudy-day",
                "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                "fog"
            ],
            i;

        for (i = list.length; i--;)
            icons.set(list[i], list[i]);

        icons.play();
    </script>
    <script>
    
        var opts = {
            lines: 12, // The number of lines to draw
            angle: 0, // The length of each line
            lineWidth: 0.4, // The line thickness
            pointer: {
                length: 0.75, // The radius of the inner circle
                strokeWidth: 0.042, // The rotation offset
                color: '#1D212A' // Fill color
            },
            limitMax: 'false', // If true, the pointer will not go past the end of the gauge
            colorStart: '#1ABC9C', // Colors
            colorStop: '#1ABC9C', // just experiment with them
            strokeColor: '#F0F3F3', // to see which ones work best for you
            generateGradient: true
        };
        var target = document.getElementById('foo2'); // your canvas element
        var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
        gauge.maxValue = 5000; // set max gauge value
        gauge.animationSpeed = 32; // set animation speed (32 is default value)
        gauge.set(3200); // set actual value
        gauge.setTextField(document.getElementById("gauge-text2"));
    </script>

</body>
</html>
<?php
session_start();
$inactive = 300;
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
	if(isset($_GET['PaymentId']))
	{	
		$PayId=$_GET['PaymentId'];
		$getQuery="SELECT 
	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.bank_branch_code,p.cheque_number,p.dd_number,p.other_payment_mode,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorId,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.ApplicationDate,v.ActivationDate
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P' and p.PaymentId=$PayId";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VendorId=$row['VendorId'];
							$VendorCode=$row['VendorCode'];
							$VendorName=$row['VendorName'];
							$ContactNumber=$row['ContactNumber'];
							$emailId=$row['emailId'];
							$CorporateStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							$originalActDate =$row['ActivationDate'];
							$ActivationDate = date("d-m-Y", strtotime($originalActDate));	
							
							$PaymentId=$row['PaymentId'];
							$Amount=$row['Amount'];
                                                        
                                                        $bank_branch_code=$row['bank_branch_code'];
                                                        $cheque_number=$row['cheque_number'];
                                                        $dd_number=$row['dd_number'];
                                                        $other_payment_mode=$row['other_payment_mode'];
                                                        
							$TaxnRefNo=$row['TaxnRefNo'];
							$PaymentProof=$row['PaymentProof'];
							$ModeofPayment=$row['ModeofPayment'];
							$DepositBankName=$row['DepositBankName'];
							$originalDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalDate));	
						}
					}					
	}
	else if(isset($_POST['SaveChanges']))
	{		
		if(isset($_POST['AdvancePay']))
		{
			date_default_timezone_set("Asia/Kolkata"); 
    		$CurrentDate=date('Y-m-d H:i:s A');				
			$FillSecAmount=$_POST['Amount'];							
			$VEnid=$_POST['VId'];
			$VECode=$_POST['VCode'];
			$PamentId=$_POST['PId'];
			$DistEmail=$_POST['EmailId'];
			$Accept=$_POST['AdvancePay'];
			if($Accept=='A')
			{
				$AcceptPayment="UPDATE tblpayment SET FinanceVerification='A',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',VerificationRemark='Payment Accepted' WHERE VendorId='$VEnid' AND RecStatus='A' AND PaymentType='A' AND PaymentId='$PamentId'";
						$RunAcceptPayment=mysqli_query($con,$AcceptPayment);	
						if($RunAcceptPayment)
						{
							$SumWallet="SELECT Balance FROM tblwallet WHERE VendorId='$VEnid'";
							$SumQuery=mysqli_query($con,$SumWallet);
							if($SumQuery)
							{
								while($row=mysqli_fetch_array($SumQuery))
								{
									$Total=$row['Balance'];
								}
								$SumTotal=$Total+$FillSecAmount;
								$updWallet="UPDATE tblwallet SET Balance='$SumTotal',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId' WHERE VendorId='$VEnid'";
								$RunSecurity=mysqli_query($con,$updWallet);
							if($RunSecurity)
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
								$mail->Subject    = "Regarding payment varification LED distribution system";
								$mail->Body = "<h3>Dear Operation Team,</h3>
												</br>
												</br><p>The Distributor <b>$VECode</b> has deposited the stock release amount in his wallet. The detailed data has been verified. Please release the stock.</p>
												
												<br><br>
												<h4>Thanks & Regards, </h4>
												<p>Finance Team </p>												
												";							
																			
								$mail->AddAddress($DistEmail);												
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
  									//echo "Mailer Error: " . $mail->ErrorInfo;
									echo "<script>alert('Payment accepted successully, Wallet recharged !')</script>";
									echo "<script>window.open('PaymentVerification.php','_self')</script>";
								} else {
									
								echo "<script>alert('Payment accepted successully, Wallet recharged  !')</script>";
								echo "<script>window.open('PaymentVerification.php','_self')</script>";
								}
							}
							else
							{
								$PamentId=$_POST['PId'];
		$getQuery="SELECT 
	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorId,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.ApplicationDate,v.ActivationDate
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P' and p.PaymentId=$PamentId";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VendorId=$row['VendorId'];
							$VendorCode=$row['VendorCode'];
							$VendorName=$row['VendorName'];
							$ContactNumber=$row['ContactNumber'];
							$emailId=$row['emailId'];
							$CorporateStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							$originalActDate =$row['ActivationDate'];
							$ActivationDate = date("d-m-Y", strtotime($originalActDate));	
							
							$PaymentId=$row['PaymentId'];
							$Amount=$row['Amount'];
							$TaxnRefNo=$row['TaxnRefNo'];
							$PaymentProof=$row['PaymentProof'];
							$ModeofPayment=$row['ModeofPayment'];
							$DepositBankName=$row['DepositBankName'];
							$originalDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalDate));	
						}
					}
							}
							
							
						}
						else
						{
							$PamentId=$_POST['PId'];
		$getQuery="SELECT 
	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorId,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.ApplicationDate,v.ActivationDate
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P' and p.PaymentId=$PamentId";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VendorId=$row['VendorId'];
							$VendorCode=$row['VendorCode'];
							$VendorName=$row['VendorName'];
							$ContactNumber=$row['ContactNumber'];
							$emailId=$row['emailId'];
							$CorporateStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							$originalActDate =$row['ActivationDate'];
							$ActivationDate = date("d-m-Y", strtotime($originalActDate));	
							
							$PaymentId=$row['PaymentId'];
							$Amount=$row['Amount'];
							$TaxnRefNo=$row['TaxnRefNo'];
							$PaymentProof=$row['PaymentProof'];
							$ModeofPayment=$row['ModeofPayment'];
							$DepositBankName=$row['DepositBankName'];
							$originalDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalDate));	
						}
						}
						}
						
						}
			}
			else if($Accept=='R')
			{
				$ReasonText=$_POST['RejectionRegion'];
				$Reasons=$_POST['RejectReasons'];
				if($Reasons!='0')
				{
					if($Reasons!='Other')
					{
					$RejectPayment="UPDATE tblpayment SET FinanceVerification='R',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',VerificationRemark='$Reasons',RecStatus='D' WHERE VendorId='$VEnid' AND RecStatus='A' AND PaymentType='A' AND PaymentId='$PamentId'";
						$RunRejectPayment=mysqli_query($con,$RejectPayment);	
						if($RunRejectPayment)
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
								$mail->Subject    = "Regarding Payment Varification LED Distribution System";
								$mail->Body = "<h3>Dear Operation Team,</h3>
												</br>
												</br><p>The Distributor <b>$VECode</b> has been rejected due to below reason:</p>
												</br></br>
												<p>=> $Reasons</p>
												
												<br><br>
												<h4>Regards,</h4>
												<p>Accounts Team</p>
												
												";																		
								$mail->AddAddress($DistEmail);												
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
  									echo "Mailer Error: " . $mail->ErrorInfo;
									echo "<script>alert('Payment verification rejected !')</script>";
									echo "<script>window.open('PaymentVerification.php','_self')</script>";
								} else {
									
								echo "<script>alert('Payment verification rejected !')</script>";
								echo "<script>window.open('PaymentVerification.php','_self')</script>";
								}
						}
					}
					else if($Reasons=='Other')
					{
						if($ReasonText!='')
						{
							$RejectPayment="UPDATE tblpayment SET FinanceVerification='R',LastUpdateOn='$CurrentDate',UpdatebyID='$UserId',VerificationRemark='$ReasonText',RecStatus='D' WHERE VendorId='$VEnid' AND RecStatus='A' AND PaymentType='A' AND PaymentId='$PamentId'";
						$RunRejectPayment=mysqli_query($con,$RejectPayment);	
						if($RunRejectPayment)
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
								$mail->Subject    = "Regarding Payment Varification LED Distribution System";
								$mail->Body = "<h3>Dear Operation Team,</h3>
												</br>
												</br><p>The Distributor <b>$VECode</b> Payment Status has been rejected due to below reason:</p>
												</br><br>
												<h4>=> $ReasonText</h4>
												
												<br><br>
												<h4>Regards,</h4>
												<p>Accounts Team</p>
												
												";
																	
								$mail->AddAddress($DistEmail);												
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
  									echo "Mailer Error: " . $mail->ErrorInfo;
									echo "<script>alert('Payment verification rejected !')</script>";
									echo "<script>window.open('PaymentVerification.php','_self')</script>";
								} else {
									
								echo "<script>alert('Payment verification rejected !')</script>";
								echo "<script>window.open('PaymentVerification.php','_self')</script>";
								}
							
						}
						}
						else
						{
							$PamentId=$_POST['PId'];
		$getQuery="SELECT 
	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorId,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.ApplicationDate,v.ActivationDate
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P' and p.PaymentId=$PamentId";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VendorId=$row['VendorId'];
							$VendorCode=$row['VendorCode'];
							$VendorName=$row['VendorName'];
							$ContactNumber=$row['ContactNumber'];
							$emailId=$row['emailId'];
							$CorporateStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							$originalActDate =$row['ActivationDate'];
							$ActivationDate = date("d-m-Y", strtotime($originalActDate));	
							
							$PaymentId=$row['PaymentId'];
							$Amount=$row['Amount'];
							$TaxnRefNo=$row['TaxnRefNo'];
							$PaymentProof=$row['PaymentProof'];
							$ModeofPayment=$row['ModeofPayment'];
							$DepositBankName=$row['DepositBankName'];
							$originalDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalDate));	
						}
					}
							echo "<script>alert('Please Insert Rejected Reason !')</script>";
						}
					}
				}
				else
				{
					$PamentId=$_POST['PId'];
		$getQuery="SELECT 
	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorId,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.ApplicationDate,v.ActivationDate
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P' and p.PaymentId=$PamentId";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VendorId=$row['VendorId'];
							$VendorCode=$row['VendorCode'];
							$VendorName=$row['VendorName'];
							$ContactNumber=$row['ContactNumber'];
							$emailId=$row['emailId'];
							$CorporateStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							$originalActDate =$row['ActivationDate'];
							$ActivationDate = date("d-m-Y", strtotime($originalActDate));	
							
							$PaymentId=$row['PaymentId'];
							$Amount=$row['Amount'];
							$TaxnRefNo=$row['TaxnRefNo'];
							$PaymentProof=$row['PaymentProof'];
							$ModeofPayment=$row['ModeofPayment'];
							$DepositBankName=$row['DepositBankName'];
							$originalDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalDate));	
						}
					}
					echo "<script>alert('Please Select Rejected Reason !')</script>";
				}
			}
		}
		else
		{
			$PamentId=$_POST['PId'];
		$getQuery="SELECT 
	p.PaymentId,p.VendorId,p.Amount,p.ModeofPayment,p.TaxnRefNo,p.PaymentDate,p.PaymentProof,p.PaymentType,p.FinanceVerification,
	p.EmailStatus,p.RecStatus,p.LastUpdateOn,p.UpdatebyID,p.DepositBankName,p.VerificationRemark,v.VendorId,v.VendorName,v.VendorCode,
	v.ContactNumber,v.emailId,v.ActivationDate,v.VendorStatus,v.CorporateStatus,v.PanNo,v.ServiceTaxNo,v.ApplicationDate,v.ActivationDate
	FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId 
	WHERE p.PaymentType='A' AND p.RecStatus='A' AND v.VendorStatus='A' AND FinanceVerification='P' and p.PaymentId=$PamentId";
					$runQuery=mysqli_query($con,$getQuery);	
					if($runQuery)
					{
						while($row=mysqli_fetch_array($runQuery))
						{
							$VendorId=$row['VendorId'];
							$VendorCode=$row['VendorCode'];
							$VendorName=$row['VendorName'];
							$ContactNumber=$row['ContactNumber'];
							$emailId=$row['emailId'];
							$CorporateStatus=$row['CorporateStatus'];
							$VPanNo=$row['PanNo'];
							$VSTexNo=$row['ServiceTaxNo'];
							$originalActDate =$row['ActivationDate'];
							$ActivationDate = date("d-m-Y", strtotime($originalActDate));	
							
							$PaymentId=$row['PaymentId'];
							$Amount=$row['Amount'];
							$TaxnRefNo=$row['TaxnRefNo'];
							$PaymentProof=$row['PaymentProof'];
							$ModeofPayment=$row['ModeofPayment'];
							$DepositBankName=$row['DepositBankName'];
							$originalDate =$row['PaymentDate'];
							$PaymentDate = date("d-m-Y", strtotime($originalDate));	
						}
					}
			echo "<script>alert('Please Select Approval Status !')</script>";
		}
	}
	else
	{
		header("location:PaymentVerification.php");
	}
}
	}
}
else
{
	header("location:../Logout.php");
}
$_SESSION['timeout'] = time();	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Payment Verification Details</title>                
                 
				<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">
	 			<link rel="stylesheet" href="../dist/css/lightbox.min.css" >
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
					$("#btnPayments").click(function () {						
						if($("input[type='radio']#r1").is(':checked')) {
							var txtAccept=$("#r1").val();							
							return true;
						}
						else if($("input[type='radio']#r2").is(':checked')){
							 var txtReject=$("#r2").val();
							 var selectReject=$("#RReason").val();
							 var txtRejectReason=$("#txtOtherReason").val();
							 if(selectReject != 0 && selectReject != "Other" ){								
								return true; 
							}
							else if(selectReject == "Other" && txtRejectReason!=""){								
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
						document.getElementById('lblReason').style.display = 'none';
    				});
    				$("#r2").click(function()
					{        					
        				document.getElementById('RReason').style.display = 'block';
						document.getElementById('lblReason').style.display = 'block';
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
                                            <li><a href="PaymentVerification.php"><i class="fa fa-inr"></i> Payment Verification </a></li>                                            <li><a href="EslPayment.php"><i class="fa fa-inr"></i> EESL Payment </a></li>
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
            	<form method="post" action="PaymentVerificationDetails.php">
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
                                        	<input type="hidden" value="<?php echo $VendorId;  ?>" name="VId" />
                                            <input type="hidden" value="<?php echo $VendorCode;  ?>" name="VCode" />
                                        	<input type="hidden" value="<?php echo $PaymentId;  ?>" name="PId" />
                                            <input type="hidden" value="<?php echo $Amount;  ?>" name="Amount" />
                                            <input type="hidden" value="<?php echo $emailId;  ?>" name="EmailId" />
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Code</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VendorCode ?>
                                            </div>
                                        </div>
                                        <div class="form-group">                                        	
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $VendorName ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Email Id </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $emailId ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mobile No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ContactNumber; ?>
                                            </div>
                                        </div>                                        
                                        </div>                              
                                    </div>
                                </div>
                               
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">                                    
                                    <div class="form-horizontal form-label-left">
                                    	<div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Activation Date</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ActivationDate; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Corporate Status</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $CorporateStatus; ?>
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
                                    <h2>Payment Details</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-4 col-xs-12">
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
                                            <label class="col-md-4 col-sm-4 col-xs-12">Payment Date</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $PaymentDate; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                <div class="x_content">
                                    
                                    <div class="form-horizontal form-label-left">

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Bank Name </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $DepositBankName; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Payment Mode </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $ModeofPayment; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Payment Proof</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            
                                            <?php 
											if($PaymentProof!="")
											{											
                                            echo"<a class='example-image-link' href='$PaymentProof' data-lightbox='example-1'><img class='example-image img-responsive' src='$PaymentProof' alt='image-1' style='max-height:100px; min-height:100px;' /></a>";
											}
											else
											{
												
											echo"<a class='example-image-link' href='$PaymentProof' data-lightbox='example-1'><img class='example-image img-responsive' src='../Proof_img/image5.jpg' style='max-height:100px; min-height:100px;' /></a>";											
											}
											?>
                                             
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                </div>
                                   <?php 
                                        
                                            $var_name='';
                                            $var_val='';
                                            if($ModeofPayment=='CASH'){
                                                $var_name='Bank Branch Code';
                                                 $var_val=$bank_branch_code;
                                            }else if($ModeofPayment=='CHEQUE'){
                                                $var_name='Cheque Number';
                                                $var_val=$cheque_number;
                                            }else if($ModeofPayment=='Demand Draft'){
                                                $var_name='DD Number'; 
                                                $var_val=$dd_number;
                                             }else if($ModeofPayment=='OTHER'){
                                                $var_name='Other Payment Mode Name';
                                                $var_val=$other_payment_mode;
                                            }
                                            ///echo "here-->".$cheque_number;print_r($cheque_number);
                                         ?> 
                                  <?php if($var_name!=''){ ?>
                                        <div class="col-md-4 col-xs-12">
                                             <div class="form-group">

                                                <label class="col-md-5 col-sm-5 col-xs-12"><?php echo $var_name; ?> </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <?php echo $var_val; ?>
                                                </div>
                                            </div>
                                         </div>
                                    
                                  <?php } ?>
                                </div>
                            </div>
                        </div>                        
                        </div>
                   
                        <div class="row">
                        	<div class="col-md-12 col-sm-12 col-xs-12">
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
                                            			<input type="radio" name="AdvancePay" value="A" id="r1" class="radioPayment">
                                                    </div>
        											<div class="col-md-6 col-sm-6 col-xs-6">
                                            			<input type="radio" name="AdvancePay" value="R" id="r2" class="radioPayment">
                                                    </div>
    											</div>
                                            	<div class="row"><br />
                                            		<div class="col-md-6 col-sm-6 col-xs-12" >
                                                        													
                                                	</div>
                                            		<div class="col-md-4 col-sm-4 col-xs-12">
                                                    <label style="display:none;" id="lblReason">Select Reason </label>
    													  <?php
													$ReasonQuery="SELECT RejectedId,RejectedReason FROM tblrejectedreasons WHERE ResStatus='A' and type='F'";
													$RunReason=mysqli_query($con,$ReasonQuery);
													echo "<select name='RejectReasons' id='RReason' style='display:none;' class='form-control input-sm' id='ddlPassport'>
														 <option value='0'>Select disapproval reasons</option>";
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
                                                    <span id="dvPassport" style="display:none">
                                                    <input type="text" name="RejectionRegion" class="form-control" id='txtOtherReason' placeholder="Fill disapproval reason" maxlength="100" />
                                                    </span>
                                                	</div>
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
                                		<button type="submit" name="SaveChanges" class="btn btn-primary" onclick="return confirm('Are you sure to verify record ?');" id="btnPayments">Save Changes</button>
                                    	<a href="PaymentVerification.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
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
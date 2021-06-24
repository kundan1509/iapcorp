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
			if(isset($_POST['UserRegistration']))
			{
				require_once('../PHPMailer_5.2.4/class.phpmailer.php');
				date_default_timezone_set("Asia/Kolkata"); 
				$CurrentDate=date('Y-m-d H:i:s A');	
				$UType=$_POST['UsrType'];
				$emailId=$_POST['emailId'];
				$Password=base64_encode($_POST['Password']);
				$Repass=base64_encode($_POST['RePassword']);
				$descode=base64_decode($Password);
				if($UType!='0')
				{					
					
					if($Password==$Repass)
					{
						$checkQuery="SELECT COUNT(Id) As Number, Id,UserId FROM tbluser WHERE UserID='$emailId'";
						$Runcheck=mysqli_query($con,$checkQuery);
						if($Runcheck)
						{	
							while($row=mysqli_fetch_array($Runcheck))
							{		
								$GetNumber=$row['Number'];
								if($GetNumber<='0')
								{
									$InsUser="INSERT INTO tbluser(UserId,PASSWORD,UserTypes,FinanceVerificationStatus,RefId,RecStatus,LastUpdateOn,UpdatebyID)
									VALUES('$emailId','$Password','$UType','A','0','A','$CurrentDate','$UserId')";
									$RunUser=mysqli_query($con,$InsUser);	
									if($RunUser)
									{
                                                                                        $mail             = new PHPMailer();

											$mail->IsSMTP(); // telling the class to use SMTP
											$mail->Host       = 'mail.iapcorp.com'; // SMTP server
											//$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

											$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
											$mail->AddReplyTo('projectled@iapcorp.com');


											$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
											$mail->Subject    = "This is Confirmation Message";
											$mail->Body = "<h3>Dear User</h3>
												</br><h4>Thank you for registration with LED system. You can now login with the registered email and password.</h4>
												</br><p>The email id and password are given below.</p>
												</br><p>Email Id	:	$emailId</p>
												</br><p>Password	:	$descode</p>
												<br><br><br>
												<h4>Thanks & Regards </h4>
												<p>Administrator Team</p><br>
												";	
                                                                            
                                                                            
										$MailingList="SELECT Id,RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
										$MailingQuery=mysqli_query($con,$MailingList);
										while($rowMailing=mysqli_fetch_array($MailingQuery))
										{
											$roMail=$rowMailing['RO'];
											$financeMail=$rowMailing['Finance'];
											$adminMail=$rowMailing['SuperAdmin'];
						
											require_once('../PHPMailer_5.2.4/class.phpmailer.php');
											//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

																		
								
											$mail->addCC($roMail);
											$mail->addCC($financeMail);
											$mail->addCC($adminMail);
											$mail->Send();
											$mail->ClearCCs();
										}
										$mail->AddAddress($emailId);
										//$mail->Send();
										if(!$mail->Send())
										{
  									
											echo "<script>alert('User registerd successfully password Send on your registerd email Id!')</script>";
											echo "<script>window.open('UserRegistration.php','_self')</script>";
										} 
										else
										{									
											echo "<script>alert('User registerd successfully password send on your registerd email Id !')</script>";
											echo "<script>window.open('UserRegistration.php','_self')</script>";
						
										}						
								
									}
									else
									{
										echo "<script>alert('User not register check details and try again !')</script>";
										echo "<script>window.open('UserRegistration.php','_self')</script>";
									}
								}
								else
								{
									echo "<script>alert('The email Id already exist try again with other email Id !')</script>";
									echo "<script>window.open('UserRegistration.php','_self')</script>";
								}
							}
						}				
					}
					else
					{
						echo "<script>alert('Password is not match !')</script>";
						echo "<script>window.open('UserRegistration.php','_self')</script>";
					}			
				}
				else
				{
					echo "<script>alert('Please Select User Type !')</script>";
					echo "<script>window.open('UserRegistration.php','_self')</script>";
				}
			
		
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

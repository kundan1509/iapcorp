<?php
include("Connection/Connection.php");		
session_start();
$cap = 'notEq';
	if(isset($_POST['SignIn']))
	{
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if ($_POST['captcha'] == $_SESSION['cap_code'])
			{
				$username=$_POST['username'];
				$userpass=base64_encode($_POST['password']);
				$cap = 'Eq';
				
				if($username=='' && $userpass=='' )
				{
					echo "<script>alert('Please Enter User Name and Password !')</script>";
					exit();
				}
				else
				{
					$CheckUsers="select Id,UserId,PASSWORD,UserTypes,FinanceVerificationStatus,RefId from tbluser where UserId='$username' and PASSWORD='$userpass' and RecStatus='A' and FinanceVerificationStatus='A'";
		
					$RunQuery=mysqli_query($con,$CheckUsers);
					if(mysqli_num_rows($RunQuery)>0)
					{
						while($row=mysqli_fetch_array($RunQuery))
						{
							$UserId=$row["Id"];	
							$Name=$row["UserId"];	
							$Pass=$row["PASSWORD"];	
							$UserType=$row["UserTypes"];
							$RefId=$row["RefId"];
							$FVerigy=$row["FinanceVerificationStatus"];
							if($UserType=="SA")
							{
								$_SESSION['UserID']=$UserId;
								$_SESSION['Name']=$Name;
								$_SESSION['Type']=$UserType;
								$_SESSION['Ref']=$RefId;
								$_SESSION['FV']=$FVerigy;
								$_SESSION['timeout'] = time();
								echo "<script>window.open('Administrator/AdministratorDSB.php','_self')</script>";
							}
							else if($UserType=="FA")
							{
								$_SESSION['UserID']=$UserId;
								$_SESSION['Name']=$Name;
								$_SESSION['Type']=$UserType;
								$_SESSION['Ref']=$RefId;
								$_SESSION['FV']=$FVerigy;
								$_SESSION['timeout'] = time();
								echo "<script>window.open('Finance Management/FinanceManagementDSB.php','_self')</script>";
							}
							else if($UserType=="RO")
							{
								$_SESSION['UserID']=$UserId;
								$_SESSION['Name']=$Name;
								$_SESSION['Type']=$UserType;
								$_SESSION['Ref']=$RefId;
								$_SESSION['FV']=$FVerigy;
								$_SESSION['timeout'] = time();
								echo "<script>window.open('Opreation/OperationDSB.php','_self')</script>";
							}
							else if($UserType=="FS")
							{
								$_SESSION['UserID']=$UserId;
								$_SESSION['Name']=$Name;
								$_SESSION['Type']=$UserType;
								$_SESSION['Ref']=$RefId;
								$_SESSION['FV']=$FVerigy;
								$_SESSION['timeout'] = time();
								echo "<script>window.open('Finance Management/FinanceManagementDSB.php','_self')</script>";
							}
							else if($UserType=="VN")
							{
								$_SESSION['UserID']=$UserId;
								$_SESSION['Name']=$Name;
								$_SESSION['Type']=$UserType;
								$_SESSION['Ref']=$RefId;
								$_SESSION['FV']=$FVerigy;
								$_SESSION['timeout'] = time();
								echo "<script>window.open('VenderManagement/VenderManagementDSB.php','_self')</script>";
							}
							else if($UserType=="DS")
							{
								$_SESSION['UserID']=$UserId;
								$_SESSION['Name']=$Name;
								$_SESSION['Type']=$UserType;
								$_SESSION['Ref']=$RefId;
								$_SESSION['FV']=$FVerigy;
								$_SESSION['timeout'] = time();
								echo "<script>window.open('Dispatcher/ConsignmentDSB.php','_self')</script>";
							}
							else
							{
								echo "<script>window.open('ErrorPage.php','_self')</script>";
							}
						}
					}
					else
					{
						echo "<script>alert('Email Id or Password is not Match Try Again !')</script>";
						
					}
				}
			}				
			else 
			{
				$cap = '';
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login System</title>
   <link href="Designing/css/bootstrap.min.css" rel="stylesheet">
    <link href="Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="Designing/css/animate.min.css" rel="stylesheet">
    <link href="Designing/css/style.css" rel="stylesheet">  
	<script src="Designing/js/jquery.js"></script>
    <script src="Designing/js/bootstrap.min.js"></script>
    <script type="text/javascript" src='Captcha/js/jquery.min.js'></script>
    <script type="text/javascript">
     	$(document).ready(function(){
        	$('#submit').click(function(){
            	var name = $('#name').val();
            	var msg = $('#msg').val();
            	var captcha = $('#captcha').val();
            	if( name.length == 0){
            		$('#name').addClass('error');
            	}
            	else{
            		$('#name').removeClass('error');
            	}
				if( msg.length == 0){
                	$('#msg').addClass('error');
                }
                else{
                    $('#msg').removeClass('error');
                }
                if( captcha.length == 0){
	                $('#captcha').addClass('error');
                }
                else{
                    $('#captcha').removeClass('error');
                }
                if(name.length != 0 && msg.length != 0 && captcha.length != 0){
     	           return true;
                }
                return false;
            });
             var capch = '<?php echo $cap; ?>';
             if(capch != 'notEq'){
                if(capch == 'Eq'){
                    
                }
		else{
                    $('.cap_status').html("Human verification Wrong !").addClass('cap_status_error').fadeIn('slow');
                }
			}
        });
    </script>
</head>
<body>
	<div id="login-page">
		<div class="container">
        	<form method="post" action="index.php" class="form-login" >	  	
			<h2 class="form-login-heading">LED Project Management System</h2>
		   <div class="login-wrap">
            <img src="Designing/img/logo.png" class="img-responsive" />
			
               	<div class="row">
               		<div class="col-lg-8 col-sm-8">
                   		<img src="Designing/images/ledImage.jpg" class="img-responsive" />
                   	</div>
                   	
                   		<div class="col-lg-4 col-sm-4">
        					<div id="form">
               					<table width="100%">
                   					<tr>
                                       	<td><input type="text" name="username" id="name" class="form-control input-sm" placeholder="User ID" autofocus ></td>										
									</tr>									
									<tr>
										<td><input type="password" name="password" id="msg" class="form-control input-sm" placeholder="Password"autocomplete="off"></td>
									</tr>
									<tr>
										<td><input type="text" name="captcha" id="captcha" maxlength="4" size="4" placeholder="Enter Content of Image" class="form-control input-sm" autocomplete="off"/></td>
									</tr>
									<tr>
										<td><img src="Captcha/captcha.php"/></td>
									</tr>
									<tr>
										<td><button class="btn btn-theme btn-block" type="submit" id="submit" name="SignIn"><i class="fa fa-lock"></i> SIGN IN</button></td>
                       				</tr>
								</table>
							</div>
							<div class="cap_status col-lg-4"></div>
						</div>
					      
					</div>
				
			</div>
            
            </form>                   
        </div>		
	</div>	
</body>

</html>

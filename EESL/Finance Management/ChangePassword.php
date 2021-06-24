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
			if(isset($_POST['ResetPassword']))
			{
				$txtOldpass=base64_encode($_POST["Password"]);
				$txtnewPass=base64_encode($_POST["RePassword"]);
				$txtreenterPass=base64_encode($_POST["RePassword1"]);
		
				$fetchData="select password from tbluser where Id='$UserId'";
				$query=mysqli_query($con,$fetchData);
				$row = mysqli_fetch_array($query);
				$oldPassword=$row['password'];
				if($txtOldpass==$oldPassword)
				{
					if($txtnewPass==$txtreenterPass)
					{
						$updatePassword="update tbluser set password='$txtreenterPass' where Id='$UserId'";
						if(mysqli_query($con,$updatePassword))
						{
							echo "<script>alert('Password Changed Successfully !')</script>";
						}
						else
						{
							echo "<script>alert('Password not changed')</script>";
						}
					}
					else
					{
						echo "<script>alert('New Password and Reenter password not match')</script>";
					}
				}
				else
				{
					echo "<script>alert('Old Password does not match')</script>";
				}	 
		
			}
			
		}
	}
}
$_SESSION['timeout'] = time();	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Change Password</title>
				<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">

    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet">



	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script type="text/javascript">
                function CheckPassword()   
				{
 				var passw =/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=])\w{8,15}.*$/;
      			if (inputtxt.value.match(passw)) {
         alert('Correct, try another...')
         return true;
      }
      else {
         alert('Wrong...!')
         return false;
      }
}  
                
		function fd3() {
    var v1 = document.getElementById("newpassword").value;
	if(v1!="")
	{
        pass =/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,15}$/;

    if(!pass.test(v1)) {
        alert("Password is Not Correct Format\n!!Password format should be uppercase,\n lowercase,numeric and atleast one special charectar");
		
		window.setTimeout(function ()
    {
        document.getElementById("newpassword").focus();
    }, 0);
		return false;
		
	}
    }
}

function fd4() {
    var v2 = document.getElementById("newpass").value;
	if(v2!="")
	{
        pass1 =/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,15}$/;

    if(!pass1.test(v2)) {
        alert("Password is Not Correct Format\n!!Password format should be uppercase,\n lowercase,numeric and atleast one special charectar");
		
		window.setTimeout(function ()
    {
        document.getElementById("newpass").focus();
    }, 0);
		return false;
		
	}
    }
}
</script>
				<script type="text/javascript">
				$(document).ready(function () {
					$("#btnValidatepass").click(function () {
						var passText = $("#txtpass").val();
						var newPassText = $("#newpass").val();
						var newRepassText = $("#newpassword").val();						
						if (passText == ""){
							alert("Please enter old password !");
							window.setTimeout(function ()
							{
								$('#txtpass').focus();
							}, 0);
            				return false;
						}
						else if(newPassText == "")
						{
							alert("Please enter new password !");
							window.setTimeout(function ()
							{
								$('#newpass').focus();
							}, 0);
            				return false;
						}
						else if(newRepassText == "")
						{
							alert("Please re-enter passrord !");
							window.setTimeout(function ()
							{
								$('#newpassword').focus();
							}, 0);
            				return false;
						}						
						else if(newPassText != newRepassText)
						{
							alert("Re-enter password not match !");
							window.setTimeout(function ()
							{
								$('#newpassword').focus();
							}, 0);
            				return false;
						}
						else
						{							
							return true;
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


    <script src="../Designing/js/jquery.min.js"></script>
	
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
                                			<li><a href="MISReport.php"><i class="glyphicon glyphicon-print"></i> MIS Report</a></li>                        
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
            <!-- page content -->
<div class="right_col" role="main">
	<div class="">                    
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
            	
                      	<div class="x_panel">                	
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Change Password</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                <div class="row">
                                <div class="col-md-12 col-xs-12">
                                <div class="x_content">
                                    <form action="ChangePassword.php" method="post">
                                    <div class="form-horizontal form-label-left">
                                       
                                        <div class="form-group">
                                            <label class="col-md-3 col-sm-3 col-xs-12" for="password">Enter Old Password <span class="required" style="color:red;">*</span> </label>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                            	<input type="password"  name="Password"  class="form-control input-sm" id="txtpass"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 col-sm-3 col-xs-12" for="password">Enter New Password <span class="required" style="color:red;">*</span></label>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                               <input type="password" name="RePassword" class="form-control input-sm"  id="newpass"  onblur="return fd4();" maxlength="15"/>
                                                <span style=" color:#60C">password should be 8-15 character</span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-md-3 col-sm-3 col-xs-12" for="password2">Re-Enter New Password <span style="color:red;">*</span></label>
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                               <input type="password" name="RePassword1"  class="form-control input-sm" onblur="return fd3();" id="newpassword" maxlength="15"/>
                                                <span style=" color:#60C">password should be 8-15 character</span>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group"><br />
                            		<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-5">
                                		<button type="submit" name="ResetPassword" class="btn btn-primary" id="btnValidatepass">Save Changes</button>
                                    	
                            		</div>
                        		</div>
                                    </div>
                                    </form>
                                </div>
                                </div>
                                
                                </div>
                            </div>
                        </div>                        
                        </div>
              			</div>
                       
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

    <script src="../Designing/js/bootstrap.min.js"></script>
    <script src="../Designing/js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="../Designing/js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="../Designing/js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="../Designing/js/icheck/icheck.min.js"></script>

    <script src="../Designing/js/custom.js"></script>
    <!-- form validation -->
    <script src="../Designing/js/validator/validator.js"></script>
    

</body>
</html>
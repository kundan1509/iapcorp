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
if(isset($_GET['UsrID']))
{
	$GetId=$_GET['UsrID'];
	$SelectQuery="SELECT Id,UserId,PASSWORD,UserTypes,FinanceVerificationStatus FROM tbluser WHERE Id='$GetId' AND RecStatus='A'";
	$runSelect=mysqli_query($con,$SelectQuery);
	$row=mysqli_fetch_array($runSelect);
	
	if($runSelect)
	{
		$id=$row['Id'];
		$UserEmail=$row['UserId'];
		$UserPass=base64_encode($row['PASSWORD']);
		$usrType=$row['UserTypes'];
		if($usrType=='SA')
		{
			$utyp='ADMINISTATOR';
		}
		else if($usrType=='FA')
		{
			$utyp='FINANCE';
		}
		else if($usrType=='RO')
		{
			$utyp='OPERATION';
		}
		else if($usrType=='DS')
		{
			$utyp='WAREHOUSE';
		}
		else if($usrType=='VN')
		{
			$utyp='DISTRIBUTOR';
		}
	}
	else
	{
		$UserEmail="";
		$utyp="";
		echo "<script>alert('No Record Found Go Back !')";	
	}
}

if(isset($_POST['ResetPassword']))
{
	$Pass=base64_encode($_POST['Password']);
	$RePass=base64_encode($_POST['RePassword']);
	$UID=$_POST['UID'];
	$EMAIL=$_POST['UserEmail'];
	$descode=base64_decode($Pass);
	if($Pass==$RePass)
	{
		$resetPassQuery="UPDATE tbluser SET PASSWORD='$Pass' WHERE Id='$UID' AND UserId='$EMAIL' AND RecStatus='A' AND FinanceVerificationStatus='A'";
		$runResetPassword=mysqli_query($con,$resetPassQuery);
		if($runResetPassword)
		{
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
								$mail->Subject    = "This is confirmation Message";
								$mail->Body = "<h3>Dear User</h3>
												</br><h4>Your password Reset successfully. Please login with the new email id and password.</h4>
												</br><p>The Email Id and password are Given below.</p>
												</br><p>Email Id	:	$EMAIL</p>
												</br><p>Password	:	$descode</p>
												<br><br><br>
												<h4>Thanks & Regards </h4>
												<p>Administrator Team</p><br>
												";								
								$mail->AddAddress($EMAIL);	
								
								if(!$mail->Send()) {
									echo "<script>alert('Password reset successfully !')</script>";
									echo "<script>window.open('ResetPassword.php','_self')</script>";
								} else {
									
								echo "<script>alert('Password reset successfully !')</script>";
  								echo "<script>window.open('ResetPassword.php','_self')</script>";
								}
		}
		else
		{
		
		$SelectQuery="SELECT Id,UserId,PASSWORD,UserTypes,FinanceVerificationStatus FROM tbluser WHERE Id='$UID' AND RecStatus='A'";
		$runSelect=mysqli_query($con,$SelectQuery);
		$row=mysqli_fetch_array($runSelect);
	
		if($runSelect)
		{
			$id=$row['Id'];
			$UserEmail=$row['UserId'];
			$UserPass=$row['PASSWORD'];
			$usrType=$row['UserTypes'];
			if($usrType=='SA')
			{
				$utyp='ADMINISTATOR';
			}
			else if($usrType=='FA')
			{
				$utyp='FINANCE';
			}
			else if($usrType=='RO')
			{
				$utyp='OPERATION';
			}
			else if($usrType=='DS')
			{
				$utyp='WAREHOUSE';
			}
			else if($usrType=='VN')
			{
				$utyp='DISTRIBUTOR';
			}
		}
		}
	}
	else
	{
		$SelectQuery="SELECT Id,UserId,PASSWORD,UserTypes,FinanceVerificationStatus FROM tbluser WHERE Id='$UID' AND RecStatus='A'";
		$runSelect=mysqli_query($con,$SelectQuery);
		$row=mysqli_fetch_array($runSelect);
	
		if($runSelect)
		{
			$id=$row['Id'];
			$UserEmail=$row['UserId'];
			$UserPass=$row['PASSWORD'];
			$usrType=$row['UserTypes'];
			if($usrType=='SA')
			{
				$utyp='ADMINISTATOR';
			}
			else if($usrType=='FA')
			{
				$utyp='FINANCE';
			}
			else if($usrType=='RO')
			{
				$utyp='OPERATION';
			}
			else if($usrType=='DS')
			{
				$utyp='WAREHOUSE';
			}
			else if($usrType=='VN')
			{
				$utyp='DISTRIBUTOR';
			}
		}
		echo "<script>alert('Password not match try again !')</script>";
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>Reset Password</title>
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
					$("#btnResetPass").click(function () {
						var passText = $("#txtPass").val();
						var newPassText = $("#txtRepass").val();												
						if (passText == ""){
							alert("Please enter password !");
							window.setTimeout(function ()
							{
								$('#txtPass').focus();
							}, 0);
            				return false;
						}
						else if(newPassText == "")
						{
							alert("Please re-enter password !");
							window.setTimeout(function ()
							{
								$('#txtRepass').focus();
							}, 0);
            				return false;
						}												
						else if(passText != newPassText)
						{
							alert("Re-enter password not match !");
							window.setTimeout(function ()
							{
								$('#txtRepass').focus();
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
    var v1 = document.getElementById("txtPass").value;
	if(v1!="")
	{
        pass =/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,15}$/;

    if(!pass.test(v1)) {
        alert("Password is Not Correct Format\n!!Password format should be uppercase,\n lowercase,numeric and atleast one special charectar");
		
		window.setTimeout(function ()
    {
        document.getElementById("txtPass").focus();
    }, 0);
		return false;
		
	}
    }
}

function fd4() {
    var v2 = document.getElementById("txtRepass").value;
	if(v2!="")
	{
        pass1 =/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,15}$/;

    if(!pass1.test(v2)) {
        alert("Password is Not Correct Format\n!!Password format should be uppercase,\n lowercase,numeric and atleast one special charectar");
		
		window.setTimeout(function ()
    {
        document.getElementById("txtRepass").focus();
    }, 0);
		return false;
		
	}
    }
}

</script>


	
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
                                			<li><a href="AdministratorDSB.php"><i class="fa fa-home"></i> Home </a></li>
                                			<li><a href="UserRegistration.php"><i class="fa fa-check"></i>User Registration </a></li> 
                                			<li><a href="ResetPassword.php"><i class="fa fa-inr"></i> Reset Password </a></li>
                                            <li><a href="MISReport.php"><i class="glyphicon glyphicon-print"></i> &nbsp;&nbsp;&nbsp;MIS Report</a></li>                           
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
            	
                      	<div class="x_panel">                	
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Reset Password</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                                
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                
                                <div class="row">
                                <div class="col-md-6 col-xs-12">
                                <div class="x_content">
                                    <form action="DetailsResetPassword.php" method="post">
                                    <div class="form-horizontal form-label-left">
                                        <div class="form-group">
                                        <input type="hidden" value="<?php echo $id; ?>" name="UID" />
                                        <input type="hidden" value="<?php echo $UserEmail; ?>" name="UserEmail" />
                                            <label class="col-md-4 col-sm-4 col-xs-12">User Type</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $utyp; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Email Id</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $UserEmail; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Enter Password</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            	<input type="password" name="Password" class="form-control input-sm" pattern=".{8,15}" required title="8 to 15 characters" class="form-control input-sm" maxlength="15" id="txtPass" onblur="return fd3();"/>
                                                <span style=" color:#60C">password should be 8-15 character</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Re-Enter Password</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               <input type="password" name="RePassword" class="form-control input-sm" pattern=".{8,15}" required title="8 to 15 characters" class="form-control input-sm" maxlength="15" id="txtRepass" onblur="return fd4();"/>
                                               <span style=" color:#60C">password should be 8-15 character</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                     <br />
                            		<div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-5 col-sm-5 col-xs-12 col-lg-offset-4">
                                		<button type="submit" name="ResetPassword" class="btn btn-primary" onclick="return confirm('Are you sure to reset password ?');" id="btnResetPass">Save Changes</button>
                                        </div>
                                       <div class="col-md-3 col-sm-3 col-xs-12">
                                    	<a href="ResetPassword.php" class="btn btn-info"><i class="fa fa-arrow-left"></i>Go Back</a>
                                        </div>
                            		
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
                        <p class="col-sm-offset-3">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd<a>
                           
                        </p>
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
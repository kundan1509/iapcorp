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
		if(!($_SESSION['Name']&& $_SESSION['Type']=='SA'))
		{
			header("location:../Logout.php");
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
				<title>User Registration</title>
				<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">

    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Designing/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="../Designing/css/floatexamples.css" rel="stylesheet" type="text/css" />   
				
    			<!--[if lt IE 9]>
        			<script src="../assets/js/ie8-responsive-file-warning.js"></script>
        		<![endif]-->
				<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    			<!--[if lt IE 9]>
          			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        		<![endif]-->
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>                
            <script type="text/javascript">
				$(document).ready(function(){
				$("#txtEmail").blur(function() {
				var email = $('#txtEmail').val();
				var reg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
				if(email=="")
				{
					$("#disp").html("");
				}
				else if(reg.test(email) == false)
				{
					alert('Invalid Email Address');
					window.setTimeout(function ()
					{
						$('#txtEmail').focus();
					}, 0);
            		return false;    		
				}
				else
				{
				$.ajax({
					type: "POST",
					url: "get_userEmail.php",
					data: "email="+ email,
						success: function(html){
						$("#disp").html(html);
						}
					});
				return false;
				}
			});
		});
		</script>
        <script type="text/javascript">
				$(document).ready(function () {
					$("#btnValidate").click(function () {						
						var EmailText = $("#txtEmail").val();
						var TypeUsers = $("#UserType").val();
						var PassText = $("#txtpass").val();
						var RepassText = $("#txtrepass").val();
						var emailmsg=$('#disp').find('span').html();
						var check='Available';
						if (EmailText == "" || TypeUsers == 0 || PassText == "" || RepassText == ""){
							alert("Please select user type and fill all field ");
							return false;
						}
						else if(PassText != RepassText)
						{
							alert("Re-enter password not match !");
							window.setTimeout(function ()
							{
								$('#txtrepass').focus();
							}, 0);
            				return false; 
						}						
						else
						{
							
							if(emailmsg == check)
							{
								return true;
							}
							else
							{
								alert(emailmsg);
								return false;
							}
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
    			var v1 = document.getElementById("txtpass").value;
				if(v1!="")
				{
        		pass =/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,15}$/;

    		if(!pass.test(v1)) {
        alert("Password is Not Correct Format\n!!Password format should be uppercase,\n lowercase,numeric and atleast one special charectar");
		
			window.setTimeout(function ()
    		{
        		document.getElementById("txtpass").focus();
    		}, 0);
			return false;		
			}
    	}
	}

			function fd4() {
    		var v2 = document.getElementById("txtrepass").value;
			if(v2!="")
			{
        		pass1 =/^(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,15}$/;

    			if(!pass1.test(v2)) {
        alert("Password is Not Correct Format\n!!Password format should be uppercase,\n lowercase,numeric and atleast one special charectar");
		
				window.setTimeout(function ()
    			{
        			document.getElementById("txtrepass").focus();
    			}, 0);
				return false;
		
			}
    	}
	}
</script>
            <script src="../Designing/js/jquery.min.js"></script>   			
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
                                    
                                    <li><a href="../Logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
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
            	<form method="post" action="ResultUserRegistration.php">
                      	<div class="x_panel">                	
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>User Registration</h2>
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
                                            <label class="col-md-5 col-sm-5 col-xs-12">User Type <span style="color:red;">*</span></label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <select class="form-control input-sm" name="UsrType" required="required" id="UserType">
                                                <option value="0">Select User Type</option>                                                
                                                <option value="SA">Administrator</option>
                                                <option value="FA">Finance</option>
                                                <option value="RO">Operation</option>                                                
                                                </select>
                                            </div>
                                        </div>
                                    	<div class="form-group">                                        	
                                            <label class="col-md-5 col-sm-5 col-xs-12">Email Id <span style="color:red;">*</span></label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="email" name="emailId" class="form-control input-sm mail" id="txtEmail" maxlength="50"/>
                                            </div>
                                            <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-5"  id="disp"></div>
                                        </div>                                        
                                        <div class="form-group">
                                            <label class="col-md-5 col-sm-5 col-xs-12">Password  <span style="color:red;">*</span></label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="password" name="Password" class="form-control input-sm" pattern=".{8,15}" title="8 to 15 characters" class="form-control input-sm" id="txtpass" onblur="return fd3();" maxlength="15"/>
                                                <span style=" color:#60C">password should be 8-15 character</span>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-5 col-sm-5 col-xs-12">Re-Enter Password <span style="color:red;">*</span></label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="password" name="RePassword" class="form-control input-sm" pattern=".{8,15}" title="8 to 15 characters" class="form-control input-sm" id="txtrepass" onblur="return fd4();" maxlength="15"/>
                                                <span style=" color:#60C">password should be 8-15 character</span>
                                            </div>
                                        </div>  
                                        <div class="form-group"><br />
                            		<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-5">
                                		<button type="submit" name="UserRegistration" class="btn btn-primary" onclick="return confirm('Are you sure to create user ?');" id="btnValidate">Save Record</button>
                                    	
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
                        </form>
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
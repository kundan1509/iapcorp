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
		if(!($_SESSION['Name']&& $_SESSION['Type']=='DS'))
		{
			header("location:../Logout.php");
		}
		else
		{
			$id=$_SESSION['UserID'];
			$Ref=$_SESSION['Ref'];
			$errQuantity="";

			if(isset($_POST['ok']))
			{
				$Quantity =$_POST['LEDQuentity'];
				$reason =$_POST['Reason'];
				$DamageDate=$_POST['date'];
				$newDate=date('d-m-Y',strtotime(str_replace('-','/', $DamageDate)));
				$CurrentDate = date('d-m-Y');	
				if(!preg_match("/^[0-9]*$/",$Quantity))
				{
					$errQuantity="Only Number Are Allowed";
				}
				else
				{
					if($newDate>$CurrentDate)
					{
						echo "<script>alert('Selected date could not be future date!')</script>";
					}
					else
					{
						
						
						
			
						$checkwhinvent="SELECT count(Quantity)as cqty,SUM(Quantity)as qty FROM tblwarehouseinventory WHERE txntypein_out='IN' AND warehouseid='$RefId'";
						$chekqry=mysqli_query($con,$checkwhinvent);
						$fetchinvent=mysqli_fetch_array($chekqry);
						$chkqty=$fetchinvent['cqty'];
						if($chkqty>0)
						{
							$inqty=$fetchinvent['qty'];
						}
						else
						{
							$inqty=0;
						}
		 
		 
						$checkwhinvent_out="SELECT count(Quantity)as cqty,SUM(Quantity)as qty FROM tblwarehouseinventory WHERE txntypein_out='OUT' AND warehouseid='$RefId'";
						$chekqry_out=mysqli_query($con,$checkwhinvent_out);
						$fetchinvent_out=mysqli_fetch_array($chekqry_out);
						$chkqty_out=$fetchinvent_out['cqty'];
		
						if($chkqty_out>0)
						{
							$inqty_out=$fetchinvent_out['qty'];
						}
						else
						{
							$inqty_out=0;
						}
						
						$checkwhinvent_rep="SELECT count(Quantity)as cqty,SUM(Quantity)as qty FROM tblwarehouseinventory WHERE txntypein_out='R' AND warehouseid='$RefId'";
						$chekqry_rep=mysqli_query($con,$checkwhinvent_rep);
						$fetchinvent_rep=mysqli_fetch_array($chekqry_rep);
						$chkqty_rep=$fetchinvent_rep['cqty'];
		
						if($chkqty_rep>0)
						{
							$inqty_rep=$fetchinvent_rep['qty'];
						}
						else
						{
							$inqty_rep=0;
						}
						
						
		 
						$finalqty=$inqty-$inqty_out+$inqty_rep;
						if($Quantity>$finalqty)
						{
							
							
							echo "<script>alert('Warehouse stock is not sufficient! please Check your stock')</script>";
			 
						}
		 
		 
						else
						{
			
							
							
							$q="INSERT INTO warehousedamagestock(Quatity,DamageDate,Reason,RecStatus,Damagestatus,WareHouseId,VerifyRemarks)VALUES('$Quantity','$DamageDate','$reason','A','N','$RefId','Good')";
							$run=mysqli_query($con,$q);
							if($run)
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
								/*$mail->SMTPDebug  = 2;*/                  // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

								$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
				                $mail->AddReplyTo("projectled@iapcorp.com");


								$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
							     $mail->Subject    = "Regarding Damage LED Quantity";
								$mail->Body = "<h3>Dear Team</h3>
												</br><h4>Please Verify the following Damage LED Detail</h4>
												</br><p>Quantity:$Quantity</p>
												</br><p>Date:$DamageDate</p>
												</br><p></p>											
												</br><p></p>
												<br><br><br>
												<h4>Thanks & Regards </h4>
												<p>WArehouse Team</p><br>
												";	
								foreach($records as $rec)
								{	  
										$romail=$rec['RO'];
										$adminmail=$rec['SuperAdmin'];
										$finmail=$rec['Finance'];				
									
										$mail->AddAddress($romail);
										$mail->addCC($adminmail);									
										$mail->Send();
										$mail->ClearAllRecipients();
								}
								if(!$mail->Send()) {
  									echo "<script>alert('Damage stock records  saved successfully!')</script>";
									
								} else {
									
									echo "<script>alert('Damage stock records  saved successfully!')</script>";								
								}
		
		

							}
							else
							{
								echo "<script>alert('Damage stock records not saved !')</script>";	
							}
						}
					}
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
<title>Damage</title>
 <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="css/icheck/flat/green.css" rel="stylesheet" />
    <link href="css/floatexamples.css" rel="stylesheet" type="text/css" />
    
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script> 
<script type="text/javascript" src="http://cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
<script>
webshims.setOptions('forms-ext', {types: 'date'});
webshims.polyfill('forms forms-ext');
$.webshims.formcfg = {
en: {
    dFormat: '-',
    dateSigns: '-',
    patterns: {
        d: "dd-mm-yy"
    }
}
};
</script>

    <script src="js/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
  //called when key is pressed in textbox
  $(".number").keypress(function (e) {
	  
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
		e.ctrlKey || e.altKey
		e.preventDefault();
        $("#errmsg").html(" ").show().fadeIn("slow");
		alert('Only numeric allowed !');
               return false;
			   
    }
   });
});
				</script>
	<script type="text/javascript">
    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
		
        return false;
    return true;
}


$(function () {
		$('#Reason').keydown(function (e) {
		if (e.ctrlKey || e.altKey) {
			e.preventDefault();
			} else {
				var key = e.keyCode;
				if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90)|| (key==9))) {
				e.preventDefault();
				}
			}
		});
	});


function checkDate()
{
	
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
    		dd='0'+dd
					} 

		if(mm<10) {
    		mm='0'+mm
					} 

			today = yyyy+'-'+mm+'-'+dd;
			depdate=(document.getElementById('Pay_date1').value);
			if(depdate>today)
			{
			alert('Damage date should not greater than current date');
			window.setTimeout(function ()
    		{
        	document.getElementById('Pay_date1').focus();
			document.getElementById('Pay_date1').style.borderColor='red';
    		}, 0);
			
	
			return false;
			}
				else
				{
				document.getElementById('Pay_date1').style.borderColor='gray';
				return true;
				}

}

</script>

<script type="text/javascript">
	$(document).ready(function () {
		$('#textValidate').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
		$("#textValidate").on('keyup', function(e) {
		 		
   var val = $(this).val();
   if (val.match(/[^a-zA-Z\s]/g)) {
       $(this).val(val.replace(/[^a-zA-Z\s]/g, ''));
	   e.ctrlKey || e.altKey
	   alert(' Only character allowed !');
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
					
                    <div class="clearfix"></div>
					<br />
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">                            
                           <ul class="nav side-menu">
                             <li><a href="ConsignmentDSB.php"><i class="fa fa-home"></i> Home </a></li>
							  <li><a><i class="fa fa-truck"></i> Consignment<span class="fa fa-chevron-down"></span></a>
							  <ul  class="nav child_menu" style="display: none">
							   <li><a href="CreateConsignment.php">Create Consignment</a></li>
							   <li><a href="Consignment.php">Consignment Details</a></li>
							   </ul>
                              </li>
                               <li><a href="IssueSlip.php"><i class="glyphicon glyphicon-hdd" ></i>&nbsp;&nbsp;&nbsp;Issue slip </a></li>
                               <li><a href="Damage.php"><i class="fa fa-chain-broken" ></i>Damage Stock</a></li>
                               <li><a href="Replacement.php"><i class="fa fa-chain-broken" ></i>Replacement Stock </a></li>               
                               <li><a href="MISReport.php"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;MIS Report</a></li>                      
                            </ul>
                        </div>
                        

                    </div>
                    
                </div>
            </div>

             <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $Name;?>
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
            <div class="row">
			<div class="x_panel">
                        <div class="col-md-12 col-sm-12 col-xs-12">
						
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Damage Stock </h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                       
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div>

                  <div class="class="item form-group"">
                  <div class="x_content"><form class="form-horizontal form-label-left" method="post" action="Damage.php" onsubmit="return checkDate();">
  
 <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Quantity<span class="required" style="color:red">*</span></label>
<div class="col-md-5 col-sm-5 col-xs-12">

<input class="form-control col-md-7 col-xs-12 number" name="LEDQuentity" onpaste="return false" required="required" type="text" onkeypress="return isNumberKey(event)" maxlength="10" placeholder="Enter Damaged Quantity">
<span style="color:#F00;"><?php echo $errQuantity;?></span>
</div>
                                        </div>
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" > Damage Date<span class="required" style="color:red">*</span>
                                            </label>
                                            <div class="col-md-5 col-sm-5 col-xs-12">
                                                <input type="date" id="Pay_date1" name="date"  required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                        <div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Reason<span class="required" style="color:red">*</span> </label>
<div class="col-md-5 col-sm-5 col-xs-12"><input id="textValidate" name="Reason" onpaste="return false" class="form-control" maxlength="100" placeholder="Enter any Reason" required="required" type="text"></div>
                                        </div>
                                       
                                      
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-5">
                                               
                                                <button id="send" name="ok" type="submit" class="btn btn-primary">Save Record</button>
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
                <!-- footer content -->
                <footer>
                	<div class="">
                        <p class="col-sm-offset-3">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd<a>
                           
                        </p>
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

    <script src="js/bootstrap.min.js"></script>

    <!-- chart js -->
    <script src="js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="js/icheck/icheck.min.js"></script>
    <!-- gauge js -->
    <script type="text/javascript" src="js/gauge/gauge.min.js"></script>
    <script type="text/javascript" src="js/gauge/gauge_demo.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript" src="js/moment.min2.js"></script>
    <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
    <!-- sparkline -->
    <script src="js/sparkline/jquery.sparkline.min.js"></script>

    <script src="js/custom.js"></script>
    <!-- skycons -->
    <script src="js/skycons/skycons.js"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript" src="js/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="js/flot/date.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.spline.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.stack.js"></script>
    <script type="text/javascript" src="js/flot/curvedLines.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.resize.js"></script>

    <!-- flot -->

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

    <!-- moris js -->
    <script src="js/moris/raphael-min.js"></script>
    <script src="js/moris/morris.js"></script>
    <script>
        $(function () {
            var day_data = [
                {
                    "period": "Jan",
                    "Hours worked": 80
                },
                {
                    "period": "Feb",
                    "Hours worked": 125
                },
                {
                    "period": "Mar",
                    "Hours worked": 176
                },
                {
                    "period": "Apr",
                    "Hours worked": 224
                },
                {
                    "period": "May",
                    "Hours worked": 265
                },
                {
                    "period": "Jun",
                    "Hours worked": 314
                }
    ];
            Morris.Bar({
                element: 'graph_bar',
                data: day_data,
                hideHover: 'always',
                xkey: 'period',
                barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
                ykeys: ['Hours worked', 'sorned'],
                labels: ['Hours worked', 'SORN'],
                xLabelAngle: 60
            });
        });
    </script>
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



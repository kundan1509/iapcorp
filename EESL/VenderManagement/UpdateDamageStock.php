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
		$Refid=$_SESSION['Ref'];
		$FVerigy=$_SESSION['FV'];
		if(!($_SESSION['Name']&&$_SESSION['Type']=='VN'))
		{
			header("location:../Logout.php");
		}
		else
		{
			if(isset($_GET['DamageId']))
			{
				$DamId=$_GET['DamageId'];		
				$result= "SELECT L.LocationName,w.WareHouseName,w.WareHouseCode,
	                    VD.Quatity,
						VD.DamageDate,
						VD.Reason,
						VD.VerifyRemarks,
						VD.DamageId,
						VN.VendorName,
						VN.VendorCode
						FROM tbllocation AS L JOIN tblvendorlocation AS VL JOIN vendordamagestock AS VD JOIN tblvendorregistration AS VN  JOIN tblwarehouseregistration AS w    
                       WHERE L.LocId=VL.LocationId AND VL.locationid=VD.LocationId AND VD.Vendorid=VN.VendorId  AND VD.RecStatus='A' AND VD.DamageStatus='N' AND VD.DamageId='$DamId' AND VD.warehouseid=w.WarehouseId";
		
				$RunQuery=mysqli_query($con,$result);		
				while($row=mysqli_fetch_array($RunQuery))
				{
					$DamageId=$row['DamageId'];
					$VName=$row['VendorName'];
					$LName=$row['LocationName'];
					$VCode=$row['VendorCode'];
					$quantity=$row['Quatity'];
					$damagedateold=$row['DamageDate'];
					$damagedate = date("d-m-Y", strtotime($damagedateold));
					$Reson=$row['Reason'];		
					$varification=$row['VerifyRemarks'];
					$whcode=$row['WareHouseCode'];
					$whname=$row['WareHouseName'];
														
				}
		
			}

			if (isset($_POST['update_Damage']))
			{  
				$DamageId=$_POST['DamageId'];
				$quantity=$_POST['Quantity_name'];
				$reason=$_POST['Reason_name'];	
				$damagedate=$_POST['Right_off'];
				$selectdate=$_POST['Right_off1'];
				$location=$_POST['location'];	
		
				if(!preg_match("/^[0-9]*$/",$quantity))
				{
					$errQuantity="Enter Only Numeric Value";
				}
		   
				else
				{
					if($selectdate=="")
					{
						$currentStock="SELECT SUM(IF(TXNTypein_out ='IN',quantity,0)) - SUM(IF (TXNTypein_out = 'OUT',quantity,0))AS total FROM tblvendorinventory 
						WHERE RecStatus='A' AND VendorId='$Refid' AND LocationId='$location'";	 				 			   
						$query=mysqli_query($con,$currentStock);
						if($query)
						{				
							while($row = mysqli_fetch_array($query))
							{
								$tot=$row['total'];
							}
								if($tot>=$quantity)
								{
									$update = " update vendordamagestock set
									Quatity='$quantity',
									DamageDate='$damagedate',
									Reason='$reason',
									LocationId='$location'
									where DamageId='$DamageId' and RecStatus='A' and DamageStatus='N'";	 
									if(mysqli_query ($con,$update))
									{
										echo "<script>alert('Damage stock updated successfully !')</script>";	
										echo "<script>window.open('DamageDisplay.php','_self')</script>";
									}		
									else
									{
										echo "<script>alert('Damage stock  not updated !')</script>";					
									} 
								}
				
								else
								{
				     
									$DamId=$_POST['DamageId'];		
									$result= "SELECT L.LocationName,
									VD.Quatity,
									DATE_FORMAT(VD.DamageDate,'%d-%m-%y') AS DamageDate,
									VD.Reason,
									VD.VerifyRemarks,
									VD.DamageId,
									VN.VendorName,
									VN.VendorCode
									FROM tbllocation AS L JOIN tblvendorlocation AS VL JOIN vendordamagestock AS VD JOIN tblvendorregistration AS VN      
									WHERE L.LocId=VL.LocationId AND VL.locationid=VD.LocationId AND VD.Vendorid=VN.VendorId  and VD.RecStatus='A' and VD.DamageStatus='N' and VD.DamageId=$DamId";
		
									$RunQuery=mysqli_query($con,$result);		
									while($row=mysqli_fetch_array($RunQuery))
									{
										$DamageId=$row['DamageId'];
										$VName=$row['VendorName'];
										$LName=$row['LocationName'];
										$VCode=$row['VendorCode'];
										$quantity=$row['Quatity'];
										$damagedate=$row['DamageDate'];
										$Reson=$row['Reason'];		
										$varification=$row['VerifyRemarks'];															
									} 
									echo "<script>alert('Stock is not sufficient ! Please check Stock')</script>";
									echo "<script>window.open('DamageDisplay.php','_self')</script>";
						  
								}
							
						}
					}	
					else
					{
						$currentStock="SELECT SUM(IF(TXNTypein_out ='IN',quantity,0)) - SUM(IF (TXNTypein_out = 'OUT',quantity,0))AS total FROM tblvendorinventory 
						WHERE RecStatus='A' AND VendorId='$Refid' AND LocationId='$location'";	 				 			   
						$query=mysqli_query($con,$currentStock);
						if($query)
						{				
							while($row = mysqli_fetch_array($query))
							{
								$tot=$row['total'];
							}
								if($tot>=$quantity)
								{
									$update = " update vendordamagestock set
									Quatity='$quantity',
									DamageDate='$selectdate',
									Reason='$reason',
									LocationId='$location'
									where DamageId='$DamageId' and RecStatus='A' and DamageStatus='N'";	 
									if(mysqli_query ($con,$update))
									{
										echo "<script>alert('Damage stock updated successFully !')</script>";	
										echo "<script>window.open('DamageDisplay.php','_self')</script>";
									}		
									else
									{
										echo "<script>alert('Damage stock  not updated !')</script>";	
										echo "<script>window.open('DamageDisplay.php','_self')</script>";			
									} 
								}
				
								else
								{
									$DamId=$_POST['DamageId'];		
									$result= "SELECT L.LocationName,
									VD.Quatity,
									DATE_FORMAT(VD.DamageDate,'%d-%m-%y') AS DamageDate,
									VD.Reason,
									VD.VerifyRemarks,
									VD.DamageId,
									VN.VendorName,
									VN.VendorCode
									FROM tbllocation AS L JOIN tblvendorlocation AS VL JOIN vendordamagestock AS VD JOIN tblvendorregistration AS VN      
									WHERE L.LocId=VL.LocationId AND VL.locationid=VD.LocationId AND VD.Vendorid=VN.VendorId  and VD.RecStatus='A' and VD.DamageStatus='N' and VD.DamageId=$DamId";
		
									$RunQuery=mysqli_query($con,$result);		
									while($row=mysqli_fetch_array($RunQuery))
									{
										$DamageId=$row['DamageId'];
										$VName=$row['VendorName'];
										$LName=$row['LocationName'];
										$VCode=$row['VendorCode'];
										$quantity=$row['Quatity'];
										$damagedate=$row['DamageDate'];
										$Reson=$row['Reason'];		
										$varification=$row['VerifyRemarks'];	
														
									}
									echo "<script>alert('Stock is not sufficient ! Please check Stock')</script>";
									echo "<script>window.open('DamageDisplay.php','_self')</script>";
						  
						 
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
<title>Update Damage Stock</title>
<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Finance Management/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="../Designing/css/floatexamples.css" rel="stylesheet" type="text/css" />
    <script src="../Designing/js/jquery.min.js"></script>    
  	   <script type="text/javascript">
					$(document).ready(function () {
  //called when key is pressed in textbox
  $("#quantity").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html(" ").show().fadeIn("slow");
		alert(' Only  numeric allowed !');
               return false;
    }
   });
});
				</script>
			
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
depdate=(document.getElementById('name').value);
if(depdate>today)
{
	alert('Damage date not greater than current date');
	
	
			
				window.setTimeout(function ()
    {
         document.getElementById("name").focus();
    }, 0);
            return false;
}
else
{
	document.getElementById('name').style.borderColor='gray';
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
     <script type="text/javascript">
        $(function () {
            $('#btnCheck').click(function () {
				  var txtnam = $('#name1');             
			      var txtno = $('#quantity');			
				   var locationdate=$('#location');								
			    if (txtnam.val() != '' && txtno.val() != '' && locationdate.val() != '0') {
             
                   return true;
                }
                else{
                      alert('Please  fill all the field')						
					return false;
                }
				
            })
        });
</script> 
       

</head>

<body class="nav-md" >
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
                                <li><a href="VenderManagementDSB.php"><i class="glyphicon glyphicon-home"></i> &nbsp; Home </a></li>
                                <li><a href="Payments.php"><i class="fa fa-inr"></i> Payment</a></li> 
                                 <li><a href="IssueSlip.php"><i class="glyphicon glyphicon-hdd"></i>&nbsp; Issue Slip</a></li>
                                  <li><a href="Sales.php"><i class="fa fa-cc-visa"></i>&nbsp; Distribution</a></li>
                                   <li><a href="VendorDamage.php"><i class=" fa fa-chain-broken"></i>&nbsp; Damage Stock</a></li>
                                    <li><a href="Replacementstock.php"><i class=" fa fa-chain-broken"></i>&nbsp;Replacement Stock</a></li>								   
                           <li><a><i class="fa fa-th-list"></i>&nbsp; Display<span class="fa fa-chevron-down"></span></a>
                                  <ul class="nav child_menu" style="display: none">
                                        <li><a href="PaymentDisplay.php">Payments</a> </li> 
                                         <li><a href="IssueSlipDisplay.php">Issue Slip</a> </li>										
                                        <li><a href="SalesDisplay.php">Distribution</a> </li>                                       
                                        <li><a href="DamageDisplay.php">Damage Stock</a> </li>
                                       <li><a href="REplacementDisplay.php">Replacement Stock</a> </li> 										
                                    </ul>
                                </li>                                                       
                                 <li><a href="ReportMis.php"><i class="glyphicon glyphicon-print"></i> &nbsp;MIS Report </a></li>                      
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
                                    <?php echo $Name ?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li><a href="ChangePassword.php"> <i class="fa fa-sign-out pull-right"></i>Change Password</a>
                                    </li>                                    
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
				<div class="row">                
                        <div class="col-md-12 col-sm-12 col-xs-12">                        
                            <div class="x_panel">                      
                               <div class="x_title" style="color:#FFF; background-color:#889DC6;">                           
                                <h2>&nbsp; Update Damage Stock</h2>                      
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                            
                                       
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">                              
                          
                               <form class="form-horizontal" method="post" action="UpdateDamageStock.php" enctype="multipart/form-data" onsubmit="return checkDate();"  >
                             
                        	<input type="hidden" value="<?php echo $DamageId; ?>" name="DamageId" />
							<div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label>Distributor Name <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<div class="col-md-7 col-xs-12" ><?php echo $VName; ?></div>						
                                </div>
                            </div>
                        <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label>Distributor Code<span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<div class="col-md-7 col-xs-12"><?php echo $VCode; ?></div>
                                </div>
                            </div>
                            
                            
                             <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label>Warehouse Code<span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<div class="col-md-7 col-xs-12"><?php echo $whcode; ?></div>
                                </div>
                            </div>
                            
                            
                            
                             <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label>Warehouse Name<span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<div class="col-md-7 col-xs-12"><?php echo $whname; ?></div>
                                </div>
                            </div>
                            
                            
                            
                            
							  
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Quantity <span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                	<input id="quantity" class="form-control col-md-7 col-xs-12" maxlength="10" onpaste="return false;" AUTOCOMPLETE="off"  name="Quantity_name" required="Required" type="text" value="<?php echo $quantity; ?>">
									
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Damage Stock Date<span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                	<input  readonly="readonly" class="form-control col-md-7 col-xs-12" onpaste="return false;" AUTOCOMPLETE="off"  name="Right_off" type="text"" value="<?php echo $damagedate; ?>" >                           
                                </div>
                            </div>
							  <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name"></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                	<input id="name" class="form-control col-md-7 col-xs-12" placeholder=" Select damage date "  onpaste="return false;" AUTOCOMPLETE="off"  name="Right_off1" type="date" >
                           
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Reason  <span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                	<input id="textValidate" class="form-control col-md-7 col-xs-12"  maxlength="100" onpaste="return false;" AUTOCOMPLETE="off"  name="Reason_name" required="Required" type="text" value="<?php echo $Reson; ?>">
                                </div>
                            </div>
                         							
                              <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Location Name<span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                               <div class="col-md-3 col-sm-3 col-xs-12">

									  <select id="location" name="location" onpaste="return false;" AUTOCOMPLETE="off" class="form-control col-md-7 col-xs-12" value=" <?php echo $LName; ?>">                                    
                                                        <?php		                                         
                                                     $msql = mysqli_query($con,"SELECT L.LocId,L.LocationName FROM tbllocation AS L JOIN tblvendorlocation AS VL ON L.LocId=VL.LocationId WHERE VL.VendorId='$Refid'");	
                                                     while($m_row = mysqli_fetch_array($msql)) 
                                                               {
	                                                       echo "<option value='".$m_row['LocId']."'>".$m_row['LocationName']."</option>";
                                       }
                                   ?>                                               
														</select>
                                </div>
                            </div>
							
                            <div class="ln_solid"></div>
                            	<div class="form-group">
                                	<div class="col-md-6 col-md-offset-3 col-xs-12">
                                   <button  name="update_Damage" type="submit" id="btnCheck" class="btn btn-md btn-info" style=" width:120px;"  onclick="return confirm('Are you  sure to update Damage stock  ?');">Update Records</button>									
                                        <a  href="DamageDisplay.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
                                	</div>
                            </div>
                        </form>
                             
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- footer content -->
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


 
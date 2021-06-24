<?php
	
	include("../Connection/Connection.php");
	
?>
<?php

if(isset($_GET['DamageId']))
	{
		$did=$_GET["DamageId"];
	}
	
	
	$vendordamagedetail="SELECT w.WareHouseName,w.WareHouseCode,w.WarehouseId,VR.Quatity,VR.Replacementid,L.Locid,V.vendorid,VR.DamageDate, VR.Reason, V.vendorname, L.Locationname FROM tblvendorreplacement VR,tblwarehouseregistration w,
tblvendorregistration V, tbllocation L WHERE VR.Vendorid = V.Vendorid AND VR.LocationId = L.Locid AND  VR.recstatus = 'A' and VR.Replacementid='$did' and w.WarehouseId=VR.warehouseid";
	  $query=mysqli_query($con,$vendordamagedetail);
      while ($row = mysqli_fetch_array($query))	  
	  {
		$vid=$row['vendorid'];
		 $lid=$row['Locid'];
	  	$damageid=$row['Replacementid'];
      	$vendorname=$row['vendorname'];
	  	$quantity=$row['Quatity'];
	   $date=$row['DamageDate'];
	   $newAppDate = date("d-m-Y", strtotime($date));
	   $reason=$row['Reason'];
	   $location=$row['Locationname'];
	   $whname=$row['WareHouseName'];
	   $whcode=$row['WareHouseCode'];
	   $whid=$row['WarehouseId'];
	  }

?>

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
		if(!($_SESSION['Name']&&$_SESSION['Type']=='RO' || $_SESSION['Type']=='SA' ))
		{
			header("location:../Logout.php");
		}
		else
		{


if(isset($_POST['Verify']))
{
	$damid= $damageid;
	$remark=$_POST["remark"];
	
	$wid=$_POST['wid'];
	$venid=$_POST['vid'];
	$locid=$_POST['lid'];
	$qty=$_POST['qty'];
	$newAppDate = date("Y-m-d", strtotime($date));
	
$updatevendorDamage="update tblvendorreplacement set DamageStatus='Y',VerifyRemarks='$remark' where Replacementid='$damid'";
$deductvendordamage="INSERT INTO vendordamagestock(Vendorid,Quatity,DamageDate,Reason,RecStatus,LastUpdateOn,UpdatebyID,LocationId,DamageStatus,warehouseid)VALUES('$venid','$qty','$newAppDate','$remark','A','$newAppDate','$RefId','$locid','O','$wid')";

$insertinVendorInventory="insert into tblvendorinventory(vendorid,Quantity,TxnTypeIn_out,TransactionNo,LocationId,RecStatus)values('$vid','$qty','R','$remark','$lid','A')";


	if(mysqli_query($con,$updatevendorDamage))
	{
		 mysqli_query($con,$deductvendordamage);
		 mysqli_query($con,$insertinVendorInventory);
		echo "<script>alert('Replacement Successfully Verify')</script>";
		echo "<script>window.open('vendorReplacementList.php','_self')</script>";
		
		
	}
	else
	{
		
		echo "<script>alert('Replacement Not Verify')</script>";
		echo "<script>window.open('vendorReplacementList.php','_self')</script>";
		
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
				<title>Replacement  Details</title>
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
					$(document).ready(function()
					{    
    					$("#r1").click(function()
						{
							document.getElementById('AReason').style.display = 'block';
							document.getElementById('RReason').style.display = 'none';
    					});
    					$("#r2").click(function()
						{
        					document.getElementById('AReason').style.display = 'none';
        					document.getElementById('RReason').style.display = 'block';
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

                    <!-- menu prile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                           
                        </div>
                       
                    </div>
                    <!-- /menu prile quick info -->

                    <br />

                    <!-- sidebar menu -->
                 <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
                           
                         <ul class="nav side-menu">
                                <li><a href="OperationDSB.php"><i class="fa fa-home"></i> Home</a></li>
							    <li><a><i class="fa fa-cog"></i> Configuration<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                    	<li><a href="state.php">State</a></li>
                                        <li><a href="distric.php">District</a></li>
                                        <li><a href="Location.php">Location</a></li>
                                        <li><a href="Eligibility.php">Eligibility</a></li>
                                        <li><a href="MailingList.php">E-Mails</a></li>
                                        <li><a href="masterIssue.php">Issue</a></li>
                                         <li><a href="poolbankaccount.php">PoolAccount</a></li>
                                         <li><a href="refrral.php">Referral</a></li>
                                         
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-edit"></i> Registration <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
										<li><a href="VendorRegistration.php">Distributor</a></li>
                                        <li><a href="WarehouseRegistration.php">WareHouse</a></li>
                                    </ul>
                                </li>
								<li><a href="Consignment.php"><i class="fa fa-truck"></i>Consignment</a></li>
                                <li><a href="IssueSlip.php"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;&nbsp;&nbsp;Issue Slip</a></li>
							
								<li><a><i class="fa fa-chain-broken"></i>&nbsp;Damage Stock<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                       <li><a href="vendordamagelist.php">Distributor</a></li>
                                       <li><a href="warehousedamagelist.php">WareHouse</a></li>
                                    </ul>
                                </li>
								
					          <li><a><i class="fa fa-chain-broken"></i>&nbsp;Replacement Stock<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                       <li><a href="vendorReplacementList.php">Distributor</a></li>
                                       <li><a href="warehouseReplacementList.php">WareHouse</a></li>
                                    </ul>
                                </li>

								
								
                              <li><a><i class="fa fa-th-list"></i>&nbsp;Display<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
										<li><a href="VendorList.php">Distributor List</a></li>
										<li><a href="VedorLocationList.php">Distributor Location List</a></li>
										<li><a href="warehouselist.php">WareHouse List</a></li>
										<li><a href="Consignmentlist.php">Consignment List</a></li>
										<li><a href="IssueslipList.php">IssueSlip List </a></li>
										<li><a href="LocationList.php">Location List</a></li>
										<li><a href="EligibilityList.php">Eligibility List</a></li>
									    <li><a href="StateList.php">State List</a></li>
                                        <li><a href="DistrictList.php">District List</a></li>
                                        <li><a href="PoolAcountLIst.php">PoolAccount List</a></li>
                                         <li><a href="RefrralList.php">Referral  List</a></li>
                                             
										
                                    </ul>
                                </li>
 <li><a href="ReportMis.php"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;&nbsp;&nbsp;MisReport</a></li>
                            </ul>
                        </div>
                        <div class="menu_section">
                            
                                
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
            	<form method="post">
                      	<div class="x_panel">                	
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>Distributor Replacement Verification</h2>
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
                                        	<input type="hidden" value="<?php echo $damageid;  ?>" name="VId" />
                                        	
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Name:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $vendorname ?>
                                            </div>
                                        </div>
                                        <div class="form-group">                                        	
                                            <label class="col-md-4 col-sm-4 col-xs-12">Quantity:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $quantity;?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">                                        	
                                            <label class="col-md-4 col-sm-4 col-xs-12">Warehouse Code:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $whcode;?>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">                                        	
                                            <label class="col-md-4 col-sm-4 col-xs-12">Warehouse Name:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $whname;?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Damage Date:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $newAppDate; ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Location:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $location; ?>
                                            </div>
                                        </div>   
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Reason</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $reason ?>
                                            </div>
                                        </div> 
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Remark</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <textarea name="remark" class="form-control" style="resize:none;"></textarea>
                                                              </div>
                                        </div> 
                                                                               
                                        </div>                              
                                    </div>
                                </div>
                                
                                <input type="hidden" value="<?php echo $vid;?>" name="vid" />
                                <input type="hidden" value="<?php echo $lid;?>" name="lid" />
                                <input type="hidden" value="<?php echo $whid;?>" name="wid" />
                                <input type="hidden" value="<?php echo $quantity;?>" name="qty" />
                               
                               <!-- <div class="col-md-6 col-xs-12">
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
                                </div>-->
                                </div>
                            </div>
                        </div>                        
                    </div>
                  <!--  <div class="row">
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
                                            <label class="col-md-4 col-sm-4 col-xs-12">Payment Date</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $PaymentDate; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
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
                                                <?php echo $PaymentProof; ?>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>                        
                        </div>-->
                   
                       <!-- <div class="row">
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
        											<label class="col-md-6 col-sm-6 col-xs-6">Accepted</label>
        											<label class="col-md-6 col-sm-6 col-xs-6"> Rejected</label>
    											</div>
                                            	<div class="row">
        											<div class="col-md-6 col-sm-6 col-xs-6">
                                            			<input type="radio" id="r1" name="AdvancePay" value="A">
                                                    </div>
        											<div class="col-md-6 col-sm-6 col-xs-6">
                                            			<input type="radio" id="r2" name="AdvancePay" value="R">
                                                    </div>
    											</div>
                                            	<div class="row"><br />
                                            		<div class="col-md-6 col-sm-6 col-xs-12" >
    													<textarea type="text" id="AReason" class="form-control" style="resize:none;" placeholder="Accepted Reaon ... " name="AcceptReason"></textarea>
                                                	</div>
                                            		<div class="col-md-6 col-sm-6 col-xs-12" >
    													<textarea type="text" id="RReason" class="form-control" style="resize:none;" placeholder="Rejected Reaon ... " name="RejectReason"></textarea>
                                                	</div>
                                            	</div>
    										</div>
                                    	</div>
                                	</div>
                            	</div>
                        	</div>
                        	
                        </div>-->
                        <div class="row">
							<div class="ln_solid"></div>
                        		<div class="form-group">
                            		<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-5">
                                		<button type="submit" name="Verify" class="btn btn-primary" onclick="return confirm('Are you Sure Verify Record ?');">Save Changes</button>
                                    	<a href="vendorReplacementList.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
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
                    <div class="">
                        <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd<a>
                           
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
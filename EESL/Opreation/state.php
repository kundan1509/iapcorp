
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


if(isset($_POST['SaveState']))
{
	include("../Connection/Connection.php");
	$StateName=$_POST['StateName'];
	$StateCode=$_POST['StateCode'];
	$buyrate=$_POST['buyrate'];
	$sellrate=$_POST['sellrate'];
	date_default_timezone_set("Asia/Kolkata"); 
    $CurrentDate=date('Y-m-d H:i:s A');
	if($StateName!="" && $StateCode!="")
	{
		$SelectState="SELECT COUNT(Stateid) AS StateCount ,StateName,StateCode FROM tblstate WHERE RecStatus='A' AND StateName='$StateName'";
		$SelectQuery=mysqli_query($con,$SelectState);
		$RowCount=mysqli_fetch_array($SelectQuery);
		if($RowCount['StateCount']>0)
		{
			echo "<script>alert('State Name Already Exist !')</script>";
		}
		else
		{
		$QueryState="INSERT INTO tblstate (StateName,StateCode,RecStatus,LastUnitVendor,LastUnitWarehouse,LastUpdateOn,UpdateById,buyrate,sellrate)
	VALUES('$StateName','$StateCode','A','0','0','$CurrentDate','$UserId','$buyrate','$sellrate')";
		$runState=mysqli_query($con,$QueryState);
		if($runState)
		{
			echo "<script>alert('Record Inserted Successfully !')</script>";
		}
		else
		{
			echo "<script>alert('Record Not Inserted Try Again !')</script>";
		}
		}
	}
	else
	{
		echo "<script>alert('Please Fill All the Field !')</script>";
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
	<title> State</title>
   	<link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
             <script type="text/javascript">
	$(document).ready(function () {
		$('.textValidate').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
		$(".textValidate").on('keyup', function(e) {
		 		
   var val = $(this).val();
   if (val.match(/[^a-zA-Z\s]/g)) {
       $(this).val(val.replace(/[^a-zA-Z\s]/g, ''));
	   e.ctrlKey || e.altKey
	   alert(' Only character allowed !');
   }
});
	});
</script>

    <script src="../Designing/js/jquery.min.js"></script>				
    <script type="text/javascript">
	$(document).ready(function () {
  //called when key is pressed in textbox
  $(".txtNumeric").keypress(function (e) {
	$('.txtNumeric').bind("cut copy paste",function(e) {
          e.preventDefault();
      });  
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46 && e.which != 13) {
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
</script>		
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
                            	<li><a href="OperationDSB.php"><i class="fa fa-home"></i> Home</a></li>                                
                                   <li><a><i class="fa fa-cog"></i> Configuration<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                    	<li><a href="state.php">State</a></li>
                                        <li><a href="distric.php">District</a></li>
                                        <li><a href="Location.php">Location</a></li>
                                        <li><a href="Eligibility.php">Eligibility</a></li>
                                        <li><a href="MailingList.php">E-Mails</a></li>
                                     
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
                                         <li><a href="RefrralList.php">Referral List</a></li>
                                             
										
                                    </ul>
                                </li>
								<li><a href="ReportMis.php"><i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;MisReport</a></li>
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
                            <li>
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
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel" style="height:500px;">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>State Configuration</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                     </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                	<form method="post" action="state.php" autocomplete="off">
                                    	<div class="col-lg-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                            	<div class="col-lg-2 col-sm-2 col-xs-12">
                                                	<label class="control-label">State Name<span style="color:red; margin-top:8px;"> *</span></label>
                                                </div>
                                                <div class="col-lg-4 col-sm-4 col-xs-12">
                                                	<input type="text" name="StateName" maxlength="20" class="textValidate form-control input-sm" required="required" onkeypress="return onlyAlphabets(event,this);" />
                                                </div>
                                            </div><br />
                                            <div class="row">
                                            	<div class="col-lg-2 col-sm-2 col-xs-12">
                                                	<label class="control-label">State Code<span style="color:red; margin-top:8px;"> *</span></label>
                                                </div>
                                                <div class="col-lg-4 col-sm-4 col-xs-12">
                                                	<input type="text" name="StateCode" maxlength="5" class="textValidate form-control input-sm" required="required" onkeypress="return onlyAlphabets(event,this);" />
                                                </div>
                                            </div><br />
                                            
                                            
                                             <div class="row">
                                            	<div class="col-lg-2 col-sm-2 col-xs-12">
                                                	<label class="control-label">Buy Rate<span style="color:red; margin-top:8px;"> *</span></label>
                                                </div>
                                                <div class="col-lg-4 col-sm-4 col-xs-12">
                                                	<input type="text" name="buyrate" maxlength="6" class="txtNumeric form-control input-sm" required="required" onkeypress="return isNumberKey(event);" />
                                                </div>
                                            </div><br />
                                            
                                            
                                             <div class="row">
                                            	<div class="col-lg-2 col-sm-2 col-xs-12">
                                                	<label class="control-label">Sell Rate<span style="color:red; margin-top:8px;"> *</span></label>
                                                </div>
                                                <div class="col-lg-4 col-sm-4 col-xs-12">
              <input type="text" name="sellrate" maxlength="6" class="txtNumeric form-control input-sm" required="required" onkeypress="return isNumberKey(event);" />
                                                </div>
                                            </div><br />
                                            
                                            
                                            
                                            
                                            <div class="row">
                                            	<div class="col-lg-3 col-sm-3 col-xs-12 col-sm-offset-2">
                                                	<input type="submit" name="SaveState" class="btn btn-primary" value="Save Record" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer>
                    <div class="">
                        <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd
                           
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </footer>

            </div>
					
				</div>
			</div>
         </div>
	</div>
</div>
<div id="custom_notifications" class="custom-notifications dsp_none">
	<ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group"></ul>
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

    <script src="js/custom.js"></script>

    <!-- moris js -->
    <script src="js/moris/raphael-min.js"></script>
    <script src="js/moris/morris.js"></script>
    <script src="js/moris/example.js"></script>
</body>
</html>
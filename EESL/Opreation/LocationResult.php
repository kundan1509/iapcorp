<?php
session_start();
	include("../Connection/Connection.php");
	$UserId=$_SESSION['UserID'];
	$Name=$_SESSION['Name'];
	$UserType=$_SESSION['Type'];
	$RefId=$_SESSION['Ref'];
	$FVerigy=$_SESSION['FV'];
	if(!($_SESSION['Name']&&$_SESSION['Type']=='RO' || $_SESSION['Type']=='SA'))
	{
		header("location:../Logout.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Meta, title, CSS, favicons, etc. -->
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Loacation</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">
	<script src="js/jquery.min.js"></script>
 
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <img src="../Designing/img/HeaderLogo.png" alt="..." class="img-responsive">
                    <div class="clearfix"></div>                    
                    <div class="profile"><div class="profile_pic"></div></div>
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
                                         <li><a href="RefrralList.php">Referral  List</a></li>
                                             
										
                                    </ul>
                                </li>
								<li><a href="ReportMis.php"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;&nbsp;&nbsp;MisReport</a></li>
							</ul>
						</div>
						<div class="menu_section"></div>
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
            
            <div class="right_col" role="main">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title" style="color:#FFF; background-color:#889DC6">
								<h2>Location Configuration</h2>
								<ul class="nav navbar-right panel_toolbox">
									<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
							<?php
								if(isset($_POST['SaveLocation']))
								{
									$StateId=$_POST['StateList'];
									$DistricId=$_POST['DistricList'];
									$LocationName=$_POST['LocationName'];
									$LocationDesc=$_POST['Description'];
									date_default_timezone_set("Asia/Kolkata"); 
   									$CurrentDate=date('Y-m-d H:i:s A');									
									$SelState="SELECT s.Stateid,s.StateName,s.StateCode,s.LastUnitVendor,s.LastUnitWarehouse,d.CityId,d.CityName,d.CityCode,d.LastUnitVendor,d.LastUnitWarehouse
FROM tblstate AS s JOIN tblcity AS d ON s.Stateid=d.StateId
WHERE s.RecStatus='A' AND d.RecStatus='A' AND d.CityId='$DistricId' AND s.Stateid='$StateId'";
										$RunSelState=mysqli_query($con,$SelState);
										$GetSelStateRow=mysqli_fetch_array($RunSelState);
										
										$stateName=$GetSelStateRow['StateName'];
										$districName=$GetSelStateRow['CityName'];
										$stateCode=$GetSelStateRow['StateCode'];
										$districCode=$GetSelStateRow['CityCode'];	
									$SelectLoc="SELECT COUNT(LocId) AS LocCount,LocationName,LocationCode,Description,RecStatus,LastUpdateOn,UpdatebyID,CityId FROM tbllocation WHERE RecStatus='A' AND LocationName='$LocationName' AND CityId='$DistricId'";
									$RunSelectLoc=mysqli_query($con,$SelectLoc);
									$GetSelectRow=mysqli_fetch_array($RunSelectLoc);
									if($GetSelectRow['LocCount']>=1)
									{										
										$locCode=$GetSelectRow['LocationCode'];
										$locName=$GetSelectRow['LocationName'];						
										
                                        echo "<div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>State Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$stateName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Distric Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$districName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Location Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$locName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Location Code</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$locCode
                                            </div>
                                        </div><br />
                                        <div class='row' style='background-color:#FFCACA;'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Error Message : </label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	Record Already Exist Check Details Try Again !
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-3 col-sm-3 col-xs-12 col-sm-offset-2'>
                                            	<a href='Location.php' class='btn btn-primary'> &nbsp; OK &nbsp; </a>
                                            </div>
                                        </div><br />";                       
										
									}
									else
									{
										$SelectMaxLoc="SELECT COUNT(CityId) AS CityCount,MAX(LocationCode) AS maxLocCod FROM tbllocation WHERE RecStatus='A' AND CityId='$DistricId'";
										$runMaxLoc=mysqli_query($con,$SelectMaxLoc);
										$rowMaxLoc=mysqli_fetch_array($runMaxLoc);
										$CityCount=$rowMaxLoc['CityCount'];
										$maxLocCode=$rowMaxLoc['maxLocCod'];
										if($CityCount>=1)
										{
											$incremntVid=$CityCount+'01';
											$maxLocCode="$stateCode/$districCode/0$incremntVid";											
											
											$insLocation1="INSERT INTO tbllocation(LocationName,LocationCode,Description,RecStatus,LastUpdateOn,UpdatebyID,CityId)
	VALUES('$LocationName','$maxLocCode','$LocationDesc','A','$CurrentDate','$UserId','$DistricId')";
											$runInsLocation1=mysqli_query($con,$insLocation1);
											if($runInsLocation1)
											{
												$selectMaxRec="SELECT LocId,LocationName,LocationCode FROM tbllocation WHERE RecStatus='A' AND CityId='$DistricId' AND LocationCode='$maxLocCode'";
												$runMaxRec=mysqli_query($con,$selectMaxRec);
												$rowMaxRec=mysqli_fetch_array($runMaxRec);
												$locCod=$rowMaxRec['LocationCode'];
												$locNam=$rowMaxRec['LocationName'];						
										
                                        echo "<div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>State Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$stateName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Distric Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$districName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Location Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$locNam
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Location Code</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$locCod
                                            </div>
                                        </div><br />
                                        <div class='row' style='background-color:#CCF9EB;'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Success Message : </label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	Record Inserted Successfully !
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-3 col-sm-3 col-xs-12 col-sm-offset-2'>
                                            	<a href='Location.php' class='btn btn-primary'> &nbsp; OK &nbsp; </a>
                                            </div>
                                        </div><br />"; 
											}
											else
											{
												echo "
												<div class='row' style='background-color:#FFCACA;'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Error Message : </label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	Record Not Inserted Check Details !
                                            </div>
                                        	</div>												
												";
											}
										}
										else
										{
											$maxLocCode="$stateCode/$districCode/01";
											
											$insLocation="INSERT INTO tbllocation(LocationName,LocationCode,Description,RecStatus,LastUpdateOn,UpdatebyID,CityId)
	VALUES('$LocationName','$maxLocCode','$LocationDesc','A','$CurrentDate','$UserId','$DistricId')";
											$runInsLocation=mysqli_query($con,$insLocation);
											if($runInsLocation)
											{
												$selectMaxRec="SELECT LocId,LocationName,LocationCode FROM tbllocation WHERE RecStatus='A' AND CityId='$DistricId' AND LocationCode='$maxLocCode'";
												$runMaxRec=mysqli_query($con,$selectMaxRec);
												$rowMaxRec=mysqli_fetch_array($runMaxRec);
												$locCod=$rowMaxRec['LocationCode'];
												$locNam=$rowMaxRec['LocationName'];						
										
                                        echo "<div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>State Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$stateName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Distric Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$districName
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Location Name</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$locNam
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Location Code</label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	$locCod
                                            </div>
                                        </div><br />
                                        <div class='row' style='background-color:#CCF9EB;'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Success Message : </label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	Record Inserted Successfully !
                                            </div>
                                        </div><br />
                                        <div class='row'>
                                        	<div class='col-lg-3 col-sm-3 col-xs-12 col-sm-offset-2'>
                                            	<a href='Location.php' class='btn btn-primary'> &nbsp; OK &nbsp; </a>
                                            </div>
                                        </div><br />"; 
											}
											else
											{
												echo "
												<div class='row' style='background-color:#FFCACA;'>
                                        	<div class='col-lg-2 col-sm-2 col-xs-12'>
                                            	<label>Error Message : </label>
                                            </div>
                                            <div class='col-lg-5 col-sm-5 col-xs-12'>
                                            	Record Not Inserted Check Details !
                                            </div>
                                        	</div>												
												";
											}
										}
									}
								}
								else
								{
									
								}
							?>
							</div>
						</div>
					</div>
				</div>
			
            <footer>
                <div class="">
                    <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd</p>
                </div>
                <div class="clearfix"></div>
            </footer>
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
   
    <!-- bootstrap progress js -->
   
    <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="js/icheck/icheck.min.js"></script>

    <script src="js/custom.js"></script>
    <!-- form validation -->
     
      
       
    </body>
</html>





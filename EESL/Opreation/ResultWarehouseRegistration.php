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
<title>WareHose Registration</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Warehouse Registration</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    
     <link href="css/select/select2.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">
     <link rel="stylesheet" href="css/switchery/switchery.min.css" />
    
     
     
		<script>
            $(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Select a state",
                    allowClear: true
                });
                $(".select2_group").select2({});
                $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
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

function ValidateAlpha(evt)
    {
        var keyCode = (evt.which) ? evt.which : evt.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        
        return false;
		
            return true;
			
    }
	
	
	
</script>
<script type="text/javascript">	
function onlyAlphabets(e, t) {
             var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32); 
        }
		
		
		function validateEmail(emailField){
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(emailField.value!="")
		{
        if (reg.test(emailField.value) == false) 
        {
            alert('Invalid Email Address');
			window.setTimeout(function ()
    {
        emailField.focus();
    }, 0);
            return false;
			
        }
		}
        return true;
		

} 


	
</script>

    <script src="js/jquery.min.js"></script>
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

                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                   <!-- <i class="fa fa-envelope-o"></i>
                                    <span class="badge bg-green">6</span>-->
                                </a>
                                <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                                    <li>
                                        <a>
                                            <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where... 
                                    </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where... 
                                    </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where... 
                                    </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where... 
                                    </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="text-center">
                                            <a>
                                                <strong><a href="inbox.html">See All Alerts</strong>
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->


            <!-- page content -->
            <!--<div class="right_col" role="main">

                <!-- top tiles -->
               
                            </div>
                            
                            <div class="right_col" role="main">

                <!-- top tiles -->
                


<div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                               <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>WareHouse Registration</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                      
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                
                 
                          
						<div class="x_content">
								<?php
								if(isset($_POST['SaveWareHouse']))
								{
									$StateId=$_POST['StateList'];
									$DistricId=$_POST['DistricList'];
									$LocationId=$_POST['LocationList'];
									$ManagerName=$_POST['txtmanagername'];
									$warehousename=$_POST['txtwarehousename'];
									$ManagerEmail=$_POST['txtEmail'];
									$ManagerContact1=$_POST['txtContactNumber'];
									$ManagerContact2=$_POST['txtalterContactNumber'];
									$ManagerAddress=$_POST['txtAddress'];
									$ManagerDesc=$_POST['txtDescription'];
									date_default_timezone_set("Asia/Kolkata"); 
   									$CurrentDate=date('Y-m-d H:i:s A');	
									$CodeDate=date('dmy');	
									$Password = rand(1000,5000);
									$encryptpass=base64_encode($Password);		
									$selectDuplicate="SELECT COUNT(DispatcherId) AS counter,ContactNumber,EmailId FROM tbldispatcherregistraion WHERE ContactNumber='$ManagerContact1' OR EmailId='$ManagerEmail' AND RecStatus='A'";
									$runSelectDuplicat=mysqli_query($con,$selectDuplicate);
									$rowDuplicat=mysqli_fetch_array($runSelectDuplicat);
									if($rowDuplicat['counter']>=1)
									{										
										echo "<script>alert('Email Id OR Contact No. already Exist !')</script>";
										echo "<script>window.open('WarehouseRegistration.php','_self')</script>";
									}
									else
									{						
									$SelState="SELECT s.Stateid,s.StateName,s.StateCode,s.LastUnitWarehouse,d.CityId,d.CityName,d.CityCode,d.LastUnitWarehouse,l.LocId,l.LocationName,l.LocationCode
FROM tblstate AS s JOIN tblcity AS d ON s.Stateid=d.StateId JOIN tbllocation AS l ON d.CityId=l.CityId
WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' AND d.CityId='$DistricId' AND s.Stateid='$StateId' AND LocId='$LocationId'";
									$RunSelState=mysqli_query($con,$SelState);
									$GetSelStateRow=mysqli_fetch_array($RunSelState);
										
									$stateName=$GetSelStateRow['StateName'];
									$stateCode=$GetSelStateRow['StateCode'];
									$StateWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$districName=$GetSelStateRow['CityName'];										
									$districCode=$GetSelStateRow['CityCode'];
									$DistWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$LocationId=$GetSelStateRow['LocId'];
									$LocationCode=$GetSelStateRow['LocationCode'];
									$LocationName=$GetSelStateRow['LocationName'];	
									
									$SelStatUnit="SELECT LastUnitWarehouse FROM tblstate WHERE RecStatus='A' AND Stateid='$StateId'";
									$runSelStatUnit=mysqli_query($con,$SelStatUnit);
									$rowSelStatUnit=mysqli_fetch_array($runSelStatUnit);										
									$StatUnitCode=$rowSelStatUnit['LastUnitWarehouse'];
										
									$SelDistUnit="SELECT LastUnitWarehouse FROM tblcity WHERE RecStatus='A' AND CityId='$DistricId'";
									$runSelDistUnit=mysqli_query($con,$SelDistUnit);
									$rowSelDistUnit=mysqli_fetch_array($runSelDistUnit);
									$DistUnitCode=$rowSelDistUnit['LastUnitWarehouse'];
																			
									$selectExistWarehous="SELECT COUNT(DispatcherId) AS DispatcherCount FROM tbldispatcherregistraion WHERE RecStatus='A' AND LocationId='$LocationId'";
									$runExistWarehouse=mysqli_query($con,$selectExistWarehous);
									$rowExistWarehouse=mysqli_fetch_array($runExistWarehouse);
									if($rowExistWarehouse['DispatcherCount']>=1)
									{
										$getExistWarehouse="SELECT d.DispatcherName,d.ContactNumber,d.EmailId,d.MailingAddress,wr.WareHouseName,d.AlternateContactNo,wr.WareHouseCode FROM tbldispatcherregistraion AS d
JOIN tblwarehouseregistration AS wr ON d.DispatcherId=wr.DisPatcherId WHERE d.RecStatus='A' AND wr.RecStatus='A' AND d.LocationId='$LocationId'";
										$runGetExist=mysqli_query($con,$getExistWarehouse);
										$rowGetExixt=mysqli_fetch_array($runGetExist);
										
										$wcode=$rowGetExixt['WareHouseCode'];
										$MName=$rowGetExixt['DispatcherName'];
										$MContact1=$rowGetExixt['ContactNumber'];
										$Memail=$rowGetExixt['EmailId'];
										$MAdress=$rowGetExixt['MailingAddress'];
										$MContact2=$rowGetExixt['AlternateContactNo'];
										$whname=$rowGetExixt['WareHouseName'];
										
											
										echo "	<div class='row' style='background-color:#FED3D8'>
														<div class='col-lg-2 col-sm-2 col-xs-12'>
															<label>Error Message : </label>
														</div>
														<div class='col-lg-4 col-sm-4 col-xs-12'>
															Record already Exist !
														</div>
														</div><br>
											
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>State</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$stateName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>District Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$districName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Location Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$LocationName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Warehouse Code</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$wcode
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Warehouse Manager Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$MName
															</div>
														</div><br>
														
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Warehouse Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$whname
															</div>
														</div><br>
														
														
														
														
														
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Manager Email</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$Memail
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Contact No.</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$MContact1 
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Alter Contact No.</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$MContact2
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Mailing Address</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$MAdress
															</div>
														</div><br>
														
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<a href='WarehouseRegistration.php' class='btn btn-primary'> &nbsp;OK&nbsp; </a>
															</div>
														</div><br>													
													";
										}
										else
										{
											$StateInc=$StatUnitCode+'1';
											$DistInc=$DistUnitCode+'1';
											$WarehouseCode="$stateCode/$districCode/$CodeDate/$StateInc/$DistInc";										
											
											$queryDispatcher="INSERT INTO tbldispatcherregistraion(DispatcherName,LocationId,ContactNumber,EmailId,MailingAddress,Description,RecStatus,LastUpdateOn,UpdatebyID,AlternateContactNo)
VALUES('$ManagerName','$LocationId','$ManagerContact1','$ManagerEmail','$ManagerAddress','$ManagerDesc','A','$CurrentDate','$UserId','$ManagerContact2')";
											$runDispatcher=mysqli_query($con,$queryDispatcher);
											if($runDispatcher)
											{
												$MaxDispatch="SELECT MAX(warehouseid) AS DispatcherId FROM tblwarehouseregistration";
	      										$runMaxDispatch=mysqli_query($con,$MaxDispatch);
         										$rowMaxDispatch = mysqli_fetch_array($runMaxDispatch);
	      										$MaxDispatchId=$rowMaxDispatch['DispatcherId'];
		
												$updWarehouse="UPDATE tblwarehouseregistration SET WareHouseCode='$WarehouseCode',WareHouseLocationID='$LocationId',WareHouseName='$warehousename',RecStatus='A',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId',stateid='$StateId' WHERE warehouseid='$MaxDispatchId'";
												$runupdWarehouse=mysqli_query($con,$updWarehouse);
		
												$InsUser="INSERT INTO tbluser(UserId,PASSWORD,UserTypes,FinanceVerificationStatus,RefId,RecStatus,LastUpdateOn,UpdatebyID)
		VALUES('$ManagerEmail','$encryptpass','DS','A','$MaxDispatchId','A','$CurrentDate','$UserId')";
												$runInsUser=mysqli_query($con,$InsUser);
												
												if($runInsUser)
												{
													$updState="UPDATE tblstate SET LastUnitWarehouse='$StateInc' WHERE RecStatus='A' AND Stateid='$StateId'";
													$runupdState=mysqli_query($con,$updState);
													
													$updDist="UPDATE tblcity SET LastUnitWarehouse='$DistInc' WHERE RecStatus='A' AND CityId='$DistricId'";
													$runupdDist=mysqli_query($con,$updDist);
													
													echo "
														<div class='row' style='background-color:#009B4E;color:#FFF;'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Success Message : </label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																Record Inserted Successfully
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>State</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$stateName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Distric Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$districName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Location Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$LocationName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Warehouse Code</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$WarehouseCode
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Warehouse Manager Name</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$ManagerName
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Manager Email</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$ManagerEmail
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Contact No.</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$ManagerContact1
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Alter Contact No.</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$ManagerContact2
															</div>
														</div><br>
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<label>Maling Address</label>
															</div>
															<div class='col-lg-4 col-sm-4 col-xs-12'>
																$ManagerAddress
															</div>
														</div><br>
														
														<div class='row'>
															<div class='col-lg-2 col-sm-2 col-xs-12'>
																<a href='WarehouseRegistration.php' class='btn btn-primary'> &nbsp;OK&nbsp; </a>
															</div>
														</div><br>													
													";
													
													$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
													$q=mysqli_query($con,$selectMail);
													$records = array();
													while($row = mysqli_fetch_assoc($q)){ 
														$records[] = $row;
													}
													
													require_once('../PHPMailer_5.2.4/class.phpmailer.php');
													//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
													$mail  = new PHPMailer();

													$mail->IsSMTP(); // telling the class to use SMTP
													$mail->Host       = 'mail.iapcorp.com'; // SMTP server
													//$mail->SMTPDebug  = 2;  // enables SMTP debug information (for testing)
													// 1 = errors and messages
													// 2 = messages only

													$mail->SetFrom('projectled@iapcorp.com', 'OpreationTeam');
													$mail->AddReplyTo("projectled@iapcorp.com","OpreationTeam");


													$mail->AltBody= "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
													$mail->Subject= "This is Confirmation Message For warehouse registration";
													$mail->Body = "<h3>Dear Warehouse Manager</h3>
														</br><h4>Warehouse Registered! Details are As follows:</h4>											
														</br><p>Warehouse Code: $WarehouseCode</p>
														</br><p>Warehouse MangerName: $ManagerName</p>
														</br><p>User Name: $ManagerEmail</p>
														</br><p>Password: $Password</p>									
														</br><p>Contact Number: $ManagerContact1</p>
														<br><br><br>
														<h4>Thanks & Regards </h4>
														<p>OpreationTeam</p><br>
														";			
													$mail->AddAddress($ManagerEmail);
													foreach($records as $rec)
													{	  
														$romail=$rec['RO'];
														$adminmail=$rec['SuperAdmin'];
														$finmail=$rec['Finance'];				
													
														$mail->addCC($adminmail);
														$mail->addCC($finmail);
														$mail->addCC($romail);
														$mail->Send();
														$mail->ClearAllRecipients();
													}													
													if(!$mail->Send())
													{
														
													}
													else
													{
									
													}
												}
														
											}											
											else
											{
												echo"<div class='row' style='background-color:#FED3D8'>
													<div class='col-lg-2 col-sm-2 col-xs-12'>
														<label>Error Message : </label>
													</div>
													<div class='col-lg-4 col-sm-4 col-xs-12'>
														Record Not Inserted Check Details and Try Again !
													</div>
												</div><br>
												<div class='row'>
													div class='col-lg-2 col-sm-2 col-xs-12'>
														<a href='WarehouseRegistration.php' class='btn btn-primary'> &nbsp;OK&nbsp; </a>
													</div>
												</div><br>";	
											}												
										}	
									}
										
									}
									?>
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

                <!-- footer content -->

                
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
 
  <script src="js/select/select2.full.js"></script>

    <!-- chart js -->
    <script src="js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="js/icheck/icheck.min.js"></script>

    <script src="js/custom.js"></script>
    <!-- form validation -->
    <script src="js/validator/validator.js"></script>
    <script>
        // initialize the validator function
       
    </script>

</body>

</body>
</html>
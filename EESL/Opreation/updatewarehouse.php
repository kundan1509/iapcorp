<?php
	include("../Connection/Connection.php");
	require_once("dbcontroller.php");
	$db_handle = new DBController();
	$query ="SELECT * FROM tblstate WHERE RecStatus='A'";
	$results = $db_handle->runQuery($query);		  
	
	
	if(isset($_GET['id']))
{
	$warehouseid=$_GET['id'];
	$fetchData="SELECT wh.warehouseid,wh.warehouselocationid,wh.stateid,s.statename,c.cityname,c.cityid,wh.WareHouseName,wh.warehousecode,wh.Dispatcherid,l.locationname,wh.warehouselocationid,
ds.dispatchername,ds.Contactnumber,ds.Emailid,ds.MailingAddress,ds.AlternateContactNo,ds.MailingAddress,ds.description FROM tbldispatcherregistraion ds,tblwarehouseregistration wh,tbllocation l,tblstate s,tblcity c
WHERE wh.dispatcherid=ds.dispatcherid AND l.locid=wh.warehouselocationid AND wh.recstatus='A' AND s.stateid=wh.stateid AND l.cityid=c.cityid
and wh.warehouseid='$warehouseid'";
$q=mysqli_query($con,$fetchData);
$row1=mysqli_fetch_array($q);
$whstateid=$row1['stateid'];
$whid=$row1['warehouseid'];
$warehousename=$row1['WareHouseName'];
$warehousecode=$row1['warehousecode'];
$warehouseLocation=$row1['locationname'];
$dispatcherName=$row1['dispatchername'];
$contactnumber=$row1['Contactnumber'];
$address=$row1['MailingAddress'];
$email=$row1['Emailid'];
$desc=$row1['description'];
$whloc=$row1['warehouselocationid'];
$dispid=$row1['Dispatcherid'];
$statename=$row1['statename'];
$cityname=$row1['cityname'];
$statid=$row1['stateid'];
$acn=$row1['AlternateContactNo'];
$lid=$row1['warehouselocationid'];

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



if(isset($_POST['SaveWareHouse']))
{
$StateId=$_POST['StateList'];
$DistricId=$_POST['DistricList'];
$LocationId=$_POST['LocationList'];
$ManagerName=$_POST['txtmanagername'];
$ManagerEmail=$_POST['txtEmail'];
$ManagerContact1=$_POST['txtContactNumber'];
$ManagerContact2=$_POST['txtalterContactNumber'];
$ManagerAddress=$_POST['txtAddress'];
$ManagerDesc=$_POST['txtDescription'];
date_default_timezone_set("Asia/Kolkata"); 
$CurrentDate=date('Y-m-d H:i:s A');	
$dispatcherid=$_POST['did'];
$wid=$_POST['wid'];
$whname=$_POST['txtwarehousename'];

$updatewarehouse="update tblwarehouseregistration set stateid='$StateId',WareHouseName='$whname',warehouselocationid='$LocationId',lastupdateon='$CurrentDate',updatebyid='$RefId' where warehouseid='$wid'";

if(mysqli_query($con,$updatewarehouse))
{

	$updatedisptacher="update tbldispatcherregistraion set Dispatchername='$ManagerName',locationid='$LocationId',contactnumber='$ManagerContact1',Emailid='$ManagerEmail',mailingaddress='$ManagerAddress',description='$ManagerDesc',
	alternatecontactno='$ManagerContact2',updatebyid='$RefId',lastupdateon='$CurrentDate' where dispatcherid='$dispatcherid'";
	
	if(mysqli_query($con,$updatedisptacher))
	{
		echo "<script>alert('Warehouse updated successfully')</script>";
		echo "<script>window.open('warehouselist.php','_self')</script>";
	}
	else
	{
		echo "<script>alert('Warehouse not updated!!')</script>";
		echo "<script>window.open('warehouselist.php','_self')</script>";
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
<title>WareHose Registration</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Update warehouse</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    
     <link href="css/select/select2.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">
     <link rel="stylesheet" href="css/switchery/switchery.min.css" />
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script>
	function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+val,
	success: function(data){
		$("#state-list").html(data);
		}
		});
		$.ajax({
	type: "POST",
	url: "getwarehouselocation.php",
	data:'state_Id='+val,
	success: function(data){
		$("#LocList-list").html(data);
		}
		});
		
		
	}
	
	function getDistric(val) {
	$.ajax({
	type: "POST",
	url: "getwarehouselocation.php",
	data:'state_Id='+val,
	success: function(data){
		$("#LocList-list").html(data);
		}
		});
	}
	</script>
     
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

function onlyAlphabets(e, t) {
             var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32||k==17); 
        } 	

	
	$(function () {
		$('.textValidate').keydown(function (e) {
		if (e.ctrlKey || e.altKey) {
			e.preventDefault();
			} else {
				var key = e.keyCode;
				if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90)|| (key==9)|| (key==16)|| (key==20))) {
					alert('Only characters allowed');
				e.preventDefault();
				}
			}
		});
	});
	

$(function () {
$('.txtNumeric').keydown(function (e) {
if (e.ctrlKey || e.altKey) {
e.preventDefault();
} else {
var key = e.keyCode;
if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)|| (key==9)|| (key==16)|| (key==20))) {
	alert('Only numeric allowed');
e.preventDefault();
}
}
});
});
	
	
	
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#email").blur(function() {
var Email = $('#email').val();
var oldemail= $('#emailhid').val();
if(Email=="")
{
$("#disp").html("");
}
else
{
$.ajax({
type: "POST",
url: "get_emailWarehouseupdate.php",
data: { email: Email, oldmail: oldemail },
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
$(document).ready(function(){
$("#contactnumber").blur(function() {
var Contactno = $('#contactnumber').val();
var cno=$('#mobhid').val();
if(Contactno=="")
{
$("#display").html("");
}
else
{
$.ajax({
type: "POST",
url: "get_emailWarehouseupdate.php",
data: {Contactno: Contactno, oldmobile:cno},
success: function(html){
$("#display").html(html);
}
});
return false;
}
});
});
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
                                    <h2>WareHouse Update</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                      
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                
                                <script>
                                
                                function chechform(){
								
								
								
								var erroremail=$('#disp').find('span').html();
								var errormobile=$('#display').find('span').html();
								var check='Available';
								var Contactno = $('#contactnumber').val();
								var cno=$('#mobhid').val();
								
								var Email = $('#email').val();
								var oldemail= $('#emailhid').val();
								if((Email==oldemail)&&(Contactno==cno))
								{
									return true;
								}
								else
								{
									return false;
								}
								
								if((erroremail!="Available")||(errormobile!="Available"))
								{
									return false 
								}
								 else 
									{
										
										return true;
										
										}
										
								
							}
                                </script>
                   
                          
						<div class="x_content">

                                    <form class="form-horizontal form-label-left" autocomplete="off" method="post" action="updatewarehouse.php" onsubmit="return chechform();">
										
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select State<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select name="StateList" required id="country-list" class="form-control" onChange="getState(this.value);">
                                                 <?php echo "<option value='$statid'>$statename</option>";?>
													<option value="">Select State</option>
                                                  
													<?php
														foreach($results as $country) {
													?>
													<option value="<?php echo $country["Stateid"]; ?>"><?php echo $country["StateName"]; ?></option>
												<?php
												}
												?>
												</select>
                                            </div>
                                        </div><br />
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select District<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                              
                                                <select name="DistricList" required="required" class="form-control" id="state-list" onChange="getDistric(this.value);">
                                                 <?php echo "<option value='$cityname'>$cityname</option>";?>
                                                    	<option value="0">Select District</option>                                       
                                                    </select>
                                            </div>
                                        </div><br />
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Location<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            
                                                <select name="LocationList" class="form-control" id="LocList-list">
                                                 <?php echo "<option value='$lid'>$warehouseLocation</option>";?>
                                                    	<option value="0">Select Location</option>                                       
                                                    </select>
                                            </div>
                                        </div><br />                                
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse Manager Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" onpaste="return false" class="textValidate form-control col-md-7 col-xs-12" value="<?php echo $dispatcherName;?>"  name="txtmanagername" placeholder="Enter Name" required type="text" maxlength="100" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div><br /> 
                                        
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" onpaste="return false" class="textValidate form-control col-md-7 col-xs-12" value="<?php echo $warehousename;?>"  name="txtwarehousename" placeholder="Enter Name" required type="text" maxlength="100" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div><br /> 
                                        
                                        
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span style=" color:#F00">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="email" id="email" onpaste="return false" name="txtEmail" value="<?php echo $email;?>" onBlur="validateEmail(this);" placeholder="Enter Manager E-mail" required class="form-control col-md-7 col-xs-12" maxlength="50">
                                            
                                            </div>
                                            <input type="hidden" value="<?php echo $email;?>" id="emailhid" />
                                            
                                           <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="disp"></div><br />
                                        </div><br /> 
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Contact Number<span style=" color:#F00">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="contactnumber" onpaste="return false" name="txtContactNumber" value="<?php echo $contactnumber;?>" placeholder="Enter Manager Contact Number" data-validate-linked="email" required class="txtNumeric form-control col-md-7 col-xs-12" maxlength="10" onkeypress="return isNumberKey(event);">
                                            </div>
                                        	<div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="display"></div><br />
                                        </div><br /> 
                                        
                                         <input type="hidden" value="<?php echo $contactnumber;?>" id="mobhid" />
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Alternate Contact Number
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input type="text" id="altercontactnumber" onpaste="return false" value="<?php echo $acn;?>" name="txtalterContactNumber" placeholder="Enter Manager Alternate Contact Number" data-validate-linked="email" class="txtNumeric form-control col-md-7 col-xs-12" maxlength="10" onkeypress="return isNumberKey(event);">
                                            </div>
                                        </div><br /> 
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">Mailing Address<span style=" color:#F00">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea id="textarea" required name="txtAddress" onpaste="return false"  class="form-control col-md-7 col-xs-12" placeholder="Enter Mailing Address" style="resize:none;"><?php echo $address;?></textarea>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">Description
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea id="textarea" name="txtDescription"  placeholder="Enter Description" class="form-control col-md-7 col-xs-12" style="resize:none;"><?php echo $desc;?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-5">
                                            <input type="submit" name="SaveWareHouse" onclick="return chechform();"  class="btn btn-primary" value="Save Record" />
                                            <input type="hidden" name="did" value="<?php echo $dispid?>" />    
                                             <input type="hidden" name="wid" value="<?php echo $whid?>" />                                             
                                          
                                            </div>
                                        </div>
                                    </form>

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
       
       
    </script>

</body>

</body>
</html>
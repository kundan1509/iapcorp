<?php
include("../Connection/Connection.php");
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
if(isset($_POST['ok']))
{

$RefType=$_POST["RefrralType"];
$RefEmpId=$_POST["EmployeeId"];
$Refname=$_POST["txtName"];	
$contactnumber=$_POST["txtContactNumber"];
$email=$_POST["txtEmail"];	
$panNo=$_POST["panumber"];
$mailingAddress=$_POST["txtAddress"];

	$checkDuplicate="SELECT mobileNo,email,panno FROM tblreferral WHERE recstatus='A'";
	$chkdup=mysqli_query($con,$checkDuplicate);
	$chkduprow=mysqli_fetch_array($chkdup);
	$refmobile=$chkduprow['mobileNo'];
	$refemail=$chkduprow['email'];
	$refpan=$chkduprow['panno'];
	if(($refmobile==$contactnumber)||($refemail==$email)||($refpan==$panNo))
	{
		echo "<script>alert('Referral MobileNumber,Panno or emailid is already registered')</script>";
	}
	else
	{
	$q="insert into tblreferral(ReferralType,EmpId,NAME,MobileNo,Email,PanNo,Address,UpdateById,RecStatus)
	values('$RefType','$RefEmpId','$Refname','$contactnumber','$email','$panNo','$mailingAddress','$UserId','A')";
	  $run=mysqli_query($con,$q);
	   if($run)
	   {
			 
		echo "<script>alert('Referral Detail Inserted Successfully')</script>";
		echo "<script>window.open('refrral.php','_self')</script>";
	}
	else
	{
		echo "<script>alert('Referral Details Not inserted')</script>";
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
<title>Referral</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                 <script type="text/javascript">
    				$(function () {
        			$("#ddlPassport").change(function () {
            		if ($(this).val() == "Employee") {
                		$("#dvPassport").show();
            		} else {
                		$("#dvPassport").hide();
            			}
        			});
    			});
				
				function ValidatePAN(Obj) {    
        if (Obj.value != "") {
            ObjVal = Obj.value;
            var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
            if (ObjVal.search(panPat) == -1) {
                alert("Invalid Pan No");
                
				window.setTimeout(function ()
    {
        Obj.focus();
    }, 0);
                return false;
            }
        }
  } 
				
				</script>
<script src="js/jquery.min.js"></script>

<script type="text/javascript">
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
if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)||(key==9)|| (key==16)|| (key==20))) {
	alert('Only numeric allowed!');
e.preventDefault();
}
}
});
});


function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
	
        return false;
		
    return true;
}
</script>

</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                   <img src="../Designing/img/HeaderLogo.png" alt="..." class="img-responsive">
                     <div class="clearfix"></div>
                        <div class="profile">
                           <div class="profile_pic">
                             </div>
                        </div>
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

                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                 
                                </a>
                                <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                                   
                                   
                                   
                                    
                                   
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>

            </div>
           
                            </div>
                            
                            <div class="right_col" role="main">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="x_panel">
											<div class="x_title" style="color:#FFF; background-color:#889DC6">
											<h2>Referral Configuration</h2>
											<ul class="nav navbar-right panel_toolbox">
											<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
										</ul>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<form class="form-horizontal form-label-left" method="post" action="refrral.php">
										<div class="col-md-12 col-sm-12 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-2 col-sm-2 col-xs-12">Referral Type<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-4 col-sm-4 col-xs-12">
													<select name="RefrralType" id="ddlPassport" class="form-control input-sm" required="required">
                                                        <option value="0">Select Referral Type</option>
                                                        <option value="Employee">Employee</option>
                                                        <option value="Consultant">Consultant</option>
                                                    </select>
													<br/>
                                                    
													
                                                     
                                            	</div>
                                        	</div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group"> 
												<label class="col-md-2 col-sm-2 col-xs-12" for="name">Emp Id</label>
												<div class="col-md-4 col-sm-4 col-xs-12">
                                                <span id="dvPassport" style="display: none">
													 <input type="text" name="EmployeeId"  maxlength="20" class="form-control input-sm" placeholder="Enter Emp Code"></span>
												</div>
											</div>
										</div>
           
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group"> 
												<label class="col-md-2 col-sm-2 col-xs-12" for="name">Name<span style=" color:#F00">*</span></label>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<input class="textValidate form-control input-sm" onpaste="return false" maxlength="30"  name="txtName" placeholder="Enter Refrral Name" required type="text" onkeypress="return onlyAlphabets(event,this);">
												</div>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="item form-group">
												<label class="col-md-2 col-sm-2 col-xs-12" for="name">Mobile No<span style=" color:#F00">*</span></label>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" id="contactnumber" name="txtContactNumber" onpaste="return false" placeholder="Enter Refrral Contact Number" data-validate-linked="email" required class="txtNumeric form-control input-sm" maxlength="10" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">	
											<div class="form-group">
												<label class="col-md-2 col-sm-2 col-xs-12" for="email">Email<span style=" color:#F00">*</span></label>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<input type="email" id="email" name="txtEmail" onpaste="return false" onBlur="validateEmail(this);" placeholder="Enter Refrral E-mail" required="required" class="form-control input-sm" maxlength="50">
												</div>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">		
											<div class="form-group">
												<label class="col-md-2 col-sm-2 col-xs-12" for="email">PAN No<span style=" color:#F00">*</span></label>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<input type="text" onpaste="return false" id="panno" onblur="ValidatePAN(this);" required="required" name="panumber" class="form-control input-sm" placeholder="Enter Distributor Pan No." maxlength="10">
												</div>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label class="col-md-2 col-sm-2 col-xs-12" for="textarea">Address<span style=" color:#F00">*</span></label>
												<div class="col-md-4 col-sm-4 col-xs-12">
													<textarea id="textarea" required name="txtAddress" class="form-control input-sm" placeholder="Enter Address" style="resize:none;"></textarea>
												</div>
											</div>
										</div>	
										<div class="form-group">
											<div class="col-md-6 col-md-offset-3">
												<button id="send" name="ok" type="submit" class="btn btn-primary"> Save Records</button>
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
						<p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd</p>
                    </div>
                    <div class="clearfix"></div>
                </footer>
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
</html>
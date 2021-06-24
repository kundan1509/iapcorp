<?php
include("../Connection/Connection.php");
?>
<?php

	if(isset($_GET['Stateid']))
	{
	   $Stateid=$_GET['Stateid'];	   
	   $result= "SELECT StateName,StateCode,Stateid,buyrate,sellrate FROM  tblstate WHERE  RecStatus='A'  and Stateid='$Stateid' ";
	   $Query=mysqli_query($con,$result);		
		while($row=mysqli_fetch_array($Query))
		{
			$Stateid=$row['Stateid'];
			$SName=$row['StateName'];
			$SCode=$row['StateCode'];	
			$buyrate=number_format($row['buyrate'],2) ;
			$sellrate=number_format($row['sellrate'],2);													
		}		
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



	if(isset($_POST['UpdateState']))
	{
		$Stateid=$_POST['StatId'];
	   	$StatName=$_POST['StatName'];
	   	$StatCode=$_POST['StatCode'];
		$buyrate=$_POST['buyrate'];
	$sellrate=$_POST['sellrate'];
	   	date_default_timezone_set("Asia/Kolkata"); 
    	$CurrentDate=date('Y-m-d');	   
	   	$StatUpdQuery="UPDATE tblstate SET StateName='$StatName',StateCode='$StatCode',LastUpdateOn='$CurrentDate',UpdateById='$UserId',buyrate='$buyrate',sellrate='$sellrate' WHERE Stateid='$Stateid' AND RecStatus='A'";
		$runUpdQuery=mysqli_query($con,$StatUpdQuery);
		
		if($runUpdQuery)
		{
			echo "<script>alert('State update Successfully !')</script>";
			echo "<script>window.open('StateList.php','_self')</script>";
		}
		else
		{
			echo "<script>alert('State not update!')</script>";
			$Stateid=$_POST['StatId'];	   
	   		$result= "SELECT StateName,StateCode,Stateid,buyrate,sellrate FROM tblstate WHERE  RecStatus='A' AND Stateid='$Stateid' ";
	   		$Query=mysqli_query($con,$result);		
			while($row=mysqli_fetch_array($Query))
			{
				$Stateid=$row['Stateid'];
				$SName=$row['StateName'];
				$SCode=$row['StateCode'];
				$buyrate=$row['buyrate'];
			    $sellrate=$row['sellrate'];																
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

    <!-- Meta, title, CSS, favicons, etc. -->
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>State Details</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


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
	
	function onlyAlphabets(e, t) {
             var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32); 
        }
</script>

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
                               <div class="x_title" style="color:#FFF; background-color:#889DC6;">                           
                                <h2>&nbsp;  State  Details</h2>                      
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>                            
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">                              
                          
                               <form class="form-horizontal" method="post" action="UpdateState.php" autocomplete="off">
                             
                        	<input type="hidden" value="<?php echo $Stateid; ?>" name="StatId" />						
                        
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">State Name<span style="color:#F00"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
                    
                                    <input class="textValidate form-control col-md-7 col-xs-12" onpaste="return false" maxlength="20" name="StatName" onkeypress="return onlyAlphabets(event,this);" required="required" type="text" value="<?php echo $SName; ?>">
									
                                </div>
                            </div>                         
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">State Code<span style="color:#F00"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
              
                                <input class="textValidate form-control col-md-7 col-xs-12" onpaste="return false" data-validate-words="2" maxlength="5" name="StatCode" required="required" type="text" value="<?php echo $SCode; ?>" onkeypress="return onlyAlphabets(event,this);">
                                </div>
                            </div>                           
                            	<div class="form-group">
                                
                                                                            
                                            
                                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Buy Rate<span style="color:#F00"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
              
                                <input class="txtNumeric form-control col-md-7 col-xs-12" onpaste="return false" data-validate-words="2" maxlength="6" name="buyrate" required="required" type="text" value="<?php echo $buyrate; ?>" onkeypress="return isNumberKey(event);">
                                </div>
                            </div>                           
                            	
                                            
                                            
                                            
                                            
                                            
                                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Sell Rate<span style="color:#F00"> *</span></label>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-12">
              
                                <input class="txtNumeric form-control col-md-7 col-xs-12" onpaste="return false" data-validate-words="2" maxlength="6" name="sellrate" required="required" type="text" value="<?php echo $sellrate; ?>" onkeypress="return isNumberKey(event);">
                                </div>
                            </div>                           
                            	<br />
                                            
                                
                                
                                	<div class="col-md-5 col-md-offset-2 col-xs-12">
                              			<input type="submit" class="btn btn-primary" value="Save Change" name="UpdateState" onclick="return confirm('Are you Sure Save Changes ?');" />					
                                        <a  href="StateList.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
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
						
                     

                <!-- footer content -->

                
                <!-- /footer content -->
            </div>
            <!-- /page content -->

        
   

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
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
       <script>
        // initialize the validator function
       
    </body>
</html>




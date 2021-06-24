<?php
include("../Connection/Connection.php");

$errRate="";
$errsize="";

$result = "SELECT Rate,PacketSize,Description FROM tblmasterissue WHERE RecStatus='A' ORDER BY id";
	  $query1=mysqli_query($con,$result);
     $row = mysqli_fetch_array($query1);	  
	  
      $Rate=$row['Rate'];
	  $Packet=$row['PacketSize'];
	   $Desc=$row['Description'];
	   

session_start();
$inactive =60;
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
	
	$Rate=$_POST["txtRate"];
	$packetSize=$_POST["txtQuantity"];
	$Description=$_POST["txt_Description"];
	$date=date('Y-m-d H:i:s');
	if(!preg_match("/^[0-9.]*$/",$Rate)) {
			
				$errRate = "Only Number are allowed here";
}
	elseif(!preg_match("/^[0-9.]*$/",$packetSize)) {
			
				$errsize = "Only Number are allowed here";
}
else
{
	
	$query="update tblmasterissue set Rate=$Rate,PacketSize='$packetSize',Description='$Description',Lastupdateon='$date',Updatebyid='$UserId'";
	if(mysqli_query($con,$query))
	{
		echo "<script>alert('Data Update successfully')</script>";
		echo "<script>window.open('masterIssue.php','_self')</script>";
	}
	else
	{
		echo "<script>alert('Data Not Update')</script>";
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Issue</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>
    
    
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
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                   
                    <!-- /menu footer buttons -->
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

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title"style="color:#FFF; background-color:#889DC6">
                                    <h2>Create Issue</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                       
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                
                 
                          
					<div class="x_content">

                                    <form class="form-horizontal form-label-left" method="post" action="masterIssue.php">

                                       

                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Packet Rate<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" onpaste="return false" value="<?php echo $Rate;?>" class="txtNumeric form-control col-md-7 col-xs-12" maxlength="10"  name="txtRate" placeholder="Enter Rate" required="*Required Field" onkeypress="return isNumberKey(event)">
                                                <span style="color:#F00;"><?php echo $errRate; ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Packet Size<span style=" color:#F00">*</span>                                             </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" onpaste="return false" class="txtNumeric form-control col-md-7 col-xs-12" maxlength="10" data-validate-length-range="6" data-validate-words="2" name="txtQuantity" value="<?php echo $Packet;?>" required="*Required Field" placeholder="Enter Packet Size" onkeypress="return isNumberKey(event)">
                                                <span style="color:#F00;"><?php echo $errsize; ?></span>
                                            </div>
                                        </div>
                                        
                                                                                                                 
                                        
                                               <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">Description
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea id="textarea"   name="txt_Description" placeholder="Enter  Description" class="form-control col-md-7 col-xs-12" style="resize:none;"><?php echo $Desc?></textarea>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                       
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-5">
                                               
                                                <button id="send" name="ok" type="submit" class="btn btn-primary">Update</button>
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
                </div>








                    </div>

                </div>

                <!-- footer content -->

                <footer>
                    <div class="">
                        <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd
                           
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

    <script src="js/custom.js"></script>
    <!-- form validation -->
    <script src="js/validator/validator.js"></script>
    <script>
        // initialize the validator function
       
    </script>

</body>
</html>
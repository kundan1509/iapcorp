<?php
include("Connection/Connection.php");
$whname="";
session_start();
$UserId=$_SESSION['UserID'];
	$Name=$_SESSION['Name'];
	$UserType=$_SESSION['Type'];
	$RefId=$_SESSION['Ref'];
	$FVerigy=$_SESSION['FV'];
	if(!($_SESSION['Name']&&$_SESSION['Type']=='RO' || $_SESSION['Type']=='SA'))
	{
		header("location:../Logout.php");
	}



if(isset($_GET['test']))
{
	
	$warehouseCode=$_GET['test'];
	$querywhName="select Dispatcherid,warehousecode,warehousename from tblwarehouseregistration where warehouseid='$warehouseCode'";
	$wh=mysqli_query($con,$querywhName);
	$row=mysqli_fetch_array($wh);
	$whname=$row['warehousename'];
	$whcode=$row['warehousecode'];
	$dispatcherid=$row['Dispatcherid'];

}
else
{
	$whname="";
	$v="";
}

if(isset($_POST['ok']))
{
	$consignmentno=$_POST['txtCode'];
	$consignmentDate=$_POST['txtDate'];
	$selected_val=$_POST['txtwarehouse'];
	$warehouseid=$_POST['code'];
	$date=date('Y-m-d H:i:s');
	$remark=$_POST['remark'];
	$dispid=$_POST['dispid'];
	
	$query="insert into tblconsignment(consignemtno,consignmentdate,warehouseid,AllotmentStatus,RecStatus,LastUpdateOn,UpdatebyId,Remark)values('$consignmentno','$consignmentDate','$warehouseid','P','A','$date','$Name','$remark')";
	if(mysqli_query($con,$query))
	{
				
		$dispatcherMail="SELECT tbldispatcherregistraion.dispatchername,tbldispatcherregistraion.Emailid FROM tblwarehouseregistration INNER JOIN tbldispatcherregistraion
ON tbldispatcherregistraion.Dispatcherid=tblwarehouseregistration.Dispatcherid where tblwarehouseregistration.Dispatcherid='$dispid'";

$dispacherEmailQuery=mysqli_query($con,$dispatcherMail);
	$row=mysqli_fetch_array($dispacherEmailQuery);
	$email=$row['Emailid'];
	echo $email;
	
	 $selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
	 	       $q=mysqli_query($con,$selectMail);
      		   $row2 = mysqli_fetch_array($q);	  
	  		   $romail=$row2['RO'];
			   $adminmail=$row2['SuperAdmin'];
			   $finmail=$row2['Finance'];
		
		 require_once('../PHPMailer_5.2.4/class.phpmailer.php');
								//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

								$mail             = new PHPMailer();

								$mail->IsSMTP(); // telling the class to use SMTP
								$mail->Host       = 'smtp.net4india.com'; // SMTP server
								$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

								$mail->SetFrom('amit@foreteksolution.in', 'Amit Kumar');
								$mail->AddReplyTo("amit@foreteksolution.in","Amit Kumar");


								$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
								$mail->Subject    = "This is confirmation Message";
								$mail->Body = "<h3>Dear User</h3>
												</br><h4>Please Check the Details of Consignment</h4>
												</br><p>Consignment No:$consignmentno </p>
												</br><p>Warehouse Name:$selected_val</p>
												</br><p></p>
												<br><br><br>
												<h4>Thanks & Regards </h4>
												<p>Roll Out Team</p><br>
												";								
								$mail->AddAddress($email, "RollOut Team");
								$mail->addCC($romail);
								$mail->addCC($adminmail);
								if(!$mail->Send()) {
  									echo "Mailer Error: " . $mail->ErrorInfo;
								} 
								
								echo "<script>alert('Consignment Successfully Created')</script>";
		echo "<script>window.open('Consignment.php','_self')</script>";
		
	}
	else
	{
		echo "<script>alert('Consignment Not Created')</script>";
	}
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consignment</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Consignment</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>
    
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

function getData(value){
var vendorId=document.getElementById("wareHouse").selectedIndex.text=value;
document.location="Consignment.php?test=" + vendorId;

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
                                    <!--<img src="images/img.jpg" alt="">--><?php echo $Name; ?>
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
          
               
                            </div>
                            
                            <div class="right_col" role="main">

                <!-- top tiles -->
                


<div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title"style="color:#FFF; background-color:#889DC6">
                                    <h2>Consignment Entry</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        
                                       
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                           
                          
						<div class="x_content">

                                    <form class="form-horizontal form-label-left" method="post" action="Consignment.php">

                                       
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Warehouse Name:
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                
                                                <select id="wareHouse" name="ddlWarehouseName" onchange="getData(value)" class="form-control col-md-7 col-xs-12">
                                  <option value="0">---Select Warehouse Name----</option>              
                                                 <?php
	
		

		$msql = mysqli_query($con,"SELECT WarehouseId,WarehouseName FROM tblwarehouseregistration where recstatus='A'");

	
 while($m_row = mysqli_fetch_array($msql)) 
 {  
	echo "<option value='".$m_row['WarehouseId']."'>".$m_row['WarehouseName']."</option>";
 }
    ?>    
        </select>
                                                
                                            </div>
                                        </div>
                                       
                                       <div id="id1" style="border:2px solid;">
                                       <br />
                                       <br />
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse Name</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12" read value="<?php echo $whname;?>"  name="txtwarehouse" required type="text" readonly="readonly">
                                            </div>
                                        </div>
                                       
										<input type="hidden" name="code" value="<?php echo $warehouseCode; ?>" />
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Consignment Number</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12" readonly="readonly"  name="txtCode" placeholder="Enter Code" required type="text" value="<?php echo $v;?>">
                                                <input type="hidden" name="dispid" value="<?php echo $dispatcherid?>" />
                                            </div>
                                        </div>
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Consignment Date
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="txtDate" placeholder="Enter Date" required type="date">
                                            </div>
                                        </div>
                                       
                                        
                                       
                                        
                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Remarks
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <textarea id="textarea" required name="remark" placeholder="Enter Remark" class="form-control col-md-7 col-xs-12" style="resize:none;"></textarea>
                                            </div>
                                        </div>
                                       
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-5">
                                               
                                                <button id="send" name="ok" type="submit" class="btn btn-info">Submit</button>
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
                        <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd<a>
                           
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
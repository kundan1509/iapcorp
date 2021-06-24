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
		if(!($_SESSION['Name']&& $_SESSION['Type']=='DS'))
		{
			header("location:../Logout.php");
		}
		else
		{
			if(isset($_GET['CosigmentId']))
			{
				$CosignmentId=$_GET['CosigmentId'];
				$consign="SELECT 
				c.CosigmentId,c.Consignmentdate,c.ConsignemtNo,w.WareHouseName,w.WarehouseId
				FROM tblconsignment c INNER JOIN tblwarehouseregistration w ON c.WarehouseId = w.WarehouseId WHERE c.AllotmentStatus='P' AND c.CosigmentId='$CosignmentId'";
	 
				$RunQuery=mysqli_query($con,$consign);
				while($row=mysqli_fetch_array($RunQuery))
				{
					$warehouseId=$row['WarehouseId'];
					$ConsignmentId=$row['CosigmentId'];		
					$Consignmentno=$row['ConsignemtNo'];
					$WareHouseName=$row['WareHouseName'];
					$cosignActDate =$row['Consignmentdate'];
					$consigndate = date("d-m-Y", strtotime($cosignActDate));
				}		
			}	

			if (isset($_POST['upd']))
			{
				$ConsignId=$_POST['CosigmentId'];	
				$Quantity=$_POST['Quant'];
				$IndentQuantity=$_POST['IndentQ'];
				$wareId=$_POST['Wareid'];
				$ImageName = $_FILES['Image']['name'];
				$fileElementName = 'Image';
				$path = '../Proof_img/'; 
				$location = $path .$_FILES['Image']['name']; 
				move_uploaded_file($_FILES['Image']['tmp_name'], $location);
				if($IndentQuantity>=$Quantity)
				{
					$update="UPDATE tblconsignment SET  Quantity='$Quantity', IndentQuantity ='$IndentQuantity', ConsignmentProof='$location', 
					AllotmentStatus='D',UpdatebyID='$RefId' WHERE Recstatus='A' AND CosigmentId='$ConsignId'";	 
					if(mysqli_query($con,$update))
					{
						$insert="INSERT into tblwarehouseinventory(Quantity,TXNTypein_out,RecStatus,WareHouseId,UpdatebyID)
						values('$Quantity','IN','A','$wareId','$RefId')";
						if(mysqli_query($con,$insert))
						{
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
							$mail->Host       = 'mail.iapcorp.com'; // SMTP server
							$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

							$mail->SetFrom('rahul@foreteksolution.in', 'LED Corporation');
							$mail->AddReplyTo(" projectled@iapcorp.com");


							$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
							$mail->Subject    = "Received Consignmnet";
							$mail->Body = "<h3>Dear Team</h3>
												</br><h4>I have Received Consignment Details!</h4>
												</br><p></p>
												</br><p></p>
												</br><p></p>											
												</br><p></p>
												<br><br><br>
												<h4>Thanks & Regards </h4>
												<p>Warehouse Team</p><br>
												";								
							$mail->AddAddress($finmail);
							$mail->addCC($adminmail);
							$mail->addCC($romail);
							if(!$mail->Send()) 
							{
								echo "<script>alert('Mail Send!')</script>";
								echo "<script>window.open('Consignment.php','_self')</script>";
							}
							else 
							{
									
								echo "<script>alert('Mail Send!')</script>";
								echo "<script>window.open('Consignment.php','_self')</script>";
							}	
						}
						else
						{
							$ConsignId=$_POST['CosigmentId'];
							$consign="SELECT 
							c.CosigmentId,c.Consignmentdate,c.ConsignemtNo,w.WareHouseName,w.WarehouseId
							FROM tblconsignment c INNER JOIN tblwarehouseregistration w ON c.WarehouseId = w.WarehouseId WHERE c.AllotmentStatus='P' AND c.CosigmentId='$ConsignId'";
	 
							$RunQuery=mysqli_query($con,$consign);
							while($row=mysqli_fetch_array($RunQuery))
							{
								$warehouseId=$row['WarehouseId'];
								$ConsignmentId=$row['CosigmentId'];		
								$Consignmentno=$row['ConsignemtNo'];
								$WareHouseName=$row['WareHouseName'];
								$cosignActDate =$row['Consignmentdate'];
								$consigndate = date("d-m-Y", strtotime($cosignActDate));
							}	
							echo "<script>alert('Not Inserted WareHouse invernory!')</script>";
						}
					}
					else
					{
						$ConsignId=$_POST['CosigmentId'];
						$consign="SELECT 
						c.CosigmentId,c.Consignmentdate,c.ConsignemtNo,w.WareHouseName,w.WarehouseId
						FROM tblconsignment c INNER JOIN tblwarehouseregistration w ON c.WarehouseId = w.WarehouseId WHERE c.AllotmentStatus='P' AND c.CosigmentId='$ConsignId'";
		 
						$RunQuery=mysqli_query($con,$consign);
						while($row=mysqli_fetch_array($RunQuery))
						{
							$warehouseId=$row['WarehouseId'];
							$ConsignmentId=$row['CosigmentId'];		
							$Consignmentno=$row['ConsignemtNo'];
							$WareHouseName=$row['WareHouseName'];
							$cosignActDate =$row['Consignmentdate'];
							$consigndate = date("d-m-Y", strtotime($cosignActDate));
						}
						echo "<script>alert('Not Update consognment!')</script>";
					}
				}
				else
				{
					$ConsignId=$_POST['CosigmentId'];
					$consign="SELECT 
					c.CosigmentId,c.Consignmentdate,c.ConsignemtNo,w.WareHouseName,w.WarehouseId
					FROM tblconsignment c INNER JOIN tblwarehouseregistration w ON c.WarehouseId = w.WarehouseId WHERE c.AllotmentStatus='P' AND c.CosigmentId='$ConsignId'";
		 
					$RunQuery=mysqli_query($con,$consign);
					while($row=mysqli_fetch_array($RunQuery))
					{
						$warehouseId=$row['WarehouseId'];
						$ConsignmentId=$row['CosigmentId'];		
						$Consignmentno=$row['ConsignemtNo'];
						$WareHouseName=$row['WareHouseName'];
						$cosignActDate =$row['Consignmentdate'];
						$consigndate = date("d-m-Y", strtotime($cosignActDate));
					}
					echo "<script>alert('Quantity is Greater then Indent Quantity !')</script>";
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
<title> </title>
 <link href="../Designing/css/bootstrap.min.css" rel="stylesheet">

    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Finance Management/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="../Designing/css/floatexamples.css" rel="stylesheet" type="text/css" />

    <script src="../Designing/js/jquery.min.js"></script>

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <link rel="stylesheet" href="../Designing/LightBox/css/lightbox.css" type="text/css" media="screen" />
	<script src="../Designing/LightBox/js/scriptaculous.js?load=effects" type="text/javascript"></script>
	<script src="../Designing/LightBox/js/lightbox.js" type="text/javascript"></script>
    
<script type="text/javascript">
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
                    <!--<div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"></a>
                    </div>-->
                    <img src="../Designing/img/HeaderLogo.png" alt="..." class="img-responsive">
                    <div class="clearfix"></div>
					<br />
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">                            
                            <ul class="nav side-menu">
                             <li><a href="ConsignmentDSB.php"><i class="fa fa-home"></i> Home </a></li>
							  <li><a><i class="fa fa-truck"></i> Consignment<span class="fa fa-chevron-down"></span></a>
							  <ul  class="nav child_menu" style="display: none">
							   <li><a href="CreateConsignment.php">Consignment</a></li>
							   <li><a href="Consignment.php">Consignment Details</a></li>
							   </ul>
                              </li>
                               <li><a href="IssueSlip.php"><i class="glyphicon glyphicon-hdd" ></i>&nbsp;&nbsp;&nbsp;Issue slip </a></li>
                               <li><a href="Damage.php"><i class="fa fa-chain-broken" ></i>Damage Stock</a></li>
                               <li><a href="Replacement.php"><i class="fa fa-chain-broken" ></i>Replacement Stock </a></li>               
                               <li><a href="MISReport.php"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;MIS Report</a></li>                      
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
	<div class="">                    
		<div class="clearfix"></div>
		<div class="row">
		<div class="x_panel">
			<div class="col-md-12 col-sm-12 col-xs-12">
            	<div class="x_panel">
                	  <div class="x_title" style="color:#FFF; background-color:#889DC6">
                      <h2>Consignment detail</h2>                                   
                    	<ul class="nav navbar-right panel_toolbox">
                        	<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>   
                   		</ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
							<form class="form-horizontal"  method="post" action="ViewConsignment.php" enctype="multipart/form-data">
                        	<input type="hidden" value="<?php echo $ConsignmentId;?>" name="CosigmentId" />
                            <input type="hidden" value="<?php echo $warehouseId ;?>" name="Wareid" />
                             <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Consignment No <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<?php echo $Consignmentno; ?>
                                </div>
                            </div>
							<div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Consignment Date <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<?php echo $consigndate; ?>
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">WareHouse Name <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<?php echo $WareHouseName; ?>
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Indent Quantity <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input class="form-control col-md-7 col-xs-12"  name="IndentQ" type="text" onkeypress="return isNumberKey(event)" required="required">
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Quantity<span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input class="form-control col-md-7 col-xs-12" name="Quant" type="text" onkeypress="return isNumberKey(event)" required="required">
                                	
                                </div>
                            </div>
                           
                            
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Consignment Proof <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input class="form-control col-md-7 col-xs-12" name="Image" type="file">
                                </div>
                            </div>
                           
                          <br/>
                            
                            	<div class="form-group">
                                	<div class="col-md-6 col-md-offset-3 col-xs-12">  
                                    <button type='Submit' name="upd" class='btn btn-info'>Received</button>                            
                                     
                                      <a href="Consignment.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
                                	</div>
                            </div>
                        </form>
					</div>
              	</div>
            </div>
			</div>
         </div>
       </div>
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
    <script src="../Designing/js/bootstrap.min.js"></script>

    <!-- chart js -->
    <script src="../Designing/js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="../Designing/js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="../Designing/js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="../Designing/js/icheck/icheck.min.js"></script>

    <script src="../Designing/js/custom.js"></script>
    <!-- form validation -->
    <script src="../Designing/js/validator/validator.js"></script>
    <script>
        // initialize the validator function
        validator.message['date'] = 'not a real date';

        // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
        $('form')
            .on('blur', 'input[required], input.optional, select.required', validator.checkField)
            .on('change', 'select.required', validator.checkField)
            .on('keypress', 'input[required][pattern]', validator.keypress);

        $('.multi.required')
            .on('keyup blur', 'input', function () {
                validator.checkField.apply($(this).siblings().last()[0]);
            });

        // bind the validation to the form submit event
        //$('#send').click('submit');//.prop('disabled', true);

        $('form').submit(function (e) {
            e.preventDefault();
            var submit = true;
            // evaluate the form using generic validaing
            if (!validator.checkAll($(this))) {
                submit = false;
            }

            if (submit)
                this.submit();
            return false;
        });

        /* FOR DEMO ONLY */
        $('#vfields').change(function () {
            $('form').toggleClass('mode2');
        }).prop('checked', false);

        $('#alerts').change(function () {
            validator.defaults.alerts = (this.checked) ? false : true;
            if (this.checked)
                $('form .alert').remove();
        }).prop('checked', false);
    </script>

</body>
</html>
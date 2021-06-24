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
			if(isset($_GET['IssueSlipId']))
			{
				$PayId=$_GET['IssueSlipId'];
				$issue=
				"SELECT tblissueslip.IssueSlipId,tblissueslip.vendorid,tblissueslip.LocationId,tblissueslip.IssueCode,tblvendorregistration.VendorName,tblvendorregistration.VendorCode,tblissueslip.Quantity,
				tblissueslip.VendorRepresentativeName,tblissueslip.ContactNo,
				tblissueslip.DispatchDate,tblissueslip.IssueDate,tblissueslip.Rate,tblissueslip.TotalAmount,tblissueslip.DispatchProof
				FROM tblissueslip,tblwarehouseregistration,tblvendorregistration WHERE tblissueslip.VendorId =tblvendorregistration.VendorId AND
				tblissueslip.WarehouseId =tblwarehouseregistration.warehouseId AND tblissueslip.RecStatus ='A' AND tblissueslip.IssueStatus = 'C' and tblissueslip.issueslipid='$PayId'";
				$RunQuery=mysqli_query($con,$issue);
				while($row=mysqli_fetch_array($RunQuery))
				{
					$vendorid=$row['vendorid'];
					$LocId=$row['LocationId'];
					$IssueSlipId=$row['IssueSlipId'];
					$IssueCode=$row['IssueCode'];
					$VendorName=$row['VendorName'];
					$vcode=$row['VendorCode'];
					
					$Quantity=$row['Quantity'];
					$VendorRepresentativeName=$row['VendorRepresentativeName'];
					$ContactNO=$row['ContactNo'];
					$Dispatchdate=$row['DispatchDate'];
		
					$CurrentDate = date('d-m-Y');	
					$issuedate1=$row['IssueDate'];
					$issuedate = date("d-m-Y", strtotime($issuedate1));
					$rate=$row['Rate'];
					$totalAmount=$row['TotalAmount'];
					$Dispatchproof=$row['DispatchProof'];
				}
			}
			if (isset($_POST['up']))
			{
		 
				$checkwhinvent="SELECT SUM(Quantity)as qty FROM tblwarehouseinventory WHERE txntypein_out='IN' AND warehouseid='$RefId'";
				$chekqry=mysqli_query($con,$checkwhinvent);
				$fetchinvent=mysqli_fetch_array($chekqry);
				$inqty=$fetchinvent['qty'];
		 
		 
				$checkwhinvent_out="SELECT count(Quantity)totalQuantity,SUM(IF(TXNTypein_out = 'IN',quantity,0)) - SUM(IF(TXNTypein_out = 'OUT',quantity,0))+SUM(IF(TXNTypein_out = 'R',quantity,0)) AS qty FROM tblwarehouseinventory WHERE WareHouseId='$RefId'";
				$chekqry_out=mysqli_query($con,$checkwhinvent);
				$fetchinvent_out=mysqli_fetch_array($chekqry_out);
				$finalqty=$fetchinvent_out['qty'];
		 
					
				$CDate = date('d-m-Y');
				$vid=$_POST['vid'];
				$LocationId=$_POST['LocId'];
				$DisDate=$_POST['DispDate'];
				$DisDate1 = date("d-m-Y", strtotime($DisDate));
				$Qty=$_POST['Qty'];
				$tid=$_POST['Icode'];
				$IssueSlipId=$_POST['IssueSlipId'];
				$ImageName = $_FILES['Image']['name'];
				$fileElementName = 'Image';
				$path = '../Proof_img/'; 
               /////////////////////
$file_name_to_save=explode('.',$_FILES["Image"]["name"]);
	$file_name_final=$file_name_to_save[0].'_'.date('mdYHis').'.'.$file_name_to_save[1];

$location = $path . $file_name_final; 
/////////////////////

                move_uploaded_file($_FILES['Image']['tmp_name'], $location);
				if($DisDate1>$CDate)
				{
					echo "<script>alert('Selected date could not be future date!')</script>";
					echo "<script>window.open('IssueSlip.php','_self')</script>";
				}
				else
				{
					echo $finalqty;
					if($Qty>$finalqty)
					{
				
						echo "<script>alert('Insufficiant Stock in warehouse! Please Check your stock')</script>";
						echo "<script>window.open('IssueSlip.php','_self')</script>";
					}
					else
					{
						$Ddate = date("Y-m-d H:i:s A", strtotime($DisDate));
						$Newdate = date("Y-m-d H:i:s A", strtotime($CDate));
						$update="UPDATE tblissueslip SET DispatchDate='$Ddate', DispatchProof='$location',LastUpdateOn ='$Newdate',UpdatebyID='$RefId', IssueStatus='D' WHERE Recstatus='A' AND IssueSlipId='$IssueSlipId'";
						if(mysqli_query($con,$update))
						{
		
							$insert="INSERT into tblwarehouseinventory(WareHouseId,Quantity,TXNTypein_out,RecStatus,UpdatebyID,TransactionNo) values('$RefId','$Qty','OUT','A','$RefId','$tid')";
		 
							if(mysqli_query($con,$insert))
							{
		   
								$insert1="INSERT into tblvendorinventory (VendorId,Quantity,TXNTypein_out,TransactionNo,LocationId,RecStatus,UpdatebyID)   
								values('$vid','$Qty','IN','$tid','$LocationId','A','$RefId')";
								if(mysqli_query($con,$insert1))
								{
			
									$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
									$q=mysqli_query($con,$selectMail);
									$records = array();
									while($row = mysqli_fetch_assoc($q)){ 
										$records[] = $row;
									}		  
	            
	 
									require_once('../PHPMailer_5.2.4/class.phpmailer.php');
									//include("../PHPMailer_5.2.4/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

									$mail             = new PHPMailer();

									$mail->IsSMTP(); // telling the class to use SMTP
									$mail->Host       = 'mail.iapcorp.com'; // SMTP server
									//$mail->SMTPDebug  = 2;                  // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

									$mail->SetFrom('projectled@iapcorp.com', 'IAP Corporation');
									$mail->AddReplyTo("projectled@iapcorp.com",'IAP Corporation');


									$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
									$mail->Subject    = "This Confirmation message for Item dispatched";
									$mail->Body = "<h3>Dear Finance Team</h3>
												</br><h4>Dispatched Item Details are as follows</h4>
												</br>Dipatched Quantity:$Qty<p></p>
												</br>Issueslip Code:$tid<p></p>											
												</br><p></p>
												<br><br><br>
												<h4>Thanks & Regards </h4>
												<p>warehouse Team</p><br>
												";								
									foreach($records as $rec)
									{	  
										$romail=$rec['RO'];
										$adminmail=$rec['SuperAdmin'];
										$finmail=$rec['Finance'];				
									
										$mail->AddAddress($romail);
										$mail->addCC($adminmail);									
										$mail->addCC($finmail);
										$mail->Send();
										$mail->ClearAllRecipients();
									}
									if(!$mail->Send()) {
										
										echo "<script>alert('IssueSlip updated sucessfully')</script>";
										echo "<script>window.open('IssueSlip.php','_self')</script>";
									}
									else {
									
										echo "<script>alert('IssueSlip updated sucessfully')</script>";
										echo "<script>window.open('IssueSlip.php','_self')</script>";
									}
		
		
								}
							}
						}
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
<title>Issue_Slip Details </title>
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
    
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script> 
<script type="text/javascript" src="http://cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
<script>
webshims.setOptions('forms-ext', {types: 'date'});
webshims.polyfill('forms forms-ext');
$.webshims.formcfg = {
en: {
    dFormat: '-',
    dateSigns: '-',
    patterns: {
        d: "dd-mm-yy"
    }
}
};
</script>
	 
<script type="text/javascript">
function Checkfiles()
{
var fup = document.getElementById('filename');
var fileName = fup.value;
var size=fup.files[0].size;
var min=512000;
var max=3000000;
/*if(size <= min)
{
	alert("File size should be greater than 500 kb");
	return false;
}*/
if(size >=max )
{
	alert("File size should be less than 3mb");
	return false;
}
var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
if(ext == "gif" || ext == "GIF" || ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG"  || ext=="png" || ext=="PNG")
{
	return true;
} 
else
{
alert("Upload gif,png,bmp or jpeg images only");
fup.focus();
return false;
}
}
function checkDate()
{
	
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
    		dd='0'+dd
					} 

		if(mm<10) {
    		mm='0'+mm
					} 

			today = yyyy+'-'+mm+'-'+dd;
			depdate=(document.getElementById('Pay_date1').value);
			if(depdate>today)
			{
			alert('IssueSlip date should not greater than current date');
			window.setTimeout(function ()
    		{
        	document.getElementById('Pay_date1').focus();
			document.getElementById('Pay_date1').style.borderColor='red';
    		}, 0);
			
	
			return false;
			}
				else
				{
				document.getElementById('Pay_date1').style.borderColor='gray';
				return true;
				}

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
							   <li><a href="CreateConsignment.php">Create Consignment</a></li>
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
                      <h2>Issue Slip Detail</h2>                                    
                    	<ul class="nav navbar-right panel_toolbox">
                        	<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>   
                   		</ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
									<form class="form-horizontal" enctype="multipart/form-data" method="post" action="viewissueSlip.php" onsubmit="return Checkfiles();">
                        	<input type="hidden" value="<?php echo $IssueSlipId; ?>" name="IssueSlipId" />
							<input type="hidden" value="<?php echo $LocId; ?>" name="LocId" />
                             <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Quantity</label>
                                    <input type="hidden" name="vid" value="<?php echo $vendorid;?>" />
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	       
                                      <input type="hidden" value="<?php echo $Quantity; ?>" name="Qty" />
                                      <?php echo $Quantity; ?>
                                </div>
                            </div>
							<div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Issue code</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" value="<?php echo $IssueCode; ?>" name="Icode" /><?php echo $IssueCode; ?>
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Distributor Name</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" name="Dname"  value="<?php echo $VendorName; ?>"/><?php echo $VendorName; ?>
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Distributor Code</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" name="Wname" value="<?php echo $vcode; ?>"/>
									<?php echo $vcode; ?>
                                </div>
                            </div>
                            
                            
                          
                            
                           
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Representative <span class="required"></span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" name="RName" value="<?php echo $VendorRepresentativeName; ?>"/>
                                    <?php echo $VendorRepresentativeName; ?>
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Contact No</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" name="Cno"  value="<?php echo $ContactNO; ?>"/>
                                    <?php echo $ContactNO; ?>
                                </div>
                            </div>
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Issue Date</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" name="Idate" id="date"  onblur="checkDate();" required="required" value="<?php echo $issuedate; ?>"/><?php echo $issuedate; ?>
                                </div>
                            </div>
                           
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Rate</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input type="hidden" name="RName" value="<?php echo $rate; ?>"/><?php echo $rate; ?>
                                </div>
                            </div>
              
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Total Amount </label>
                                </div>
                                
                                <div class="col-md-4 col-sm-4 col-xs-12">
          <input type="hidden" name="TAmount" value="<?php echo $totalAmount; ?>"/><?php echo $totalAmount; ?>
                                </div>
                            </div>
							 <div class="form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                           <label class="control-label" for="name">Dispatch date<span class="required" style="color:red">*</span> </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
            <input type="date" id="Pay_date1"  name="DispDate" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off">
                             </div>
                            </div>
                            <div class="form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
             <label class="control-label" for="name">Dispatch Proof<span class="required" style="color:red">*</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                     <input type="file" name="Image" id="filename"  required="required" /></p>
<p id="sig_errorst1" style="display:none; color:#FF0000;">Image formats should be JPG, JPEG, PNG or GIF.</p>
<p id="sig_errorst2" style="display:none; color:#FF0000;">Max file size should be 3MB.</p>
<p id="sig_errorst2" style="display:none; color:#FF0000;">Min file size should be 500 Kb.</p>
                                
                                
                           
                                </div>
                            </div>
                            <div class="ln_solid">
                            </div>
                            	<div class="form-group">
                           
                                	<div class="col-md-6 col-md-offset-3 col-xs-12">  
                                    <button type='Submit' name="up" class='btn btn-info' onclick="return checkDate();">Dispatch</button>                            
                                        <a href="IssueSlip.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
                                	</div>
                            </div>
                        </form>
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
    

    <!-- chart js -->
    <script src="../Designing/js/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="../Designing/js/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="../Designing/js/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="../Designing/js/icheck/icheck.min.js"></script>

    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
    <script type="text/javascript">
        $(function () {
            $('#txtDate').datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
    
    <script>
	$('#filename').bind('change', function() {
if ($('input:submit').attr('disabled',false)) {$('input:submit').attr('disabled',true);}
var ext = $('#filename').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
{ $('#sig_errorst1').slideDown("slow"); $('#sig_errorst2').slideUp("slow"); b=0;} else {
var picsize = (this.files[0].size);
if (picsize > 1000000)
{ $('#sig_errorst2').slideDown("slow"); b=0;} else { b=2; $('#sig_errorst2').slideUp("slow"); }
$('#sig_errorst1').slideUp("slow");
if (a==1 && b==2) {$('input:submit').attr('disabled',false);}
}
});
	</script>
    
    <style type="text/css">
        #txtDate
        {
            background-image:url(../Designing/img/date-picker-icon.png);
            background-repeat: no-repeat;
            padding-left:35px;
        }
    </style>
</body>
</html>

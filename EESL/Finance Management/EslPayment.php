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
		if(!($_SESSION['Name']&&$_SESSION['Type']=='FA' || $_SESSION['Type']=='SA'))
		{
			header("location:../Logout.php");
		}
		else
		{
			if(isset($_POST['SaveRecord']))
			{
				date_default_timezone_set("Asia/Kolkata");
	
				$State=$_POST['State'];
				$Amount=$_POST['Amount'];
				$PayMode=$_POST['Paymode'];
				$TxnNo=$_POST['TxnNo'];
				$PayDate=$_POST['PayDate'];
				$modeofDeposit=$_POST['ReasonText'];
				$newDate=date('d-m-Y',strtotime(str_replace('-','/', $PayDate)));		
				$CurrentDate= date('d-m-Y');
				$BankName=$_POST['BankName'];
				$ImageName = $_FILES['PayProof']['name'];
				$fileElementName = 'PayProof';
				$path = '../Proof_img/'; 
				$locationimg = $path . $_FILES['PayProof']['name']; 
				move_uploaded_file($_FILES['PayProof']['tmp_name'], $locationimg);
	
	
				if($newDate<=$CurrentDate)
				{
		
					if($PayMode!='0' && $BankName!='0' )
					{
						if($PayMode!='OTHER')
						{
				
							$systemdate=date('Y-m-d H:i:s A');	
							$InsEeslPayment="INSERT INTO tbleeslpayment (State,Ammount,ModeOfPayment,Txn_RefNo,PaymentDate,DepositBankName,RecStatus,LastUpdateOn,UpdateById,PaymentProof)
							VALUES('$State','$Amount','$PayMode','$TxnNo','$PayDate','$BankName','A','$systemdate','$UserId','$locationimg')";
							$runEeslPayment=mysqli_query($con,$InsEeslPayment);
							if($runEeslPayment)
							{
								echo "<script>alert('Payment Record Inserted Successfully Check Details!')</script>";
								echo "<script>window.open('EslPayment.php','_self')</script>";
							}
							else
							{
								echo "<script>alert('Payment Record Not Inserted !')</script>";
							}
						}
				
						else if($PayMode=='OTHER')
						{
					
							$systemdate=date('Y-m-d H:i:s A');	
							$InsEeslPayment="INSERT INTO tbleeslpayment (State,Ammount,ModeOfPayment,Txn_RefNo,PaymentDate,DepositBankName,RecStatus,LastUpdateOn,UpdateById,PaymentProof)
							VALUES('$State','$Amount','$modeofDeposit','$TxnNo','$PayDate','$BankName','A','$systemdate','$UserId','$locationimg')";
							$runEeslPayment=mysqli_query($con,$InsEeslPayment);
							if($runEeslPayment)
							{
								echo "<script>alert('Payment record inserted successfully !')</script>";
								echo "<script>window.open('EslPayment.php','_self')</script>";
							}
							else
							{
								echo "<script>alert('Payment Record Not Inserted Check Details!')</script>";
							}
						}
				
					} 
				}
				else
				{
					echo "<script>alert('It is not valid date,  Please select  valid payment date !')</script>";
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
				<title>EESL Payments</title>
				<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">

    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Designing/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="../Designing/css/floatexamples.css" rel="stylesheet" type="text/css" />
	<style>
    .errorClass { border:  1px solid red; }
    </style>
	
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

 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
 <script type="text/javascript">
				$(document).ready(function () {
					$("#btnValidate").click(function () {
						var statText = $("#textValidate").val();
						var amountText = $("#quantity").val();
						var txtRefnoText = $("#txtRefNo").val();
						var txtpayMode = $("#ddlPassport").val();
						var txtpaymentDate = $("#txtPayDate").val();
						var bankNametext = $("#txtBankName").val();	
						var PayProoftext = $("#txtPayProof").val();								
						var paymentTextOther = $("#txtPaymodOther").val();					
						if (statText == ""){
							
							alert("Please enter State !");
							window.setTimeout(function ()
							{								
								$('#textValidate').focus();
								$('#ddlPassport').css('border-color', '');
								$('#txtBankName').css('border-color', '');
							}, 0);
            				return false;
						}
						else if(amountText == "")
						{
							alert("Please enter amount !");
							window.setTimeout(function ()
							{
								$('#quantity').focus();
								$('#ddlPassport').css('border-color', '');
								$('#txtBankName').css('border-color', '');								
							}, 0);
            				return false;
						}
						else if(txtRefnoText == "")
						{
							alert("Please enter txn/dd/cheque no. !");
							window.setTimeout(function ()
							{
								$('#txtRefNo').focus();
								$('#ddlPassport').css('border-color', '');
								$('#txtBankName').css('border-color', '');
							}, 0);
            				return false;
						}						
						else if(txtpayMode==0)
						{
							alert("Please select Payment mode !");
							window.setTimeout(function ()
							{
								$('#ddlPassport').focus();
								$('#ddlPassport').css('border-color', 'red');
								$('#txtBankName').css('border-color', '');
							}, 0);
            				return false;
						}
						else if(txtpaymentDate=="")
						{
							alert("Please select date !");
							window.setTimeout(function ()
							{
								$('#txtPayDate').focus();
								$('#ddlPassport').css('border-color', '');
								$('#txtBankName').css('border-color', '');								
							}, 0);
            				return false;
						}
						else if(bankNametext==0)
						{
							alert("Please select bank name !");
							window.setTimeout(function ()
							{
								$('#txtBankName').focus();
								$('#txtBankName').css('border-color', 'red');
								$('#ddlPassport').css('border-color', '');								
							}, 0);
            				return false;
						}
						else if(PayProoftext=="")
						{
							alert("Please select payment proof !");
							window.setTimeout(function ()
							{
								$('#txtBankName').css('border-color', '');
								$('#txtPayProof').focus();
								$('#ddlPassport').css('border-color', '');								
							}, 0);
            				return false;
						}
						else
						{	
							if(txtpayMode=="OTHER"){
								if(paymentTextOther==""){
									alert("Please enter payment mode !");
									window.setTimeout(function ()
									{
										$('#txtBankName').css('border-color', '');
										$('#txtPaymodOther').focus();
										$('#ddlPassport').css('border-color', '');								
									}, 0);
            						return false;
								}
								else{									
									return true;
								}								
							}
							else
							{								
								return true;
							}							
						}
						
					});
				});				
			</script>
 
 
                 <script type="text/javascript">
    				$(function () {
        			$("#ddlPassport").change(function () {
            		if ($(this).val() == "OTHER") {
                		$("#dvPassport").show();
            		} else {
                		$("#dvPassport").hide();
            			}
        			});
    			});
				</script>
 <script type="text/javascript">
	function pancheck(fileall)
	{
		var fileName = fileall.value;
		var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
		var size =fileall.files[0].size;
		if(ext == "gif" || ext == "GIF" || ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "BMP" || ext == "PNG"|| ext == "bmp" || ext == "png")
		{
			return true;
			if(size<=512)
			{				
				return true;
			}
			else
			{			
				alert("Upload only less then 3mb file");
				fileall.focus();
				return false;
				
			}
		} 
		else
		{
	
			alert("Upload  Only For .Gif, .Jpg, .Bmp, .Png images  Format");	
			$('input[type=file]').val('');
			fileall.focus();
			return false;

		}	
	}
</script>

<script type="text/javascript">
	$(document).ready(function () {
		$('#textValidate').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
		$("#textValidate").on('keyup', function(e) {
		 		
   var val = $(this).val();
   if (val.match(/[^a-zA-Z\s]/g)) {
       $(this).val(val.replace(/[^a-zA-Z\s]/g, ''));
	   e.ctrlKey || e.altKey
	   alert(' Only character allowed !');
   }
});
	});
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$("#txtPaymodOther").on('keyup', function(e) {
		 		
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
  $("#quantity").keypress(function (e) {
	$('#quantity').bind("cut copy paste",function(e) {
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
								
 <script language="Javascript" type="text/javascript"> 
        function onlyAlphabets(e, t) {
            try {
                if (window.event) {
                    var charCode = window.event.keyCode;
                }
                else if (e) {
                    var charCode = e.which;
                }
                else { return true; }
                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
                    return true;
                else
                    return false;
            }
            catch (err) {
                alert(err.Description);
            }
        }
 
    </script>
    <script>
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
depdate=(document.getElementById('txtPayDate').value);
if(depdate>today)
{
	alert('Selected date is grater than current date');
	window.setTimeout(function ()
	{		
		$('#txtPayDate').focus();
		$('input[type=date]').val('');
									
	}, 0);
    return false;
}
}

</script>
    <!-- Script by hscripts.com -->
               
    			<!--[if lt IE 9]>
        			<script src="../assets/js/ie8-responsive-file-warning.js"></script>
        		<![endif]-->
				<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    			<!--[if lt IE 9]>
          			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        		<![endif]-->    			
			</head>
			<body class="nav-md">
    			<div class="container body">
        			<div class="main_container">
            			<div class="col-md-3 left_col">
                			<div class="left_col scroll-view">
                    			<img src="../Designing/img/HeaderLogo.png" alt="..." class="img-responsive">
                    			<div class="clearfix"></div><br />
                    			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        			<div class="menu_section">                            
                            			<ul class="nav side-menu">
                                			<li><a href="FinanceManagementDSB.php"><i class="fa fa-home"></i> Home </a></li>
                                			<li><a href="DistributorVerify.php"><i class="fa fa-check"></i>Distributor Verification </a></li>
                                            <li><a href="PaymentVerification.php"><i class="fa fa-inr"></i> Payment Verification </a></li>                                            <li><a href="EslPayment.php"><i class="fa fa-inr"></i> EESL Payment </a></li>
                                            <li><a href="MISReport.php"><i class="glyphicon glyphicon-print"></i>&nbsp;&nbsp;&nbsp;MIS Report</a></li>                        
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
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                	<?php echo $Name ?>
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
            	<form method="post" action="EslPayment.php" enctype="multipart/form-data">
                <div class="x_panel">                      	              	
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">
                                    <h2>EESL payments</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>                                        
                                    </ul>                                    
                                    <div class="clearfix"></div>
                                </div>
                              
                                <div class="row"><br />
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="x_content">                                    
                                    <div class="form-horizontal form-label-left">
                                    	<div class="row">
                                    	<div class="col-md-6 col-sm-6 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">State<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		<input type="text" id="textValidate"  name="State" required="required" class="form-control input-sm" placeholder="State" maxlength="20" onDrag="return false" onDrop="return false"/> 
                                            	</div>
                                        	</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Amount<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		<input type="text" name="Amount" required="required" class="form-control input-sm" placeholder="Amount" id="quantity" maxlength="10" onDrag="return false" onDrop="return false"/> 
                                            	</div>
                                        	</div>
                                        </div>
                                      </div>
                                    	<div class="row"><br />
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Txn/DD/Cheque No.<span style="color:red;">*</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		<input type="text" name="TxnNo" required="required" class="form-control input-sm" placeholder="Txn dd cheque" id="txtRefNo" maxlength="100"/> 
                                            	</div>
                                        	</div>
                                        </div>
                                    	<div class="col-md-6 col-sm-6 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Payment Mode<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		
                                                    <select name="Paymode" id="ddlPassport" class="form-control input-sm" required="required" >
                                                                    <option value="0">Select Mode Of Payment</option>
                                                                           <option value="CASH">CASH</option>
                                                                           <option value="CHEQUE">CHEQUE</option>
                                                                            <option value="NEFT">NEFT</option>
                                                                            <option value="RTGS">RTGS</option>
                                                                            <option value="IMPS">IMPS</option>
                                                                            <option value="OTHER">OTHER</option>
                                                                  </select>
																  <br/>
													<span id="dvPassport" style="display: none">
                                                     <input type="text" name="ReasonText" class="form-control input-sm" id="txtPaymodOther" maxlength="100"></span>
                                            	</div>
                                        	</div>
                                        </div>
                                        
                                        </div> 
                                        <div class="row"><br />
                                    	<div class="col-md-6 col-sm-6 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Payment Date<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		<input type="date" name="PayDate" required="required" class="form-control input-sm" placeholder="Payment Date" id="txtPayDate" onblur="checkDate();"/> 
                                                    
                                            	</div>
                                        	</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Bank Name<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		
                                                    <select name="BankName" class="form-control input-sm" required="required" id="txtBankName">
                                                 	<option value="0"> Select Bank Name</option>
                                                 <?php
												  $bankQuery="select bankid,bankname from tblbank where recstatus='A'";
												  $q=mysqli_query($con,$bankQuery);
                                                  while($m_row = mysqli_fetch_array($q)) 
                                                             {
											          echo "<option value='".$m_row['bankname']."'>".$m_row['bankname']."</option>";
																}
												?> 
                                                 </select>
                                            	</div>
                                        	</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12"><br />
                                    		<div class="form-group">                                        	
                                            	<label class="col-md-4 col-sm-4 col-xs-12">Upload Proof<span style="color:red; margin-top:8px;"> *</span></label>
                                            	<div class="col-md-8 col-sm-8 col-xs-12">
                                            		<input type="file" name="PayProof" required="required" class="form-control input-sm" type="file"  onchange="pancheck(this);" id="txtPayProof"/> 
                                            	</div>
                                        	</div>
                                        </div>
                                        </div>
                                        <div class="row"><br /><br /> 
                                        <div class="form-group">
                            		<div class="col-md-5 col-sm-5 col-xs-12 col-md-offset-5">
                                		<button type="submit" name="SaveRecord" class="btn btn-primary" onclick="return confirm('Are you sure to save record ?');" id="btnValidate">Save Record</button>
                                    	
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
                        </form>
            	</div>	
       		</div>
            <!-- footer content -->
            <footer>
            	<div class="row">
                	<div class="">
                        <p class="col-sm-offset-3">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd</p>
                    </div>
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
    <!-- gauge js -->
    <script type="text/javascript" src="../Designing/js/gauge/gauge.min.js"></script>
    <script type="text/javascript" src="../Designing/js/gauge/gauge_demo.js"></script>
    <!-- daterangepicker -->
    <script type="text/javascript" src="../Designing/js/moment.min2.js"></script>
    <script type="text/javascript" src="../Designing/js/datepicker/daterangepicker.js"></script>
    <!-- sparkline -->
    <script src="../Designing/js/sparkline/jquery.sparkline.min.js"></script>

    <script src="../Designing/js/custom.js"></script>
    <!-- skycons -->
    <script src="../Designing/js/skycons/skycons.js"></script>

    <!-- flot js -->
    <!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/date.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.spline.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.stack.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/curvedLines.js"></script>
    <script type="text/javascript" src="../Designing/js/flot/jquery.flot.resize.js"></script>

    <script>
        //random data
        var d1 = [
        [0, 1], [1, 9], [2, 6], [3, 10], [4, 5], [5, 17], [6, 6], [7, 10], [8, 7], [9, 11], [10, 35], [11, 9], [12, 12], [13, 5], [14, 3], [15, 4], [16, 9]
    ];

        //flot options
        var options = {
            series: {
                curvedLines: {
                    apply: true,
                    active: true,
                    monotonicFit: true
                }
            },
            colors: ["#26B99A"],
            grid: {
                borderWidth: {
                    top: 0,
                    right: 0,
                    bottom: 1,
                    left: 1
                },
                borderColor: {
                    bottom: "#7F8790",
                    left: "#7F8790"
                }
            }
        };
        var plot = $.plot($("#placeholder3xx3"), [{
            label: "Registrations",
            data: d1,
            lines: {
                fillColor: "rgba(150, 202, 89, 0.12)"
            }, //#96CA59 rgba(150, 202, 89, 0.42)
            points: {
                fillColor: "#fff"
            }
                }], options);
    </script>
    <!-- /flot -->
    <!--  -->
    <script>
        $('document').ready(function () {
            $(".sparkline_one").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
                type: 'bar',
                height: '40',
                barWidth: 9,
                colorMap: {
                    '7': '#a1a1a1'
                },
                barSpacing: 2,
                barColor: '#26B99A'
            });

            $(".sparkline_two").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
                type: 'line',
                width: '200',
                height: '40',
                lineColor: '#26B99A',
                fillColor: 'rgba(223, 223, 223, 0.57)',
                lineWidth: 2,
                spotColor: '#26B99A',
                minSpotColor: '#26B99A'
            });

            var doughnutData = [
                {
                    value: 30,
                    color: "#455C73"
                },
                {
                    value: 30,
                    color: "#9B59B6"
                },
                {
                    value: 60,
                    color: "#BDC3C7"
                },
                {
                    value: 100,
                    color: "#26B99A"
                },
                {
                    value: 120,
                    color: "#3498DB"
                }
    ];
            var myDoughnut = new Chart(document.getElementById("canvas1").getContext("2d")).Doughnut(doughnutData);


        })
    </script>
    <!-- -->
    <!-- datepicker -->
    <script type="text/javascript">
        $(document).ready(function () {

            var cb = function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
            }


            var optionSet1 = {
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2012',
                maxDate: '12/31/2015',
                dateLimit: {
                    days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Clear',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            };
            $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            $('#reportrange').daterangepicker(optionSet1, cb);
            $('#reportrange').on('show.daterangepicker', function () {
                console.log("show event fired");
            });
            $('#reportrange').on('hide.daterangepicker', function () {
                console.log("hide event fired");
            });
            $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
                console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
            });
            $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
                console.log("cancel event fired");
            });
            $('#options1').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function () {
                $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function () {
                $('#reportrange').data('daterangepicker').remove();
            });
        });
    </script>
    <!-- /datepicker -->

    
    <!-- skycons -->
    <script>
        var icons = new Skycons({
                "color": "#73879C"
            }),
            list = [
                "clear-day", "clear-night", "partly-cloudy-day",
                "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                "fog"
            ],
            i;

        for (i = list.length; i--;)
            icons.set(list[i], list[i]);

        icons.play();
    </script>
    <script>
    
        var opts = {
            lines: 12, // The number of lines to draw
            angle: 0, // The length of each line
            lineWidth: 0.4, // The line thickness
            pointer: {
                length: 0.75, // The radius of the inner circle
                strokeWidth: 0.042, // The rotation offset
                color: '#1D212A' // Fill color
            },
            limitMax: 'false', // If true, the pointer will not go past the end of the gauge
            colorStart: '#1ABC9C', // Colors
            colorStop: '#1ABC9C', // just experiment with them
            strokeColor: '#F0F3F3', // to see which ones work best for you
            generateGradient: true
        };
        var target = document.getElementById('foo2'); // your canvas element
        var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
        gauge.maxValue = 5000; // set max gauge value
        gauge.animationSpeed = 32; // set animation speed (32 is default value)
        gauge.set(3200); // set actual value
        gauge.setTextField(document.getElementById("gauge-text2"));
    </script>

</body>
</html>
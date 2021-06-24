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
		$Refid=$_SESSION['Ref'];
		$FVerigy=$_SESSION['FV'];
		if(!($_SESSION['Name']&&$_SESSION['Type']=='VN'))
		{
			header("location:../Logout.php");
		}
		else
		{ 
			if(isset($_GET['PaymentId']))
			{
				$PayId=$_GET['PaymentId'];		
				$ViewSecurityPayment="SELECT p.PaymentId,
				p.Amount,
				p.ModeofPayment,
                                p.bank_branch_code,
                                p.cheque_number,
                                p.dd_number,
                                p.other_payment_mode,
				p.TaxnRefNo,
				p.PaymentDate,
				p.PaymentType,
				p.DepositBankName,		
				p.PaymentProof,
				p.FinanceVerification,			
				DATE_FORMAT(p.LastUpdateOn,'%d-%m-%y') AS LastUpdateOn,
				p.UpdatebyID,
				v.VendorName,
				v.VendorCode
				FROM tblpayment AS p JOIN tblvendorregistration AS v ON p.VendorId=v.VendorId WHERE v.RecStatus='A' AND p.RecStatus='A' and PaymentId=$PayId";		
				$RunQuery=mysqli_query($con,$ViewSecurityPayment);		
				while($row=mysqli_fetch_array($RunQuery))
				{
					$PaymentId=$row['PaymentId'];
					$VName=$row['VendorName'];
					$VCode=$row['VendorCode'];
					$Amount=$row['Amount'];
					$PayMode=$row['ModeofPayment'];
                                        
                                        $bank_branch_code=$row['bank_branch_code'];
                                        $cheque_number=$row['cheque_number'];
                                        $dd_number=$row['dd_number'];
                                        $other_payment_mode=$row['other_payment_mode'];
                                        
					$TxnNo=$row['TaxnRefNo'];
					$PayType=$row['PaymentType'];
					$PatDateold=$row['PaymentDate'];
					$PatDate = date("d-m-Y", strtotime($PatDateold));
					$BankNmae=$row['DepositBankName'];
					$updateid=$row['VendorName'];
					$updatdate=$row['LastUpdateOn'];
					$PayProf=$row['PaymentProof'];
					$FinancVerify=$row['FinanceVerification'];
					if($FinancVerify=="A")
					{
						echo "<script>alert('Payment is verified updation is not possible !')</script>";	
						echo "<script>window.open('PaymentDisplay.php','_self')</script>";
					}
			
		
				}
		
			}

			if (isset($_POST['update_payment']))
			{ 
				date_default_timezone_set("Asia/Kolkata"); 
				$PaymentId=$_POST['PaymentId'];
				$Amount=$_POST['amount'];
				$PayMode=$_POST['Mode_payment'];
                                
                                $bank_branch_code=isset($_POST['bank_branch_code'])?$_POST['bank_branch_code']:'';
                                $cheque_number=isset($_POST['cheque_number'])?$_POST['cheque_number']:'';
                                $dd_number=isset($_POST['dd_number'])?$_POST['dd_number']:'';
                                
				$modeofDeposit=$_POST['ReasonText'];
				//$TxnNo=$_POST['txn_no'];
				$TxnNo='';

				if($_POST['Mode_payment']=='NEFT'){
					$TxnNo=$_POST['neft_number'];
				}else if($_POST['Mode_payment']=='RTGS'){
					$TxnNo=$_POST['rtgs_number'];
				} else if($_POST['Mode_payment']=='IMPS'){
					$TxnNo=$_POST['imps_number'];
				}
	
				$PatDate=$_POST['pay_date'];
				$selectedPatDate=$_POST['pay_date1'];
				$newDate=date('d-m-Y',strtotime(str_replace('-','/', $PatDate)));	
				$CurrentDate = date('d-m-Y');
				$BankNmae=$_POST['bank'];
				$ImageName = $_POST['oldimg'];
				$FinancVerification=$_POST['FV'];
				$fileElementName =$_FILES['Image']['name']; 
				$path ='../Proof_img/';
				/////////////////////
$file_name_to_save=explode('.',$_FILES["Image"]["name"]);
	$file_name_final=$file_name_to_save[0].'_'.date('mdYHis').'.'.$file_name_to_save[1];

$location = $path . $file_name_final; 
/////////////////////	
move_uploaded_file($_FILES['Image']['tmp_name'], $location);
				if($FinancVerification=="A")
				{
					echo "<script>alert('Payment is verified updation is not possible !')</script>";	
					echo "<script>window.open('PaymentDisplay.php','_self')</script>";
				}
				else
				{ 
					if($newDate<=$CurrentDate )
					{
					//if($PayMode!="OTHER")
					//{
						if($selectedPatDate!="")
						{
							if($fileElementName!="")
							{
								$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$PayMode',other_payment_mode='$modeofDeposit',bank_branch_code='$bank_branch_code',cheque_number='$cheque_number',dd_number='$dd_number',TaxnRefNo='$TxnNo',PaymentDate='$selectedPatDate',DepositBankName='$BankNmae',PaymentProof='$location' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
								if(mysqli_query ($con,$up))
								{
									echo "<script>alert('Payment updated successfully !')</script>";	
									echo "<script>window.open('PaymentDisplay.php','_self')</script>";
								}				
								else
								{
									echo "<script>alert('Payment  not updated !')</script>";             
								}
							}
							else
							{
								$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$PayMode',other_payment_mode='$modeofDeposit',bank_branch_code='$bank_branch_code',cheque_number='$cheque_number',dd_number='$dd_number',TaxnRefNo='$TxnNo',PaymentDate='$selectedPatDate',DepositBankName='$BankNmae',PaymentProof='$ImageName' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
								if(mysqli_query ($con,$up))
								{
									echo "<script>alert('Payment updated successfully !')</script>";	
									echo "<script>window.open('PaymentDisplay.php','_self')</script>";
								}				
								else
								{
									echo "<script>alert('Payment  not updated !')</script>";             
								}
							}
						}
						else
						{
							if($fileElementName!="")
							{
								$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$PayMode',other_payment_mode='$modeofDeposit',bank_branch_code='$bank_branch_code',cheque_number='$cheque_number',dd_number='$dd_number',TaxnRefNo='$TxnNo',PaymentDate='$PatDate',DepositBankName='$BankNmae',PaymentProof='$location' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
								if(mysqli_query ($con,$up))
								{
									echo "<script>alert('Payment updated successsfully !')</script>";	
									echo "<script>window.open('PaymentDisplay.php','_self')</script>";
								}				
								else
								{
									echo "<script>alert('Payment  not updated !')</script>";             
								}
							}
							else
								{
									$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$PayMode',other_payment_mode='$modeofDeposit',bank_branch_code='$bank_branch_code',cheque_number='$cheque_number',dd_number='$dd_number',TaxnRefNo='$TxnNo',PaymentDate='$PatDate',DepositBankName='$BankNmae',PaymentProof='$ImageName' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
									if(mysqli_query ($con,$up))
									{
										echo "<script>alert('Payment updated successfully !')</script>";	
										echo "<script>window.open('PaymentDisplay.php','_self')</script>";
									}				
									else
									{
										echo "<script>alert('Payment  not updated !')</script>";             
									}
								}
							}
						//}
						/*else
						{
							if($selectedPatDate!="")
							{
								if($fileElementName!="")
								{
									$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$modeofDeposit',TaxnRefNo='$TxnNo',PaymentDate='$selectedPatDate',DepositBankName='$BankNmae',PaymentProof='$location' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
									if(mysqli_query ($con,$up))
									{
										echo "<script>alert('Payment updated successfully !')</script>";	
										echo "<script>window.open('PaymentDisplay.php','_self')</script>";
									}				
									else
									{
										echo "<script>alert('Payment  not updated !')</script>";             
									}
								}
								else
								{
									$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$modeofDeposit',TaxnRefNo='$TxnNo',PaymentDate='$selectedPatDate',DepositBankName='$BankNmae',PaymentProof='$ImageName' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
									if(mysqli_query ($con,$up))
									{
										echo "<script>alert('Payment updated successfully !')</script>";	
										echo "<script>window.open('PaymentDisplay.php','_self')</script>";
									}				
									else
									{
										echo "<script>alert('Payment  not updated !')</script>";             
									}
								}
							}
							else
							{
								if($fileElementName!="")
								{
									$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$modeofDeposit',TaxnRefNo='$TxnNo',PaymentDate='$PatDate',DepositBankName='$BankNmae',PaymentProof='$location' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
									if(mysqli_query ($con,$up))
									{
										echo "<script>alert('Payment updated successFully !')</script>";	
										echo "<script>window.open('PaymentDisplay.php','_self')</script>";
									}				
									else
									{
										echo "<script>alert('Payment  not updated !')</script>";             
									}
								}
								else
								{
									$up = "UPDATE tblpayment SET Amount='$Amount',ModeofPayment='$modeofDeposit',TaxnRefNo='$TxnNo',PaymentDate='$PatDate',DepositBankName='$BankNmae',PaymentProof='$ImageName' WHERE PaymentId='$PaymentId' AND RecStatus='A'";
									if(mysqli_query ($con,$up))
									{
										echo "<script>alert('Payment updated successFully !')</script>";	
										echo "<script>window.open('PaymentDisplay.php','_self')</script>";
									}				
									else
									{
										echo "<script>alert('Payment  not updated !')</script>";             
									}
								}
							}
						}*/
					}
					else
					{
						echo "<script>alert('It is Not Valid Date,  Please Select The  Valid Payment Date !')</script>";
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
<title>Update Payments</title>
<link href="../Designing/css/bootstrap.min.css" rel="stylesheet">
    <link href="../Designing/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="../Designing/css/animate.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <link href="../Designing/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Finance Management/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="../Designing/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="../Designing/css/floatexamples.css" rel="stylesheet" type="text/css" />
    <script src="../Designing/js/jquery.min.js"></script> 
	     <!-- Light Box Script -->
	 <link rel="stylesheet" href="../dist/css/lightbox.min.css" type="text/css"> 
	
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                 <script type="text/javascript">
    				/*$(function () {
        			$("#ddlPassport").change(function () {
                                    if ($(this).val() == "OTHER") {
                                           // $("#span_cheque_amount").hide();
                                            $("#span_cheque_number").hide();
                                          //  $("#span_cash_amount").hide();
                                            $("#span_cash_bank_branch_code").hide();
                                           // $("#dd_dd_amount").hide();
                                            $("#dd_dd_number").hide();
                                            $("#dvPassport").show();
                                    }else if($(this).val() == "CHEQUE") {
                                            $("#dvPassport").hide();
                                          //  $("#span_cash_amount").hide();
                                            $("#span_cash_bank_branch_code").hide();
                                           // $("#dd_dd_amount").hide();
                                            $("#dd_dd_number").hide();
                                           // $("#span_cheque_amount").show();
                                            $("#span_cheque_number").show();
                                    }else if($(this).val() == "CASH") {
                                            $("#dvPassport").hide();
                                            //$("#span_cheque_amount").hide();
                                            $("#span_cheque_number").hide();
                                           // $("#dd_dd_amount").hide();
                                            $("#dd_dd_number").hide();
                                           // $("#span_cash_amount").show();
                                            $("#span_cash_bank_branch_code").show();
                                     }else if($(this).val() == "Demand Draft") {
                                            $("#dvPassport").hide();
                                         //   $("#span_cheque_amount").hide();
                                            $("#span_cheque_number").hide();
                                            
                                           // $("#span_cash_amount").hide();
                                            $("#span_cash_bank_branch_code").hide();
                                           // $("#dd_dd_amount").show();
                                            $("#dd_dd_number").show();
                                    } else {
                                        //span_cash_amount
                                             //span_cash_bank_branch_code
                                            $("#dvPassport").hide();
                                          //  $("#span_cheque_amount").hide();
                                            $("#span_cheque_number").hide();
                                          //  $("#span_cash_amount").hide();
                                            $("#span_cash_bank_branch_code").hide();
                                            
                                          //  $("#dd_dd_amount").hide();
                                            $("#dd_dd_number").hide();
                                            
                                            
            			    }
        			});
    			});*/
$(function () {
        			$("#ddlPassport").change(function () {
                                    if ($(this).val() == "OTHER") {

                                            $("#span_cheque_number").hide();

                                            $("#span_cash_bank_branch_code").hide();

                                            $("#dd_dd_number").hide();

					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();

                                            $("#dvPassport").show();
                                    }else if($(this).val() == "CHEQUE") {
                                            $("#dvPassport").hide();

                                            $("#span_cash_bank_branch_code").hide();

                                            $("#dd_dd_number").hide();
					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();

                                            $("#span_cheque_number").show();
                                    }else if($(this).val() == "CASH") {
                                            $("#dvPassport").hide();

                                            $("#span_cheque_number").hide();

                                            $("#dd_dd_number").hide();
					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();

                                            $("#span_cash_bank_branch_code").show();
                                     }else if($(this).val() == "Demand Draft") {
                                            $("#dvPassport").hide();

                                            $("#span_cheque_number").hide();
					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();
                                            

                                            $("#span_cash_bank_branch_code").hide();

                                            $("#dd_dd_number").show();
				    }else if($(this).val() == "NEFT") {
					    $("#span_cheque_number").hide();

                                            $("#span_cash_bank_branch_code").hide();

                                            $("#dd_dd_number").hide();
					    $("#dvPassport").hide();
					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();
					    $("#neft_neft_number").show();
				    }else if($(this).val() == "RTGS") {
					    $("#span_cheque_number").hide();

                                            $("#span_cash_bank_branch_code").hide();

                                            $("#dd_dd_number").hide();
				            $("#dvPassport").hide();
					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();					    
					    $("#rtgs_rtgs_number").show();
				    }else if($(this).val() == "IMPS") {
					    $("#span_cheque_number").hide();
					    $("#dvPassport").hide();
                                            $("#span_cash_bank_branch_code").hide();

                                            $("#dd_dd_number").hide();

					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();
					    $("#imps_imps_number").show();
                                    } else {


                                            $("#dvPassport").hide();

                                            $("#span_cheque_number").hide();

                                            $("#span_cash_bank_branch_code").hide();
					    $("#neft_neft_number").hide();
					    $("#rtgs_rtgs_number").hide();
					    $("#imps_imps_number").hide();
                                            

                                            $("#dd_dd_number").hide();
                                            
                                            
            			    }
        			});
    			});
				
				</script>
	
   <script type="text/javascript">
	function Checkfiles()
{
var fup = document.getElementById('filename');
var fileName = fup.value;
var size=fup.files[0].size;

/*if(size<512000)
{
	alert("File size should be greater than 500kb");
	return false;
}*/
if(size>3000000)
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
			alert('Payment date should not greater than current date');
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
	
    	   <script type="text/javascript">
		$(document).ready(function () {
  //called when key is pressed in textbox
  $("#quantity").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
		alert('Only numeric allowed !');
               return false;
    }
   });
});
				</script>
				


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
        $(function () {
            $('#btnCheck').click(function () {
				var txtno = $('#quantity');              
				var modepayment=$('#ddlPassport');
			    var txtname = $('#name');				
				var bankname=$('#location');
                var othertxt=$('#textValidate').val();					
			    if (modepayment.val() != '0' && txtno.val() != '' && txtname.val()!= '' && bankname.val() != '0') {
             
              if(modepayment.val() == 'OTHER')
				   {
						if(othertxt!=''){				
							
							return true;
						}
						else{
							alert('Please fill the payment mode !')
							return false;
						}
				   }
				   else
				   {
						return true;
				   }
				   
                }
                else{
                    alert('Please fill all the field !');					
					return false;
                }
				
            })
        });
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

</head>

<body class="nav-md" >
    <div class="container body">    
        <div class="main_container">        
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">     
                    <img src="../Designing/img/HeaderLogo.png" alt="..." class="img-responsive">
                    <div class="clearfix"></div>
					<br />
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">                            
                             <ul class="nav side-menu">
                                <li><a href="VenderManagementDSB.php"><i class="glyphicon glyphicon-home"></i> &nbsp; Home </a></li>
                                <li><a href="Payments.php"><i class="fa fa-inr"></i> Payment</a></li> 
                                 <li><a href="IssueSlip.php"><i class="glyphicon glyphicon-hdd"></i>&nbsp; Issue Slip</a></li>
                                  <li><a href="Sales.php"><i class="fa fa-cc-visa"></i>&nbsp; Distribution</a></li>
                                   <li><a href="VendorDamage.php"><i class=" fa fa-chain-broken"></i>&nbsp;Damage Stock</a></li> 
								        <li><a href="Replacementstock.php"><i class=" fa fa-chain-broken"></i>&nbsp;Replacement Stock</a></li>
                           <li><a><i class="fa fa-th-list"></i>&nbsp; Display<span class="fa fa-chevron-down"></span></a>
                                  <ul class="nav child_menu" style="display: none">
                                        <li><a href="PaymentDisplay.php">Payments</a> </li> 
                                         <li><a href="IssueSlipDisplay.php">Issue Slip</a> </li>										
                                        <li><a href="SalesDisplay.php">Distribution</a> </li>                                       
                                        <li><a href="DamageDisplay.php">Damage Stock</a> </li>
                                         <li><a href="REplacementDisplay.php">Replacement Stock</a> </li> 										
                                    </ul>
                                </li>                                                       
                            <li><a href="ReportMis.php"><i class="glyphicon glyphicon-print"></i> &nbsp;MIS Report </a></li>                      
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
                                    <?php echo $Name ?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                  <li><a href="ChangePassword.php"> <i class="fa fa-sign-out pull-right"></i>Change Password</a>
                                    </li>                                    
                                    <li><a href="../Logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
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
                                <div class="x_title" style="color:#FFF; background-color:#889DC6;">                           
                                <h2>&nbsp; Update Payment</h2>                          
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                            
                                        
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">                              
  <form class="form-horizontal"  method="post"  action="UpdatePayments.php" enctype="multipart/form-data" onsubmit="return Checkfiles();">
                        	<input type="hidden" value="<?php echo $PaymentId; ?>" name="PaymentId" />
                            <input type="hidden" value="<?php echo $FinancVerify; ?>" name="FV" />						
                               
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Amount <span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input  class="form-control col-md-7 col-xs-12"  name="amount" AUTOCOMPLETE ="off" onpaste="return false;"  maxlength="7"  required="Required" id="quantity" type="text" value="<?php echo $Amount; ?>"/>
									 
                                </div>
                            </div>
							   <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Payment Mode <span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">                                
							         <select name="Mode_payment" id="ddlPassport"   AUTOCOMPLETE ="off" onpaste="return false;"  class="form-control input-md" required="Required"/>
                                           
                                                                           <option value="CASH" <?php if($PayMode=='CASH'){ echo "selected";} ?>>CASH</option>
                                                                           <option value="CHEQUE" <?php if($PayMode=='CHEQUE'){ echo "selected";} ?>>CHEQUE</option>
                                                                           <option value="Demand Draft" <?php if($PayMode=='Demand Draft'){ echo "selected";} ?>>Demand Draft</option>
                                                                            <option value="NEFT" <?php if($PayMode=='NEFT'){ echo "selected";} ?>>NEFT</option>
                                                                            <option value="RTGS" <?php if($PayMode=='RTGS'){ echo "selected";} ?>>RTGS</option>
                                                                            <option value="IMPS" <?php if($PayMode=='IMPS'){ echo "selected";} ?>>IMPS</option>
                                                                            <option value="OTHER" <?php if($PayMode=='OTHER'){ echo "selected";} ?>>OTHER</option>
                                                                  </select>
                                                <br/>
                                                                <span id="dvPassport" <?php if($PayMode=='OTHER'){ }else{ echo 'style="display: none"';} ?> >
                                                                    <label for="Other Payment Mode">Other Payment Mode</label>
                                                                    <input type="text" id="textValidate"  name="ReasonText" value="<?php if($PayMode=='OTHER'){ echo $other_payment_mode; }else{ echo ''; }?>" class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter payment mode" onpaste="return false;">
                                                                </span>
                                                                <!--<span id="span_cheque_amount" style="display: none">
                                                                    <input type="text" id="cheque_amount"  name="cheque_amount"  class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter Cheque Amount" onpaste="return false;">
                                                                </span>
                                                                    <br/>-->
                                                                <span id="span_cheque_number" <?php if($PayMode=='CHEQUE'){ }else{ echo 'style="display: none"';} ?>>
                                                                    <label for="Cheque Number">Cheque Number</label>
                                                                    <input type="text" id="cheque_number"  name="cheque_number" value="<?php if($PayMode=='CHEQUE'){ echo $cheque_number; }else{ echo ''; }?>"  class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter Cheque Number" onpaste="return false;">
                                                                </span>

                                                                <!--<span id="span_cash_amount" style="display: none">
                                                                    <input type="text" id="cash_amount"  name="cash_amount"  class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter Cash Amount Submitted" onpaste="return false;">
                                                                </span>
                                                                    <br/>-->
                                                                <span id="span_cash_bank_branch_code" <?php if($PayMode=='CASH'){ }else{ echo 'style="display: none"';} ?>>
                                                                    <label for="Bank Branch Code">Bank Branch Code</label>
                                                                    <input type="text" id="bank_branch_code"  name="bank_branch_code" value="<?php if($PayMode=='CASH'){ echo $bank_branch_code; }else{ echo ''; }?>" class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter Bank Branch Code" onpaste="return false;">
                                                                </span>

                                                                <!--<span id="dd_dd_amount" style="display: none">
                                                                    <input type="text" id="dd_amount"  name="dd_amount"  class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter DD Amount" onpaste="return false;">
                                                                </span>
                                                                    <br/>-->
                                                                <span id="dd_dd_number" <?php if($PayMode=='Demand Draft'){ }else{ echo 'style="display: none"';} ?>>
                                                                    <label for="DD Number">DD Number</label>
                                                                    <input type="text" id="dd_number"  name="dd_number"  value="<?php if($PayMode=='Demand Draft'){ echo $dd_number; }else{ echo ''; }?>" class="form-control input-sm" maxlength="30" autocolplete="off"  placeholder="Enter DD Number" onpaste="return false;">
                                                                </span>

								 <span id="neft_neft_number" <?php if($PayMode=='NEFT'){ }else{ echo 'style="display: none"';} ?>>
                                                                        <label for="NEFT Number">NEFT Number</label>
                                                                        <input  class="form-control input-md" id="neft_number" maxlength="50" name="neft_number" value="<?php if($PayMode=='NEFT'){ echo $TxnNo; }else{ echo ''; }?>" placeholder=" Enter NEFT Number" AUTOCOMPLETE ="off" onpaste="return false;" type="text" >
                                                                    </span>
								
								<span id="rtgs_rtgs_number" <?php if($PayMode=='RTGS'){ }else{ echo 'style="display: none"';} ?>>
                                                                        <label for="RTGS Number">RTGS Number</label>
                                                                        <input  class="form-control input-md" id="rtgs_number" maxlength="50" name="rtgs_number" value="<?php if($PayMode=='RTGS'){ echo $TxnNo; }else{ echo ''; }?>" placeholder=" Enter RTGS Number" AUTOCOMPLETE ="off" onpaste="return false;" type="text" >
                                                                    </span>

								<span id="imps_imps_number" <?php if($PayMode=='IMPS'){ }else{ echo 'style="display: none"';} ?>>
                                                                        <label for="imps Number">IMPS Number</label>
                                                                        <input  class="form-control input-md" id="imps_number" maxlength="50" name="imps_number" value="<?php if($PayMode=='IMPS'){ echo $TxnNo; }else{ echo ''; }?>" placeholder=" Enter IMPS Number" AUTOCOMPLETE ="off" onpaste="return false;" type="text" >
                                                                    </span>
                                                                        
                                                
                                                
                                                
                                                
                                                
                                                
                                </div>
                            </div>
							 <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Bank Name<span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">

									<select id="location"  AUTOCOMPLETE ="off" onpaste="return false;"  name="bank" class="form-control col-md-7 col-xs-12" required="Required"> 
                                    
                                    
                                    <?php echo "<option value=$BankNmae>$BankNmae</option>";?>                                   
                                                     <?php
		                                                    //include("connection/connection.php");
															
                                                    $msql = mysqli_query($con,"SELECT BankName FROM tblbank");	
                                                              while($m_row = mysqli_fetch_array($msql)) 
                                                             {
											          echo "<option value='".$m_row['BankName']."'>".$m_row['BankName']."</option>";
																}
																	?>                                               
														</select>
                                </div>
                            </div>
                         
                           <!-- <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Txn Reference No.<span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input id="name" class="form-control col-md-7 col-xs-12"  AUTOCOMPLETE ="off" onpaste="return false;"   name="txn_no" required="Required" type="text" value=" <?php echo $TxnNo; ?>"/>
                                </div>
                            </div>-->
                            <div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name">Payment Date <span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input  readonly="readonly" class="form-control col-md-7 col-xs-12" name="pay_date"  AUTOCOMPLETE ="off" onpaste="return false;"   type="text" value=" <?php echo $PatDate; ?>"/> 
                                </div>								
                            </div>
							  <div class="item form-group">
							  <div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name"></span></label>
                                </div>
							  <div class="col-md-4 col-sm-4 col-xs-12">
                             <input type="date" class="form-control col-md-7 col-xs-12" onblur="return checkDate();" id="Pay_date1" name="pay_date1" placeholder=" Select  the payment date"   AUTOCOMPLETE ="off" onpaste="return false;" /> 
                                </div>
                            </div>
                          
                            
							
							<div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            
							<label class="control-label" for="name">Payment Proof <span style="color:red; margin-top:8px;"> *</span></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">                                
									 									
									   <input   type="hidden" name="oldimg" value="<?php echo $PayProf;?>" />
									  <img  class="img-responsive"  style="max-height:150px; min-height:150px; max-width:200px; min-width:100px;" src="<?php echo $PayProf; ?>" />
                                </div>
                            </div>                     
					
							
							<div class="item form-group">
                            	<div class="col-xl-2 col-sm-2 col-xs-12">
                            		<label class="control-label" for="name"></label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                	<input id="filename" class="form-control col-md-7 col-xs-12"  placeholder=" Please enter  the payment proof" name="Image" type="file" /> 
									<p id="sig_errorst1" style="display:none; color:#FF0000;">Image format should be gif, jpeg, png or bmp !.</p>
                                    <p id="sig_errorst2" style="display:none; color:#FF0000;">Max file size should be 1MB.</p>

                                </div>
                            </div>   
                            <div class="ln_solid"></div>
                            	<div class="form-group">
                                	<div class="col-md-6 col-md-offset-3 col-xs-12"> 
                                      <button  name="update_payment" type="submit" id="btnCheck" onclick="return checkDate();" class="btn btn-md btn-info" style=" width:120px;">Update Records</button>									
                                        <a href="PaymentDisplay.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Go Back</a>
                                	</div>                           
								</div>
                        </form>
                             
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
				
                <!-- footer content -->
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

    <!-- moris js -->
    <script src="js/moris/raphael-min.js"></script>
    <script src="js/moris/morris.js"></script>
    <script>
        $(function () {
            var day_data = [
                {
                    "period": "Jan",
                    "Hours worked": 80
                },
                {
                    "period": "Feb",
                    "Hours worked": 125
                },
                {
                    "period": "Mar",
                    "Hours worked": 176
                },
                {
                    "period": "Apr",
                    "Hours worked": 224
                },
                {
                    "period": "May",
                    "Hours worked": 265
                },
                {
                    "period": "Jun",
                    "Hours worked": 314
                }
    ];
            Morris.Bar({
                element: 'graph_bar',
                data: day_data,
                hideHover: 'always',
                xkey: 'period',
                barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
                ykeys: ['Hours worked', 'sorned'],
                labels: ['Hours worked', 'SORN'],
                xLabelAngle: 60
            });
        });
    </script>
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
</body>
</html>


 

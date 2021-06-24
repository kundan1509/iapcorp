<?php

    include("../Connection/Connection.php");
    

if(isset($_GET['id']))
{
	$venid=$_GET['id'];
	$vendorinformation="select * from tblvendorregistration where vendorid='$venid' and RecStatus='A'";
	$fetinfo=mysqli_query($con,$vendorinformation);
	$veninfo=mysqli_fetch_array($fetinfo);
	$vendorcode=$veninfo['VendorCode'];
	$vendorcontact=$veninfo['ContactNumber'];
	$vendorname=$veninfo['VendorName'];
	$eid=$veninfo['EmailId'];
	$desc=$veninfo['Description'];
	$corporatestatus=$veninfo['CorporateStatus'];
	$pno=$veninfo['PanNo'];
	$sTaxno=$veninfo['ServiceTaxNo'];
	$add1=$veninfo['Address1'];
	$add2=$veninfo['Address2'];
	$town=$veninfo['Town_City'];
	$dist=$veninfo['Distt'];
	$fstate=$veninfo['State'];
	$fpin=$veninfo['Pin'];
	$accholdername=$veninfo['ACHolderName'];
	$acnumber=$veninfo['ACNumber'];
	$fbankname=$veninfo['BankName'];
	$fifsc=$veninfo['IFSC'];
	$bankcity=$veninfo['BankCity_Town'];
	$poolac=$veninfo['PoolACNo'];
	
	$fpoolacifsc=$veninfo['PoolACIFSC'];
	$refferedby1=$veninfo['Refferedby'];
	$referalname1=$veninfo['ReferalName'];
	$kpname=$veninfo['KeyPersonName'];
	$kpadd=$veninfo['Adddress'];
	$kpmobile=$veninfo['MobileNo'];
	$kpemail=$veninfo['KeyPersonEmailId'];
	
	$paymentDetail="SELECT ModeofPayment,PaymentDate as pd,Amount,TaxnRefNo FROM tblpayment WHERE paymenttype='S' AND vendorid=$venid";
	$pinfo=mysqli_query($con,$paymentDetail);
	$pdetail=mysqli_fetch_array($pinfo);
	$depositmode=$pdetail['ModeofPayment'];
	$depositDate1=$pdetail['pd'];
	$depositDate = date("d-m-Y", strtotime($depositDate1));	
	$depositAmmount=$pdetail['Amount'];
	$ddno=$pdetail['TaxnRefNo'];
	
	/*$vendorLoc="SELECT vl.vendorid,vl.locationid,l.locid,vl.id,vl.NumberOfCenter,vl.SPOCName,vl.MobileNo,l.locationname FROM tblvendorlocation vl,
tbllocation l,tblvendorregistration WHERE vl.locationid=l.locid AND tblvendorregistration.vendorid=vl.vendorid AND tblvendorregistration.Recstatus='A'
AND tblvendorregistration.vendorid='$venid'";
$venloc=mysqli_query($con,$vendorLoc);
while($fetchloc=mysqli_fetch_array($venloc))
{
	
	$lid[]=$fetchloc['id'];
	$locationid[]=$fetchloc['locationid'];
	$dname[]=$fetchloc['locationname'];
	$cno[]=$fetchloc['NumberOfCenter'];
	$spname[]=$fetchloc['SPOCName'];
	$mno[]=$fetchloc['MobileNo'];*/
	
	
}
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

if(isset($_POST['finalsubmit']))
{
	//$distributorcode=$_POST['distibutorcode'];
	$distributorName=$_POST['distibutorName'];
	$corpotateAdd=$_POST['corporateStatus'];
	$panno=$_POST['panumber'];
	$ServiecTaxno=$_POST['servicetaxno'];
	$Emailid=$_POST['emailid'];
	$MobileNo=$_POST['mobilenumber'];
	$Adress1=$_POST['address1'];
	$Address2=$_POST['address2'];
	$Town=$_POST['town'];
	$Distt=$_POST['district'];
	$state=$_POST['state'];
	$pin=$_POST['pin'];
	$Acholdername=$_POST['accountholdername'];
	$accountNo=$_POST['acnumber'];
	$bankName=$_POST['bankname'];
	$Ifsc=$_POST['ifscCode'];
	$bankcityTown=$_POST['cityTown'];
	$PoolACNo=$_POST['poolacno'];
	$PoolACIfsc=$_POST['poolacifscno'];
	$modeofDeposit=$_POST['modeofDeposit'];
	$dateOfDeposit=$_POST['dateofdeposit'];
	$ChangedateOfDeposit=$_POST['dateofdeposit1'];
	$Amount=$_POST['amount'];
	$TxnNo=$_POST['referenceno'];
	$ReferedBy=$_POST['referncedby'];
	$RefrealName=$_POST['referalname'];
	$date=date('Y-m-d H:i:s');
	$Currentdate=date('d-m-Y');
	
	$keyPersonName=$_POST['keypersonName'];
	$keyPersonAddress=$_POST['keypersonAdd'];
	$keypersonEmail=$_POST['keypersonEmail'];
	$keypersonMobile=$_POST['keypersonContact'];
	
	$id=$_POST['vendorid'];
	if($ChangedateOfDeposit=="")
	{
	
	$query="update tblvendorregistration set VendorName='$distributorName',
	ContactNumber='$MobileNo',EmailId='$Emailid',LastUpdateOn='$date',UpdatebyID='$UserId',CorporateStatus='$corpotateAdd',
   PanNo='$panno',
  ServiceTaxNo='$ServiecTaxno',
  Address1='$Adress1',
  Address2='$Address2',
  Town_City='$Town',
  Distt='$Distt',
  State='$state',
  Pin='$pin',
  ACHolderName='$Acholdername',
  ACNumber='$accountNo',
  BankName='$bankName',
  IFSC='$Ifsc',
  BankCity_Town='$bankcityTown',
  PoolACNo='$PoolACNo',
  PoolACIFSC='$PoolACIfsc',
  Refferedby='$ReferedBy',
  ReferalName='$RefrealName',KeyPersonName='$keyPersonName',Adddress='$keyPersonAddress',MobileNo='$keypersonMobile',KeyPersonEmailId='$keypersonEmail' where vendorid='$id'";
  if(mysqli_query($con,$query))
  {
	 
	  
	  $updatepayment="update tblpayment set Amount='$Amount',ModeofPayment='$modeofDeposit',TaxnRefNo='$TxnNo',PaymentDate='$dateOfDeposit' where paymenttype='s' and vendorid='$id'";
	 mysqli_query($con,$updatepayment);
	  
	 
	  
	  
	  
	  
	  echo "<script>alert('Distributor updated successfully')</script>";
	  echo "<script>window.open('VendorList.php','_self')</script>";
	  
	 
  }
  else
  {
	  echo "<script>alert('Distributor Not Update')</script>";
  }
	}
	
	else
	{
		$query="update tblvendorregistration set VendorName='$distributorName',
	ContactNumber='$MobileNo',EmailId='$Emailid',LastUpdateOn='$date',UpdatebyID='$UserId',CorporateStatus='$corpotateAdd',
   PanNo='$panno',
  ServiceTaxNo='$ServiecTaxno',
  Address1='$Adress1',
  Address2='$Address2',
  Town_City='$Town',
  Distt='$Distt',
  State='$state',
  Pin='$pin',
  ACHolderName='$Acholdername',
  ACNumber='$accountNo',
  BankName='$bankName',
  IFSC='$Ifsc',
  BankCity_Town='$bankcityTown',
  PoolACNo='$PoolACNo',
  PoolACIFSC='$PoolACIfsc',
  Refferedby='$ReferedBy',
  ReferalName='$RefrealName',KeyPersonName='$keyPersonName',Adddress='$keyPersonAddress',MobileNo='$keypersonMobile',KeyPersonEmailId='$keypersonEmail' where vendorid='$id'";
  if(mysqli_query($con,$query))
  {
	 
	  
	  $updatepayment="update tblpayment set Amount='$Amount',ModeofPayment='$modeofDeposit',TaxnRefNo='$TxnNo',PaymentDate='$ChangedateOfDeposit' where paymenttype='s' and vendorid='$id'";
	 mysqli_query($con,$updatepayment);
	  
	 
	  
	  
	  
	  
	  echo "<script>alert('Distributor updateed successfully')</script>";
	  echo "<script>window.open('VendorList.php','_self')</script>";
	  
	 
  }
  else
  {
	  echo "<script>alert('Distributor Not Update')</script>";
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Distributor Update</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">
    <!-- editor -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
    <link href="css/editor/external/google-code-prettify/prettify.css" rel="stylesheet">
    <link href="css/editor/index.css" rel="stylesheet">
    <!-- select2 -->
    <link href="css/select/select2.min.css" rel="stylesheet">
    <!-- switchery -->
    <link rel="stylesheet" href="css/switchery/switchery.min.css" />

    <script src="js/jquery.min.js"></script>
    
    <script type="text/javascript">
	
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

        return true;
		}

}

function validateStaxno(servicetax)
{
	var fistInput = document.getElementById('panno').value;
	var staxnumber=servicetax.value;
	var staxnumberTenDigit=staxnumber.substring(0,10);
	if(staxnumber!="")
	{
	if(fistInput==staxnumberTenDigit)
	{
	
			
            var panPat1 = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})([a-zA-Z]{2})([0-9]{2})$/;
            if (servicetax.value != "") {
            ObjVal1 = servicetax.value;
            
            if (ObjVal1.search(panPat1) == -1) {
                alert("Invalid ServiceTax No");
                
				window.setTimeout(function ()
    {
        servicetax.focus();
    }, 0);
                return false;
            }
        }
           
            
	}
	else
	{
		alert("Pan Card number not match in service taxNo");
		window.setTimeout(function ()
    {
        servicetax.focus();
    }, 0);
			}
	}
	
	
}
	
	
	
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
		$('.textValidate').keydown(function (e) {
		if (e.ctrlKey || e.altKey) {
			e.preventDefault();
			} else {
				var key = e.keyCode;
				if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90)|| (key==9)|| (key==16)|| (key==20))) {
					alert('Only characters allowed!');
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



function onlyAlphabets(e, t) {
             var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32||k==17); 
        } 	
		
		</script>



 <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_referralName.php",
	data:'Refferalid='+val,
	success: function(data){
		$("#refferalName1").html(data);
		
	}
	
	});
	
}

function getIFSCCode(ac) {
	$.ajax({
	type: "POST",
	url: "get_IFSCCode.php",
	data:'acnumber='+ac,
	success: function(data){
		$("#poolacifscno1").html(data);
		
	}
	
	});
	
	
}

function get(ac) {
	$.ajax({
	type: "POST",
	url: "get_IFSCCode.php",
	data:'acnumber='+ac,
	success: function(data){
		$("#poolacifscno1").html(data);
		
	}
	
	});
	
	
}

function getWSDistt(sc) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+sc,
	success: function(data){
		$("#cname").html(data);
		
	}
	
	});
	

}

function getLocation(lc) {
	$.ajax({
	type: "POST",
	url: "get_Dist.php",
	data:'state_Id='+lc,
	success: function(data){
		$("#Distname").html(data);
		
	}
	
	});

}

</script>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#name").blur(function() {
var name = $('#name').val();
var name1 = $('#namehid').val();
if(name=="")
{
$("#disp").html("");
}
else
{
$.ajax({
type: "POST",
url: "user_checkupdatevendor.php",
data: { name: name, name1: name1 },
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
$("#panno").blur(function() {
var name = $('#panno').val();
var pan=$('#panhid').val();
if(name=="")
{
$("#disppan").html("");
}
else
{
$.ajax({
type: "POST",
url: "user_checkupdatevendor.php",
data:{ pan: name, panold: pan},
success: function(html){
$("#disppan").html(html);
}
});
return false;
}
});
});







</script>





<script type="text/javascript">
$(document).ready(function(){
$("#mobid").blur(function() {
var name = $('#mobid').val();
var mobold = $('#mobhid').val();
if(name=="")
{
$("#dispmob").html("");
}
else
{
$.ajax({
type: "POST",
url: "user_checkupdatevendor.php",
data: { mob: name, mobold: mobold },
success: function(html){
$("#dispmob").html(html);
}
});
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
                                   </li>
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
                                      
                                        <li><a href="VendorRegistration.php">Distributor</a>
                                        </li>
                                        <!-- <li><a href="DispatcherRegistration.php">Dispatcher</a>
                                        </li>-->
                                        <li><a href="WarehouseRegistration.php">WareHouse</a>
                                        </li>
                                       </ul>
                                </li>
                                
                                
                                 
                              
                              <li><a href="Consignment.php"><i class="fa fa-truck"></i>Consignment</a>
                                   </li>
                                   
                                  
                                   
                                    <li><a href="IssueSlip.php"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;&nbsp;&nbsp;Issue Slip</a>
                                   </li>                           
                              
                               
                              
                               <li><a><i class="fa fa-chain-broken"></i>&nbsp;Damage Stock<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                       <li><a href="vendordamagelist.php">Distributor</a>
                                        </li>
                                        <li><a href="warehousedamagelist.php">WareHouse</a>
                                        </li>
                                        
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
                                   </li>
                              
                                
                                
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
                                                                     
                                   
                                   
                                     <li><a href="#"><i class="fa fa-cog pull-right"></i>Change Password</a>
                                    </li>
									 <li><a href="#"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                    </li>
                                </ul>
                            </li>

                            <li role="presentation" class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                   
                                </a>
                              
                            </li>

                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">

                    <div class="page-title">
                        <div class="title_left">
                           
                        </div>
                    
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                         
                        </div>
                    </div>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('#birthday').daterangepicker({
                                singleDatePicker: true,
                                calender_style: "picker_4"
                            }, function (start, end, label) {
                                console.log(start.toISOString(), end.toISOString(), label);
                            });
                        });
						
						
						function chechform(){
							
								var errorpan=$('#disppan').find('span').html();
								var erroremail=$('#disp').find('span').html();
								var errormobile=$('#dispmob').find('span').html();
								
								
								var name = $('#name').val();
								var name1 = $('#namehid').val();
								
								var pannew = $('#panno').val();
								var pan=$('#panhid').val();
								
								var mobnew = $('#mobid').val();
								var mobold = $('#mobhid').val();
								
								if((name==name1)&&(pannew==pan)&&(mobnew==mobold))
								{
									
									return true;
								}
								if((errorpan!="Available")||(erroremail!="Available")||(errormobile!="Available"))
								{
									return false;
								}
								
							else if((errorpan!="Available")||(erroremail!="Available")||(errormobile!="Available"))
								{
									//do nothing
									alert('hello');
									return false;
								 }
													 
								 else 
									{
										
										return true;
										
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
depdate=(document.getElementById('depdate').value);
if(depdate>today)
{
	alert('Deposit date should not be greater than current date');
	
	
			
				window.setTimeout(function ()
    {
         document.getElementById("depdate").focus();
    }, 0);
            return false;
}
else
{
	document.getElementById('depdate').style.borderColor='gray';
	return true;
}


}
						
						
                    </script>


                    <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                     <form class="form-horizontal form-label-left" method="post" action="updatevendor.php" enctype="multipart/form-data" autocomplete="off" onSubmit="return checkDate()&& chechform();">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Update Distributor</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                      
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                      <div class="col-md-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Applicant Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                   
                                 
                                   

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Name<span style=" color:#F00">*</span></label>
                                            <input type="hidden" name="vendorid" value="<?php echo $venid;?>">
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" value="<?php echo $vendorname;?>" onpaste="return false" required name="distibutorName" class="textValidate form-control" placeholder="Enter Distributor Name" maxlength="100" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Corporate Status</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               <select id="corporateStatus" name="corporateStatus" class="select group form-control">									 <?php echo "<option value='$corporatestatus'>$corporatestatus</option>";
											   ?>
                                               <option value="Individual"> Individual</option>
                                               <option value="Proprietership"> Proprietership</option>
                                               <option value="Private LTD"> Private LTD.</option>
                                               <option value="NGO/Society"> NGO/Society</option>
                                               <option value="Public LTD"> Public LTD.</option> 
                                               </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pan Number<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" id="panno" onBlur="ValidatePAN(this);" onpaste="return false" required name="panumber" value="<?php echo $pno;?>" class="form-control" placeholder="Enter Distributor Pan No." maxlength="20">
                                            </div>
                                            
                                            <input type="hidden" id="panhid" value="<?php echo $pno;?>">
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="disppan"></div><br />
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Service Tax No.                                          </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" name="servicetaxno" onpaste="return false" onBlur="validateStaxno(this);" value="<?php echo $sTaxno;?>" class="form-control" placeholder="Enter Service Tax No" maxlength="20">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">E-Mail Id<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               <input type="text" required name="emailid" onpaste="return false" onBlur="validateEmail(this);" value="<?php echo $eid;?>" class="form-control" id="name" placeholder="Enter Distributor Service Tax No." maxlength="50">
                                           
                                               <input type="hidden" id="namehid" value="<?php echo $eid;?>">
                                               
                                            </div>
                                             
                                              <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="disp"></div><br />
                                        </div>
                                         
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mobile Number<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" value="<?php echo $vendorcontact; ?>" onpaste="return false" required name="mobilenumber" class="txtNumeric form-control" id="mobid" placeholder="Enter Distributor Mobile No" maxlength="12" min="10" onkeypress="return isNumberKey(event);">
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="dispmob"></div><br>
                                             <input type="hidden" id="mobhid" value="<?php echo $vendorcontact;?>">
                                            
                                        </div>
                                        
                                        <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Address</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                        
                                       
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Line 1</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="address1" onpaste="return false" value=" <?php echo $add1;?>" class="form-control" placeholder="Enter Distributor Address" maxlength="200">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Line 2</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            
                                               <input type="text" name="address2" onpaste="return false" value="<?php echo $add2;?>" class="form-control" placeholder="Enter Distributor Address" maxlength="200">
                                               
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Town/City</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" onpaste="return false" name="town" value="<?php echo $town;?>" class="form-control" placeholder="Enter Distributor Town/City" maxlength="50">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">District</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" onpaste="return false" name="district" value=" <?php echo $dist;?>" class="textValidate form-control" placeholder="Enter Distributor District" maxlength="50" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                       
                                       
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">State</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="state" onpaste="return false" value="<?php echo $fstate;?>" class="textValidate form-control" placeholder="Enter Distributor State" maxlength="20" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        
                                        
                                          <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pin</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="pin" onpaste="return false" value="<?php echo $fpin;?>" class="txtNumeric form-control" placeholder="Enter Distributor Area Pin No." maxlength="6" onkeypress="return isNumberKey(event)" >
                                            </div>
                                        </div>
									
                                    	      <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Key Person Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">KeyPerson Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" value="<?php echo $kpname;?>" onpaste="return false" onkeypress="return onlyAlphabets(event,this);" name="keypersonName" class="textValidate form-control" required placeholder="Enter KeyPerson Name"  maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" value="<?php echo $kpadd;?>" onpaste="return false" name="keypersonAdd" class="form-control" required placeholder="Enter KeyPerson Address"  maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">E-Mail Id <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" value="<?php echo $kpemail;?>" onpaste="return false" onBlur="validateEmail(this);" name="keypersonEmail" class="form-control" placeholder="Enter KeyPerson Email-Id" required  maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Contact No.<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" value="<?php echo $kpmobile;?>" onpaste="return false" onkeypress="return isNumberKey(event);" name="keypersonContact" class="txtNumeric form-control" placeholder="Enter KeyPerson Contact" required  maxlength="12">
                                            </div>
                                        </div>
                                        
                                      
                                      
                    
                                </div>
                            </div>
                        </div>
                        

                        <div class="col-md-6 col-xs-12">
                            <div class="x_panel">
                            
                            
                            <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Bank Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                
                                <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Account Holder Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="accountholdername" onpaste="return false" value="<?php echo $accholdername;?>" class="textValidate form-control" placeholder="Enter Distributor AC Holder Name" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Account No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="acnumber" onpaste="return false" value="<?php echo $acnumber;?>" onkeypress="return isNumberKey(event)" class="txtNumeric form-control" placeholder="Enter Distributor AC No.">
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Bank Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select name="bankname" class="select2_group form-control">
                                                 <?php echo "<option value='$fbankname'>$fbankname</option>";
											   ?>
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
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">IFSC Code</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="ifscCode" onpaste="return false" value="<?php echo $fifsc;?>" class="form-control" placeholder="Enter Distributor Bank IFSC Code" maxlength="20">
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">City/Town</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="cityTown" onpaste="return false" value="<?php echo $bankcity;?>"  class="form-control" placeholder="Enter Distributor Bank City Name" maxlength="50">
                                            </div>
                                        </div>
                                        
                            
                            
                            
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Security Deposit Detail</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />
                                    

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pool Account No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <!--<input type="text" maxlength="20" name="poolacno" onkeypress="return isNumberKey(event)" class="form-control" required placeholder="Enter Company Pool AC No.">-->
                                                 
           <select name="poolacno" class="form-control input-md"  required="Required" onChange="getIFSCCode(this.value);">
           	<?php echo "<option value='$poolac'>$poolac</option>";?>
            <option value="0">Please Select Pool Account</option>
             <?php
												  $bankQuery="SELECT AccountNo FROM tblpollaccount WHERE recstatus='A'";
												  $q=mysqli_query($con,$bankQuery);
                                                  while($m_row = mysqli_fetch_array($q)) 
                                                             {
											          echo "<option value='".$m_row['AccountNo']."'>".$m_row['AccountNo']."</option>";
																}
																	?>                                                           
			
</select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">IFSC Code</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               <!--  <input type="text" required name="poolacifscno" class="form-control" placeholder="Enter Pool AC IFSC No." maxlength="20">-->
                                                 <select name="poolacifscno" id="poolacifscno1" class="form-control input-md" required="Required">
                                                 
                                                  <?php echo "<option value='$fpoolacifsc'>$fpoolacifsc</option>";?>
                                                  <option value="0">Please Select Pool Account IFSC Code</option>
                                                    
                                                                    
                                                                           
                                                      </select>
                                                      
                                                      
                           
                                                      
                                               
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mode Of Deposit</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             <select name="modeofDeposit" class="form-control input-md" required="Required">
                                             <?php echo "<option value='$depositmode'>$depositmode</option>";?>
                                                                    <option value="0">Select Please Mode Of Payment</option>
                                                                           <option value="CASH">CASH</option>
                                                                           <option value="CHEQUE">CHEQUE</option>
                                                                            <option value="NEFT">NEFT</option>
                                                                            <option value="RTGS">RTGS</option>
                                                                            <option value="IMPS">IMPS</option>
                                                                            <option value="OTHER">OTHER</option>
                                                                  </select>   
                                            
                                                 <!--<input type="text" onKeyPress="return ValidateAlpha(event);" name="modeofDeposit" class="form-control" required placeholder="Enter Payment Deposit Mode" maxlength="50">-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Date Of Deposit</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
    <input type="text" readonly required name="dateofdeposit" value="<?php echo $depositDate;?>" onpaste="return false" class="form-control" placeholder="Select Payment Date">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Date Of Deposit(Select if you want change)</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
    <input type="date" id="depdate" name="dateofdeposit1" onpaste="return false" class="form-control" onBlur="checkDate();" placeholder="Select Payment Date">
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Amount</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" value="<?php echo $depositAmmount;?>" onpaste="return false" required onkeypress="return isNumberKey(event)" name="amount" class="txtNumeric form-control" maxlength="10" placeholder="Enter Amount">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">DD/CHQ/TXN No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" value="<?php echo $ddno;?>" onpaste="return false" required name="referenceno" class="form-control" placeholder="Enter DD/Chq/Txn No." maxlength="7">
                                            </div>
                                        </div>
                                        
                                     
                                        
                                        <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Reference</small></h2>
                                
                                    <div class="clearfix"></div>
                                    </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Referred By:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             
                                              <select name="referncedby" class="form-control" onChange="getState(this.value);">
                                              <?php echo "<option value='$refferedby1'>$refferedby1</option>";?>
                                                    <option>Employee</option>
                                                    <option>Consultant</option>
                                                    
                                                </select>
 						
						
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Referral Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                                                                 
                                                 <select name="referalname" id="refferalName1" class="form-control">
                                                 <?php echo "<option value='$referalname1'>$referalname1</option>";?>
                                                    <option value="0">Select Referral Name</option>
                                                    </select>
                                                 
                                            </div>
                                        </div>
                                        
                                        

                                      
                                      


                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                               
                                                <button type="submit" onClick="return chechform();" name="finalsubmit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>

                                    
                                </div>
                            </div>
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
                <!-- /page content -->

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
        <!-- tags -->
        <script src="js/tags/jquery.tagsinput.min.js"></script>
        <!-- switchery -->
        <script src="js/switchery/switchery.min.js"></script>
        <!-- daterangepicker -->
        <script type="text/javascript" src="js/moment.min2.js"></script>
        <script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
        <!-- richtext editor -->
        <script src="js/editor/bootstrap-wysiwyg.js"></script>
        <script src="js/editor/external/jquery.hotkeys.js"></script>
        <script src="js/editor/external/google-code-prettify/prettify.js"></script>
        <!-- select2 -->
        <script src="js/select/select2.full.js"></script>
        <!-- form validation -->
        <script type="text/javascript" src="js/parsley/parsley.min.js"></script>
        <!-- textarea resize -->
        <script src="js/textarea/autosize.min.js"></script>
        <script>
            autosize($('.resizable_textarea'));
        </script>
        <!-- Autocomplete -->
        <script type="text/javascript" src="js/autocomplete/countries.js"></script>
        <script src="js/autocomplete/jquery.autocomplete.js"></script>
        <script type="text/javascript">
            $(function () {
                'use strict';
                var countriesArray = $.map(countries, function (value, key) {
                    return {
                        value: value,
                        data: key
                    };
                });
                // Initialize autocomplete with custom appendTo:
                $('#autocomplete-custom-append').autocomplete({
                    lookup: countriesArray,
                    appendTo: '#autocomplete-container'
                });
            });
        </script>
        <script src="js/custom.js"></script>


        <!-- select2 -->
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
        <!-- /select2 -->
        <!-- input tags -->
        <script>
            function onAddTag(tag) {
                alert("Added a tag: " + tag);
            }

            function onRemoveTag(tag) {
                alert("Removed a tag: " + tag);
            }

            function onChangeTag(input, tag) {
                alert("Changed a tag: " + tag);
            }

            $(function () {
                $('#tags_1').tagsInput({
                    width: 'auto'
                });
            });
        </script>
        <!-- /input tags -->
        <!-- form validation -->
        <script type="text/javascript">
            $(document).ready(function () {
                $.listen('parsley:field:validate', function () {
                    validateFront();
                });
                $('#demo-form .btn').on('click', function () {
                    $('#demo-form').parsley().validate();
                    validateFront();
                });
                var validateFront = function () {
                    if (true === $('#demo-form').parsley().isValid()) {
                        $('.bs-callout-info').removeClass('hidden');
                        $('.bs-callout-warning').addClass('hidden');
                    } else {
                        $('.bs-callout-info').addClass('hidden');
                        $('.bs-callout-warning').removeClass('hidden');
                    }
                };
            });

            $(document).ready(function () {
                $.listen('parsley:field:validate', function () {
                    validateFront();
                });
                $('#demo-form2 .btn').on('click', function () {
                    $('#demo-form2').parsley().validate();
                    validateFront();
                });
                var validateFront = function () {
                    if (true === $('#demo-form2').parsley().isValid()) {
                        $('.bs-callout-info').removeClass('hidden');
                        $('.bs-callout-warning').addClass('hidden');
                    } else {
                        $('.bs-callout-info').addClass('hidden');
                        $('.bs-callout-warning').removeClass('hidden');
                    }
                };
            });
            try {
                hljs.initHighlightingOnLoad();
            } catch (err) {}
        </script>
        <!-- /form validation -->
        <!-- editor -->
        <script>
            $(document).ready(function () {
                $('.xcxc').click(function () {
                    $('#descr').val($('#editor').html());
                });
            });

            $(function () {
                function initToolbarBootstrapBindings() {
                    var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                'Times New Roman', 'Verdana'],
                        fontTarget = $('[title=Font]').siblings('.dropdown-menu');
                    $.each(fonts, function (idx, fontName) {
                        fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
                    });
                    $('a[title]').tooltip({
                        container: 'body'
                    });
                    $('.dropdown-menu input').click(function () {
                            return false;
                        })
                        .change(function () {
                            $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                        })
                        .keydown('esc', function () {
                            this.value = '';
                            $(this).change();
                        });

                    $('[data-role=magic-overlay]').each(function () {
                        var overlay = $(this),
                            target = $(overlay.data('target'));
                        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
                    });
                    if ("onwebkitspeechchange" in document.createElement("input")) {
                        var editorOffset = $('#editor').offset();
                        $('#voiceBtn').css('position', 'absolute').offset({
                            top: editorOffset.top,
                            left: editorOffset.left + $('#editor').innerWidth() - 35
                        });
                    } else {
                        $('#voiceBtn').hide();
                    }
                };

                function showErrorAlert(reason, detail) {
                    var msg = '';
                    if (reason === 'unsupported-file-type') {
                        msg = "Unsupported format " + detail;
                    } else {
                        console.log("error uploading file", reason, detail);
                    }
                    $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
                };
                initToolbarBootstrapBindings();
                $('#editor').wysiwyg({
                    fileUploadError: showErrorAlert
                });
                window.prettyPrint && prettyPrint();
            });
        </script>
        
        <!-- /editor -->
</body>

</html>
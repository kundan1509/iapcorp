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

if(isset($_POST['finalsubmit']))
{
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
	$dateOfDeposit1=date("d-m-Y", strtotime($dateOfDeposit));
	$Amount=$_POST['amount'];
	$TxnNo=$_POST['referenceno'];
	$ReferedBy=$_POST['referncedby'];
	$rate=$_POST['rate'];
	if($ReferedBy=='1')
	{
		$refby="Employee";
	}
	else
	{
		$refby="Consultant";
	}
	$RefrealName=$_POST['referalname'];
	$date=date('Y-m-d H:i:s');
	$Currentdate=date('d-m-Y');
	$Currentdate1=date('dmY');
	$password = rand(1000,5000);
	$encryptpass=base64_encode($password);
	$distname1=$_POST['Distname'];
	$centerno1=$_POST['noofcenter'];
	$spocname1=$_POST['spocname'];
	$mobile1=$_POST['mobile'];
	
	
	/*$distname2=$_POST['Distname1'];
	$centerno2=$_POST['name1'];
	$spocname2=$_POST['spocname1'];
	$mobile2=$_POST['mobile1'];*/
	$otherpaymentreason=$_POST['color'];
	
	$keyPersonName=$_POST['keypersonName'];
	$keyPersonAddress=$_POST['keypersonAdd'];
	$keypersonEmail=$_POST['keypersonEmail'];
	$keypersonMobile=$_POST['keypersonContact'];
	$locationType=$_POST['sl'];
	if($locationType=='Single Location')
	{
		$lt="S";
	}
	else
	{
		$lt="M";
	}
	
	
	$checkmail="select emailid,contactnumber,Panno from tblvendorregistration where emailid='$Emailid' or panno='$panno' or contactnumber='$MobileNo'";
	$emailqry=mysqli_query($con,$checkmail);
	$emailvalid=mysqli_fetch_array($emailqry);
	$emailid1=$emailvalid['emailid'];
	$mob=$emailvalid['contactnumber'];
	$pan=$emailvalid['Panno'];
	if(($emailid1==$Emailid)||($pan==$panno)||($mob==$MobileNo))
	{
		echo"<script>alert('This Mobile No.Or PanNo. Or Eamil-id is already registered')</script>";
		echo "<script>window.open('VendorRegistration.php','_self')</script>";
	}
	else
	{
	$statid=$_POST['stateName'];
	$cityid=$_POST['CityName22'];
	$getStateCode="select Lastunitvendor,StateCode from tblstate where stateid='$statid'";
	$querys=mysqli_query($con,$getStateCode);
    $Stcode = mysqli_fetch_array($querys);
	$statecode=$Stcode['StateCode'];
	$lastUnitVendor=$Stcode['Lastunitvendor'];
	
	
	
	$getcityCode="select Lastunitvendor,CityCode from tblcity where cityid='$cityid'";
	
	$cityquerys=mysqli_query($con,$getcityCode);
	
	$cittcode = mysqli_fetch_array($cityquerys);
	
	
	$citycodes=$cittcode['CityCode'];
	$lastunitcity=$cittcode['Lastunitvendor'];
	$incrementCityUnit=$lastunitcity+'1';
	$incremntStateUnit=$lastUnitVendor+'1';
	$vendorcode=$statecode.'/'.$citycodes.'/'.$Currentdate1.'/'.$lt.'/'.$incremntStateUnit.'/'.$incrementCityUnit;
	
	
	
	
	$query="insert into tblvendorregistration(VendorCode,VendorName,ContactNumber,EmailId,MailStatus,VendorStatus,RecStatus,LastUpdateOn,UpdatebyID,CorporateStatus,
  PanNo,
  ServiceTaxNo,
  Address1,
  Address2,
  Town_City,
  Distt,
  State,
  Pin,
  ACHolderName,
  ACNumber,
  BankName,
  IFSC,
  BankCity_Town,
  PoolACNo,
  PoolACIFSC,
  Refferedby,
  ReferalName,
  ApplicationDate,keypersonName,Adddress,MobileNo, keyPersonEmailId,Locationtype)values('$vendorcode','$distributorName','$MobileNo','$Emailid','N','P','A','$date','$Name','$corpotateAdd','$panno','$ServiecTaxno','$Adress1','$Address2','$Town','$Distt','$state','$pin','$Acholdername','$accountNo','$bankName','$Ifsc','$bankcityTown','$PoolACNo','$PoolACIfsc','$refby','$RefrealName','$date','$keyPersonName','$keyPersonAddress','$keypersonMobile','$keypersonEmail','$lt')";
  
  
  //echo "herer---->".$query;
	//echo "<br><br/>errrorrr ------->".mysqli_error($con);
 if(mysqli_query($con,$query))
 {
	// echo "<br><br/>I m inside if condition ------->".mysqli_error($con);
	// echo "i am herre now";die();
	  $result = "SELECT MAX(vendorId) FROM tblvendorregistration";
	  $query=mysqli_query($con,$result);
      $row = mysqli_fetch_array($query);
	  $highest_id=$row[0]; 
	  	 
/*insert vendorlocation*/
$insertVendorLocation1="insert into tblvendorlocation(LocationId,LastUpdateOn,UpdatebyId,NumberOfCenter,SPOCName,MobileNo,Vendorid,RecStatus,rate)values('$distname1','$date','$Name','$centerno1','$spocname1','$mobile1','$highest_id','A','$rate')";
mysqli_query($con,$insertVendorLocation1);

/*$insertVendorLocation2="insert into tblvendorLocation(LocationId,LastUpdateOn,UpdatebyId,NumberOfCenter,SPOCName,MobileNo,Vendorid,RecStatus)values('$distname2','$date','$Name','$centerno2','$spocname2','$mobile2','$highest_id','A')";
mysqli_query($con,$insertVendorLocation2);*/

/*end vendorlocation*/ 
		 
	
	
	 /*insert pancardproof*/
	 
	$ImageName = $_FILES['file']['name'];
	$fileElementName = 'pancard';
    $path = '../Proof_img/'; 

/////////////////////
$file_name_to_save1=explode('.',$_FILES["file"]["name"]);
	$file_name_final1=$file_name_to_save1[0].'_'.date('mdYHis').'.'.$file_name_to_save1[1];


/////////////////////
$locationimg = $path . $file_name_final1;


    move_uploaded_file($_FILES['file']['tmp_name'], $locationimg);
	$documentQuery="insert into tblvendordocument(Vendorid,Documentname,DocumentUrl,RecStatus,LastUpdateOn,UpdatebyId)values('$highest_id','PanCard','$locationimg','A','$date','$Name')";
	 mysqli_query($con,$documentQuery);
	 /*end pancardprooff*/
	 
	  /*insert securitydeposit*/
	 
	$ImageName = $_FILES['file1']['name'];
    $fileElementName = 'security';
    $path = '../Proof_img/'; 

/////////////////////
$file_name_to_save2=explode('.',$_FILES["file1"]["name"]);
	$file_name_final2=$file_name_to_save2[0].'_'.date('mdYHis').'.'.$file_name_to_save2[1];


/////////////////////
$secuityimg = $path . $file_name_final2;



    move_uploaded_file($_FILES['file1']['tmp_name'], $secuityimg);
	$securityQuery="insert into tblvendordocument(Vendorid,Documentname,DocumentUrl,RecStatus,LastUpdateOn,UpdatebyId)values('$highest_id','Security','$secuityimg','A','$date','$Name')";
	 mysqli_query($con,$securityQuery);
	 /*end securitydeposit*/
	 
	   /*insert addressproffdoc*/
	 
	$ImageName = $_FILES['file2']['name'];
    $fileElementName = 'address';
    $path = '../Proof_img/'; 

/////////////////////
$file_name_to_save3=explode('.',$_FILES["file2"]["name"]);
	$file_name_final3=$file_name_to_save3[0].'_'.date('mdYHis').'.'.$file_name_to_save3[1];


/////////////////////
$addressimg = $path . $file_name_final3;


    
    move_uploaded_file($_FILES['file2']['tmp_name'], $addressimg);
	$addressQuery="insert into tblvendordocument(Vendorid,Documentname,DocumentUrl,RecStatus,LastUpdateOn,UpdatebyId)values('$highest_id','Address','$addressimg','A','$date','$Name')";
	 mysqli_query($con,$addressQuery);
	 /*end addressproffdoc*/
	 
	   /*insert payment*/
	   if($modeofDeposit=="OTHER")
	   {
	   	 $insertPayment="insert into tblpayment(VendorId,Amount,ModeOfPayment,TaxnRefNo,PaymentDate,PaymentType,FinanceVerification,LastUpdateOn,UpdatebyId,RecStatus,PaymentProof,depositbankname)values('$highest_id','$Amount','$otherpaymentreason','$TxnNo','$dateOfDeposit','S','P','$date','$Name','A','$secuityimg','$bankName')";
	   mysqli_query($con,$insertPayment);
	   }
	   else
	   {
		   $insertPayment="insert into tblpayment(VendorId,Amount,ModeOfPayment,TaxnRefNo,PaymentDate,PaymentType,FinanceVerification,LastUpdateOn,UpdatebyId,RecStatus,PaymentProof,depositbankname)values('$highest_id','$Amount','$modeofDeposit','$TxnNo','$dateOfDeposit','S','P','$date','$Name','A','$secuityimg','$bankName')";
	   mysqli_query($con,$insertPayment);
	   }
	   
	   /*end payment*/	 
	 
	   /*insert cancelCheque*/
	 
	$ImageName = $_FILES['file3']['name'];
    $fileElementName = 'cancel';
    $path = '../Proof_img/'; 

/////////////////////
$file_name_to_save4=explode('.',$_FILES["file3"]["name"]);
	$file_name_final4=$file_name_to_save4[0].'_'.date('mdYHis').'.'.$file_name_to_save4[1];


/////////////////////
$cancelimg = $path . $file_name_final4;


   // $cancelimg = $path . $_FILES['file3']['name']; 
    move_uploaded_file($_FILES['file3']['tmp_name'], $cancelimg);
	$chequeQuery="insert into tblvendordocument(Vendorid,Documentname,DocumentUrl,RecStatus,LastUpdateOn,UpdatebyId)values('$highest_id','Cheque','$cancelimg','A','$date','$Name')";
	 mysqli_query($con,$chequeQuery);
	 /*end cancelCheque*/
	 
	  /*insert servicetax*/
	 
	$ImageName = $_FILES['file4']['name'];
	$fileElementName = 'servicetax';
    $path = '../Proof_img/'; 

/////////////////////
$file_name_to_save5=explode('.',$_FILES["file4"]["name"]);
	$file_name_final5=$file_name_to_save5[0].'_'.date('mdYHis').'.'.$file_name_to_save5[1];


/////////////////////
$locationimg = $path . $file_name_final5;




    move_uploaded_file($_FILES['file4']['tmp_name'], $locationimg);
	$documentQuery="insert into tblvendordocument(Vendorid,Documentname,DocumentUrl,RecStatus,LastUpdateOn,UpdatebyId)values('$highest_id','PanCard','$locationimg','A','$date','$Name')";
	 mysqli_query($con,$documentQuery);
	 /*end servicetax*/ 
	 
	 /*create user*/
	 
	 $createUser="update tbluser set userid='$Emailid',password='$encryptpass',FinanceVerificationStatus='P',RecStatus='A',LastUpdateOn='$date',UpdatebyId='$Name' where refid='$highest_id' and UserTypes='VN'";
	 mysqli_query($con,$createUser);
	 
	 /*end user*/
	 			   
			   
	$updateStateUnit="update tblstate set lastunitvendor='$incremntStateUnit' where stateid='$statid'";
	$updateStateqry=mysqli_query($con,$updateStateUnit);
	
	$updateCityUnit="update tblcity set lastunitvendor='$incrementCityUnit' where cityid='$cityid'";
	$updatecityqry=mysqli_query($con,$updateCityUnit);
			   
			  
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
								/*$mail->SMTPDebug  = 2;    */              // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

								$mail->SetFrom('projectled@iapcorp.com', 'OpreationTeam');
								$mail->AddReplyTo('projectled@iapcorp.com','OpreationTeam');


								$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
								$mail->Subject    = "Distributor Registration Alert";
								$mail->Body = "<h3>Dear Accounts Team</h3>
												</br>Distributor:
												</br><p>Distributor Name: $distributorName</p>
												</br><p>Distributor Code: $vendorcode</p>
												</br><p>has been created in the system.</p>
												</br><p>Please validate the document proof and security money associated with the distributor and send confirmation on it</p>
												<br><br><br>
												<h4>Regards</h4>
												<p>Operation Team</p><br>
												";
															
				foreach($records as $rec)
				{	  
					$romail=$rec['RO'];
					$adminmail=$rec['SuperAdmin'];
					$finmail=$rec['Finance'];				
					
					$mail->AddAddress($finmail);
					$mail->addCC($romail);
					$mail->addCC($adminmail);				
					$mail->Send();
					$mail->ClearAllRecipients();
				}
				if(!$mail->Send()) {  								
					echo "<script>alert('Distributor Registered Successfully')</script>";
					} else {
						echo "<script>alert('Distributor Registered Successfully')</script>";
					}
	 
	 
	 
	 
	   
	 
	 

 }
 else
 {
		
	 echo "<script>alert('Distributor Not Registered')</script>";
	 //echo "<br/>i am ttttttttttttthhhhh now==>".mysqli_error($con);die();
 }
	}
	}
	

else
{
	$getlastRecord="SELECT VendorCode,Vendorname FROM tblvendorregistration WHERE recstatus='A' AND vendorid=(SELECT MAX(vendorid)AS vid FROM tblvendorregistration WHERE recstatus='A')";
			$getlasquery=mysqli_query($con,$getlastRecord);
			$lastid = mysqli_fetch_array($getlasquery);
			
			$distributorName=$lastid['Vendorname'];
			$vendorcode=$lastid['VendorCode'];
}



if(isset($_POST['locsubmit']))
{
	
	$date=date('Y-m-d H:i:s');
	$Currentdate=date('d-m-Y');
	$CurrentdateModi=date('dmY');
	
	$distname1=$_POST['Distname'];
	$centerno1=$_POST['noofcenter'];
	$spocname1=$_POST['spocname'];
	$mobile1=$_POST['mobile'];
	$sellrate=$_POST['rate'];
	
	
		
			$getlastRecord="SELECT MAX(vendorid)AS vid,Locationtype FROM tblvendorregistration where recstatus='A'";
			$getlasquery=mysqli_query($con,$getlastRecord);
			$lastid = mysqli_fetch_array($getlasquery);
			$maxid=$lastid['vid'];
			$getlastRecordLoctype="SELECT Locationtype FROM tblvendorregistration where recstatus='A' and vendorid='$maxid'";
			$getlasqueryloctype=mysqli_query($con,$getlastRecordLoctype);
			$lastidloctype = mysqli_fetch_array($getlasqueryloctype);
			$loctype=$lastidloctype['Locationtype'];
			if($loctype=='M')
			{
			
			$insertVendorLocation1="insert into tblvendorlocation(LocationId,LastUpdateOn,UpdatebyID,NumberOFCenter,SPOCName,MobileNo,VendorId,RecStatus,rate) values('$distname1','$date','$Name','$centerno1','$spocname1','$mobile1','$maxid','A','$sellrate')";

if(mysqli_query($con,$insertVendorLocation1))
{
			echo "<script>alert('WorkStation Created Successfully')</script>";
			echo "<script>window.open('VendorList.php','_self')</script>";
}
else
{
	echo "<script>alert('WorkStation Not Created')</script>";
}
			
		}
		
		else
		{
			echo "<script>alert('Distributor Registered only for single location')</script>";
		}

		
	
		}
		
		
else
	{
		
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

    <title>Distributor Registration</title>

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
    <script src="js/datetimepicker_css.js"></script>

    <script src="js/jquery.min.js"></script>
    
    <script type="text/javascript">

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



function ValidateAlpha(evt)
    {
        var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32); 
		
         
			
    }
	
</script>

<script type="text/javascript">	
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

function getrate(rate) {
	
	$.ajax({
		
	type: "POST",
	url: "getRate.php",
	data:'sid='+rate,
	success: function(data){
	$("#sellrate").val(data);
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
                                        <li><a href="masterIssue.php">Issue</a></li>
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
                                                                     
                                    <li><a href="ChangePassword.php"><i class="fa fa-cog pull-right"></i>Change Password</a>
                                    </li>
                                    <li><a href="../Logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
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
						
						
						
						
						
                    </script>
					
                    <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                     <form class="form-horizontal form-label-left" action="ResultVendorRegistration.php" method="post" onSubmit="return chechform1();">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Distributor  Registration</h2>
                                    </div>
                                    
                                      
                                      
                                    </ul>
                                    
                                    <div class="clearfix"></div>
                                </div>
                      <div class="col-md-8 col-xs-12 col-md-offset-2">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Distributor Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                
                                <div class="x_content">
                                   
                                   <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Code</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             <label class="col-md-10 col-sm-4 col-xs-12"><?php echo $vendorcode;?></label>
                                            </div>
                                        </div>
                                   

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Distributor Name<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <label class="col-md-4 col-sm-4 col-xs-12"><?php echo $distributorName;?></label>
                                            </div>
                                        </div>
                                        
                                        
                                      
                                        
                                     
                                        
                                       <div class="form-group">
                                            <div class="col-md-10 col-sm-9 col-xs-12 col-md-offset-4">
                                            
                                                                                     
                                            
                                                          
                
          <a href="VendorRegistration.php" class="btn btn-success">Go Back</a>                      
              
                                            </div>
                                        </div>
                                     
									
                                    	
                                       
                                        <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>WorkStation Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                         <!--<div class="clearfix"><b>WorkStation 1</b></div>-->
                                        
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">State Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select id="Distname1" name="stateName" required="required" onChange="getWSDistt(this.value); getrate(this.value);" class="select group form-control">
                                                 <option value="0">Select State</option>
                                                 <?php
												    $query12="SELECT Stateid,StateName,StateCode FROM tblstate WHERE recstatus='a'";
                                                    $msql1=mysqli_query($con,$query12);
                                                     while($m_row1 = mysqli_fetch_array($msql1)) 
                                                        {
										                      
											              echo "<option value='".$m_row1['Stateid']."'>".$m_row1['StateName']."</option>";
													    }
														?> 
                                                 </select>
                                            </div>
                                        </div>
                                        
                                          <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Sell Rate</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="rate" onpaste="return false" class="form-control" autocomplete="off" id="sellrate" maxlength="6" readonly >
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                         
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">District Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select id="cname" name="CityName22" onChange="getLocation(this.value);" class="select group form-control" required="required">
                                                  <option value="0">Select District Name</option>
                                                 </select>
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Location Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select id="Distname" name="Distname" class="select group form-control" required="required">
                                                 <option value="0">Select Location</option>
                                                 </select>
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">No.Of Centers <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               
                                                <input id = "noofcenter" onkeypress="return isNumberKey(event)" required name = "noofcenter" type = "text" class="txtNumeric form-control" placeholder="Enter No. Of Centers" maxlength="3" >
                                                
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">SPOC Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            
                                           <input id = "spocname" name = "spocname" type = "text" required class="form-control" placeholder="Enter SPOC Name" maxlength="50">
                                                <!-- <input type="text" id="spoc" class="form-control" placeholder="Default Input">-->
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mobile Number <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input id = "mobile" required name = "mobile" type= "text" class="txtNumeric form-control" placeholder="Enter WorkStation Mobile No."  maxlength="10" onkeypress="return isNumberKey(event)">
                                               
                                            </div>
                                        </div>
            
                                </div>
                            </div>
                        </div>
                   




      


                                        
                                        <div class="form-group">
                                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            
                                                                                     
                                            
                                                          <input type="submit" name="locsubmit" class="btn btn-primary" value="Submit">
                                               
                                                
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

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
   
			if(isset($_GET['id']))
			{
	  
				$issueslipid= $_GET['id'];
				$fetchvendorid="select IssueslipId,vendorid,Issuecode,DATE_FORMAT(issuedate,'%d-%m-%Y')as id from tblissueslip where IssueslipId='$issueslipid'";
				$q=mysqli_query($con,$fetchvendorid);
				$row3=mysqli_fetch_array($q);
				$isid=$row3['IssueslipId'];
				$vid=$row3['vendorid'];
				$iscode=$row3['Issuecode'];
				$isdate=$row3['id'];
  
				$fetchVendorName1="select vendorname,emailid from tblvendorregistration where vendorid='$vid'";
				$fetchvname1=mysqli_query($con,$fetchVendorName1);
				$row4 = mysqli_fetch_array($fetchvname1);
				$vendorName1=$row4['vendorname']; 
  
			}
                       // echo "herere--><pre>";print_r($_POST);
			if(isset($_POST['getinfo']))
			{
  
				$customerid=$_POST['vid'];//$vid
				$slipid=$_POST['isid'];//$isid
				
				$selectvendorlocatio="SELECT count(tblvendorlocation.locationid) as lid,tblissueslip.locationid,tbllocation.locationname FROM tbllocation,tblissueslip,tblvendorlocation 
WHERE tblissueslip.locationid=tblvendorlocation.locationid AND tblvendorlocation.locationid AND tblvendorlocation.locationid=tbllocation.locid  AND tblissueslip.issueslipid='$slipid' AND tbllocation.recstatus='A' AND tblissueslip.recstatus='A'";
				$resultloc=mysqli_query($con,$selectvendorlocatio);
				$finalresultloc=mysqli_fetch_array($resultloc);
				if($finalresultloc['lid']>0)
				{
				  $locationname=$finalresultloc['locationname'];
				}
				else
				{
					$locationname="";
				}
				
				
				
				
 
				$fetchVendorName="select vendorname,emailid from tblvendorregistration where vendorid='$customerid'";
				$fetchvname=mysqli_query($con,$fetchVendorName);
				$row = mysqli_fetch_array($fetchvname);
				$vendorName=$row['vendorname'];
				$email=$row['emailid'];
				  
				$walletbalance="select balance,count(vendorid)as vid from tblwallet where vendorid='$customerid'";
				$query=mysqli_query($con,$walletbalance);
				while ($row = mysqli_fetch_array($query))	  
				{
					if($row['vid']>0)
					{
						$balance=$row[0];
					}
					else
					{
						$balance=0;
					}
		 
				}
	  
				$stockElig="select DailyLimit,count(vendorid)as venid from tblvendoreligibility where vendorid='$customerid'";
	  
				$queryElig=mysqli_query($con,$stockElig);
				while ($row = mysqli_fetch_array($queryElig))	  
				{
					if($row['venid']>0)
					{
			  
						$eligibility=$row[0];
						$actualQuantity=$eligibility/50;
					}
					else
					{
						$eligibility=$row[0];
					}
				}
	  
				$Rate="SELECT tblstate.statename,tblstate.sellrate,tbllocation.locationname,tblcity.cityname FROM tbllocation,tblvendorlocation,tblcity,tblstate,tblissueslip 
WHERE tblvendorlocation.locationid=tbllocation.locid AND tbllocation.cityid=tblcity.cityid AND tblstate.stateid=tblcity.stateid AND tblissueslip.locationid=tblvendorlocation.locationid AND tblissueslip.issueslipid='$slipid' AND tblstate.recstatus='A'
";
				$masterRate=mysqli_query($con,$Rate);
				$row = mysqli_fetch_array($masterRate); 
				
				$OnePieceRate=$row['sellrate'];
				
				$issuedquantity=intval($balance/$OnePieceRate);
	  
				$checkinInventory="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$customerid' and TXNTypein_out='IN'";
				$inStatus=mysqli_query($con,$checkinInventory);
		  
				while ($row = mysqli_fetch_array($inStatus))	  
				{
					$inQuantity=$row['Quantity'];
				}
			 
				$checkoutInventory="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$customerid' and TXNTypein_out='OUT'";
				$outStatus=mysqli_query($con,$checkoutInventory);
				while ($row = mysqli_fetch_array($outStatus))	  
				{
				 
					$outQuantity=$row['Quantity'];
				}
				
				
				$checkoutInventory1="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$customerid' and TXNTypein_out='R'";
				$outStatus1=mysqli_query($con,$checkoutInventory1);
				while ($row1 = mysqli_fetch_array($outStatus1))	  
				{
				 
					$outQuantity1=$row1['Quantity'];
				}
				
				
				$currentStockPosition=$inQuantity-$outQuantity+$outQuantity1;
			 
			
			
				$pendingIssue="select count(vendorId) as vendorId,sum(Quantity)as Quantity from tblissueslip where vendorid='$customerid' and IssueStatus='P'";
				$pissue=mysqli_query($con,$pendingIssue);
				while ($row = mysqli_fetch_array($pissue))	  
				{
					if($row['vendorId']>0)
					{
						$pending=$row['Quantity'];
					}
					else
					{
						$pending=0;
					}
				
		
				}
			 
				$claimIssue="select count(vendorId) as distributorId,sum(Quantity)as Quantity from tblissueslip where vendorid='$customerid' and IssueStatus='C'";
				$cissue=mysqli_query($con,$claimIssue);
				while ($row = mysqli_fetch_array($cissue))	  
				{
					if($row['distributorId']>0)
					{ 
						$claimed=$row['Quantity'];
					}
					else
					{
						$claimed=0;
					}
					$totalPendingissue=$pending+$claimed;
				
					$totalPendingCurrentStock=$totalPendingissue+$currentStockPosition;
					$grandtotalqant=$eligibility-$totalPendingCurrentStock;
				
				
				
			
			
				}
				$selectwhdetail="SELECT tblwarehouseregistration.warehousecode,tblissueslip.quantity,tbllocation.locationname FROM tblwarehouseregistration,tblissueslip,tbllocation WHERE tblwarehouseregistration.warehouseid=tblissueslip.warehouseid AND tblwarehouseregistration.recstatus='A' AND tblwarehouseregistration.warehouselocationid=tbllocation.locid
AND tblissueslip.issueslipid='$slipid'";
				$whquery=mysqli_query($con,$selectwhdetail);
				$whrow=mysqli_fetch_array($whquery);
				$whname=$whrow['warehousecode'];
				$whloc=$whrow['locationname'];
				$iqty=$whrow['quantity'];
			 
			
			 
			 
			}
			else
			{
                                $customerid=$vid;
				$slipid=$isid;
				
				$selectvendorlocatio="SELECT count(tblvendorlocation.locationid) as lid,tblissueslip.locationid,tbllocation.locationname FROM tbllocation,tblissueslip,tblvendorlocation 
WHERE tblissueslip.locationid=tblvendorlocation.locationid AND tblvendorlocation.locationid AND tblvendorlocation.locationid=tbllocation.locid  AND tblissueslip.issueslipid='$slipid' AND tbllocation.recstatus='A' AND tblissueslip.recstatus='A'";
				$resultloc=mysqli_query($con,$selectvendorlocatio);
				$finalresultloc=mysqli_fetch_array($resultloc);
				if($finalresultloc['lid']>0)
				{
				  $locationname=$finalresultloc['locationname'];
				}
				else
				{
					$locationname="";
				}
				
				
				
				
 
				$fetchVendorName="select vendorname,emailid from tblvendorregistration where vendorid='$customerid'";
				$fetchvname=mysqli_query($con,$fetchVendorName);
				$row = mysqli_fetch_array($fetchvname);
				$vendorName=$row['vendorname'];
				$email=$row['emailid'];
				  
				$walletbalance="select balance,count(vendorid)as vid from tblwallet where vendorid='$customerid'";
				$query=mysqli_query($con,$walletbalance);
				while ($row = mysqli_fetch_array($query))	  
				{
					if($row['vid']>0)
					{
						$balance=$row[0];
					}
					else
					{
						$balance=0;
					}
		 
				}
	  
				$stockElig="select DailyLimit,count(vendorid)as venid from tblvendoreligibility where vendorid='$customerid'";
	  
				$queryElig=mysqli_query($con,$stockElig);
				while ($row = mysqli_fetch_array($queryElig))	  
				{
					if($row['venid']>0)
					{
			  
						$eligibility=$row[0];
						$actualQuantity=$eligibility/50;
					}
					else
					{
						$eligibility=$row[0];
					}
				}
	  
				$Rate="SELECT tblstate.statename,tblstate.sellrate,tbllocation.locationname,tblcity.cityname FROM tbllocation,tblvendorlocation,tblcity,tblstate,tblissueslip 
WHERE tblvendorlocation.locationid=tbllocation.locid AND tbllocation.cityid=tblcity.cityid AND tblstate.stateid=tblcity.stateid AND tblissueslip.locationid=tblvendorlocation.locationid AND tblissueslip.issueslipid='$slipid' AND tblstate.recstatus='A'
";
				$masterRate=mysqli_query($con,$Rate);
				$row = mysqli_fetch_array($masterRate); 
				
				$OnePieceRate=$row['sellrate'];
				
				$issuedquantity=intval($balance/$OnePieceRate);
	  
				$checkinInventory="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$customerid' and TXNTypein_out='IN'";
				$inStatus=mysqli_query($con,$checkinInventory);
		  
				while ($row = mysqli_fetch_array($inStatus))	  
				{
					$inQuantity=$row['Quantity'];
				}
			 
				$checkoutInventory="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$customerid' and TXNTypein_out='OUT'";
				$outStatus=mysqli_query($con,$checkoutInventory);
				while ($row = mysqli_fetch_array($outStatus))	  
				{
				 
					$outQuantity=$row['Quantity'];
				}
				
				
				$checkoutInventory1="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$customerid' and TXNTypein_out='R'";
				$outStatus1=mysqli_query($con,$checkoutInventory1);
				while ($row1 = mysqli_fetch_array($outStatus1))	  
				{
				 
					$outQuantity1=$row1['Quantity'];
				}
				
				
				$currentStockPosition=$inQuantity-$outQuantity+$outQuantity1;
			 
			
			
				$pendingIssue="select count(vendorId) as vendorId,sum(Quantity)as Quantity from tblissueslip where vendorid='$customerid' and IssueStatus='P'";
				$pissue=mysqli_query($con,$pendingIssue);
				while ($row = mysqli_fetch_array($pissue))	  
				{
					if($row['vendorId']>0)
					{
						$pending=$row['Quantity'];
					}
					else
					{
						$pending=0;
					}
				
		
				}
			 
				$claimIssue="select count(vendorId) as distributorId,sum(Quantity)as Quantity from tblissueslip where vendorid='$customerid' and IssueStatus='C'";
				$cissue=mysqli_query($con,$claimIssue);
				while ($row = mysqli_fetch_array($cissue))	  
				{
					if($row['distributorId']>0)
					{ 
						$claimed=$row['Quantity'];
					}
					else
					{
						$claimed=0;
					}
					$totalPendingissue=$pending+$claimed;
				
					$totalPendingCurrentStock=$totalPendingissue+$currentStockPosition;
					$grandtotalqant=$eligibility-$totalPendingCurrentStock;
				
				
				
			
			
				}
				$selectwhdetail="SELECT tblwarehouseregistration.warehousecode,tblissueslip.quantity,tbllocation.locationname FROM tblwarehouseregistration,tblissueslip,tbllocation WHERE tblwarehouseregistration.warehouseid=tblissueslip.warehouseid AND tblwarehouseregistration.recstatus='A' AND tblwarehouseregistration.warehouselocationid=tbllocation.locid
AND tblissueslip.issueslipid='$slipid'";
				$whquery=mysqli_query($con,$selectwhdetail);
				$whrow=mysqli_fetch_array($whquery);
				$whname=$whrow['warehousecode'];
				$whloc=$whrow['locationname'];
				$iqty=$whrow['quantity'];
				/*$currentStockPosition=0.00;
				$issuedquantity=0.00;
				$balance=0.00;
				$eligibility=0.00;
				$totalPendingissue=0.00;
				$vendorName="";
				$v="";
				$whname="";
				$whloc="";
				$locationname="";*/
			}
	 
			if(isset($_POST['ok']))
			{
				$vendorid=$_POST["vid"];
				$actualrate1=$_POST["acrate"];
		  
				$issuslipid=$_POST["isid"];
				$quant=$_POST["Quantity"];
				$balance1=$_POST["bal"];
				$date=date('Y-m-d H:i:s');
				$currentdate=date('d-m-Y');
				$Vendoremail=$_POST['em'];
				$iqthid=$_POST['qtyhid'];
		   
				$eligibility=$_POST['elg'];
				$totalPendingCurrentStock=$_POST['tps'];
				$grandtotalqant=$_POST['gtq'];
				$issuedquantity=$_POST['isquant'];
		   
		  
			
			
			
				if($quant<$iqthid)
				{
					$total=$quant*$actualrate1;
					$diffrenceqty=$iqthid-$quant;
					$curretwalbal=$diffrenceqty*$actualrate1;
					$totalcurrentwallbal=$balance1+$curretwalbal;
		
			
					$insertIssueSlip= "update tblissueslip set Quantity='$quant',TotalAmount='$total',LastUpdateOn='$date',UpdatebyId='$UserId' where IssueSlipid='$issuslipid'";
					$updatewallet="update tblwallet set balance='$totalcurrentwallbal',LastUpdateOn='$date',UpdatebyId='$UserId' where vendorid='$vendorid'";
					if(mysqli_query($con,$updatewallet))
					{
						if(mysqli_query($con,$insertIssueSlip))
						{
							echo "<script>alert('Issueslip updated successfully')</script>";				 
							echo "<script>window.open('IssueslipList.php','_self')</script>";
						}
						else
						{
							echo "<script>alert('Issueslip not updated )</script>";
							echo "<script>window.open('IssueslipList.php','_self')</script>";
						}
			 
			
					}
					else
					{
						echo "<script>alert('Issueslip updated successfully')</script>";
						echo "<script>window.open('IssueSlipList.php','_self')</script>";
					}

				}
			
				else if($quant==$iqthid)
				{
					$total=$quant*$actualrate1;
					$diffrenceqty=$iqthid-$quant;
					$curretwalbal=$diffrenceqty*$actualrate1;
					$totalcurrentwallbal=$balance1+$curretwalbal;
		
			
					$insertIssueSlip= "update tblissueslip set Quantity='$quant',TotalAmount='$total',LastUpdateOn='$date',UpdatebyId='$UserId' where IssueSlipid='$issuslipid'";
					$updatewallet="update tblwallet set balance='$totalcurrentwallbal',LastUpdateOn='$date',UpdatebyId='$UserId' where vendorid='$vendorid'";
					if(mysqli_query($con,$updatewallet))
					{
						if(mysqli_query($con,$insertIssueSlip))
						{
							echo "<script>alert('Issueslip updated successfully')</script>";
							echo "<script>window.open('IssueSlipList.php','_self')</script>";
				 
						}
						else
						{
							echo "<script>alert('Issueslip not updated )</script>";
							echo "<script>window.open('IssueSlipList.php','_self')</script>";
						}
			 
			
					}
					else
					{
						echo "<script>alert('Issueslip updated successfully')</script>";
						echo "<script>window.open('IssueSlipList.php','_self')</script>";
					}

				}
			
			 
			
				else
				{
					$total=$quant*$actualrate1;
					$diffrenceqty=$quant-$iqthid;
					$curretwalbal=$diffrenceqty*$actualrate1;
					$totalcurrentwallbal=$balance1-$curretwalbal;
					$insertIssueSlip= "update tblissueslip set Quantity='$quant',TotalAmount='$total',LastUpdateOn='$date',UpdatebyId='$UserId' where IssueSlipid='$issuslipid'";
					$updatewallet="update tblwallet set balance='$totalcurrentwallbal',LastUpdateOn='$date',UpdatebyId='$UserId' where vendorid='$vendorid'";
		  
					if($quant>$eligibility)
					{  
		  		
						echo "<script>alert('Quantity could not be greater then defined eligibility')</script>";
						echo "<script>window.open('IssueslipList.php','_self')</script>";
			  
					}
		  
		   
					else if($quant>$issuedquantity)
					{
						echo "<script>alert('Quantity could not be greater wallet balance Amount')</script>";
						echo "<script>window.open('IssueslipList.php','_self')</script>";
					}
		 
		  
		 		  
					else if($totalPendingCurrentStock>$eligibility)
					{
						echo "<script>alert('Quantity could not be greater than stock limit!Pleae check pending issue')</script>";
					}
			  
					else if($quant>$grandtotalqant)
					{
						echo "<script>alert('Your Stock is full please check Stock or pending issue')</script>";
					}
		 
		  
		  
		  
		 
	
				else
				{
		  
					if(mysqli_query($con,$updatewallet))
					{
			  
						mysqli_query($con,$insertIssueSlip);
						$selectMail="SELECT RO,SuperAdmin,Finance FROM tblmailinglist WHERE RecStatus='A'";
						$q=mysqli_query($con,$selectMail);
						$row2 = mysqli_fetch_array($q);	  
						$romail=$row2['RO'];
						$adminmail=$row2['SuperAdmin'];
						$finmail=$row2['Finance'];
						echo "<script>alert('Issueslip updated successfully')</script>";								
						echo "<script>window.open('IssueslipList.php','_self')</script>";
					}
		  
					else
					{
						echo "<script>alert('Issueslip not updated')</script>";
						echo "<script>window.open('IssueSlipList.php','_self')</script>";
					}
		  
				}
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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Issue Slip</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

   

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">

    <script src="js/jquery.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
	<script type="text/javascript">
  	function AllowNumbersOnly(e) {
    	var code = (e.which) ? e.which : e.keyCode;
		var bla = $('#OnlyNumbers').val();		
		var fix = $('#fixvalue').val();		
    	if (code > 31 && (code < 48 || code > 57)) {
			alert('Only numeric allowed');
      	e.preventDefault();
    	}
		else
		{
			
		}			
  	}
	
	function calc() {
    var textValue1 = document.getElementById('OnlyNumbers').value;
    var textValue2 = document.getElementById('fixvalue').value;
    document.getElementById('ActualQuantity').value = textValue1 * textValue2;
	
	
	
}
</script>

<script>

function checkelig()
{
	
	var quant=document.getElementById('ActualQuantity').value;
	var eligibility=document.getElementById('elg1').value;
	var isdqunt=document.getElementById('isquant1').value;
	
	var totpenstock=document.getElementById('tps1').value;
	var grandtotqant=document.getElementById('gtq1').value;
	var acqty=document.getElementById('qtyhid1').value;
	var quant1=parseInt(quant);
	var eligibility1=parseInt(eligibility);
	var isdqunt1=parseInt(isdqunt);
	var totpenstock1=parseInt(totpenstock);
	var grandtotqant1=parseInt(grandtotqant);
	var acqty1=parseInt(acqty);
	
	
	
	if(quant1<=acqty1)
	{
		
		return true;
	}
	
	if(quant1>eligibility1)
	{
		
		alert('Quantity could not be greater then defined eligibility');
		return false;
	}
	
	if(quant1>isdqunt1)
	{
		
		alert('Quantity could not be greater wallet balance Amount');
		return false;
	}
	if(totpenstock1>eligibility1)
	{
		alert('Quantity could not be greater than stock limit!Pleae check pending issue');
		return false;
	}
	if(quant1>grandtotqant1)
	{
		alert('Your Stock is full please check Stock or pending issue');
		return false;
	}
	if(quant==0)
	{
		alert('!Quantiy could not be zero');
		return false;
	}
	else
	{
		return true;
	}
	
}

</script>
    
    
    
    
    
    
    <SCRIPT language=JavaScript>
function getData(value){
var vendorId=document.getElementById("country-list").selectedIndex.value=value;
document.location="issueslip.php?test=" + vendorId;

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
                                       <li><a href="vendordamagelist.php">Distributor </a>
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
                                             <input type="hidden" name="isid" value="<?php $issueslipid;?>" />
										
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
                                                                    
                                   
                                   <li><a href="../Logout.php"><i class="fa fa-sign-out pull-right"></i>Log Out</a>
                                    </li>
									<li><a href="#"><i class="fa fa-cog pull-right"></i>Change Password</a>
                                    </li>
                                </ul>
                            </li>

                         

                        </ul>
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->


            <!-- page content -->
            <!--<div class="right_col" role="main">

                <!-- top tiles -->
               
                            </div>
                            
                            <div class="right_col" role="main">

                <!-- top tiles -->
                


<div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                 <div class="x_title"style="color:#FFF; background-color:#889DC6">
                                    <h2>Issue Slip</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                      
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                
                <!-- /top tiles -->

                
                            <!-- End to do list -->


                            <!-- start of weather widget -->
                          
						<div class="x_content">

                                    <form class="form-horizontal form-label-left" method="post">

                                       

                                        <div class="item form-group" style="display: none">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Select Distributor
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                              
                  <input type="text" name="distibutorname" readonly="readonly" class="form-control col-md-7" value="<?php echo $vendorName1;?>" />
                                              
                                                 
                                                 <input type="hidden" name="acrate" value="<?php echo $OnePieceRate;?>" />
                                        <input type="hidden" name="bal" value="<?php echo $balance;?>" />
        
                                                
                                            </div>
                                             <div class="col-md-3 col-sm-3 col-xs-12">
                                            <button type="submit" name="getinfo" id="getinfo" class="btn btn-success">Get Issueslip Detail</button>
                                             <input type="hidden" name="vid" value="<?php echo $vid;?>" />
                                             </div>
                                        </div>
                                        
                                        <div id="id1" style="border:2px solid;">
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Distributor Name
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $vendorName; ?>							<input type="hidden" name="em" value="<?php echo $email;?>" />
                                            
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Wallet Amount
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $balance ?>
                                                <input type="hidden" name="wb" value="<?php echo $balance ?>" /> 
                                            </label>
                                            </div>
                                        </div>
                                        
                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Stock Eligibility</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $eligibility; ?>
                                            </label>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Max.Alloted Quantity</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $issuedquantity; ?>
                                                 <input type="hidden" value="<?php echo $isid;?>" name="isid" />
                                            </label>
                                            </div>
                                        </div>
                                        
                                        
                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Current Stock</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $currentStockPosition; ?>
                                            </label>
                                            </div>
                                        </div>
                                        
                                        
                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pending Quantity</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                 <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"><?php echo $totalPendingissue;?>
                                            </label>
                                            </div>
                                        </div>

                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Location Name</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                               <label class="control-label col-md-4 col-sm-3 col-xs-12"><?php echo $locationname;?></label>
                                            </div>
                                        </div>


                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                               <label class="control-label col-md-4 col-sm-3 col-xs-12"><?php echo $isdate;?></label>
                                            </div>
                                        </div>
                                        
                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Warehouse Code
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label col-md-4 col-sm-3 col-xs-12" for="email"><?php echo $whname;?>
                                            </label>
                                            </div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Warehouse Location
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <label class="control-label col-md-4 col-sm-3 col-xs-12" for="email"><?php echo $whloc;?>
                                            </label>
                                            </div>
                                        </div>
                                        
                                        <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Quantity
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            
                                            
                                                 <!--<input type="text" id="email" name="txtQuantity" placeholder="Enter Quantity" required class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKey(event)" maxlength="6">-->

										<div class='col-lg-2 col-sm-2 col-xs-6'>
														<input type='text' name='Number' class='form-control input-sm' onpaste="return false" value="<?php echo $iqty/50;?>" onkeypress ='return AllowNumbersOnly(event)' id='OnlyNumbers' onkeyup='calc()' autocomplete='off'/>													
													</div>
													<div class='col-lg-2 col-sm-2 col-xs-6'>
														<input type='text' name='FixSize' class='form-control input-sm' value='50' readonly='readonly' id='fixvalue'/>													
													</div>
													<div class='col-lg-2 col-sm-2 col-xs-6'>
														<input type='text' name='Quantity' class='form-control input-sm' id='ActualQuantity' readonly='readonly' value="<?php echo $iqty;?>"/>													
													</div>
                                        
                                        
                                                 
                                                 
                                            </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        <input type="hidden" value="<?php echo $iqty;?>" id="qtyhid1" name="qtyhid" />
                                       
                                        <input type="hidden" value="<?php echo $eligibility?>" id="elg1" name="elg" />
                                         <input type="hidden" value="<?php echo $issuedquantity;?>" id="isquant1" name="isquant" />
                                          <input type="hidden" value="<?php echo $totalPendingCurrentStock;?>" id="tps1" name="tps" />
                                           <input type="hidden" value="<?php echo $grandtotalqant;?>" id="gtq1" name="gtq" />
                                       
                                       
                                       
                                       
                                       
                                        
                                     
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                            
                                                <button id="send" name="ok" type="submit" class="btn btn-primary" onclick="return checkelig();">Submit</button>
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
                        <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd
                           
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




</body>
</html>
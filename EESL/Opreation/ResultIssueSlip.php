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
    
     <link href="css/select/select2.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">
     <link rel="stylesheet" href="css/switchery/switchery.min.css" />
     <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    	<script type="text/javascript">
				function Validate () {						
						var WAmount = $("#txtWalletAmount").val();
						var totStockTxt = $("#totsstock").val();
						var gtStockTxt = $("#gtsstoc").val();
						var limitText = $("#textDailylimit").val();
						var maxQtyTxt = $("#txtMaxAllodqty").val();
						var allodQtyTxt = $("#textActQty").val();
						var Qty = $("#OnlyNumbers").val();
						var WA=parseInt(WAmount);
						var TPS=parseInt(totStockTxt);
						var GTS=parseInt(gtStockTxt);
						var DLT=parseInt(limitText);
						var MAQT=parseInt(maxQtyTxt);
						var AQ=parseInt(allodQtyTxt);
						if(Qty!="" && Qty!=0){						
							if(WA > 0){
								if(AQ <= DLT){
									if(AQ <= MAQT){
										if(TPS <= DLT){
											if(AQ <= GTS ){
												return true;
											}
											else{
												alert('Your Stock is full please check Stock or pending issue !');
												return false;
											}
										}
										else{
											alert('Quantity could not be greater than stock limit!Pleae check pending issue  !');
											return false;
										}										
									}
									else{
										alert('Quantity is grater then maximum allowed quantity  !');							
										return false;
									}
								}
								else{
									alert('Quantity is grater then stock eligibility  !');								
									return false;
								}							
							}
							else{
								alert('Wallet amount is Insufficient !! Please recharge your wallet !');
								return false;
							}
						}
						else{
								alert('Please insert quantity !');
								window.setTimeout(function ()
								{								
									$('#OnlyNumbers').focus();								
								}, 0);
            					return false;
						}
				}
			</script>
     
     
    <script src="../Designing/js/jquery.min.js"></script>				
    <script type="text/javascript">
	$(document).ready(function () {
  //called when key is pressed in textbox
  $("#OnlyNumbers").keypress(function (e) {
	  
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
  <script>
	function calc() {
    var textValue1 = document.getElementById('OnlyNumbers').value;
    var textValue2 = document.getElementById('fixvalue').value;
    document.getElementById('textActQty').value = textValue1 * textValue2;
	
	
	
}
</script>
     
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
                        <div class="menu_section"></div>
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
                               <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Create Issue Slip</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                      
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                
                 
                          
						<div class="x_content">
                        
                        	<?php
                            	if(isset($_POST['SaveIssueSlip']))
								{
									
									$StatId=$_POST['StateList'];
									
									$DistrictId=$_POST['DistricList'];
									$LocationId=$_POST['LocationList'];
									
									$DistributorId=$_POST['DistribtorList'];
									$WarehouseId=$_POST['WarehouseCodeList'];
									$IssuSelectDate=$_POST['SelectIssuDate'];
									$newDate = date("d-m-Y", strtotime($IssuSelectDate));
									$newMatchDate = date("Y-m-d", strtotime($IssuSelectDate));
									date_default_timezone_set("Asia/Kolkata"); 
   									$CurrentDate=date('d-m-Y');	
									$CodeDate=date('dmy');	
									
									if($newDate<=$CurrentDate)
									{
									if(!($StatId=='0' && $DistrictId=='0' && $LocationId=='0' && $DistributorId=='0' && $WarehouseId=='0'))
									{
										
										/*$getState="SELECT Stateid,StateName,StateCode FROM tblstate WHERE RecStatus='A' AND Stateid='$StatId'";
										$StateQuery=mysqli_query($con,$getState);
										$rowState=mysqli_fetch_array($StateQuery);										
										$statName=$rowState['StateName'];
										$statCode=$rowState['StateCode'];
										$getDistrict="SELECT CityId,CityName,CityCode FROM tblcity WHERE RecStatus='A' AND Stateid='$StatId' AND CityId='$DistrictId'";
										$DistrictQuery=mysqli_query($con,$getState);
										$rowDistrict=mysqli_fetch_array($StateQuery);										
										$DistricName=$rowDistrict['CityName'];
										$DistricCode=$rowDistrict['CityCode'];*/
										$selectDistributor="SELECT VendorId,VendorCode,VendorName,EmailId FROM tblvendorregistration WHERE RecStatus='A' AND VendorId='$DistributorId'";
										$runDistributor=mysqli_query($con,$selectDistributor);
										$rowDistributor=mysqli_fetch_array($runDistributor);
										
										$DId=$rowDistributor['VendorId'];
										$DCode=$rowDistributor['VendorCode'];
										$DName=$rowDistributor['VendorName'];
										$DEmail=$rowDistributor['EmailId'];
										
										$getDistributorLoc="SELECT ID,LocationId FROM tblvendorlocation WHERE RecStatus='A' AND LocationId='$LocationId'";
										$runDistributorLoc=mysqli_query($con,$getDistributorLoc);
										$rowDistributorLoc=mysqli_fetch_array($runDistributorLoc);
										$DistLocId=$rowDistributorLoc['ID'];
										$DistCurrentLocId=$rowDistributorLoc['LocationId'];
										
										$SelState="SELECT s.Stateid,s.StateName,s.StateCode,s.LastUnitWarehouse,d.CityId,d.CityName,d.CityCode,d.LastUnitWarehouse,l.LocId,l.LocationName,l.LocationCode
FROM tblstate AS s JOIN tblcity AS d ON s.Stateid=d.StateId JOIN tbllocation AS l ON d.CityId=l.CityId
WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' AND d.CityId='$DistrictId' AND s.Stateid='$StatId' AND LocId='$LocationId'";
									$RunSelState=mysqli_query($con,$SelState);
									$GetSelStateRow=mysqli_fetch_array($RunSelState);
									
									$statsId=$GetSelStateRow['Stateid'];	
									$stateName=$GetSelStateRow['StateName'];
									$stateCode=$GetSelStateRow['StateCode'];
									$StateWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$distId=$GetSelStateRow['CityId'];									
									$districName=$GetSelStateRow['CityName'];										
									$districCode=$GetSelStateRow['CityCode'];
									$DistWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$LocationId=$GetSelStateRow['LocId'];
									$LocationCode=$GetSelStateRow['LocationCode'];
									$LocationName=$GetSelStateRow['LocationName'];
									
									$GetWarehouse="SELECT WarehouseId,WareHouseCode,WareHouseName FROM tblwarehouseregistration WHERE RecStatus='A' AND WarehouseId='$WarehouseId'";
									$runWarehouse=mysqli_query($con,$GetWarehouse);
									$rowWarehouse=mysqli_fetch_array($runWarehouse);
									$WareId=$rowWarehouse['WarehouseId'];
									$WareCode=$rowWarehouse['WareHouseCode'];
									$whname=$rowWarehouse['WareHouseName'];
									
									$selectWallet="SELECT WalletId,Balance FROM tblwallet WHERE VendorId='$DistributorId'";
									
								
									$runWallet=mysqli_query($con,$selectWallet);
									$rowWallet=mysqli_fetch_array($runWallet);
									$WalletId=$rowWallet['WalletId'];
									$WalletAmmount=$rowWallet['Balance'];
									
									$selectLimit="SELECT Id,DailyLimit,TotalSecurity FROM tblvendoreligibility WHERE VendorId='$DistributorId'";
									$runLimit=mysqli_query($con,$selectLimit);
									$rowLimit=mysqli_fetch_array($runLimit);
									$LimitId=$rowLimit['Id'];
									$Dailylimits=$rowLimit['DailyLimit'];
														
									$selectRate="SELECT Stateid,sellrate from tblstate WHERE recstatus='A' and Stateid='$StatId'";
									$runRate=mysqli_query($con,$selectRate);
									$rowRate=mysqli_fetch_array($runRate);									
									$rateId=$rowRate['Stateid'];	
									$rateMax=$rowRate['sellrate'];	
									
									$issueQuantity=intval($WalletAmmount/$rateMax);	
									
									
									
									$checkinInventory="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$DistributorId' and TXNTypein_out='IN'";
	  								$runinInventory=mysqli_query($con,$checkinInventory);
									$rowinInventory=mysqli_fetch_array($runinInventory);
									$inQuantity=$rowinInventory['Quantity'];
									
									
									$checkrepqty="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$DistributorId' and TXNTypein_out='R'";
	  								$runrepInventory=mysqli_query($con,$checkrepqty);
									$rowrepInventory=mysqli_fetch_array($runrepInventory);
									$repQuantity=$rowrepInventory['Quantity'];
									
									
									
									
									
									$checkoutInventory="select sum(Quantity)as Quantity from tblvendorinventory where vendorId='$DistributorId' and TXNTypein_out='OUT'";
									$runoutInventory=mysqli_query($con,$checkoutInventory);
									$rowoutInventory=mysqli_fetch_array($runoutInventory);
									$outQuantity=$rowoutInventory['Quantity'];
									
									$currentStockPosition=$inQuantity-$outQuantity+$repQuantity;
									
									$PendingQuery="SELECT SUM(Quantity)AS Quantity FROM tblissueslip WHERE VendorID='$DistributorId' AND IssueStatus='P'";
									$runPending=mysqli_query($con,$PendingQuery);	
									$rowPending=mysqli_fetch_array($runPending);									
									$PendintStock=$rowPending['Quantity'];
									
									$ClaimedQuery="SELECT SUM(Quantity)AS Quantity FROM tblissueslip WHERE vendorid='$DistributorId' AND IssueStatus='C'";
									$runClaimed=mysqli_query($con,$ClaimedQuery);	
									$rowClaimed=mysqli_fetch_array($runClaimed);									
									$ClaimedStock=$rowClaimed['Quantity'];
									
									$totalPendingissue=$PendintStock+$ClaimedStock;
				
									$totalPendingCurrentStock=$totalPendingissue+$currentStockPosition;
									$grandtotalqant=$Dailylimits-$totalPendingCurrentStock;
														
									$IssueCodeQuery="SELECT IssueSlipId,VendorID,DATE_FORMAT(IssueDate,'%d-%m-%Y')AS IssueDate,IssueCode,IssueNumber FROM tblissueslip WHERE RecStatus='A' AND IssueDate='$newMatchDate' ORDER BY IssueSlipId DESC LIMIT 1";	
									$runIssueCode=mysqli_query($con,$IssueCodeQuery);
									$rowIssueCode=mysqli_fetch_array($runIssueCode);
									$issueId=$rowIssueCode['IssueSlipId'];
									$issueDate=$rowIssueCode['IssueDate'];
									$issueNo=$rowIssueCode['IssueNumber'];
									
									if($rowIssueCode>0)
									{
										$issueCodeInc=$issueNo+'1';
										if($issueCodeInc<=9)
										{
											$issueSlilipCode="$stateCode/$districCode/$CodeDate/00$issueCodeInc";
										}
										else if($issueCodeInc>=10)
										{
											$issueSlilipCode="$stateCode/$districCode/$CodeDate/0$issueCodeInc";
										}
										else if($issueCodeInc>=100)
										{
											$issueSlilipCode="$stateCode/$districCode/$CodeDate/$issueCodeInc";
										}
										
									}
									else
									{
										$issueCodeInc=$issueNo+'1';
										$issueSlilipCode="$stateCode/$districCode/$CodeDate/00$issueCodeInc";
									}			
										
									?>	
										
										<form method="post" action="FinalResultIssueSlip.php">											
											<div class="col-lg-12 col-sm-12 col-xs-12">
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>State Name</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="StatId" value="<?php echo $statsId; ?>">
														<?php echo $stateName; ?>
														
														<input type="hidden" name="totpenstock" value="<?php echo $totalPendingCurrentStock; ?>" id="totsstock">										
														<input type="hidden" name="gtstock" value="<?php echo $grandtotalqant;?>" id="gtsstoc">											
														
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>District Name</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="DistID" value="<?php echo $distId;?>">
														<?php echo $districName ;?>
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Location Name</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="LocId" value="<?php echo $LocationId; ?>">
														<?php echo $LocationName;?>
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Distributor Code</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="VendorId" value="<?php echo $DId; ?>">
														<input type="hidden" name="VendorEmail" value="<?php echo $DEmail; ?>">
														<input type="hidden" name="VendorCode" value="<?php echo $DCode; ?>">
														<input type="hidden" name="VendorName" value="<?php echo $DName; ?>">
														<input type="hidden" name="IssueDate" value="<?php echo $IssuSelectDate; ?>">
														<input type="hidden" name="IssueCodeInc" value="<?php echo $issueCodeInc; ?>">
														<input type="hidden" name="DistLocID" value="<?php echo $DistLocId; ?>">
														<input type="hidden" name="DistCurrentLocId" value="<?php echo $DistCurrentLocId; ?>">
														
														<?php echo $DCode; ?>
													</div>
												</div><br>
                                                
                                                <div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Distributor Name</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
												
														<?php echo $DName; ?>
													</div>
												</div><br>
                                                
                                                
                                                
                                                
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Stock Eligibility</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
												<input type="hidden" name="DailyLimit" value="<?php echo $Dailylimits; ?>" id="textDailylimit">
														<input type="hidden" name="Rate" value="<?php echo $rateMax; ?>">
														<?php echo $Dailylimits; ?>
													</div>
												</div><br>
                                                
                                                
                                                
                                                
                                                
                                                
                                                
                                                
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Wallet Amount</label>
													</div>
													<div class='col-lg-3 col-sm-3 col-xs-12'>
														<input type="hidden" name="WalletAmount" value="<?php echo $WalletAmmount; ?>" id="txtWalletAmount">
														<?php echo $WalletAmmount; ?>
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Max. Allowed Quantity</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="AllotedQuantity" value="<?php echo $issueQuantity; ?>" id="txtMaxAllodqty">
														<?php echo $issueQuantity; ?>
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Current Stock</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="CurrentStock" value="<?php echo $currentStockPosition; ?>">
														<?php echo $currentStockPosition; ?>
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Pending Stock</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="PendingStock" value="<?php echo $totalPendingissue; ?>">
														<?php echo $totalPendingissue; ?>
													</div>
												</div><br>
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Warehouse Code</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														<input type="hidden" name="WarehouseId" value="<?php echo $WareId; ?>">
														<input type="hidden" name="WarehouseCode" value="<?php echo $WareCode ; ?>">
														<?php echo $WareCode; ?>
													</div>
												</div><br>
                                                
                                                
                                                <div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Warehouse Name</label>
													</div>
													<div class="col-lg-3 col-sm-3 col-xs-12">
														
														<?php echo $whname; ?>
													</div>
												</div><br>
                                                
                                                
                                                
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Issue Code</label>
													</div>
													<div class='col-lg-3 col-sm-3 col-xs-12'>
														<input type="hidden" name="IssueCode" value="<?php echo $issueSlilipCode; ?>">
														<?php echo $issueSlilipCode; ?>
													</div>
												</div><br>
												
												<div class="row">
													<div class="col-lg-2 col-sm-2 col-xs-12">
														<label>Quantity </label>
													</div>
													<div class="col-lg-1 col-sm-1 col-xs-3">
														<input type="text" name="Number" class="form-control input-sm" onkeypress ="return AllowNumbersOnly(event)" id="OnlyNumbers" required="required" onkeyup="calc();" autocomplete="off" maxlength="10"/>													
													</div>
													<div class="col-lg-1 col-sm-1 col-xs-3">
														<input type="text" name="FixSize" class="form-control input-sm" value="50" readonly="readonly" id="fixvalue"/>													
													</div>
													<div class="col-lg-2 col-sm-2 col-xs-3">
														<input type="text" name="Quantity" class="form-control input-sm" id="textActQty" readonly="readonly"/>													
													</div>
												</div>
												<div class='row'><br><br>													
													<div class='col-lg-3 col-sm-3 col-xs-12 col-sm-offset-2'>
														<input type="submit" name="SaveFinalIssueShilip" class="btn btn-primary" value="Save Record" id="btnSaveIssue" onclick="return Validate();" />													
													</div>
												</div>																
											</div>
											</form>
								<?php }
									else
									{
										echo "<script>alert('Please Select All Field !')</script>";
										echo "<script>window.open('IssueSlip.php','_self')</script>";
									}
								}
								else
								{
										echo "<script>alert('Please Select Only Current Date !')</script>";
										echo "<script>window.open('IssueSlip.php','_self')</script>";
								}
								}
								else
								{
									echo "<script>window.open('IssueSlip.php','_self')</script>";
								}
                            ?>
                                
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
 
  <script src="js/select/select2.full.js"></script>

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

</body>
</html>

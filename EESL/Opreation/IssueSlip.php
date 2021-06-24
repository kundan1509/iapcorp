
<?php
	session_start();
	require_once("dbcontroller.php");
	$db_handle = new DBController();
	$query ="SELECT Stateid,StateName FROM tblstate WHERE RecStatus='A'";
	$results = $db_handle->runQuery($query);		  
	$UserId=$_SESSION['UserID'];
	$Name=$_SESSION['Name'];
	$UserType=$_SESSION['Type'];
	$RefId=$_SESSION['Ref'];
	$FVerigy=$_SESSION['FV'];
	
	if(!($_SESSION['Name']&&$_SESSION['Type']=='RO' || $_SESSION['Type']=='SA'))
	{
		header("location:../Logout.php");
	}
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
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">
				$(document).ready(function () {
					$("#send").click(function () {
						var statText = $("#txtIssueDate").val();				
						if (statText == ""){							
							alert("Please select date !");
							window.setTimeout(function ()
							{								
								$('#txtIssueDate').focus();
								$('#txtIssueDate').css('border-color','red');
							}, 0);
            				return false;
						}						
						else
						{														
							return true;													
						}
						
					});
				});				
			</script>
	<script>
	function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_id='+val,
	success: function(data){
		$("#state-list").html(data);
		}
		});
		$.ajax({
	type: "POST",
	url: "get_DistLocation.php",
	data:'state_Id='+val,
	success: function(data){
		$("#LocList-list").html(data);
		}
		});
	}
	
	function getDistric(val) {
	$.ajax({
	type: "POST",
	url: "get_DistLocation.php",
	data:'state_Id='+val,
	success: function(data){
		$("#LocList-list").html(data);
		}
		});
	}
	
	function getwarehouse(val_location) {
        
        var state=$('#country-list option:selected').val();
	$.ajax({
	type: "POST",
	
	url: "get_warehousecode.php",

	data:'state_Id='+state+'&location='+val_location,
	success: function(data){
		
		$("#Warehouse-list").html(data);
		}
		});
		
	}
	
	
	
	function getwarehousenames(val) {
	$.ajax({
	type: "POST",
	
	url: "get_warehousename.php",

	data:'warehouseid='+val,
	success: function(data){
		var code = data;
		code = code.replace(/\s+/g, '');
		document.getElementById('warehousename').value = code;
		}
		});
		
			
	}
	
	
	
	
	function getdistributorname(val) {
	$.ajax({
	type: "POST",
	
	url: "get_DistributorName.php",

	data:'vendorid='+val,
	success: function(data){
		var code = data;
		code = code.replace(/\s+/g, '');
		document.getElementById('distnamename').value = code;
		}
		});
		
			
	}
	
	
	
	
	function getDistribtor(val) {
	$.ajax({
	type: "POST",
	url: "get_DistributorCode.php",
	data:'Loc_Id='+val,
	success: function(data){
		$("#DistCodeList-list").html(data);
		}
		});
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
depdate=(document.getElementById('txtIssueDate').value);
if(depdate>today)
{
	alert('Selected date is grater then current date !');
	window.setTimeout(function ()
	{		
		$('#txtIssueDate').focus();
		$('input[type=date]').val('');
									
	}, 0);
    return false;
}
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
function onlyAlphabets(e, t) {
             var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32); 
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
		}
        return true;
		

} 


	
</script>
<script src="js/jquery.min.js"></script>

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
                                    <form class="form-horizontal form-label-left" method="post" action="ResultIssueSlip.php">
										
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select State<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select name="StateList" id="country-list" class="form-control" onChange="getState(this.value);" required="required">
													<option value="">Select State</option>
													<?php
														foreach($results as $country) {
													?>
						<option value="<?php echo $country["Stateid"]; ?>"><?php echo $country["StateName"]; ?></option>
												<?php
												}
												?>
												</select>
                                            </div>
                                        </div><br />
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select District<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select name="DistricList" class="form-control" id="state-list" onChange="getDistric(this.value);"  required="required">
                                                    	<option value="0">Select District</option>                                       
                                                   </select>
                                            </div>
                                        </div><br />
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Location<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select name="LocationList"  class="form-control" id="LocList-list" onChange="getDistribtor(this.value);getwarehouse(this.value);" required="required">
                                                    	<option value="0">Select Location</option>                                       
                                                    </select>
                                            </div>
                                        </div><br />                                
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Distributor Code<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <select name="DistribtorList" class="form-control" id="DistCodeList-list" required="required" onchange="getdistributorname(this.value);">
                                                    	<option value="0">Select Distributor Code</option>                                       
                                                   </select>
                                            </div>
                                        </div><br />
                                        
                                            <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Distributor Name</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="txtvendorname" id="distnamename" readonly="readonly" class="form-control input-sm"/>
                                                                                     
                                                                                               </div>
                                        </div>
                                        
                                        
                                        
                                       <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse Code<span style=" color:#F00">*</span></label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                               <select name="WarehouseCodeList" class="select2_group form-control input-sm" id="Warehouse-list" required="required" onchange="getwarehousenames(this.value);">
                                                    	<option value="0">Select Warehouse Code</option>
                                                                                     
                                                    </select>
                                            </div>
                                        </div>
                                        <br />
                                         <div class="item form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Warehouse Name</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="txtwarehosename" id="warehousename" readonly="readonly" class="form-control input-sm"/>
                                                                                     
                                                                                               </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="form-group">
                                        	<label class="control-label col-md-3 col-sm-3 col-xs-12">Select Date<span style=" color:#F00">*</span></label><br />                                            
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                            	<input type="date" name="SelectIssuDate" required="required" class="form-control input-sm" onblur="checkDate();" id="txtIssueDate"/>
                                            </div>
                                        </div>
                                        <br /><br />
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-5">
                                               
                                                <button id="send" name="SaveIssueSlip" type="submit" class="btn btn-primary"> Submit </button>
                                            </div>
                                        </div><br /><br /><br /><br />
                                    </form>

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
    <script>
       
       
    </script>
    
</body>

</body>
</html>
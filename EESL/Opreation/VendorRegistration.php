<?php
 include("../Connection/Connection.php");
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


?>

<?php
if(isset($_POST['Refferalid']))
{
	$email=$_POST['Refferalid'];
	echo $email;
}
$_SESSION['timeout'] = time();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
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
    
     <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

  
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


function onlyAlphabets(e, t) {
             var k = e.charCode ? e.charCode : e.keyCode;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 9||k==8 ||k==32||k==17); 
        } 	



function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
	
        return false;
		
    return true;
}

	
	
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

function validate() {
	$("file_error").html("");
	$(".demoInputBox").css("border-color","#F0F0F0");
	var file_size = $('#file')[0].files[0].size;
	if(file_size>2097152) {
		$("#file_error").html("File size is greater than 2MB");
		$(".demoInputBox").css("border-color","#FF0000");
		return false;
	} 
	return true;
}
	

function pancheck(fileall)
{
 
	
	var fileName = fileall.value;
var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
	var size = fileall.files[0].size;
	if(ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG"  || ext == "PNG")
{
			return true;
			if(size<=3072)
			{
				
				return true;
			}
			else
			{
				
				alert("Upload only max 3mb file");
				window.setTimeout(function ()
    {
        fileall.focus();
    }, 0);
				
			
				
			}
} 
else
{
	
	alert("Upload Gif,Jpg,png,pdf images only");
fileall.focus();
return false;

}
	
	
}

function CheckColors(val){
 var element=document.getElementById('modeofDeposit');
 if(val=='OTHER'||val=='OTHER')
   element.style.display='block';
 else  
   element.style.display='none';
}


function stateCity()
{
	var x=document.getElementById('cname').value;
	var y=document.getElementById('Distname1').value;
	alert(x);
	alert(y);
}

	
	
	
</script>



    
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
	alert('Deposit date should not greater than current date');
	
	
			
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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#name").blur(function() {
var name = $('#name').val();
if(name=="")
{
$("#disp").html("");
}
else
{
$.ajax({
type: "POST",
url: "user_check.php",
data: "name="+ name ,
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
if(name=="")
{
$("#disppan").html("");
}
else
{
$.ajax({
type: "POST",
url: "user_check.php",
data: "pan="+ name ,
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
if(name=="")
{
$("#dispmob").html("");
}
else
{
$.ajax({
type: "POST",
url: "user_check.php",
data: "mob="+ name ,
success: function(html){
$("#dispmob").html(html);
}
});
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




<script src="../Designing/js/jquery.min.js"></script>

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
						
						function chechform(){
								var errorpan=$('#disppan').find('span').html();
								var erroremail=$('#disp').find('span').html();
								var errormobile=$('#dispmob').find('span').html();
								var stateid=document.getElementById('Distname').selectedIndex;
								var dist=document.getElementById('cname').selectedIndex;
								var location=document.getElementById('Distname');
								
								if((errorpan!="Available")||(erroremail!="Available")||(errormobile!="Available")||(stateid=='0')||(dist=='0')||(location=='0'))
								{
									//do nothing
									if(stateid=='0')
									{
										alert('Please select state')
									}
									else if(dist=='0')
									{
										alert('Please select district')
									}
									else if(location=='0')
									{
										alert('Please select location')
									}
									return false;
								 }else 
									{
										
										return true;
										
									}
										
										
								/*--------------------------validation for state and distric------------------*/
								
								
							
										
								
							}
                    </script>


                    <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                     <form class="form-horizontal form-label-left" autocomplete="off" method="post" onSubmit="return chechform()&& checkDate()&&pancheck(this);" action="ResultVendorRegistration.php" enctype="multipart/form-data">
                            <div class="x_panel">
                                <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Distributor  Registration</h2>
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
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" required name="distibutorName" onpaste="return false" autocomplete="off" class="textValidate form-control" placeholder="Enter Distributor Name" maxlength="100" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Corporate Status</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               <select id="corporateStatus" name="corporateStatus" class="select group form-control">
                                               <option value="Individual"> Individual</option>
                                               <option value="Proprietership"> Proprietership</option>
                                               <option value="Private LTD"> Private LTD.</option>
                                               <option value="NGO/Society"> NGO/Society</option>
                                               <option value="Public LTD"> Public LTD.</option> 
                                               <option value="Partnership">Partnership.</option> 
                                               </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pan Number<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" id="panno" onpaste="return false" onblur="ValidatePAN(this);" autocomplete="off" required name="panumber" class="form-control" placeholder="Enter Distributor Pan No." maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="disppan"></div><br />
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Service Tax No.                                          </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" onpaste="return false"  name="servicetaxno" autocomplete="off" onBlur="validateStaxno(this);" class="form-control" placeholder="Enter Service Tax No" maxlength="14">
                                            </div>
                                         
                                            
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">E-Mail Id <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               <input type="text" id="name" onpaste="return false" required name="emailid" autocomplete="off" onBlur="validateEmail(this);" class="form-control" placeholder=" Enter Distributor E-mail." maxlength="50">
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="disp"></div><br />
                                            
                                        </div>
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mobile Number<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" id="mobid" onpaste="return false" required name="mobilenumber" autocomplete="off"  class="txtNumeric form-control" placeholder="Enter Distributor Mobile No" maxlength="10" min="10" onkeypress="return isNumberKey(event)">
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4"  id="dispmob"></div><br />
                                        </div>
                                        
                                        <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Address</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                        
                                       
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Line 1</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="address1" onpaste="return false" class="form-control" autocomplete="off" placeholder="Enter Distributor Address" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Line 2</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            
                                               <input type="text" name="address2" onpaste="return false" class="form-control" autocomplete="off" placeholder="Enter Distributor Address" maxlength="50">
                                               
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Town/City</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="town" onpaste="return false" class="form-control" autocomplete="off" placeholder="Enter Distributor Town/City" maxlength="50">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">District</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" onpaste="return false" name="district" class="textValidate form-control" autocomplete="off" placeholder="Enter Distributor District" maxlength="50" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                       
                                       
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">State</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" onpaste="return false" name="state" class="textValidate form-control" autocomplete="off" placeholder="Enter Distributor State" maxlength="20" onKeyPress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        
                                        
                                          <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pin</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="pin" onpaste="return false" class="txtNumeric form-control" autocomplete="off" placeholder="Enter Distributor Area Pin No." maxlength="6" onkeypress="return isNumberKey(event);" >
                                            </div>
                                        </div>
									
                                    	
                                        
                                        <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>WorkStation Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                         
                                        
                                        <input type="radio" value="Single Location" name="sl" checked="checked"><b>Single Location</b>
                                        <input type="radio" value="Multiple Location" name="sl"><b>Multiple Location</b>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">State Name<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select id="Distname1" name="stateName" required="required" onChange="getWSDistt(this.value); getrate(this.value);" class="select group form-control">
                                                 <option id="state" value="0">Select State</option>
                                                 <?php
												    $query12="SELECT Stateid,StateName,StateCode,buyrate FROM tblstate WHERE recstatus='a'";
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
                                            <label class="col-md-4 col-sm-4 col-xs-12">District Name<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select id="cname" name="CityName22" required="required"  onChange="getLocation(this.value);" class="select group form-control">
                                                  <option id="district" value="0">Select District Name</option>
                                                 </select>
                                            </div>
                                        </div>
                                        
                                        
                                      
                                        
                                        
                                        
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Location Name<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select id="Distname" name="Distname" required="required" class="select group form-control">
                                                 <option value="0">Select Location</option>
                                                 </select>
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">No.Of Centers<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                               
                                                <input id = "noofcenter" onpaste="return false" required name = "noofcenter" type = "text" autocomplete="off"  class="txtNumeric form-control" placeholder="Enter No. Of Centers" maxlength="3" onkeypress="return isNumberKey(event);" >
                                                
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">SPOC Name<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            
                                           <input id = "spocname" onpaste="return false" required name ="spocname" type = "text"  class="txtValidate form-control" placeholder="Enter SPOC Name" maxlength="50">
                                                <!-- <input type="text" id="spoc" class="form-control" placeholder="Default Input">-->
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mobile Number <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input id = "mobile" required name = "mobile" onpaste="return false" type = "text" class="txtNumeric form-control" placeholder="Enter WorkStation Mobile No."  maxlength="10" onkeypress="return isNumberKey(event)">
                                               
                                            </div>
                                        </div>
                                        
                                           <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Key Person Details</small></h2>
                                
                                    <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">KeyPerson Name <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" name="keypersonName" onpaste="return false" class="textValidate form-control" required placeholder="Enter KeyPerson Name"  maxlength="100" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" name="keypersonAdd" onpaste="return false" class="form-control" required placeholder="Enter KeyPerson Address"  maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">E-Mail Id <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" id="email" onpaste="return false" onBlur="validateEmail(this);" name="keypersonEmail" class="form-control" placeholder="Enter KeyPerson Email-Id" required  maxlength="50">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Contact No. <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" name="keypersonContact" onpaste="return false" class="txtNumeric form-control" placeholder="Enter KeyPerson Contact" required  maxlength="10" onkeypress="return isNumberKey(event);">
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
                                                  <input type="text" name="accountholdername" onpaste="return false" maxlength="50" class="textValidate form-control" placeholder="Enter Distributor AC Holder Name" onkeypress="return onlyAlphabets(event,this);">
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Account No.</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="acnumber" onpaste="return false" class="txtNumeric form-control" placeholder="Enter Distributor AC No." maxlength="16" onkeypress="return isNumberKey(event);">
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Bank Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select name="bankname" class="select2_group form-control">
                                                 
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
                                                  <input type="text" name="ifscCode" onpaste="return false" class="form-control" placeholder="Enter Distributor Bank IFSC Code" maxlength="15">
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">City/Town</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                  <input type="text" name="cityTown" onpaste="return false" class="form-control" placeholder="Enter Distributor Bank City Name" maxlength="50">
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
                                                 
           <select name="poolacno" class="form-control input-md" required="Required" onChange="getIFSCCode(this.value);">
                                                                    <option value="0">Please Select Pool Account</option>
                                                                          <?php
												  $bankQuery="SELECT AccountNo, concat(bankname,':',AccountNo)as mergac FROM tblpollaccount WHERE recstatus='A'";
												  $q=mysqli_query($con,$bankQuery);
                                                  while($m_row = mysqli_fetch_array($q)) 
                                                             {
																											
											          echo "<option value='".$m_row['AccountNo']."'>".$m_row['mergac']."</option>";
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
                                                 <option value="0">Please Select Pool Account IFSC Code</option>
               
                                                 </select>
                                               
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Mode Of Deposit</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             <select name="modeofDeposit" class="form-control input-md" required="Required" onchange="CheckColors(this.value);">
                                                                    <option value="0">Select Please Mode Of Payment</option>
                                                                           <option value="CASH">CASH</option>
                                                                           <option value="CHEQUE">CHEQUE</option>
                                                                            <option value="NEFT">NEFT</option>
                                                                            <option value="RTGS">RTGS</option>
                                                                            <option value="IMPS">IMPS</option>
                                                                            <option value="OTHER">OTHER</option>
                                                                  </select>   
                                            
                                                 <!--<input type="text" onKeyPress="return ValidateAlpha(event);" name="modeofDeposit" class="form-control" required placeholder="Enter Payment Deposit Mode" maxlength="50">-->
                                                 <br>
                                                 <input type="text" name="color" onpaste="return false" id="modeofDeposit" style='display:none;' class="form-control" placeholder="Enter Payment Mode"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Date Of Deposit <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="date" id="depdate" onpaste="return false" onChange="checkDate();" required name="dateofdeposit" class="form-control" placeholder="Select Payment Date">
                                                 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Amount <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" onpaste="return false" required onkeypress="return isNumberKey(event)" name="amount" class="txtNumeric form-control" maxlength="10" placeholder="Enter Amount">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">DD/CHQ/TXN No. <span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <input type="text" required name="referenceno" onpaste="return false" class="form-control" placeholder="Enter DD/Chq/Txn No." maxlength="30">
                                            </div>
                                        </div>
                                        
                                        <div class="x_title" style="color:#FFF; background-color:#889DC6">
                                    <h2>Check List</small></h2>
                                
                                    <div class="clearfix"></div>
                                </div>
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Pan Card<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="file" name="file" id="picture" required /></p>
<p id="pic_error1" style="display:none; color:#FF0000;">Image formats should be JPG, JPEG, PNG or GIF.</p>
<p id="pic_error2" style="display:none; color:#FF0000;">Max file size should be 3MB.</p>

                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Security Deposit<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                           <input type="file" name="file1" id="signature" required /></p>
<p id="sig_error1" style="display:none; color:#FF0000;">Image formats should be JPG, JPEG, PNG or GIF.</p>
<p id="sig_error2" style="display:none; color:#FF0000;">Max file size should be 3MB.</p>

 											
						
                                            </div>
                                        </div>
                                    
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Address Proof<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                              <input type="file" name="file2" id="ap" required /></p>
<p id="sig_errorap1" style="display:none; color:#FF0000;">Image formats should be JPG, JPEG, PNG or GIF.</p>
<p id="sig_errorap2" style="display:none; color:#FF0000;">Max file size should be 3MB.</p>

						
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Canceled Cheque<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                              <input type="file" name="file3" id="cc" required /></p>
<p id="sig_errorcc1" style="display:none; color:#FF0000;">Image formats should be JPG, JPEG, PNG or GIF.</p>
<p id="sig_errorcc2" style="display:none; color:#FF0000;">Max file size should be 3MB.</p>
						
                                            </div>
                                        </div>
                                       
                                        
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">ServiceTax<span style=" color:#F00">*</span></label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             <input type="file" name="file4" id="st" required /></p>
<p id="sig_errorst1" style="display:none; color:#FF0000;">Image formats should be JPG, JPEG, PNG or GIF.</p>
<p id="sig_errorst2" style="display:none; color:#FF0000;">Max file size should be 3MB.</p>
						
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
                                              	<option value="0">Select Referral Type</option>
                                                    <option value="1">Employee</option>
                                                    <option value="2">Consultant</option>
                                                    
                                                </select>
 						
						
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12">Referral Name</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                 <select name="referalname" id="refferalName1" class="form-control">
                                                    <option value="0">Select Referral Name</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        
                                        

                                      
                                      


                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            
                                                                                     
                                            
                                                 <p><input name="finalsubmit" type="submit" value="Submit" id="submit" class="btn btn-primary" /></p>       
												 


                                               
                                           
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
        
        <script>
$('input[type="submit"]').prop("disabled", true);
var a=0;
var b=0;
//binds to onchange event of your input field
$('#picture').bind('change', function() {
if ($('input:submit').attr('disabled',false)) {$('input:submit').attr('disabled',true);}
var ext = $('#picture').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
{ $('#pic_error1').slideDown("slow"); $('#pic_error2').slideUp("slow"); a=0;} else {
var picsize = (this.files[0].size);
if (picsize > 3000000||picsize <51200)
{ $('#pic_error2').slideDown("slow"); a=0;} else { a=1; $('#pic_error2').slideUp("slow"); }
$('#pic_error1').slideUp("slow");
if (a==1 && b==2) {$('input:submit').attr('disabled',false);}
}
});
 
$('#signature').bind('change', function() {
if ($('input:submit').attr('disabled',false)) {$('input:submit').attr('disabled',true);}
var ext = $('#signature').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
{ $('#sig_error1').slideDown("slow"); $('#sig_error2').slideUp("slow"); b=0;} else {
var picsize = (this.files[0].size);
if (picsize > 3000000||picsize <51200)
{ $('#sig_error2').slideDown("slow"); b=0;} else { b=2; $('#sig_error2').slideUp("slow"); }
$('#sig_error1').slideUp("slow");
if (a==1 && b==2) {$('input:submit').attr('disabled',false);}
}
});


/*--------------------------------------addressproof -----------------------*/
$('#ap').bind('change', function() {
if ($('input:submit').attr('disabled',false)) {$('input:submit').attr('disabled',true);}
var ext = $('#ap').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
{ $('#sig_errorap1').slideDown("slow"); $('#sig_errorap2').slideUp("slow"); b=0;} else {
var picsize = (this.files[0].size);
if (picsize > 3000000||picsize <51200)
{ $('#sig_errorap2').slideDown("slow"); b=0;} else { b=2; $('#sig_errorap2').slideUp("slow"); }
$('#sig_errorap1').slideUp("slow");
if (a==1 && b==2) {$('input:submit').attr('disabled',false);}
}
});
/*--------------------------------------end addressproof-----------------------*/

/*--------------------------------------cancel check-----------------------*/
$('#cc').bind('change', function() {
if ($('input:submit').attr('disabled',false)) {$('input:submit').attr('disabled',true);}
var ext = $('#cc').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
{ $('#sig_errorcc1').slideDown("slow"); $('#sig_errorcc2').slideUp("slow"); b=0;} else {
var picsize = (this.files[0].size);
if (picsize > 3000000||picsize <51200)
{ $('#sig_errorcc2').slideDown("slow"); b=0;} else { b=2; $('#sig_errorcc2').slideUp("slow"); }
$('#sig_errorcc1').slideUp("slow");
if (a==1 && b==2) {$('input:submit').attr('disabled',false);}
}
});
/*--------------------------------------end cancel check-----------------------*/

/*--------------------------------------servicetax-----------------------*/
$('#st').bind('change', function() {
if ($('input:submit').attr('disabled',false)) {$('input:submit').attr('disabled',true);}
var ext = $('#st').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
{ $('#sig_errorst1').slideDown("slow"); $('#sig_errorst2').slideUp("slow"); b=0;} else {
var picsize = (this.files[0].size);
if (picsize > 3000000||picsize <51200)
{ $('#sig_errorst2').slideDown("slow"); b=0;} else { b=2; $('#sig_errorst2').slideUp("slow"); }
$('#sig_errorst1').slideUp("slow");
if (a==1 && b==2) {$('input:submit').attr('disabled',false);}
}
});
/*--------------------------------------end servicetax-----------------------*/


</script>

        
        <!-- /editor -->
</body>

</html>

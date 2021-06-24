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


<?php
	if(isset($_GET['LocId']))
	{
		$LocId=$_GET['LocId'];
		$RemoveQuery="UPDATE tbllocation SET RecStatus='D' WHERE LocId='$LocId' AND RecStatus='A' ";
		$RemoveRun=mysqli_query($con,$RemoveQuery);
		if($RemoveRun)
		{
			echo "<script>alert('User Removed Successfully !')</script>";			
		}
		else
		{
		
			echo "<script>alert('User Not Removed Check Details !')</script>";
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Location List</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Location List</title>

    <!-- Bootstrap core CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/icheck/flat/green.css" rel="stylesheet">


    <script src="js/jquery.min.js"></script>

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
                                <div class="x_title"style="color:#FFF; background-color:#889DC6">
                                    <h2>Location List</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                       
                                      
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                            
                          
						<div class="x_content">
					
						 <div class="table-responsive">
 						
						 <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width:20%;">State Name </th>
                <th style="width:20%;">District Name </th>
				  <th style="width:20%;"> Location Name</th>	
                <th style="width:20%;"> Location Code </th>             		   
                 <th style="width:20%;">Action</th>
            </tr>
        </thead>

        <tbody>
          <?php
		  
		  $per_page=10;
                   if (isset($_GET["page"]))
	                     {
                        $page = $_GET["page"];
                        }
                    else
                        {
                     $page=1;

                       }
                    $start_from = ($page-1) * $per_page;
		  
	
		   $result="SELECT l.LocId,l.LocationName,l.LocationCode,s.StateName,d.CityName
                       FROM tbllocation AS l JOIN tblcity AS d ON l.CityId=d.CityId
                       JOIN tblstate AS s ON d.StateId=s.Stateid
                       WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' ORDER BY LocId DESC LIMIT $start_from,$per_page";
	  $query=mysqli_query($con,$result);
      while ($row = mysqli_fetch_array($query))	  
	  {
		$LocId=$row['LocId'];
      $satatename=$row['StateName'];
	  $districname=$row['CityName'];
	  $locationname=$row['LocationName'];
	  $locationcode=$row['LocationCode'];
	 	
		 echo "
            <tr>
                 <td>$satatename</td>
				 <td>$districname</td>
				 <td>$locationname</td>
				  <td>$locationcode</td>
				                       				
                <td>
                    <a href='LocationUpdate.php?LocId=$LocId' class='btn btn-success' title='Edit Location Records'><i class='fa fa-edit'></i></a>
					  <a href='DetailsLocation.php?LocId=$LocId' class='btn btn-info' title='View  Location  Records'><i class='fa fa-list'></i></a>
				     <a href='LocationList.php?LocId=$LocId' class='btn btn-danger' data-toggle='tooltip' title='Remove  Location' onclick=\"return confirm('Are You sure Remove User Details ?');\"><i class='fa fa-trash'></i></a>	
				    		
					
                
                </td>
            </tr>";
	  }
			?>     
      
            
        </tbody>
    </table>
    <?php
            $query = ("SELECT l.LocId,l.LocationName,l.LocationCode,s.StateName,d.CityName
                       FROM tbllocation AS l JOIN tblcity AS d ON l.CityId=d.CityId
                       JOIN tblstate AS s ON d.StateId=s.Stateid
                       WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' ORDER BY LocId");
            $res = mysqli_query($con,$query);
            $total_records = mysqli_num_rows($res); 	
            $total_pages = ceil($total_records / $per_page);	
             echo "<center><a href='LocationList.php?page=1' class='btn btn-default fa fa-long-arrow-left'>".'First'."</a> ";
              for ($i=1; $i<=$total_pages; $i++)
	           {
               echo "  <a href='LocationList.php?page=".$i."' class='btn btn-default'>".$i."</a> ";
               };
               echo "<a href=LocationList.php?page=$total_pages' class='btn btn-default'>".'Last'." <i class='fa fa-long-arrow-right'></i></a></center> ";
?>
    
                        </div>
                                

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>


<footer>
                    <div class="">
                        <p class="col-sm-offset-5">Designed & Developed by Foretek solution LLP  Copyright © 2015-2016 IAP Company Pvt. Ltd<a>
                           
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
    <script>
        // initialize the validator function
       
    </script>


</body>
</html>
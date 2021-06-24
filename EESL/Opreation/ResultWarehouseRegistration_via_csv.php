<?php
        //session_start();
	include("../Connection/Connection.php");		  
	//$UserId=$_SESSION['UserID'];
        $UserId=1;
        
    
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<body class="nav-md">
    <?php if(isset($_FILES) && (count($_FILES)==0)){ ?>
    <form action="ResultWarehouseRegistration_via_csv.php" method="post" enctype="multipart/form-data">
  File name: <input type="file" name="fname"><br>
  <input type="submit" value="Submit">
</form>
    <?php } ?>
   
    <div class="container body">


       
                            
                            <div class="right_col" role="main">

                                <div>
                                    
                                </div>
                


<div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                               
                                
                 
                          
						<div class="x_content">
								<?php
                                                                if(isset($_FILES) && ($_FILES['fname']['error']==0) && (count($_FILES)>0)){
                                                                        $file = fopen($_FILES['fname']['tmp_name'],"r");
                                                                        $i=0;
                                                                        while ( ($data = fgetcsv($file) ) !== FALSE ) {
                                                                            if($i<3){
                                                                                $i++;
                                                                                continue; 
                                                                            }
                                                                            //echo "<pre>";
                                                                            //print_r($data);
                                                                            //echo "</pre>";
                                                                            
                                                                            
                                                                            $VendorId=isset($data[0])?$data[0]:'';//$_POST['StateList'];
                                                                            $remarks='';
                                                                            
                                                                            $spoc_name=isset($data[31])?$data[31]:'';
                                                                            $mobile_number=isset($data[33])?$data[33]:'';
                                                                            $rate=isset($data[39])?$data[39]:'';
                                                                            $locationid=isset($data[40])?$data[40]:'';
                                                                            $status=isset($data[41])?$data[41]:'';
                                                                            
                                                                            $RecStatus='A';
                                                                            $LastUpdateOn=date('Y-m-d H:i:s');
                                                                            $UpdatebyId=1;
                                                                            if($status=='Y'){
                                                                                // $createUser="INSERT INTO tbluser set userid='$UserId',password='$encryptpass',FinanceVerificationStatus='A',RecStatus='A',LastUpdateOn='$LastUpdateOn',UpdatebyId=1,refid=0,UserTypes='VN'";
                                                                                $createUser="INSERT INTO tblvendorlocation set LocationId='$locationid',Remarks='',RecStatus='A',LastUpdateOn='$LastUpdateOn',UpdatebyID=1,NumberOFCenter=5,SPOCName='$spoc_name',MobileNo='$mobile_number',VendorId='$VendorId',rate='$rate'";
                                                                           // 
                                                                                   //echo $createUser."<br/>";
                                                                                     mysqli_query($con,$createUser);
                                                                            }
                                                                           
                                                                            
                                                                            continue;
                                                                            
                                                                            /*$StateId=isset($data[0])?$data[0]:'';//$_POST['StateList'];
                                                                            $DistricId=isset($data[1])?$data[1]:'';//$_POST['DistricList'];
                                                                            $LocationId=isset($data[2])?$data[2]:'';//$_POST['LocationList'];
                                                                            $ManagerName=isset($data[3])?$data[3]:'';//$_POST['txtmanagername'];
                                                                            $warehousename=isset($data[4])?$data[4]:'';//$_POST['txtwarehousename'];
                                                                            $ManagerEmail=isset($data[5])?$data[5]:'';//$_POST['txtEmail'];
                                                                            $ManagerContact1=isset($data[6])?$data[6]:'';//$_POST['txtContactNumber'];
                                                                            $ManagerContact2=isset($data[7])?$data[7]:'';//$_POST['txtalterContactNumber'];
                                                                            $ManagerAddress=isset($data[8])?$data[8]:'';//$_POST['txtAddress'];
                                                                            $ManagerDesc=isset($data[9])?$data[9]:'';//$_POST['txtDescription'];
                                                                            date_default_timezone_set("Asia/Kolkata"); 
                                                                            $CurrentDate=date('Y-m-d H:i:s A');	
                                                                            $CodeDate=date('dmy');	
                                                                            $Password = rand(1000,5000);
                                                                            $encryptpass=base64_encode($Password);		
                                                                            $selectDuplicate="SELECT COUNT(DispatcherId) AS counter,ContactNumber,EmailId FROM tbldispatcherregistraion WHERE ContactNumber='$ManagerContact1' OR EmailId='$ManagerEmail' AND RecStatus='A'";
                                                                            $runSelectDuplicat=mysqli_query($con,$selectDuplicate);
                                                                            $rowDuplicat=mysqli_fetch_array($runSelectDuplicat);
                                                                          //  echo "here-->stateid=".$StateId.";DistricId=".$DistricId.";ManagerName=".$ManagerName.";ManagerDesc=".$ManagerDesc.";CodeDate".$CodeDate;
                                                                            
                                                                            
                                                                        if($rowDuplicat['counter']>=1)
									{										
										echo "<script>alert('Email Id OR Contact No. already Exist !')</script>";
										echo "<script>window.open('WarehouseRegistration.php','_self')</script>";
									}
									else
									{						
									$SelState="SELECT s.Stateid,s.StateName,s.StateCode,s.LastUnitWarehouse,d.CityId,d.CityName,d.CityCode,d.LastUnitWarehouse,l.LocId,l.LocationName,l.LocationCode
FROM tblstate AS s JOIN tblcity AS d ON s.Stateid=d.StateId JOIN tbllocation AS l ON d.CityId=l.CityId
WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' AND d.CityId='$DistricId' AND s.Stateid='$StateId' AND LocId='$LocationId'";
									$RunSelState=mysqli_query($con,$SelState);
									$GetSelStateRow=mysqli_fetch_array($RunSelState);
										
									$stateName=$GetSelStateRow['StateName'];
									$stateCode=$GetSelStateRow['StateCode'];
									$StateWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$districName=$GetSelStateRow['CityName'];										
									$districCode=$GetSelStateRow['CityCode'];
									$DistWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$LocationId=$GetSelStateRow['LocId'];
									$LocationCode=$GetSelStateRow['LocationCode'];
									$LocationName=$GetSelStateRow['LocationName'];	
									
									$SelStatUnit="SELECT LastUnitWarehouse FROM tblstate WHERE RecStatus='A' AND Stateid='$StateId'";
									$runSelStatUnit=mysqli_query($con,$SelStatUnit);
									$rowSelStatUnit=mysqli_fetch_array($runSelStatUnit);										
									$StatUnitCode=$rowSelStatUnit['LastUnitWarehouse'];
										
									$SelDistUnit="SELECT LastUnitWarehouse FROM tblcity WHERE RecStatus='A' AND CityId='$DistricId'";
									$runSelDistUnit=mysqli_query($con,$SelDistUnit);
									$rowSelDistUnit=mysqli_fetch_array($runSelDistUnit);
									$DistUnitCode=$rowSelDistUnit['LastUnitWarehouse'];
																			
									$selectExistWarehous="SELECT COUNT(DispatcherId) AS DispatcherCount FROM tbldispatcherregistraion WHERE RecStatus='A' AND LocationId='$LocationId'";
									$runExistWarehouse=mysqli_query($con,$selectExistWarehous);
									$rowExistWarehouse=mysqli_fetch_array($runExistWarehouse);
									
									
										
											$StateInc=$StatUnitCode+'1';
											$DistInc=$DistUnitCode+'1';
											$WarehouseCode="$stateCode/$districCode/$CodeDate/$StateInc/$DistInc";										
											
											$queryDispatcher="INSERT INTO tbldispatcherregistraion(DispatcherName,LocationId,ContactNumber,EmailId,MailingAddress,Description,RecStatus,LastUpdateOn,UpdatebyID,AlternateContactNo)
VALUES('$ManagerName','$LocationId','$ManagerContact1','$ManagerEmail','$ManagerAddress','$ManagerDesc','A','$CurrentDate','$UserId','$ManagerContact2')";
                                                                                        echo "<br/>".$queryDispatcher."<br/>";
											$runDispatcher=mysqli_query($con,$queryDispatcher);
											if($runDispatcher)
											{
												$MaxDispatch="SELECT MAX(warehouseid) AS DispatcherId FROM tblwarehouseregistration";
	      										$runMaxDispatch=mysqli_query($con,$MaxDispatch);
         										$rowMaxDispatch = mysqli_fetch_array($runMaxDispatch);
	      										$MaxDispatchId=$rowMaxDispatch['DispatcherId'];
		
												$updWarehouse="UPDATE tblwarehouseregistration SET WareHouseCode='$WarehouseCode',WareHouseLocationID='$LocationId',WareHouseName='$warehousename',RecStatus='A',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId',stateid='$StateId' WHERE warehouseid='$MaxDispatchId'";
                                                                                                echo "<br/>".$updWarehouse."<br/>";
												$runupdWarehouse=mysqli_query($con,$updWarehouse);
		
												$InsUser="INSERT INTO tbluser(UserId,PASSWORD,UserTypes,FinanceVerificationStatus,RefId,RecStatus,LastUpdateOn,UpdatebyID)
		VALUES('$ManagerEmail','$encryptpass','DS','A','$MaxDispatchId','A','$CurrentDate','$UserId')";
												$runInsUser=mysqli_query($con,$InsUser);
												
												if($runInsUser)
												{
													$updState="UPDATE tblstate SET LastUnitWarehouse='$StateInc' WHERE RecStatus='A' AND Stateid='$StateId'";
													$runupdState=mysqli_query($con,$updState);
													
													$updDist="UPDATE tblcity SET LastUnitWarehouse='$DistInc' WHERE RecStatus='A' AND CityId='$DistricId'";
													$runupdDist=mysqli_query($con,$updDist);
													
													
													
																																						
													
												}
														
											}											
																						
										
									}*/
                                                                            
                                                                        }
                                                                    } 
                                                                

                                                                fclose($file);
                                                                
                                                                die();
                                                                
								
									?>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
        

</body>


</html>


<?php

die();
        //session_start();
	include("../Connection/Connection.php");		  
	//$UserId=$_SESSION['UserID'];
        $UserId=1;
        
    
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<body class="nav-md">
    <?php if(isset($_FILES) && (count($_FILES)==0)){ ?>
    <form action="ResultWarehouseRegistration_via_csv.php" method="post" enctype="multipart/form-data">
  File name: <input type="file" name="fname"><br>
  <input type="submit" value="Submit">
</form>
    <?php } ?>
   
    <div class="container body">


       
                            
                            <div class="right_col" role="main">

                                <div>
                                    
                                </div>
                


<div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                               
                                
                 
                          
						<div class="x_content">
								<?php
                                                                if(isset($_FILES) && ($_FILES['fname']['error']==0) && (count($_FILES)>0)){
                                                                        $file = fopen($_FILES['fname']['tmp_name'],"r");
                                                                        $i=0;
                                                                        while ( ($data = fgetcsv($file) ) !== FALSE ) {
                                                                            if($i==0){
                                                                                $i++;
                                                                                continue; 
                                                                            }
                                                                            //echo "<pre>";
                                                                            //print_r($data);
                                                                           // echo "</pre>";
                                                                            
                                                                            
                                                                            $UserId=isset($data[4])?$data[4]:'';//$_POST['StateList'];
                                                                            $PASSWORD='led@123';
                                                                            $encryptpass=base64_encode($PASSWORD);
                                                                            $UserTypes='VN';
                                                                            $FinanceVerificationStatus='A';
                                                                            $RefId=0;
                                                                            $RecStatus='A';
                                                                            $LastUpdateOn=date('Y-m-d H:i:s');
                                                                            $UpdatebyId=1;
                                                                            
                                                                            $createUser="INSERT INTO tbluser set userid='$UserId',password='$encryptpass',FinanceVerificationStatus='A',RecStatus='A',LastUpdateOn='$LastUpdateOn',UpdatebyId=1,refid=0,UserTypes='VN'";
                                                                           // 
                                                                            //echo $createUser."<br/>";
                                                                            mysqli_query($con,$createUser);
                                                                            
                                                                            continue;
                                                                            
                                                                            /*$StateId=isset($data[0])?$data[0]:'';//$_POST['StateList'];
                                                                            $DistricId=isset($data[1])?$data[1]:'';//$_POST['DistricList'];
                                                                            $LocationId=isset($data[2])?$data[2]:'';//$_POST['LocationList'];
                                                                            $ManagerName=isset($data[3])?$data[3]:'';//$_POST['txtmanagername'];
                                                                            $warehousename=isset($data[4])?$data[4]:'';//$_POST['txtwarehousename'];
                                                                            $ManagerEmail=isset($data[5])?$data[5]:'';//$_POST['txtEmail'];
                                                                            $ManagerContact1=isset($data[6])?$data[6]:'';//$_POST['txtContactNumber'];
                                                                            $ManagerContact2=isset($data[7])?$data[7]:'';//$_POST['txtalterContactNumber'];
                                                                            $ManagerAddress=isset($data[8])?$data[8]:'';//$_POST['txtAddress'];
                                                                            $ManagerDesc=isset($data[9])?$data[9]:'';//$_POST['txtDescription'];
                                                                            date_default_timezone_set("Asia/Kolkata"); 
                                                                            $CurrentDate=date('Y-m-d H:i:s A');	
                                                                            $CodeDate=date('dmy');	
                                                                            $Password = rand(1000,5000);
                                                                            $encryptpass=base64_encode($Password);		
                                                                            $selectDuplicate="SELECT COUNT(DispatcherId) AS counter,ContactNumber,EmailId FROM tbldispatcherregistraion WHERE ContactNumber='$ManagerContact1' OR EmailId='$ManagerEmail' AND RecStatus='A'";
                                                                            $runSelectDuplicat=mysqli_query($con,$selectDuplicate);
                                                                            $rowDuplicat=mysqli_fetch_array($runSelectDuplicat);
                                                                          //  echo "here-->stateid=".$StateId.";DistricId=".$DistricId.";ManagerName=".$ManagerName.";ManagerDesc=".$ManagerDesc.";CodeDate".$CodeDate;
                                                                            
                                                                            
                                                                        if($rowDuplicat['counter']>=1)
									{										
										echo "<script>alert('Email Id OR Contact No. already Exist !')</script>";
										echo "<script>window.open('WarehouseRegistration.php','_self')</script>";
									}
									else
									{						
									$SelState="SELECT s.Stateid,s.StateName,s.StateCode,s.LastUnitWarehouse,d.CityId,d.CityName,d.CityCode,d.LastUnitWarehouse,l.LocId,l.LocationName,l.LocationCode
FROM tblstate AS s JOIN tblcity AS d ON s.Stateid=d.StateId JOIN tbllocation AS l ON d.CityId=l.CityId
WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' AND d.CityId='$DistricId' AND s.Stateid='$StateId' AND LocId='$LocationId'";
									$RunSelState=mysqli_query($con,$SelState);
									$GetSelStateRow=mysqli_fetch_array($RunSelState);
										
									$stateName=$GetSelStateRow['StateName'];
									$stateCode=$GetSelStateRow['StateCode'];
									$StateWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$districName=$GetSelStateRow['CityName'];										
									$districCode=$GetSelStateRow['CityCode'];
									$DistWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$LocationId=$GetSelStateRow['LocId'];
									$LocationCode=$GetSelStateRow['LocationCode'];
									$LocationName=$GetSelStateRow['LocationName'];	
									
									$SelStatUnit="SELECT LastUnitWarehouse FROM tblstate WHERE RecStatus='A' AND Stateid='$StateId'";
									$runSelStatUnit=mysqli_query($con,$SelStatUnit);
									$rowSelStatUnit=mysqli_fetch_array($runSelStatUnit);										
									$StatUnitCode=$rowSelStatUnit['LastUnitWarehouse'];
										
									$SelDistUnit="SELECT LastUnitWarehouse FROM tblcity WHERE RecStatus='A' AND CityId='$DistricId'";
									$runSelDistUnit=mysqli_query($con,$SelDistUnit);
									$rowSelDistUnit=mysqli_fetch_array($runSelDistUnit);
									$DistUnitCode=$rowSelDistUnit['LastUnitWarehouse'];
																			
									$selectExistWarehous="SELECT COUNT(DispatcherId) AS DispatcherCount FROM tbldispatcherregistraion WHERE RecStatus='A' AND LocationId='$LocationId'";
									$runExistWarehouse=mysqli_query($con,$selectExistWarehous);
									$rowExistWarehouse=mysqli_fetch_array($runExistWarehouse);
									
									
										
											$StateInc=$StatUnitCode+'1';
											$DistInc=$DistUnitCode+'1';
											$WarehouseCode="$stateCode/$districCode/$CodeDate/$StateInc/$DistInc";										
											
											$queryDispatcher="INSERT INTO tbldispatcherregistraion(DispatcherName,LocationId,ContactNumber,EmailId,MailingAddress,Description,RecStatus,LastUpdateOn,UpdatebyID,AlternateContactNo)
VALUES('$ManagerName','$LocationId','$ManagerContact1','$ManagerEmail','$ManagerAddress','$ManagerDesc','A','$CurrentDate','$UserId','$ManagerContact2')";
                                                                                        echo "<br/>".$queryDispatcher."<br/>";
											$runDispatcher=mysqli_query($con,$queryDispatcher);
											if($runDispatcher)
											{
												$MaxDispatch="SELECT MAX(warehouseid) AS DispatcherId FROM tblwarehouseregistration";
	      										$runMaxDispatch=mysqli_query($con,$MaxDispatch);
         										$rowMaxDispatch = mysqli_fetch_array($runMaxDispatch);
	      										$MaxDispatchId=$rowMaxDispatch['DispatcherId'];
		
												$updWarehouse="UPDATE tblwarehouseregistration SET WareHouseCode='$WarehouseCode',WareHouseLocationID='$LocationId',WareHouseName='$warehousename',RecStatus='A',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId',stateid='$StateId' WHERE warehouseid='$MaxDispatchId'";
                                                                                                echo "<br/>".$updWarehouse."<br/>";
												$runupdWarehouse=mysqli_query($con,$updWarehouse);
		
												$InsUser="INSERT INTO tbluser(UserId,PASSWORD,UserTypes,FinanceVerificationStatus,RefId,RecStatus,LastUpdateOn,UpdatebyID)
		VALUES('$ManagerEmail','$encryptpass','DS','A','$MaxDispatchId','A','$CurrentDate','$UserId')";
												$runInsUser=mysqli_query($con,$InsUser);
												
												if($runInsUser)
												{
													$updState="UPDATE tblstate SET LastUnitWarehouse='$StateInc' WHERE RecStatus='A' AND Stateid='$StateId'";
													$runupdState=mysqli_query($con,$updState);
													
													$updDist="UPDATE tblcity SET LastUnitWarehouse='$DistInc' WHERE RecStatus='A' AND CityId='$DistricId'";
													$runupdDist=mysqli_query($con,$updDist);
													
													
													
																																						
													
												}
														
											}											
																						
										
									}*/
                                                                            
                                                                        }
                                                                    } 
                                                                

                                                                fclose($file);
                                                                
                                                                die();
                                                                
								
									?>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
        

</body>


</html>







<?php
        
die();

        //session_start();
	include("../Connection/Connection.php");		  
	//$UserId=$_SESSION['UserID'];
        $UserId=1;
        
    
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<body class="nav-md">
    <?php if(isset($_FILES) && (count($_FILES)==0)){ ?>
    <form action="ResultWarehouseRegistration_via_csv.php" method="post" enctype="multipart/form-data">
  File name: <input type="file" name="fname"><br>
  <input type="submit" value="Submit">
</form>
    <?php } ?>
   
    <div class="container body">


       
                            
                            <div class="right_col" role="main">

                                <div>
                                    
                                </div>
                


<div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                               
                                
                 
                          
						<div class="x_content">
								<?php
                                                                if(isset($_FILES) && ($_FILES['fname']['error']==0) && (count($_FILES)>0)){
                                                                        $file = fopen($_FILES['fname']['tmp_name'],"r");
                                                                        $i=0;
                                                                        while ( ($data = fgetcsv($file) ) !== FALSE ) {
                                                                       // while(! feof($file))
                                                                        //{
                                                                            if($i==0){
                                                                                $i++;
                                                                                continue; 
                                                                            }
                                                                            
                                                                            $StateId=isset($data[0])?$data[0]:'';//$_POST['StateList'];
                                                                            $DistricId=isset($data[1])?$data[1]:'';//$_POST['DistricList'];
                                                                            $LocationId=isset($data[2])?$data[2]:'';//$_POST['LocationList'];
                                                                            $ManagerName=isset($data[3])?$data[3]:'';//$_POST['txtmanagername'];
                                                                            $warehousename=isset($data[4])?$data[4]:'';//$_POST['txtwarehousename'];
                                                                            $ManagerEmail=isset($data[5])?$data[5]:'';//$_POST['txtEmail'];
                                                                            $ManagerContact1=isset($data[6])?$data[6]:'';//$_POST['txtContactNumber'];
                                                                            $ManagerContact2=isset($data[7])?$data[7]:'';//$_POST['txtalterContactNumber'];
                                                                            $ManagerAddress=isset($data[8])?$data[8]:'';//$_POST['txtAddress'];
                                                                            $ManagerDesc=isset($data[9])?$data[9]:'';//$_POST['txtDescription'];
                                                                            date_default_timezone_set("Asia/Kolkata"); 
                                                                            $CurrentDate=date('Y-m-d H:i:s A');	
                                                                            $CodeDate=date('dmy');	
                                                                            $Password = rand(1000,5000);
                                                                            $encryptpass=base64_encode($Password);		
                                                                            $selectDuplicate="SELECT COUNT(DispatcherId) AS counter,ContactNumber,EmailId FROM tbldispatcherregistraion WHERE ContactNumber='$ManagerContact1' OR EmailId='$ManagerEmail' AND RecStatus='A'";
                                                                            $runSelectDuplicat=mysqli_query($con,$selectDuplicate);
                                                                            $rowDuplicat=mysqli_fetch_array($runSelectDuplicat);
                                                                          //  echo "here-->stateid=".$StateId.";DistricId=".$DistricId.";ManagerName=".$ManagerName.";ManagerDesc=".$ManagerDesc.";CodeDate".$CodeDate;
                                                                            
                                                                            
                                                                        if($rowDuplicat['counter']>=1)
									{										
										echo "<script>alert('Email Id OR Contact No. already Exist !')</script>";
										echo "<script>window.open('WarehouseRegistration.php','_self')</script>";
									}
									else
									{						
									$SelState="SELECT s.Stateid,s.StateName,s.StateCode,s.LastUnitWarehouse,d.CityId,d.CityName,d.CityCode,d.LastUnitWarehouse,l.LocId,l.LocationName,l.LocationCode
FROM tblstate AS s JOIN tblcity AS d ON s.Stateid=d.StateId JOIN tbllocation AS l ON d.CityId=l.CityId
WHERE s.RecStatus='A' AND d.RecStatus='A' AND l.RecStatus='A' AND d.CityId='$DistricId' AND s.Stateid='$StateId' AND LocId='$LocationId'";
									$RunSelState=mysqli_query($con,$SelState);
									$GetSelStateRow=mysqli_fetch_array($RunSelState);
										
									$stateName=$GetSelStateRow['StateName'];
									$stateCode=$GetSelStateRow['StateCode'];
									$StateWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$districName=$GetSelStateRow['CityName'];										
									$districCode=$GetSelStateRow['CityCode'];
									$DistWarehouseUnit=$GetSelStateRow['LastUnitWarehouse'];
									$LocationId=$GetSelStateRow['LocId'];
									$LocationCode=$GetSelStateRow['LocationCode'];
									$LocationName=$GetSelStateRow['LocationName'];	
									
									$SelStatUnit="SELECT LastUnitWarehouse FROM tblstate WHERE RecStatus='A' AND Stateid='$StateId'";
									$runSelStatUnit=mysqli_query($con,$SelStatUnit);
									$rowSelStatUnit=mysqli_fetch_array($runSelStatUnit);										
									$StatUnitCode=$rowSelStatUnit['LastUnitWarehouse'];
										
									$SelDistUnit="SELECT LastUnitWarehouse FROM tblcity WHERE RecStatus='A' AND CityId='$DistricId'";
									$runSelDistUnit=mysqli_query($con,$SelDistUnit);
									$rowSelDistUnit=mysqli_fetch_array($runSelDistUnit);
									$DistUnitCode=$rowSelDistUnit['LastUnitWarehouse'];
																			
									$selectExistWarehous="SELECT COUNT(DispatcherId) AS DispatcherCount FROM tbldispatcherregistraion WHERE RecStatus='A' AND LocationId='$LocationId'";
									$runExistWarehouse=mysqli_query($con,$selectExistWarehous);
									$rowExistWarehouse=mysqli_fetch_array($runExistWarehouse);
									
									
										
											$StateInc=$StatUnitCode+'1';
											$DistInc=$DistUnitCode+'1';
											$WarehouseCode="$stateCode/$districCode/$CodeDate/$StateInc/$DistInc";										
											
											$queryDispatcher="INSERT INTO tbldispatcherregistraion(DispatcherName,LocationId,ContactNumber,EmailId,MailingAddress,Description,RecStatus,LastUpdateOn,UpdatebyID,AlternateContactNo)
VALUES('$ManagerName','$LocationId','$ManagerContact1','$ManagerEmail','$ManagerAddress','$ManagerDesc','A','$CurrentDate','$UserId','$ManagerContact2')";
                                                                                        echo "<br/>".$queryDispatcher."<br/>";
											$runDispatcher=mysqli_query($con,$queryDispatcher);
											if($runDispatcher)
											{
												$MaxDispatch="SELECT MAX(warehouseid) AS DispatcherId FROM tblwarehouseregistration";
	      										$runMaxDispatch=mysqli_query($con,$MaxDispatch);
         										$rowMaxDispatch = mysqli_fetch_array($runMaxDispatch);
	      										$MaxDispatchId=$rowMaxDispatch['DispatcherId'];
		
												$updWarehouse="UPDATE tblwarehouseregistration SET WareHouseCode='$WarehouseCode',WareHouseLocationID='$LocationId',WareHouseName='$warehousename',RecStatus='A',LastUpdateOn='$CurrentDate',UpdatebyId='$UserId',stateid='$StateId' WHERE warehouseid='$MaxDispatchId'";
                                                                                                echo "<br/>".$updWarehouse."<br/>";
												$runupdWarehouse=mysqli_query($con,$updWarehouse);
		
												$InsUser="INSERT INTO tbluser(UserId,PASSWORD,UserTypes,FinanceVerificationStatus,RefId,RecStatus,LastUpdateOn,UpdatebyID)
		VALUES('$ManagerEmail','$encryptpass','DS','A','$MaxDispatchId','A','$CurrentDate','$UserId')";
												$runInsUser=mysqli_query($con,$InsUser);
												
												if($runInsUser)
												{
													$updState="UPDATE tblstate SET LastUnitWarehouse='$StateInc' WHERE RecStatus='A' AND Stateid='$StateId'";
													$runupdState=mysqli_query($con,$updState);
													
													$updDist="UPDATE tblcity SET LastUnitWarehouse='$DistInc' WHERE RecStatus='A' AND CityId='$DistricId'";
													$runupdDist=mysqli_query($con,$updDist);
													
													
													
																																						
													
												}
														
											}											
																						
										
									}
                                                                            
                                                                        }
                                                                    } 
                                                                

                                                                fclose($file);
                                                                
                                                                die();
                                                                
								
									?>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
        

</body>


</html>

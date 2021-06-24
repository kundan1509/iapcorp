<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
 
if(!empty($_POST["vendor_id"])) {
	$query ="SELECT SUM(tvr.Quatity) as total_replace,tvr.Vendorid,tvr.LocationId FROM tblvendorreplacement tvr WHERE tvr.Vendorid='".$_POST["vendor_id"]."' AND tvr.RecStatus='A' AND tvr.LocationID='".$_POST["location"]."'";
       
	$results = $db_handle->runQuery($query);
        
        $query2 ="SELECT tvi.VendorId,SUM(tvi.Quantity) as total_damage,tvi.TXNTypein_out,tvi.LocationId,tvi.RecStatus FROM tblvendorinventory tvi where tvi.VendorId='".$_POST["vendor_id"]."' AND TXNTypein_out='OUT' AND LocationId='".$_POST["location"]."' AND RecStatus='A'";
       
	$results2 = $db_handle->runQuery($query2);
        $total_items_can_rep=0;
        if($results2[0]['total_damage']>0){
            
            if($results[0]['total_replace']>0){
                $total_items_can_rep = $results2[0]['total_damage'] - $results[0]['total_replace'];
            }else{
                $total_items_can_rep = $results2[0]['total_damage'];
            }
           echo $total_items_can_rep;
        }else {
            echo $total_items_can_rep;
        }
 }
?>

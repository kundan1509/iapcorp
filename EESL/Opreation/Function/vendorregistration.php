<?php

 public function Location($LocationCode,$locationname,$Description){
 
 $sql = "INSERT INTO tblLocation(LocationName,LocationCode,Description,RecStatus)
 VALUES('$LocationCode', '$locationname','$Description','A')";
 
	}
	
	



?>
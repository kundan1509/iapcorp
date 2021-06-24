<?php
class DBController {
	private $host = "174.142.120.42";
	private $user = "root";
	private $password = "iap@123";
	private $database = "led";
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	
	
	function runQuery($query) {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		$result = mysqli_query($conn,$query);
              //  echo "her->".$result;
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	
}
?>

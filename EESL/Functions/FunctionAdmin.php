<?php
function getUsers()
{
	$con=mysqli_connect("127.0.0.1","root","","led");
	$getQuery="SELECT Id,UserId,PASSWORD,UserTypes FROM tbluser WHERE RecStatus='A' AND FinanceVerificationStatus='A'";
	$runQuery=mysqli_query($con,$getQuery);
	if($runQuery)
	{
		while($row=mysqli_fetch_array($runQuery))
		{
			$UserID=$row['Id'];			
			$EmailId=$row['UserId'];
			$Password=$row['PASSWORD'];
			$UserType=$row['UserTypes'];
					
			echo"<tr>
				<td>$EmailId</td>				
							
				<td>
					<a href='../Administrator/ResetPassword.php?UserID=$UserID' class='btn btn-info' data-toggle='tooltip' title='Reset Password'><i class='fa fa-list'></i></a>
					<a href='../Administrator/ResetPassword.php?TempId=$UserID' class='btn btn-danger' data-toggle='tooltip' title='Remove Users' onclick=\"return confirm('Are You sure Remove User Details ?');\"><i class='fa fa-trash'></i></a>
				</td>
			</tr>";
			
	}
	}		
}

?>



 <?php
function reftDetails(){
$RefId=$_SESSION['Ref'];
include("../Connection/Connection.php");
$per_page=5;
                   if (isset($_GET["page"]))
	                     {
                        $page = $_GET["page"];
                        }
                    else
                        {
                     $page=1;

                       }
                    $start_from = ($page-1) * $per_page;
	
	$ref="SELECT ReferralId,ReferralType,EmpId,Name,MobileNo,PanNo,Email FROM tblreferral where recStatus='A'
	ORDER BY ReferralId DESC LIMIT $start_from,$per_page";
	$RunQuery=mysqli_query($con,$ref);
	while($row=mysqli_fetch_array($RunQuery))
	  {
		$ReferralId=$row['ReferralId'];
		$RefrType=$row['ReferralType'];
		$EmpId=$row['EmpId'];
		$Name=$row['Name'];
		$Mobile=$row['MobileNo'];
		$Pan=$row['PanNo'];
		$Email=$row['Email'];
		echo"<tr>
              
				 <td>$RefrType</td>
				  <td>$EmpId</td>
				<td>$Name</td>
				<td>$Mobile</td>
				<td>$Pan</td>
				<td>$Email</td>
				<td>
				 <a href='DetailsReferral.php?ReferralId=$ReferralId' class='btn btn-info' title='View Referral  Records'><i class='fa fa-list'></i></a>
				<a href='refrralList.php?id=$ReferralId' class='btn btn-danger' title='Delete Refrral Records' onclick = 'return confirm('Are you sure want to delete record')'><i class='fa fa-trash'></i></a> 
                </td>
				</tr>";
	}

	
 }
 

?>
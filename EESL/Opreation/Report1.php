<!DOCTYPE html>
<html>
<head>
<script src="jspdf/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="jspdf/bootstrap.min.css"/>
<script type="text/javascript" src="tableExport.js"></script>
<script type="text/javascript" src="jquery.base64.js"></script>
<script type="text/javascript" src="html2canvas.js"></script>
<script type="text/javascript" src="jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="jspdf/jspdf.js"></script>
<script type="text/javascript" src="jspdf/libs/base64.js"></script>
<script type="text/javascript" src="jspdf/bootstrap.min.js"></script>

<?php
include("../Connection/Connection.php");
}

 $qry="SELECT * FROM tbluser";
 $result=mysqli_query($con, $qry);


 $records = array();

 while($row = mysqli_fetch_assoc($result)){ 
	$records[] = $row;
  }

?>
<div class="container">
	<div class="row">
		<div class="btn-group pull-right" style=" padding: 10px;">
			<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
     <span class="glyphicon glyphicon-th-list"></span> Dropdown
   
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#" onclick="$('#employees').tableExport({type:'json',escape:'false'});"> <img src="images/json.jpg" width="24px"> JSON</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'json',escape:'false',ignoreColumn:'[2,3]'});"><img src="images/json.jpg" width="24px">JSON (ignoreColumn)</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'json',escape:'true'});"> <img src="images/json.jpg" width="24px"> JSON (with Escape)</a></li>
								<li class="divider"></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'xml',escape:'false'});"> <img src="images/xml.png" width="24px"> XML</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'sql'});"> <img src="images/sql.png" width="24px"> SQL</a></li>
								<li class="divider"></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'csv',escape:'false'});"> <img src="images/csv.png" width="24px"> CSV</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'txt',escape:'false'});"> <img src="images/txt.png" width="24px"> TXT</a></li>
								<li class="divider"></li>				
								
								<li><a href="#" onclick="$('#employees').tableExport({type:'excel',escape:'false'});"> <img src="images/xls.png" width="24px"> XLS</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'doc',escape:'false'});"> <img src="images/word.png" width="24px"> Word</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'powerpoint',escape:'false'});"> <img src="images/ppt.png" width="24px"> PowerPoint</a></li>
								<li class="divider"></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'png',escape:'false'});"> <img src="images/png.png" width="24px"> PNG</a></li>
								<li><a href="#" onclick="$('#employees').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"> <img src="images/pdf.png" width="24px"> PDF</a></li>
								
  </ul>
</div>
		</div>
	</div>	
	<div class="row" style="height:300px !important;overflow:scroll;">
						<table id="employees" class="table table-striped">
				<thead>			
					<tr class="warning">
						<th>Id</th>
						<th>Name</th>
						<th>Salary</th>
						<th>age</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($records as $rec):?>
					<tr>
						<td><?php echo $rec['Id']?></td>
						<td><?php echo $rec['UserId']?></td>
						<td><?php echo $rec['UserTypes']?></td>
						<td><?php echo $rec['FinanceVerificationStatus']?></td>
					</tr>
					<?php endforeach; ?>
					</tbody>
					</table>
</div>
</div>

</body>
</html>
<script type="text/javascript">
//$('#employees').tableExport();
$(function(){
	$('#example').DataTable();
      }); 
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script>
function pop_up(url){
window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=200,height=200,directories=no,location=no') 
}
</script>
</head>

<body>


<table border=1 align=center>
  <tr bgcolor='#FFC600'>
    <th>sectionID</th>
    <th>name</th><th>price</th>
    <th>image</th>
    <th>add</th>
  </tr>
  <form action=ss.php enctype=multipart/form-data method=post>
  <tr>;
  // give the insert row a special color
  <tr bgcolor='#FFC600'>
    <td>
      <select name=cat_id>
        <option value="a">aa</option>
        <option value="b">bb</option>
        <option value="c">cc</option>
        <option value="d">dd</option>
      </select>
    </td>
    <td><input type=text name=item_name></td>
    <td><input type=text name=item_price></td>
    <td><input type=file name=uploaded_file></td>
    <td><input type=submit name=add value=addNew> </td>
  </tr>
  </form>
</table>
<br>




</body>
</html>
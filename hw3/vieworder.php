<?php 
session_start();
if ((!isset($_SESSION['sess_user_id'])) && (!isset($_SESSION['sess_username']) ))
{
	require 'accountpre.html';
    require 'accountpost.html';
	exit();
	
}
if (!isset($_SESSION['time']))
require 'logout.php';
else {
$t=time();
if(($t-$_SESSION['time']>1200))
require 'logout.php';}
$cid=$_SESSION['sess_user_id'];
$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW"); //Start DB
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con); ?>
<!DOCTYPE html><html><head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script type="text/javascript">

var xmlhttp;
			function goProducts() {				
				var pCategoryID = document.menuForm.productCategorySelect.value;
				var pKeyWords = document.menuForm.productKeyWords.value;
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				}
				else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = handleReply;
				function handleReply() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
					}
				}
				
				xmlhttp.open("GET","getSearchProducts.php?pCategoryID="+pCategoryID+"&pKeyWords="+pKeyWords,true);
				xmlhttp.send();
			}
			

 

</script>




		<title> SoCal Clothing Line</title>
<link rel="stylesheet" type="text/css" href="global.css">
		</head>
		<body>
		
	  <h1></h1>
      <h2>SoCal Clothing Line</h2>
	  <div class="menuDiv">
			<form name="menuForm">
   
  
		
				<b>Search: </b>
				<select name="productCategorySelect">
					<option value="-1" selected="selected">All Product Categories</option>
<?php
    $res=mysql_query("SELECT * FROM productcategory");
 while($row=mysql_fetch_array($res)){
echo '<option value='.$row['productcategoryid'] .'>'.$row['productcategoryname'] .'</option>';
} ?></select>
<input type="text" style="width:300pt" name="productKeyWords"/>
				<input type="button" value="Go" onclick="goProducts()"/>
				<span style = "position:absolute;right:140px;top:-45px">
				<a  href="logout.php"><img src="logout.png" alt="Logout" height="40px" width="80px"></a></span>
				<span style = "position:absolute;right:230px;top:-45px">
				<a  href="account.php"><img src="home.png" alt="Home" height="40px" width="40px"></a></span>

<span style = "position:absolute;right:150px">
				View Cart<a  href="shoppingcart.php"><img src="cart1.png" alt="Shopping Cart" ></a></span>
		
				<span style = "position:relative;left:20px">
				<a class="menuA" href="login.php">Login</a></span></div></form>
	         <table width="100%"  border="0">
			  <tr valign="top">
    <td style="background-image:url('bg4.jpg');height:500px;
                  width:200px;text-align:top;position:relative;top:108px;border: 5px outset #009ACD;"">
				  
			<p><b>Departments:</b></p>
<?php $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'"><b>'.$row['productcategoryname'] .'</b></a></li>';} ?>				<li><a style="color:#E00000" href="home.php"><b>Special Sales</b></a></li> </td>
		<td><div style="background-image:url('bg3.jpg')" id="txtdisplay">
		<?php $sql="SELECT * FROM `order` WHERE customerid=$cid";

$res=mysql_query($sql);
print mysql_error();
if(!($row=mysql_fetch_array($res)))
{ $errmsg='No such orders found'; }  
else
{ echo'
<center><table border="1" bgcolor="#ddd" style="border-collapse:collapse;border: 1px solid black;">
<tr>
<th>Total Cost</th>
<th>Shipping Address</th>
<th>Billing Address</th>
<th>Date</th>

</tr>';

 while($row = mysql_fetch_array($res)){
  echo "<tr>";
  echo "<td>" . $row['totalcost'] . "</td>";
 echo "<td>" . $row['shippingaddress'] . "</td>";
 echo "<td>" . $row['billingaddress'] . "</td>";
  echo "<td>" . $row['date'] . "</td>";
echo '<td><a href="fetch.php?orderid='. $row['orderid'] .'">Click to know order details</a></td>';
  echo "</tr>";}

echo "</table></center>"; 

 } ?>	<div id="display"></div>
</td></tr><tr><td><div id="txtHint"></div></td></tr></table>
<center><input type="button" name="return" value="Return to HomePage" onClick='location.href="account.php"' /></center></div>
<?php mysql_close($con); ?>  
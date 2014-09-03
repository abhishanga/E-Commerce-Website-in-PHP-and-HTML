<?php
session_start();
 //Check whether the session variable SESS_MEMBER_ID is present or not
if ((!isset($_SESSION['sess_user_id'])) && (!isset($_SESSION['sess_username']) ))
{
	require 'accountpre.html';
require 'accountpost.html';
	exit();
}if (!isset($_SESSION['time']))
require 'logout.php';
else {
$t=time();
if(($t-$_SESSION['time']>1200))
require 'logout.php';}

$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
$uname=$_POST['cusrn'];
$password=$_POST['cpwd1'];
$fname=$_POST['firstName'];
$lname=$_POST['lastName'];
$age=$_POST['age'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$uid=$_SESSION['sess_user_id'];
 $sql="UPDATE customer SET username='$uname',password='$password',firstname='$fname',lastname='$lname',email='$email',phone=$phone,age=$age WHERE customerid=$uid";

$retval = mysql_query( $sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}




?>
<!DOCTYPE html>
<html>
<head>

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
						document.getElementById("txtdisplay").innerHTML = xmlhttp.responseText;
					}
				}
				
				xmlhttp.open("GET","getSearchProducts.php?pCategoryID="+pCategoryID+"&pKeyWords="+pKeyWords,true);
				xmlhttp.send();
			}
</script>




		<title> SoCal Clothing Line</title><link rel="stylesheet" type="text/css" href="global.css">
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
}?></select>
<input type="text" style="width:300pt" name="productKeyWords"/>
				<input type="button" value="Go" onclick="goProducts()"/>
				<span style = "position:absolute;right:140px;top:-45px">
				<a  href="logout.php"><img src="logout.png" alt="Logout" height="40px" width="80px"></a></span>
				<span style = "position:absolute;right:230px;top:-45px">
				<a  href="account.php"><img src="home.png" alt="Home" height="40px" width="40px"></a></span>

<span style = "position:absolute;right:150px">
				<a  href="shoppingcart.php"><img src="cart1.png" alt="Shopping Cart" ></a></span>
		
				<span style = "position:relative;left:20px">
				<a class="menuA" href="login.php">Login</a></span></div></form>
	         <table width="100%"  border="0">
			  <tr valign="top">
    <td style="background-image:url('bg4.jpg');height:500px;
                  width:200px;text-align:top;position:relative;top:108px;border: 5px outset #009ACD;">
			<p><b>Departments:</b></p><?php  $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'"><b>'.$row['productcategoryname'] .'</b></a></li>';} ?>
		<li><a style="color:#E00000" href="home.php"><b>Special Sales</b></a></li> </td>
	<td><div style="background-image:url('bg3.jpg')" id="txtdisplay">
		<td><?php echo "<p style='position:relative;top:150px;left:150px'>Profile Updated</p>"; 
	?></td>
</div></table><?php mysql_close($con); ?>

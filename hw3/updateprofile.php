<?php
session_start();
if ((!isset($_SESSION['sess_user_id'])) && (!isset($_SESSION['sess_username']) ))
{
	require 'prelogin.html';
    require 'postlogin.html';
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
$sid= $_SESSION['sess_user_id'];
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

function validateAll() {			
		var doc = document.cAccountForm;
		var vldflag = 1;
		
		var emailvld = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var phonevld = /^\d{10}$/;
		
		if (doc.firstName.value == "" || doc.lastName.value == "") {
			document.getElementById("nameError").innerHTML = "Invalid Name";
			vldflag = 0;
		}
		else {
			document.getElementById("nameError").innerHTML = "";
		}

	
		
		if ((doc.age.value != "" && isNaN(doc.age.value)) || doc.age.value == ""){
			document.getElementById("ageError").innerHTML = "Invalid Age";
			vldflag = 0;
		}
		else {
			document.getElementById("ageError").innerHTML = "";
		}
		
		if ((doc.email.value != "" && !emailvld.test(doc.email.value)) || doc.email.value == "" ) {
			document.getElementById("emailError").innerHTML = "Invalid E-mail";
			vldflag = 0;
		}
		else {
			document.getElementById("emailError").innerHTML = "";
		}
		
		if ((doc.phone.value != "" && !phonevld.test(doc.phone.value)) || doc.phone.value == "") {
			document.getElementById("phoneError").innerHTML = "Invalid Phone (format:xxxxxxxxxx)";
			vldflag = 0;
		}
		else {
			document.getElementById("phoneError").innerHTML = "";
		}
		
		if (doc.cusrn.value == "") {
			document.getElementById("cusrnError").innerHTML = "Invalid Username";
			vldflag = 0;
		}
		else {
			document.getElementById("cusrnError").innerHTML = "";
		}
		
		if (doc.cpwd1.value == "") {
			document.getElementById("cpwd1Error").innerHTML = "Invalid Password";
			vldflag = 0;
		}
		else {
			document.getElementById("cpwd1Error").innerHTML = "";
		}
		
		
		
		
		
		if (vldflag == 1) {
			return true;
		}
		else {
			return false;
		}
	}

</script></head>
<body>
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
				  
			<p><b>Departments:</b></p>
<?php  $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'"><b>'.$row['productcategoryname'] .'</b></a></li>';} ?>		<li><a style="color:#E00000" href="home.php">Special Sales</a></li> </td>
		<td><div style="background-image:url('bg3.jpg')" id="txtdisplay">
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="40%">
	<tr>
					<td> 
					<?php $sql="SELECT * from customer WHERE customerid=$sid";
$res=mysql_query($sql);
print mysql_error();
if($row = mysql_fetch_array($res)) { ?>
<form name="cAccountForm" method = "POST" action = "update.php" onsubmit="return validateAll();" method="POST">
	<table>
		<p style="font-size:24px;font-weight:bond;color:blue;font-family:calibri">Update Details</p>
		<p style="font-size:14px;color:red">(*) required</p>
			<tr>
			<td><label for="cusrn"><span style="color:red">*</span>Username:</label></td>
			<td><input type="text" id="cusrn" name="cusrn" size="20" value="<?php echo $row['username']; ?>"/>
			<span type="text" id="cusrnError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
		<tr>
			<td><label for="cpwd1"><span style="color:red">*</span>Password:</label></td>
			<td><input type="password" id="cpwd1" name="cpwd1" size="20" value="<?php echo $row['password']; ?>"/>
			<span type="text" id="cpwd1Error" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
		<tr>
			<td><label for="firstName"><span style="color:red">*</span>Name (First, Last):</label></td>
			<td><input type="text" id="firstName" name="firstName" size="20" placeholder="First name" value="<?php echo $row['firstname']; ?>"/>
			<input type="text" id="lastName" name="lastName" size="20" placeholder="Last name" value="<?php echo $row['lastname']; ?>"/>
			<span type="text" id="nameError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
	
	
		<tr>
			<td><label for="age" style="position:relative;left:0.5em"><span style="color:red">*</span>Age:</label></td>
			<td><input type="text" id="age" name="age" size="17" value="<?php echo $row['age']; ?>" /> years
			<span type="text" id="ageError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
		<tr>
			<td><label for="email" style="position:relative;left:0.5em"><span style="color:red">*</span>E-mail:</label></td>
			<td><input type="text" id="email" name="email" value="<?php echo $row['email']; ?>"/>
			<span type="text" id="emailError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
		<tr>
			<td><label for="phone" style="position:relative;left:0.5em"><span style="color:red">*</span>Phone:</label></td>
			<td><input type="text" id="phone" name="phone" value="<?php echo $row['phone']; ?>"/>
			<span type="text" id="phoneError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
		<? } ?>
	
		
	</table>
	<input type="submit" value="Change Details" name="submit" />
</form></td></tr></table>

		</div>
	</body>
</html>
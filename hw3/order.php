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
function get_price($pid){
		$result=mysql_query("select productprice from product where productid=$pid") or die("select productname from product where productid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['productprice'];
	}
	
	function get_order_total(){
		$max=count($_SESSION['cart']);
		$sum=0;
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid);
			$sum+=$price*$q;
		}
		return $sum;
	}

$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW"); //Start DB
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
//we take security measure on SQL injection "mysql_real_escape_string()"
$bill=$_POST['cBillingAddress'];
$ship=$_POST['cShippingAddress'];
$type=$_POST['cCreditCardType'];
$fname=$_POST['firstName'];
$lname=$_POST['lastName'];
$number=$_POST['cCreditCardNumber'];
$cvv=$_POST['cvv'];


/*

      $returnString .='Transaction Details:';
      $returnString .= '<tr><td>First Name:' .$fname.'</td></tr>';
	  $returnString .= '<tr><td>Last Name:' .$lname.'</td></tr>';
	  $returnString .= '<tr><td>Credit Card Type:' .$type.'</td></tr>';
	  $returnString .= '<tr><td>CVV:' .$cvv.'</td></tr>';
	  $returnString .= '<tr><td>Billing Address:' .$bill.'</td></tr>';
	  $returnString .= '<tr><td>Shipping Address' .$ship.'</td></tr>';*/
	 
	  
	  
	  
   $returnString .='Thank you for your order!';
      

 

 // this will be our return value to our ajax request
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
<?php $con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
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
	         <table width="100%" height="100%"  border="0">
			  <tr valign="top">
    <td style="background-image:url('bg4.jpg');height:500px;
                  width:200px;text-align:top;position:relative;top:108px;border: 5px outset #009ACD;">
			<p><b>Departments:</b></p><?php  $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'"><b>'.$row['productcategoryname'] .'</b></a></li>';} ?>
		<li><a style="color:#E00000" href="home.php"><b>Special Sales</b></a></li> </td>
	<td><div style="background-image:url('bg3.jpg');height:1000px;" id="txtdisplay" >
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
	<tr>
					<td>
<?php echo $returnString; ?></td>   </tr></table></div>
</body>
<?php mysql_close($con); ?>
<?php
	session_start();
	$currency='$';
	if ((!isset($_SESSION['sess_username']) ))
{    
	$_SESSION['checkout']=1;
	
    require 'accountpre.html';
echo "<p style='position:absolute;left:270px;top:350px'> <font color=red size='5pt'><b>Create a new account or Login!</b></p></font>";
require 'accountpost.html';
	exit();
	
 } else { if(!isset($_SESSION['time']))
 $_SESSION['time']=time();
 else{
 $t=time();
if(($t-$_SESSION['time']>1200))
require 'logout.php';} }
	$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
	
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);


	function get_product_name($pid){
		$result=mysql_query("select productname from product where productid=$pid") or die("select productname from product where productid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['productname'];
	}
	function get_price($pid){
		$result=mysql_query("select productprice from product where productid=$pid") or die("select productname from product where productid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['productprice'];
	}
	function remove_product($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				unset($_SESSION['cart'][$i]);
				break;
			}
		}
		$_SESSION['cart']=array_values($_SESSION['cart']);
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
	function addtocart($pid,$q){
		if($pid<1 or $q<1) return;
		
		if(is_array($_SESSION['cart'])){
			if(product_exists($pid)) return;
			$max=count($_SESSION['cart']);
			$_SESSION['cart'][$max]['productid']=$pid;
			$_SESSION['cart'][$max]['qty']=$q;
		}
		else{
			$_SESSION['cart']=array();
			$_SESSION['cart'][0]['productid']=$pid;
			$_SESSION['cart'][0]['qty']=$q;
		}
	}
	function product_exists($pid){
		$pid=intval($pid);
		$max=count($_SESSION['cart']);
		$flag=0;
		for($i=0;$i<$max;$i++){
			if($pid==$_SESSION['cart'][$i]['productid']){
				$flag=1;
				break;
			}
		}
		return $flag;
	}


	

	mysql_query("SET autocommit = 0");
	if($_REQUEST['command']=='update'){
		
		$billingaddress=$_REQUEST['cBillingAddress'];
		$shippingaddress=$_REQUEST['cShippingAddress'];
		$total=get_order_total();
		$date=date('Y-m-d');
		$customerid=$_SESSION['sess_user_id'];
		mysql_query("start transaction");
$sql="INSERT INTO `order` (`totalcost`,`billingaddress`,`shippingaddress`,`customerid`,`date`)  VALUES($total,'$billingaddress','$shippingaddress',$customerid,'$date')";
$retval = mysql_query( $sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

$orderid=mysql_insert_id();
		
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid);
			
			$sql="INSERT INTO orderdetail (productid,orderid,productprice,productquantity)  VALUES($pid,$orderid,$price,$q)"; 
$retval = mysql_query( $sql); 
if(! $retval )
{
  die('Could not enter data: ' . mysql_error()); mysql_query("rollback");
}
else
{mysql_query("commit"); header("location:order.php"); }
		/*die('Thank You! your order has been placed!')*/;

	} }
?>
<!DOCTYPE html>
<html>
<head>

<title>Billing Info</title>
<script type="text/javascript">
	function validateall(){
		
	var doc = document.cAccountForm;
var vldflag = 1;
			
	
		if (doc.firstName.value == "" || doc.lastName.value == "") {
			document.getElementById("nameError").innerHTML = "Invalid Name";
			vldflag = 0;
		}
		else {
			document.getElementById("nameError").innerHTML = "";
		}
		
		if (doc.cCreditCardNumber.value == "" || isNaN(doc.cCreditCardNumber.value)) {
			document.getElementById("cCreditCardNumberError").innerHTML = "Invalid Credit Card Number";
			vldflag = 0;
		}
		
		
		if (doc.cvv.value == "" || isNaN(doc.cvv.value)) {
			document.getElementById("cvverror").innerHTML = "Not Valid CVV.";
			vldflag = 0;
		}
		
		var isChecked = false;
		for (i = 0; i < doc.cCreditCardType.length; i++) {
			if (doc.cCreditCardType[i].checked) {
				isChecked = true;
				break;
			}
		}		
		if (isChecked == false) {
			document.getElementById("cCreditCardTypeError").innerHTML = "Invalid Credit Card Type";
			vldflag = 0;
		}
		else {
			document.getElementById("cCreditCardTypeError").innerHTML = "";
		}
		if (doc.cBillingAddress.value == "") {
			document.getElementById("cBillingAddressError").innerHTML = "Invalid Billing Address";
			vldflag = 0;
		}
		else {
			document.getElementById("cBillingAddressError").innerHTML = "";
		}
		if (doc.cShippingAddress.value == "") {
			document.getElementById("cShippingAddressError").innerHTML = "Invalid Shipping Address";
			vldflag = 0;
		}
		else {
			document.getElementById("cShippingAddressError").innerHTML = "";
		}
		
		if (vldflag == 0) {
			return false;
		} 
        else 
return true;		}
		function validate()
		{ var r1=true; var r2=true; var r3=true;r1=validateall(); r2=cc();r3=cvv();
		var doc = document.cAccountForm;
		if(!(r1==true && r2==true && r3==true))
		{ return false;
		}
		
		doc.command.value='update';
		doc.submit();
		}
		function cc(){
if(!(document.getElementById('cCreditCardNumber').value.length == 16)){
document.getElementById("cCreditCardNumberError").innerHTML = "Enter a 16 digit number";
return false;}
else
return true;
} 
function cvv(){
if(!(document.getElementById('cvv').value.length == 3)){
document.getElementById("cvverror").innerHTML = "Enter a 3 digit number";
return false;}
else
return true;
} 
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
}?></select>
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
<?php  $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'"><b>'.$row['productcategoryname'] .'</b></a></li>';} ?>				<li><a style="color:#E00000" href="home.php"><b>Special Sales</b></a></li> </td>
		<td><div style="background-image:url('bg3.jpg')" id="txtdisplay">
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
	<tr>
					<td><b>Order Summary:</b><br><?php   //Post Data received from product list page.
   
	echo '<ul>';
	    
			
			

		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++)
        {
          $pid=$_SESSION['cart'][$i]['productid'];
		   $results = mysql_query("SELECT productname,productprice,productimage FROM product WHERE productid=$pid LIMIT 1");
		   print mysql_error();
		   $obj=mysql_fetch_array($results);
		   $q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid);
		   
		    echo '<li class="cart-itm">';
			echo '<p>'.$obj['productname'].'</p> ';
			echo '<p>Price'. $currency.$obj['productprice'] . '</p>' ;
           
			echo '<img style="border:#777 1px solid; " src="'.$obj['productimage'] . '"  width="80" height="80"></img>';
            echo '<div class="p-qty">Qty : '.$q.'</div>';
            
		
            echo '</li>';
			
			
        }
    
		
		echo '<strong>Total : '.$currency.get_order_total().'</strong>  ';
	    
		
	  ?> </td><td>
<form name="cAccountForm" onsubmit="return validate()" >
    <input type="hidden" name="command" />
<table>

        <p style="font-size:24px;font-weight:bond;color:blue;font-family:calibri">Billing Info</p>
    
        	Order Total:<?php echo get_order_total()?>
            <p style="font-size:14px;color:red">(*) required</p>
		
			<td><label for="firstName"><span style="color:red">*</span>Name (First, Last):</label></td>
			<td><input type="text" id="firstName" name="firstName" size="20" placeholder="First name" value=""/><br>
			<input type="text" id="lastName" name="lastName" size="20" placeholder="Last name" value=""/><br>
			<span type="text" id="nameError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
	
		<tr>
			<td><label for="cCreditCardNumber"><span style="color:red">*</span>Credit Card Number:</label></td>
			<td><input type="number" id="cCreditCardNumber" name="cCreditCardNumber" size="20" value=""/><br>
			<span type="text" id="cCreditCardNumberError" style="color:red;font-size:small"></span></td><br>
		</tr>
		<tr>
			<td><label for="cCreditCardType"><span style="color:red">*</span>Credit Card Type:</label></td><br>
			<td>
				<input type="radio" name="cCreditCardType" id="visa" value="Visa" />Visa<br>
				
				<input type="radio" name="cCreditCardType" id="mastercard" value="MasterCard" />MasterCard<br>
				
				<input type="radio" name="cCreditCardType" id="discover" value="Discover" />Discover<br>
				
				<input type="radio" name="cCreditCardType" id="americanexpress" value="AmericanExpress" />AmericanExpress<br>
				<span type="text" id="cCreditCardTypeError" style="color:red;font-size:small;position:relative;left:3.5em"></span>
			</td>
		</tr>
		
		<tr>
			<td><label for="cvv"><span style="color:red">*</span>CVV:</label></td>
			<td><input type="number" id="cvv" name="cvv" size="3" value=""/><br>
			<span type="text" id="cvverror" style="color:red;font-size:small"></span></td>
		</tr>
         <tr>
			<td><label for="cBillingAddress"><span style="color:red">*</span>Billing Address:</label></td>
			<td><input type="text" id="cBillingAddress" name="cBillingAddress" size="60" value=""/><br>
			<span type="text" id="cBillingAddressError" style="color:red;font-size:small"></span></td>
		</tr>
		<tr>
			<td><label for="cShippingAddress"><span style="color:red">*</span>Shipping Address:</label></td>
			<td><input type="text" id="cShippingAddress" name="cShippingAddress" size="60" value=""/><br>
			<span type="text" id="cShippingAddressError" style="color:red;font-size:small;position:relative;left:0.5em"></span></td>
		</tr>
          
            <tr><td>&nbsp;</td><td><input type="submit" value="Place Order"  /></td></tr>
			  <tr><td>&nbsp;<input type="button" value="Change your order?" onclick="javascript:history.back()" /></td></tr>
        </table>

</form></td>

		</div>
		</td>
				</tr>
			</table></div></td></tr></table>

          
          
          
        </table>
	</div>
</form>
</body>
<?php mysql_close($con); ?>
</html>

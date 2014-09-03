<?php 
	session_start();
	if (isset($_SESSION['time'])){
$t=time();
if(($t-$_SESSION['time']>300))
require 'logout.php';}
	$currency='$';
	$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
	$pid=$_GET['productcategoryid'];
		
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
if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		header("location:shoppingcart.php");
		exit();
	}
	?>
	<!DOCTYPE html>
	<html>
<head>




<script language="javascript">
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
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
				  
			<p>Departments:</p>
<?php  $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'">'.$row['productcategoryname'] .'</a></li>';} ?>		<li><a style="color:#E00000" href="home.php">Special Sales</a></li> </td>
		<td><div style="background-image:url('bg3.jpg')" id="txtdisplay">
		<table border="0" align="center" cellpadding="10" cellspacing="10" width="50%">
	<tr>
					<td> 
   
 
	<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />

</form>

    <?php
    //current URL of the Page. cart_update.php redirects back to this URL

	$results = mysql_query("SELECT * from product");
	
    print mysql_error();
	
  while($obj = mysql_fetch_array($results))
        {  
			 
            //echo '<form method="post" action="cart_update.php">';
			echo '<div style="display: inline-block;"><img style="border:#777 1px solid; " src="'.$obj['productimage'] . '"  width="80" height="80"></img>';
           echo '<p>'.$obj['productname'].'<br />Price $'.$obj['productprice'].'</p>';  
				$a=$obj['productid'];
				 $a=$obj['productid'];
		   $res=mysql_query("SELECT * from specialsales  WHERE productid=$a");
	        if($row=mysql_fetch_array($res))
			{ echo '<p>Sales Price $' .$row['discount'] . '</p>';}
			else echo '<p>Sales Price N/A</p>';
			echo '<p><input type="image" src="cart2.png" height="25" onclick="addtocart('.$a.')"/>    </p></div>';
		

	 }  

			
 
 


         
           
         ?>
    
    
    
			
			</table></div></td></tr></table>
			

    
</div>

</body>
<?php mysql_close($con); ?>
</html>


	

	
	
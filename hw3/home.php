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
		function get_product_name($pid){
		$result=mysql_query("select productname from product where productid=$pid") or die("select productname from product where productid=$pid"."<br/><br/>".mysql_error());
		$row=mysql_fetch_array($result);
		return $row['productname'];
	}
	function get_price($pid){
		$result=mysql_query("select productprice from product where productid=$pid") or die("select productprice from product where productid=$pid"."<br/><br/>".mysql_error());
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
if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		header("location:shoppingcart.php");
		exit();
	}
	?>
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
						document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
					}
				}
				
				xmlhttp.open("GET","getSearchProducts.php?pCategoryID="+pCategoryID+"&pKeyWords="+pKeyWords,true);
				xmlhttp.send();
			}
</script>
<style>@font-face {
  font-family: 'Lato';
  font-style: normal;
  font-weight: 400;
  src: local('Lato Regular'), local('Lato-Regular'), url(http://themes.googleusercontent.com/static/fonts/lato/v7/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
}

body {
	
	font-family: "Lato" ;
}

</style>




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
} ?></select>
<input type="text" style="width:300pt" name="productKeyWords"/>
				<input type="button" value="Go" onclick="goProducts()"/>
<span style = "position:absolute;right:150px">
				<a  href="shoppingcart.php"><img src="cart1.png" alt="Shopping Cart" ></a></span>
		
				<span style = "position:relative;left:20px">
				<a class="menuA" href="login.php">Login</a></span></div></form>
	         <table width="100%"  border="0">
			  <tr valign="top">
    <td style="background-image:url('bg4.jpg');height:500px;
                  width:200px;text-align:top;position:relative;top:108px;border: 5px outset #009ACD;">
				  
			<p>Departments:</p>
<?php $res=mysql_query("SELECT * FROM productcategory");
			while($row=mysql_fetch_array($res)){ 
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'">'.$row['productcategoryname'] .'</a></li>';} ?>				<li><a style="color:#E00000" href="home.php">Special Sales</a></li> </td>
		<td><div style="background-image:url('bg3.jpg')" id="txtdisplay">
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="40%">
	<tr>
					<td> 
   
 
	<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />
</form>
    <?php
    //current URL of the Page. cart_update.php redirects back to this URL

    $pid=$_GET['productcategoryid'];
	$results = mysql_query("SELECT * from specialsales as s,product as p WHERE s.productid=p.productid ");
    print mysql_error();
	
        //fetch results set as object and output HTML
        while($obj = mysql_fetch_array($results))
        {  echo '<table border="0" cellpadding="5" cellspacing="5" width="600">';
			echo '<div class="product">'; 
            //echo '<form method="post" action="cart_update.php">';
			echo '<td width="20%" valign="top"><img style="border:#777 1px solid; " src="'.$obj['productimage'] . '"  width="80" height="80"></img></td>';
           echo '<td><b>'.$obj['productname'].'</b><br />Price<span style="color:red;text-decoration:line-through;">$ '.$obj['productprice'].'</span> ';  
		   echo 'Sales Price $' .$obj['discount'] . '<br>';
				$a=$obj['productid'];
			echo '<input type="image" src="cart2.png" height="25"  onclick="addtocart('.$a.')"  </div> </td> </tr>'; } 


         
           
         ?>
    
    
    
 
			</table></div></td></tr></table>
			

    
</div>

</body>
</html>


	

	
	
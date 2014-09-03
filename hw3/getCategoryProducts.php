<?php 
session_start();
if (isset($_SESSION['time'])){
$t=time();
if(($t-$_SESSION['time']>1200))
require 'logout.php';}
	$currency='$';
	$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
	

	
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


<script type="text/javascript">
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


</script><style>@font-face {
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
echo '<li><a href="getCategoryProducts.php?productcategoryid='.$row['productcategoryid'] .'"><b>'.$row['productcategoryname'] .'</b></a></li>';} ?>
		<li><a style="color:#E00000" href="home.php"><b>Special Sales</b></a></li> </td>
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
	$sql="SELECT * from product WHERE productcategoryid=$pid";
	$results = mysql_query("SELECT * from product WHERE productcategoryid=$pid");
    print mysql_error();
		   
    
        //fetch results set as object and output HTML
        while($obj=mysql_fetch_array($results))
        {  echo '<table border="0" cellpadding="5" cellspacing="5" width="600">';
			echo '<div class="product">'; 
            //echo '<form method="post" action="cart_update.php">';
			echo '<tr><td width="20%" valign="top"><img style="border:#777 1px solid; " src="'.$obj['productimage'] . '"  width="80" height="80"></img></td>';
           echo '<td><b>'.$obj['productname'].'</b><br />Price $'.$obj['productprice'].'<br>';  
		   $a=$obj['productid'];
		   $res=mysql_query("SELECT * from specialsales  WHERE productid=$a");
	        if($row=mysql_fetch_array($res))
			{ echo '<p style="font-weight:bond;color:blue;font-family:calibri">Sales Price $' .$row['discount'] . '</p><br>';}
			else echo 'Sales Price N/A<br>';
				$a=$obj['productid'];
			echo '<input type="image" src="cart2.png" height="25" onclick="addtocart('.$a.')"/>   </div> </td>';}
				                                                                          
			
	
	
  																		  
																						  
																						 echo '</tr></table>';  


         
           
         ?>
    
    
    
			
</div> <td style="position:relative;top:10px;"></td></td></tr></table>
			

    
</div>

</body><?php mysql_close($con); ?>
</html>


	

	
	
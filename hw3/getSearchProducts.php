<?php 
session_start();
if (isset($_SESSION['time'])){
$t=time();
if(($t-$_SESSION['time']>300))
require 'logout.php';}
$pid=intval($_GET['pCategoryID']);
$desc=$_GET['pKeyWords']; 
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
		header("shoppingcart.php");
		exit();
	}

$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW"); //Start DB
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		header("location:shoppingcart.php");
		exit();
	}
if($pid==-1)
$sql="SELECT * FROM product WHERE productname LIKE '%" .$desc. "%'";
else
$sql="SELECT * FROM product WHERE productcategoryid = '".$pid."' AND productname LIKE '%" .$desc. "%'";
$result = mysql_query($sql);
echo '<form name="form1">
	<input type="hidden" name="productid" />
    <input type="hidden" name="command" />
</form>';



while($row = mysql_fetch_array($result)) {
echo "<table border='0' cellpadding='5' cellspacing='5' width='400'>";
  echo "<tr>";
   echo '<td><img src="'.$row['productimage'] . '"width="80" height="80" />  </td>'; 
echo '<td><b>'.$row['productname'].'</b><br />Price $'.$row['productprice'].'<br>'; 
$a=$row['productid'];
		   $res=mysql_query("SELECT * from specialsales  WHERE productid=$a");
	        if($obj=mysql_fetch_array($res))
			{ echo 'Sales Price $' .$obj['discount'] . '<br>';}
			else echo 'Sales Price N/A<br>';
			/*echo '<input type="image" src="cart2.png" height="25" onclick="addtocart('.$a.')"/></td>';*/ 

  echo "</tr>";
}
echo "</table>";

mysql_close($con); 
?>
<!DOCTYPE html>
	<html>
<head>


<script language="javascript">
	function addtocart(pid){
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
	} </script></head></html>
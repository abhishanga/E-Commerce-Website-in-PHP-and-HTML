<?php session_start();
$username=$_POST['username'];
$password=$_POST['password'];
$errmsg="";

if(strlen($username)==0)
{ $errmsg='Invalid login'; }

if(strlen($password)==0)
{ $errmsg='Invalid login'; }

if(strlen($password)==0 && strlen($username)==0)
{ $errmsg=""; }
// Goto DB to validate when both exist
if(strlen($username)>0 && strlen($password)>0)
{ $sql="SELECT * from customer where username='$username' and password='$password'";

$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
$res=mysql_query($sql);
print mysql_error();
if(!($row=mysql_fetch_array($res)))
{ $errmsg='<p><center><b>Invalid login</b></center></p>'; }  
else
{
    $_SESSION['sess_user_id'] = $row['customerid'];
	$_SESSION['sess_username'] = $row['username'];
	}
	
mysql_close($con);} //End DB

if(strlen($errmsg)>0)
{
require 'accountpre.html';
echo "<p style='position:absolute;left:270px;top:350px'> <font color=red size='5pt'><b>$errmsg</b></p></font>";
require 'accountpost.html';


}
else if(!$res)
{ 
require 'accountpre.html';
require 'accountpost.html';exit();
}


else
{ if($_SESSION['checkout']==1)
{ $_SESSION['checkout']=0; require 'billing.php'; exit();}
else
{header("location:account.php"); exit();} }

    
?>
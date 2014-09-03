<?php
$con=mysql_connect("cs-server.usc.edu:7123","root","NEWPW");
if(!$con)
{ die('Could not connect to database'.mysql_error());}
mysql_select_db("socal",$con);
if(isset($_SESSION['time']))
{
 $t=time();
if(($t-$_SESSION['time']>3000))
require 'logout.php';}
function NewUser() {
$fname=$_POST['firstName'];
$lname=$_POST['lastName'];
$uname=$_POST['cusrn'];	
$password=$_POST['cpwd1'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$age=$_POST['age'];

$sql="INSERT INTO customer (username,password,firstname,lastname,age,email,phone)  VALUES('$uname','$password','$fname','$lname',$age,'$email',$phone)";


$retval = mysql_query( $sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
else
{ echo "Sucessful Registration";
 } }


function SignUp() 
{ $uname=$_POST['cusrn'];	
$password=$_POST['cpwd1'];
if(!empty($uname)) //checking the 'user' name which is from Sign-Up.html, is it empty or have some text 
{  $sql="SELECT * FROM customer WHERE username ='$uname' AND password ='$password'"; 
$query = mysql_query($sql);
print mysql_error();
if(!($row = mysql_fetch_array($query))) 
 { newuser(); }
else 
{  echo "Sorry same username exists"; } } } 
if(isset($_POST['submit'])) 
{ SignUp(); }



?>


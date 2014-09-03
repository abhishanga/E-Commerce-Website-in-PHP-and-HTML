<?php

session_start();
unset($_SESSION['sess_username']);
session_destroy();

header("Location: login.php");
exit();

?>
<?php
//Declare********************************************************
$databaseServerName = "localhost"; //Tells the system that the database is stored locally
$databaseUserName = "root";
$databasePassword = "";
$databaseName = "shop"; //The name of my databaseecho $VaribleArray[0];
$Connection = mysqli_connect($databaseServerName,$databaseUserName,$databasePassword,$databaseName);
//***************************************************************

if (!$Connection) {
    die("Connection failed: " . mysqli_connect_error());
}
//******************************************************

?>
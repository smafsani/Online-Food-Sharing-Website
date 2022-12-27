<?php
	$servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'mydb';
    $con = mysqli_connect($servername, $username, $password, $dbname);
    if(!$con){
        die("Connection Failed Due To: ".mysqli_connect_error());
    }
	/*
	$servername = 'sql113.epizy.com';
    $username = 'epiz_32997301';
    $password = 'dgJHgCYbJmwMN';
    $dbname = 'epiz_32997301_foodshare';
    $con = mysqli_connect($servername, $username, $password, $dbname);
    if(!$con){
        die("Connection Failed Due To: ".mysqli_connect_error());
    }*/
?>
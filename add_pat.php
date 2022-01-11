<?php

$regNo = $_POST["regNo"];
$regDate = $_POST["regDate"];
$fullname = $_POST["fullname"];
$gender = $_POST["gender"];
$phoneno = $_POST["phoneno"];
$age = $_POST["age"];
$address = $_POST["address"];

$conn = mysqli_connect("localhost","root","","hhc_main") or die("Connection failed!");

$sql = "INSERT INTO patient_info VALUES('{$regNo}','{$fullname}','{$gender}','{$phoneno}','{$address}',{$age},'{$regDate}')";

if(mysqli_query($conn, $sql)){
	echo 1;
}else{
	echo 0;
}

?>
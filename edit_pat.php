<?php

$conn = mysqli_connect("localhost","root","","hhc_main") or die("Connection failed!");

if($_POST["action"] == "UPDATEPATIENT"){
	$regNo = $_POST["regNo"];
	$fullname = $_POST["fullname"];
	$gender = $_POST["gender"];
	$phoneno = $_POST["phoneno"];
	$age = $_POST["age"];
	$address = $_POST["address"];

	$upd_pat_details = "UPDATE patient_info SET p_fullname='{$fullname}', p_gender='{$gender}', p_pno='{$phoneno}', p_age='{$age}', p_address='{$address}' WHERE reg_no='{$regNo}'";

	if(mysqli_query($conn, $upd_pat_details)){
		echo 1;
	}else{
		echo 0;
	}
}

if($_POST["action"] == "GETPATIENT"){
	$reg_no = $_POST["reg_no"];
	$get_pat_details = "SELECT * FROM patient_info WHERE reg_no='{$reg_no}'";

	$res_pat_details = mysqli_query($conn, $get_pat_details) or die("SQL Query Failed");
	$out_pat_details = array();

	if(mysqli_num_rows($res_pat_details)>0){
		while($row = mysqli_fetch_assoc($res_pat_details)){
			$out_pat_details[] = $row;
		}
	}else{
		$out_pat_details = "EMPTY_PATIENT";
	}

	echo json_encode($out_pat_details);
}

?>
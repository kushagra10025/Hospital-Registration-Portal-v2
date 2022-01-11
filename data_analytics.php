<?php

$conn = mysqli_connect("localhost","root","","hhc_main") or die("Connection failed!");

if($_POST["action"] == "VISITS"){
	$query_visit = "SELECT d.doctor_name, COUNT(v.visit_id) 'count' FROM visit_details AS v LEFT JOIN doctor_info AS d ON d.doctor_id = v.doctor_id GROUP BY v.doctor_id ORDER BY count DESC";

	$result_visit = mysqli_query($conn, $query_visit) or die("SQL Query Failed");
	$output_visit = array();

	if(mysqli_num_rows($result_visit)>0){
		while($row = mysqli_fetch_assoc($result_visit)){
			$output_visit[] = $row;
		}
	}else{
		$output_visit = "EMPTY_VISITS";
	}

	echo json_encode($output_visit);
}

if($_POST["action"] == "PLACES"){
	$query_places = "SELECT p.p_address, COUNT(v.visit_id) 'count' FROM visit_details AS v LEFT JOIN patient_info AS p ON p.reg_no = v.reg_no GROUP BY p.p_address ORDER BY count DESC";

	$result_places = mysqli_query($conn, $query_places) or die("SQL Query Failed");
	$output_places = array();

	if(mysqli_num_rows($result_places)>0){
		while($row = mysqli_fetch_assoc($result_places)){
			$output_places[] = $row;
		}
	}else{
		$output_places = "EMPTY_PLACES";
	}

	echo json_encode($output_places);
}

if($_POST["action"] == "SPECIALITY"){
	$query_speciality = "SELECT d.doctor_speciality, count(v.visit_id) 'count' FROM visit_details AS v LEFT JOIN doctor_info AS d ON d.doctor_id = v.doctor_id GROUP BY d.doctor_speciality ORDER BY count DESC;";

	$result_speciality = mysqli_query($conn, $query_speciality) or die("SQL Query Failed");
	$output_speciality = array();

	if(mysqli_num_rows($result_speciality)>0){
		while($row = mysqli_fetch_assoc($result_speciality)){
			$output_speciality[] = $row;
		}
	}else{
		$output_speciality = "EMPTY_SPECIALITY";
	}

	echo json_encode($output_speciality);
}

if($_POST["action"] == "SPECIALITYMODE"){
	$query_speciality = "SELECT d.doctor_speciality, v.consultation_mode, COUNT(v.visit_id) 'count' FROM visit_details AS v LEFT JOIN doctor_info AS d ON d.doctor_id = v.doctor_id GROUP BY d.doctor_speciality, v.consultation_mode";

	$result_speciality = mysqli_query($conn, $query_speciality) or die("SQL Query Failed");
	$output_speciality = array();

	if(mysqli_num_rows($result_speciality)>0){
		while($row = mysqli_fetch_assoc($result_speciality)){
			$output_speciality[] = $row;
		}
	}else{
		$output_speciality = "EMPTY_SPECIALITYMODE";
	}

	echo json_encode($output_speciality);
}

if($_POST["action"] == "UNITED"){
	$query_utd = "SELECT p.reg_no, p.p_fullname, p.p_gender, p.p_pno, p.p_address, p.p_age, p.p_regdate, v.visit_id,v.date_of_visit, d.doctor_name, d.doctor_speciality, v.consultation_fees,v.consultation_mode, v.payment_method, v.payment_status, v.payment_date, v.remarks FROM patient_info AS p LEFT JOIN visit_details AS v ON v.reg_no = p.reg_no LEFT JOIN doctor_info AS d ON d.doctor_id = v.doctor_id;";

	$result_utd = mysqli_query($conn, $query_utd) or die("SQL Query Failed");
	$output_utd = array();

	if(mysqli_num_rows($result_utd)>0){
		while($row = mysqli_fetch_assoc($result_utd)){
			$output_utd[] = $row;
		}
	}else{
		$output_utd = "EMPTY_UNITED";
	}

	echo json_encode($output_utd);
}

?>
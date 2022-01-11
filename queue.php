<?php

$conn = mysqli_connect("localhost","root","","hhc_main") or die("Connection failed!");

$date = new DateTime();
$date->setTimezone(new DateTimeZone('Asia/Kolkata'));
$currentDate = $date->format("Y-m-d");
// $currentDate = "2022-01-07";

$sql = "SELECT visit_id, reg_no, doctor_id, date_of_visit, entry_time FROM visit_details WHERE date_of_visit='{$currentDate}'";

$result = mysqli_query($conn, $sql) or die("SQL Query Failed");
$output = array();
$lastInsertToQueue = false;

if(mysqli_num_rows($result)>0){
	while($row = mysqli_fetch_assoc($result)){
		$output[] = $row;
	}
}

foreach($output as $out){
	$reg_no = $out["reg_no"];
	$visit_id = $out["visit_id"];
	$doctor_id = $out["doctor_id"];
	$date_of_visit = $out["date_of_visit"];
	$entry_time = $out["entry_time"];

	// $add_to_queue = "INSERT INTO queue_info (reg_no, visit_id, doctor_id, visit_date, visit_time) VALUES ('{$reg_no}', '{$visit_id}', '{$doctor_id}', '{$date_of_visit}', '{$entry_time}')";
	// Insert if Visit ID Already Doesnt Exist;
	$add_to_queue = "INSERT INTO queue_info (reg_no, visit_id, doctor_id, visit_date, visit_time)\n"
	. "SELECT * FROM (SELECT '{$reg_no}', '{$visit_id}', '{$doctor_id}', '{$date_of_visit}', '{$entry_time}') AS tmp\n"
	. "WHERE NOT EXISTS (\n"
	. "SELECT visit_id FROM queue_info WHERE visit_id = '{$visit_id}'\n"
	. ") LIMIT 1;";

	if(mysqli_query($conn, $add_to_queue)){
		$lastInsertToQueue = true;
	}else{
		$lastInsertToQueue = false;
	}
}

if($_POST["action"] == "LOADQUEUE"){

	$get_queue = "SELECT \n"
	. "p.p_fullname, d.doctor_name, q.queue_id,q.reg_no,q.visit_date,q.visit_time, q.visit_status \n"
	. "FROM queue_info AS q, patient_info AS p, doctor_info AS d \n"
	. "WHERE p.reg_no = q.reg_no AND d.doctor_id = q.doctor_id \n"
	. "AND visit_date='{$currentDate}' ORDER BY visit_status DESC, visit_time ASC;";

	// $get_queue = "SELECT * FROM queue_info WHERE visit_date='{$currentDate}' ORDER BY visit_time ASC";
	$get_queue_result = mysqli_query($conn, $get_queue) or die("SQL Query Failed");
	$queue_out = array();

	if(mysqli_num_rows($get_queue_result)>0){
		while($row = mysqli_fetch_assoc($get_queue_result)){
			$queue_out[] = $row;
		}
	}else{
		$queue_out = "EMPTY_QUEUE";
	}
	
	// echo $output[0]["visit_id"] . " - " . $output[0]["doctor_id"]. " - " . $output[0]["entry_time"];
	echo json_encode($queue_out);
}

if($_POST["action"]=="UPDATEVISIT"){
	$queue_id = $_POST["queue_id"];
	$statusBool = $_POST["status"];
	$status = "";
	if($statusBool === "true"){
		$status = "VISITED";
	}else{
		$status = "NOTVISITED";
	}

	$upd_queue = "UPDATE queue_info SET visit_status='{$status}' WHERE queue_id='{$queue_id}'";

	if(mysqli_query($conn,$upd_queue)){
		echo 1;
	}else{
		echo 0;
	}
}

?>
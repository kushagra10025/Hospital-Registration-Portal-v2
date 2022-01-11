<?php

$conn = mysqli_connect("localhost","root","","hhc_main") or die("Connection failed!");

if($_POST["action"] == "DOCDETAILS"){
	$sql = "SELECT * FROM doctor_info";

	$result = mysqli_query($conn, $sql) or die("SQL Query Failed");
	$output = array();

	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$output[] = $row;
		}
	}

	echo json_encode($output);
}

if($_POST["action"] == "DETAILS"){
	$visitid = $_POST["visitid"];

	$sql = "SELECT \n"
	. "p.reg_no, v.visit_id, p.p_fullname, p.p_gender, p.p_pno, p.p_address, p.p_age, \n"
	. "v.date_of_visit,v.consultation_fees, v.consultation_mode, v.payment_method, v.payment_status, v.remarks,\n"
	. "d.doctor_name FROM patient_info AS p LEFT JOIN visit_details AS v ON v.reg_no = p.reg_no LEFT JOIN doctor_info AS d ON v.doctor_id = d.doctor_id WHERE v.visit_id = '{$visitid}';";

	$result = mysqli_query($conn, $sql) or die("SQL Query Failed");
	$output = array();

	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_assoc($result)){
			$output[] = $row;
		}
	}

	echo json_encode($output);

}

if($_POST["action"] == "INSERT"){
	$visitdate = $_POST["visitdate"];
	$visitid = $_POST["visitid"];
	$docname = $_POST["docname"];
	$consulmode = $_POST["consulmode"];
	$consulfees = $_POST["consulfees"];
	$paymode = $_POST["paymode"];
	$paystatus = $_POST["paystatus"];
	$paydate = $_POST["paydate"];
	$remtext = $_POST["remtext"];
	$regno = $_POST["regno"];

	$time = new DateTime();
	$time->setTimezone(new DateTimeZone('Asia/Kolkata'));
	$currentTime = $time->format("H:i:s");

	$query = "INSERT INTO visit_details (visit_id, reg_no, date_of_visit, doctor_id, consultation_fees, consultation_mode, payment_method, payment_status, payment_date, remarks, entry_time) VALUES ('{$visitid}', '{$regno}', '{$visitdate}', '{$docname}', '{$consulfees}', '{$consulmode}', '{$paymode}', '{$paystatus}', '{$paydate}', '{$remtext}','{$currentTime}')";
	if(mysqli_query($conn, $query)){
		echo 1;
	}else{
		echo 0;
	}
}

if($_POST["action"] == "SEARCH"){
	$searchText = $_POST["searchText"];

	$sql = "SELECT * FROM patient_info WHERE reg_no LIKE '%{$searchText}%' OR p_fullname LIKE '%{$searchText}%' OR p_pno LIKE '%{$searchText}%'";

	$result = mysqli_query($conn, $sql) or die("SQL Query Failed");
	$output = "";
	// $outI = 0;

	if(mysqli_num_rows($result)>0){

		
		while($row = mysqli_fetch_assoc($result)){
			$output .= "<a href='#' class='list-group-item list-group-item-action patdetails'>
						<div class='d-flex w-100 justify-content-between'>
							<h5 class='mb-1'>
							<span id='patfname'>{$row["p_fullname"]}</span>
							<span class='badge rounded-pill bg-secondary' id='pataddress'>{$row["p_address"]}</span>
							</h5>
						</div>
						<p class='mb-1'><span class='badge rounded-pill bg-info text-dark' id='patpno'>{$row["p_pno"]}</span> | <span class='badge rounded-pill bg-primary' id='patregno'>{$row["reg_no"]}</span></p>
						</a>";
			// $outI = $outI+1;
		}
		// mysqli_close($conn);

		echo $output;
	}else{
		echo "<li class='list-group-item'>No Record Found</li>";
	}
}

if($_POST["action"]=="VISITDETAILS"){
	$reg_no = $_POST["reg_no"];

	$out_visit_details = array();

	$get_visit_details = "SELECT \n"
	. "v.visit_id,v.date_of_visit,v.consultation_fees, v.consultation_mode, v.payment_method, v.payment_status,v.payment_date,v.remarks,\n"
	. "d.doctor_name \n"
	. "FROM visit_details AS v LEFT JOIN doctor_info AS d ON v.doctor_id = d.doctor_id \n"
	. "WHERE v.reg_no='{$reg_no}' ORDER BY v.date_of_visit DESC;";
	
	$res_visit_details = mysqli_query($conn, $get_visit_details) or die("SQL Query Failed");
	
	if(mysqli_num_rows($res_visit_details)>0){
		while($row = mysqli_fetch_assoc($res_visit_details)){
			$out_visit_details[] = $row;
		}
	}else{
		$out_visit_details = "EMPTY_VISIT_DETAILS";
	}

	echo json_encode($out_visit_details);
}

// mysqli_close($conn);
?>
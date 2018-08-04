<?php

	include 'lib_link2DB.php';

	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : "%"; 
	$Permission = !empty($_POST['Permission']) ? $_POST['Permission'] : "1"; 
	
	$sql = 
	"SELECT EmployeeID, Name, BirthDate, HireDate, LeaveDate, Address, Phone, Account, Permission ".
	"FROM Employees ".
	"WHERE ";
	if($EmployeeID != "%") {
		$sql = $sql."EmployeeID = '$EmployeeID' AND ";
	}
	$sql = $sql.
	"Permission >= $Permission AND ".
	"State = 1 ".
	"ORDER BY EmployeeID ASC ;";
	
	$res = mysqli_query($lib_link, stripslashes($sql));
	while($result = mysqli_fetch_assoc($res) ) {
		$resp[] = $result;
	}
	mysqli_close($lib_link);
	
	if(isset($resp)) {
		print(json_encode($resp));
	}else{
		print(json_encode(array('result' => 'empty')));
	}
?>
<?php

	include 'lib_link2DB.php';
	
	/** Edit Employee **/
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$Mobile = !empty($_POST['Mobile']) ? $_POST['Mobile'] : 'false';
	
	
	$sql = "SELECT * FROM Employees WHERE EmployeeID = '$EmployeeID'";
	$res = mysqli_query($lib_link, stripslashes($sql));
	$row = mysqli_fetch_array($res);
	if($row['IsLogin'] == 1 && $Mobile == 'false') {
		$resp = array(
			'result' => "logged"
		);
	} else {
		/*if($EmployeeID < 99 && $Mobile == 'false') {
			$sql = 
			"UPDATE Employees SET ".
			"IsLogin = 1 ".
			"WHERE EmployeeID = '$EmployeeID'";
			mysqli_query($lib_link, stripslashes($sql));	
		}*/
		$resp = array(
			'result' => "success"
		);
	}	
	print(json_encode($resp));
	mysqli_close($lib_link);
?>
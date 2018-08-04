<?php

	include 'lib_link2DB.php';
	
	/** Edit Employee **/
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	
	$sql = 
	"UPDATE Employees SET ".
	"IsLogin = 0 ".
	"WHERE EmployeeID = '$EmployeeID'";
	mysqli_query($lib_link, stripslashes($sql));	
	

	/** Response **/
	$resp = array(
        'result' => "success"
    );
	
	print(json_encode($resp));
	mysqli_close($lib_link);
?>
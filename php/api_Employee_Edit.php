<?php

	include 'lib_link2DB.php';
	
	/** Edit Employee **/
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : null;
	$BirthDate = !empty($_POST['BirthDate']) ? $_POST['BirthDate'] : null; 
	$HireDate = !empty($_POST['HireDate']) ? $_POST['HireDate'] : null; 
	$LeaveDate = !empty($_POST['LeaveDate']) ? $_POST['LeaveDate'] : null; 
	$Address = !empty($_POST['Address']) ? $_POST['Address'] : null; 
	$Phone = !empty($_POST['Phone']) ? $_POST['Phone'] : null; 
	$Account = !empty($_POST['Account']) ? $_POST['Account'] : null; 
	$Password = !empty($_POST['Password']) ? md5($_POST['Password']): null; 
	$Permission = !empty($_POST['Permission']) ? $_POST['Permission'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	if($State == 0) {
		$LeaveDate = date("y-m-d");
	}
	
	
	$sql = 
	"UPDATE Employees SET ".
	"Name = '$Name', ".
	"BirthDate = '$BirthDate', ".
	"HireDate = '$HireDate', ".
	"LeaveDate = '$LeaveDate', ".
	"Address = '$Address', ".
	"Phone = '$Phone', ".
	"Account = '$Account', ";
	if($Password != null) {
		$sql = $sql."Password = '$Password', ";
	}
	$sql = $sql."Permission = '$Permission', ".
	"State = '$State' ".
	"WHERE EmployeeID = '$EmployeeID'";
	
	mysqli_query($lib_link, stripslashes($sql));	
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'EmployeeID' => $EmployeeID
    );
	
	print(json_encode($resp));
?>
<?php

	include 'lib_link2DB.php';
	
	/** Add Employee **/
	$Name = !empty($_POST['Name']) ? $_POST['Name'] : null;
	$BirthDate = !empty($_POST['BirthDate']) ? $_POST['BirthDate'] : null; 
	$HireDate = !empty($_POST['HireDate']) ? $_POST['HireDate'] : null;
	//$LeaveDate = !empty($_POST['LeaveDate']) ? $_POST['LeaveDate'] : null; 
	$Address = !empty($_POST['Address']) ? $_POST['Address'] : null; 
	$Phone = !empty($_POST['Phone']) ? $_POST['Phone'] : null; 
	$Account = !empty($_POST['Account']) ? $_POST['Account'] : null; 
	$Password = !empty($_POST['Password']) ? md5($_POST['Password']) : null; 
	$Permission = !empty($_POST['Permission']) ? $_POST['Permission'] : null; 
	
	
	$sql = "INSERT INTO Employees VALUES ";
	$sql .= "(NULL, '$Name', '$BirthDate', '$HireDate', '0000-00-00', '$Address', '$Phone', '$Account', '$Password', '$Permission', 0, 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$EmployeeID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'EmployeeID' => $EmployeeID
    );
	
	print(json_encode($resp));
?>
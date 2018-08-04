<?php
	
	include 'lib_link2DB.php';
	
	$Account = !empty($_POST['Account']) ? $_POST['Account'] : null; 
	$Password = !empty($_POST['Password']) ? md5($_POST['Password']) : null;
	$Mobile = !empty($_POST['Mobile']) ? $_POST['Mobile'] : 'false';
	
	$res = mysqli_query($lib_link, stripslashes("SELECT SUM(IsLogin) FROM Employees"));
	$LoginNumber = mysqli_fetch_row($res)[0];
	
	if($LoginNumber < 3) {
		$sql = "SELECT * FROM Employees ";
		$sql .= "WHERE Account = '$Account' AND State = 1 ";
		
		$res = mysqli_query($lib_link, stripslashes($sql));
		
		if(mysqli_num_rows($res) != 0) {
			while($row = mysqli_fetch_array($res)){
				if($row['IsLogin'] == 1 && $Mobile == 'false') {
					$resp = array(
						'result' => "logged"
					);
				}else if($row['Password'] == $Password) {
					$resp = array(
						'result'	=> "success",
						'EmployeeID'=> $row['EmployeeID'],
						'Name'		=> $row['Name'],
						'Account' 	=> $row['Account'],
						'Permission'=> $row['Permission']
					);
					/*$EmployeeID = $row['EmployeeID'];
					if($EmployeeID < 99) {
						$sql = 
						"UPDATE Employees SET ".
						"IsLogin = 1 ".
						"WHERE EmployeeID = '$EmployeeID'";
						mysqli_query($lib_link, stripslashes($sql));
					}*/
				} else {
					$resp = array(
						'result' => "fault"
					);
				}
			}
		} else {
			$resp = array(
				'result' => "fault"
			);
		}
	} else {
		$resp = array(
			'result' => "full"
		);
	}
	print($resp);
	print(json_encode($resp));
	mysqli_close($lib_link);
?>
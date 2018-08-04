<?php

	include 'lib_link2DB.php';

	$MaterialInventoryID = !empty($_POST['MaterialInventoryID']) ? $_POST['MaterialInventoryID'] : "%"; 
	
		
	$sql = 
	"SELECT M.MaterialInventoryID, M.Barcode, E.EmployeeID, E.Name as EmployeeName, M.Date ".
	"From MaterialInventories M, Employees E ".
	"WHERE ";
	if($MaterialInventoryID != "%") {
		$sql = $sql."M.MaterialInventoryID = '$MaterialInventoryID' AND ";
	}
	$sql = $sql.
	"M.EmployeeID = E.EmployeeID AND ".
	"M.State = 1 ".
	"ORDER BY M.MaterialInventoryID ASC ;";

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
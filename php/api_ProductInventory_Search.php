<?php

	include 'lib_link2DB.php';

	$ProductInventoryID = !empty($_POST['ProductInventoryID']) ? $_POST['ProductInventoryID'] : "%"; 
	
		
	$sql = 
	"SELECT P.ProductInventoryID, P.Barcode, E.EmployeeID, E.Name as EmployeeName, P.Date ".
	"From ProductInventories P, Employees E ".
	"WHERE ";
	if($ProductInventoryID != "%") {
		$sql = $sql."P.ProductInventoryID = '$ProductInventoryID' AND ";
	}
	$sql = $sql.
	"P.EmployeeID = E.EmployeeID AND ".
	"P.State = 1 ".
	"ORDER BY P.ProductInventoryID ASC ;";

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
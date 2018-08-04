<?php

	include 'lib_link2DB.php';

	$ProductShipmentID = !empty($_POST['ProductShipmentID']) ? $_POST['ProductShipmentID'] : "%"; 
	
		
	$sql = 
	"SELECT P.ProductShipmentID, P.Barcode, C.CompanyID, C.CompanyName , C.Phone, C.Address, C.TaxID, E.EmployeeID, E.Name as SetupEmployee, P.SetupDate, P.VerifyDate, P.VerifyEmployee, P.VerifyState ".
	"From (
		SELECT P.*, E.Name as VerifyEmployee
        FROM ProductShipments P Left JOIN Employees E 
        ON
        P.VerifyEmployeeID = E.EmployeeID 
		WHere
        P.State = 1 
        ORDER BY P.ProductShipmentID ASC) P, Companies C, Employees E ".
	"WHERE ";
	if($ProductShipmentID != "%") {
		$sql = $sql."P.ProductShipmentID = '$ProductShipmentID' AND ";
	}
	$sql = $sql.
	"P.CompanyID = C.CompanyID AND ".
	"P.SetupEmployeeID = E.EmployeeID AND ".
	"P.State = 1 ".
	"ORDER BY P.ProductShipmentID ASC ;";

	
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
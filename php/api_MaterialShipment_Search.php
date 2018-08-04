<?php

	include 'lib_link2DB.php';

	$MaterialShipmentID = !empty($_POST['MaterialShipmentID']) ? $_POST['MaterialShipmentID'] : "%"; 
	
		
	$sql = 
	"SELECT M.MaterialShipmentID, M.Barcode, C.CompanyID, C.CompanyName , C.Phone, C.Address, C.TaxID, E.EmployeeID, E.Name as SetupEmployee, M.SetupDate, M.VerifyDate, M.VerifyEmployee, M.VerifyState ".
	"From (
		SELECT M.*, E.Name as VerifyEmployee
        FROM MaterialShipments M Left JOIN Employees E 
        ON
        M.VerifyEmployeeID = E.EmployeeID 
		WHere
        M.State = 1 
        ORDER BY M.MaterialShipmentID ASC) M, Companies C, Employees E ".
	"WHERE ";
	if($MaterialShipmentID != "%") {
		$sql = $sql."M.MaterialShipmentID = '$MaterialShipmentID' AND ";
	}
	$sql = $sql.
	"M.CompanyID = C.CompanyID AND ".
	"M.SetupEmployeeID = E.EmployeeID AND ".
	"M.State = 1 ".
	"ORDER BY M.MaterialShipmentID ASC ;";

	
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
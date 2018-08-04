<?php

	include 'lib_link2DB.php';

	$IngredientShipmentID = !empty($_POST['IngredientShipmentID']) ? $_POST['IngredientShipmentID'] : "%"; 
	
		
	$sql = 
	"SELECT I.IngredientShipmentID, I.Barcode, C.CompanyID, C.CompanyName , C.Phone, C.Address, C.TaxID, E.EmployeeID, E.Name as SetupEmployee, I.SetupDate, I.VerifyDate, I.VerifyEmployee, I.VerifyState ".
	"From (
		SELECT I.*, E.Name as VerifyEmployee
        FROM IngredientShipments I Left JOIN Employees E 
        ON
        I.VerifyEmployeeID = E.EmployeeID 
		WHere
        I.State = 1 
        ORDER BY I.IngredientShipmentID ASC) I, Companies C, Employees E ".
	"WHERE ";
	if($IngredientShipmentID != "%") {
		$sql = $sql."I.IngredientShipmentID = '$IngredientShipmentID' AND ";
	}
	$sql = $sql.
	"I.CompanyID = C.CompanyID AND ".
	"I.SetupEmployeeID = E.EmployeeID AND ".
	"I.State = 1 ".
	"ORDER BY I.IngredientShipmentID ASC ;";

	
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
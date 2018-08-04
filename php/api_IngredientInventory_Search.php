<?php

	include 'lib_link2DB.php';

	$IngredientInventoryID = !empty($_POST['IngredientInventoryID']) ? $_POST['IngredientInventoryID'] : "%"; 
	
		
	$sql = 
	"SELECT I.IngredientInventoryID, I.Barcode, E.EmployeeID, E.Name as EmployeeName, I.Date ".
	"From IngredientInventories I, Employees E ".
	"WHERE ";
	if($IngredientInventoryID != "%") {
		$sql = $sql."I.IngredientInventoryID = '$IngredientInventoryID' AND ";
	}
	$sql = $sql.
	"I.EmployeeID = E.EmployeeID AND ".
	"I.State = 1 ".
	"ORDER BY I.IngredientInventoryID ASC ;";

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
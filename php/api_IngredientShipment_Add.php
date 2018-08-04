<?php

	include 'lib_link2DB.php';
	
	/** Add Ingredient Shipment	**/
	$CompanyID = !empty($_POST['CompanyID']) ? $_POST['CompanyID'] : null;
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$SetupDate = !empty($_POST['SetupDate']) ? $_POST['SetupDate'] : date("Y-m-d"); 
	
	
	$sql = "INSERT INTO IngredientShipments VALUES ";
	$sql .= "(NULL, '$CompanyID', '$EmployeeID', '', '$SetupDate', '0000-00-00', 0, 0, 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$IngredientShipmentID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($SetupDate), "ymd").'7'.sprintf("%03d", $IngredientShipmentID);

	$sql = 
	"UPDATE IngredientShipments SET ".
	"Barcode = '$Barcode' ".
	"WHERE IngredientShipmentID = '$IngredientShipmentID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientShipmentID' => $IngredientShipmentID
    );
	
	print(json_encode($resp));
?>
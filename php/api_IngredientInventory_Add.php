<?php

	include 'lib_link2DB.php';
	
	/** Add Ingredient Purchase **/
	$EmployeeID = !empty($_POST['EmployeeID']) ? $_POST['EmployeeID'] : null; 
	$Date = !empty($_POST['Date']) ? $_POST['Date'] : date("Y-m-d"); 
	
	
	$sql = "INSERT INTO IngredientInventories VALUES ";
	$sql .= "(NULL, '$EmployeeID', '', '$Date', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$IngredientInventoryID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	$Barcode = date_format(date_create($Date), "ymd").'I'.sprintf("%03d", $IngredientInventoryID);

	$sql = 
	"UPDATE IngredientInventories SET ".
	"Barcode = '$Barcode' ".
	"WHERE IngredientInventoryID = '$IngredientInventoryID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientInventoryID' => $IngredientInventoryID
    );
	
	print(json_encode($resp));
?>
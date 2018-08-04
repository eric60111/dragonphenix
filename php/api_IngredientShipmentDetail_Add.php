<?php

	include 'lib_link2DB.php';
	
	/** Add Ingredient Shipment Detail **/
	$IngredientShipmentID = !empty($_POST['IngredientShipmentID']) ? $_POST['IngredientShipmentID'] : null;
	$IngredientID = !empty($_POST['IngredientID']) ? $_POST['IngredientID'] : null; 
	$SoldPrice = !empty($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
		
	$sql = "INSERT INTO IngredientShipmentDetails VALUES ";
	$sql .= "(NULL, '$IngredientShipmentID', '$IngredientID', '$SoldPrice', '$Quantity', '$Quantity', 1)";
	mysqli_query($lib_link, stripslashes($sql));	
	$IngredientShipmentDetailID = mysqli_insert_id($lib_link);
	LogSql($lib_link, $sql);
	
	$sql = 
	"UPDATE Ingredients SET ".
	"SoldPrice = '$SoldPrice' ".
	"WHERE IngredientID = '$IngredientID'";
	mysqli_query($lib_link, stripslashes($sql));
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);
	
	/** Response **/
	$resp = array(
        'result' => "successful",
        'IngredientShipmentDetailID' => $IngredientShipmentDetailID
    );
	
	print(json_encode($resp));
?>
<?php

	include 'lib_link2DB.php';
	
	/** Edit Shipment Detail **/
	$IngredientShipmentDetailID = !empty($_POST['IngredientShipmentDetailID']) ? $_POST['IngredientShipmentDetailID'] : null; 
	$IngredientID = !empty($_POST['IngredientID']) ? $_POST['IngredientID'] : null; 
	$SoldPrice = !empty($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
	$ActualQuantity = !empty($_POST['ActualQuantity']) ? $_POST['ActualQuantity'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE IngredientShipmentDetails SET ".
	"SoldPrice = '$SoldPrice' ".
	"WHERE IngredientShipmentDetailID = '$IngredientShipmentDetailID'";
	
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
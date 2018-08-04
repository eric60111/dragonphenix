<?php

	include 'lib_link2DB.php';
	
	/** Edit Shipment Detail **/
	$ProductShipmentDetailID = !empty($_POST['ProductShipmentDetailID']) ? $_POST['ProductShipmentDetailID'] : null; 
	$ProductID = !empty($_POST['ProductID']) ? $_POST['ProductID'] : null; 
	$SoldPrice = !empty($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
	$ActualQuantity = !empty($_POST['ActualQuantity']) ? $_POST['ActualQuantity'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE ProductShipmentDetails SET ".
	"SoldPrice = '$SoldPrice' ".
	"WHERE ProductShipmentDetailID = '$ProductShipmentDetailID'";
	
	mysqli_query($lib_link, stripslashes($sql));	
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'ProductShipmentDetailID' => $ProductShipmentDetailID
    );
	
	print(json_encode($resp));
?>
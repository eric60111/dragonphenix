<?php

	include 'lib_link2DB.php';
	
	/** Edit Shipment Detail **/
	$MaterialShipmentDetailID = !empty($_POST['MaterialShipmentDetailID']) ? $_POST['MaterialShipmentDetailID'] : null; 
	$MaterialID = !empty($_POST['MaterialID']) ? $_POST['MaterialID'] : null; 
	$SoldPrice = !empty($_POST['SoldPrice']) ? $_POST['SoldPrice'] : null; 
	$Quantity = !empty($_POST['Quantity']) ? $_POST['Quantity'] : null; 
	$ActualQuantity = !empty($_POST['ActualQuantity']) ? $_POST['ActualQuantity'] : null; 
	$State = !empty($_POST['State']) ? $_POST['State'] : null; 
	
	
	$sql = 
	"UPDATE MaterialShipmentDetails SET ".
	"SoldPrice = '$SoldPrice' ".
	"WHERE MaterialShipmentDetailID = '$MaterialShipmentDetailID'";
	
	mysqli_query($lib_link, stripslashes($sql));	
	LogSql($lib_link, $sql);
	mysqli_close($lib_link);

	/** Response **/
	$resp = array(
        'result' => "successful",
        'MaterialShipmentDetailID' => $MaterialShipmentDetailID
    );
	
	print(json_encode($resp));
?>